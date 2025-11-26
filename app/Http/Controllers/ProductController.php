<?php
// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Start the query, loading related user and category data, and filtering out sold items
        $query = Product::with(['user', 'category'])->where('is_sold', false);
        
        // 1. Category Filtering (FIXED)
        if ($request->has('category')) {
            $categorySlug = $request->input('category');
            
            // Find the category based on the slug provided in the URL
            $category = Category::where('slug', $categorySlug)->first();

            // ONLY filter the products if a category was actually found
            if ($category) {
                // Filter products by the category_id (the most reliable way)
                $query->where('category_id', $category->id);
            }
        }

        // 2. Search Filtering (IMPLEMENTED)
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            // Filter by product title OR description (for a better search experience)
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Execute the final query with pagination
        $products = $query->latest()->paginate(12);
        $categories = Category::all();
        
        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // FIX: Clean IDR format (removes dots for database storage)
        $request->merge([
            'price' => str_replace('.', '', $request->input('price'))
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|in:like_new,good,fair,poor',
            'brand' => 'nullable|string|max:255',
            
            // Image Validation (Max 10 images)
            'images' => 'required|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB per image
            
            // Video Validation (Max 3 videos, Max 10MB each)
            'video' => 'nullable|array|max:3',
            'video.*' => 'mimes:mp4,mov,avi|max:10240' // 10MB (10240 KB)
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // The storage path must be saved, not the full URL
                $path = $image->store('product_images', 'public');
                $imagePaths[] = $path;
            }
        }

        $videoPaths = []; 
        if ($request->hasFile('video')) {
            foreach ($request->file('video') as $video) {
                $path = $video->store('product_videos', 'public');
                $videoPaths[] = $path;
            }
        }

        $product = Product::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'condition' => $validated['condition'],
            'brand' => $validated['brand'],
            'images' => $imagePaths, // Saved as JSON array
            'video' => $videoPaths   // Saved as JSON array
        ]);

        // FIX: Redirects to the My Items page after successful listing.
        return redirect()->route('products.my-products')
                         ->with('success', 'Product listed successfully! Check your items below.');
    }

    public function show(Product $product)
    {
        $product->load('user', 'category');
        return view('products.show', compact('product'));
    }
    
    public function myProducts()
    {
        $products = Product::where('user_id', Auth::id())
                        ->latest()
                        ->paginate(15); 
                        
        return view('products.my-products', compact('products'));
    }
    
    public function edit(Product $product)
    {
        $categories = Category::all();
        // This line tries to find resources/views/products/edit.blade.php
        return view('products.edit', compact('product', 'categories')); 
    }
    
    public function update(Request $request, Product $product)
    {
        // 1. Clean IDR format before validation
        $request->merge([
            'price' => str_replace('.', '', $request->input('price'))
        ]);

        // 2. Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|in:like_new,good,fair,poor',
            'brand' => 'nullable|string|max:255',
            'is_sold' => 'boolean', // Added the is_sold flag

            // Media is optional on update, and we handle replacement logic below
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
            
            'video' => 'nullable|array|max:3',
            'video.*' => 'mimes:mp4,mov,avi|max:10240'
        ]);

        // 3. Handle Media Replacement
        $imagePaths = $product->images; // Default to existing images
        $videoPaths = $product->video;   // Default to existing video

        // Check if new images were uploaded (if so, REPLACE the old ones)
        if ($request->hasFile('images')) {
            // You might want to delete the old files here! (Optional for now)
            $imagePaths = []; 
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('product_images', 'public');
            }
        }
        
        // Check if new videos were uploaded (if so, REPLACE the old ones)
        if ($request->hasFile('video')) {
            // You might want to delete the old files here! (Optional for now)
            $videoPaths = []; 
            foreach ($request->file('video') as $video) {
                $videoPaths[] = $video->store('product_videos', 'public');
            }
        }

        // 4. Update the Product in the database
        $product->update(array_merge($validated, [
            'images' => $imagePaths,
            'video' => $videoPaths,
            'is_sold' => $request->boolean('is_sold') // Ensure boolean is correctly set
        ]));

        // 5. Redirect back to My Items Dashboard
        return redirect()->route('products.index')
                        ->with('success', 'Product listing updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Placeholder delete logic
        $product->delete();
        return redirect()->route('products.my-products')->with('success', 'Product deleted!');
    }
}