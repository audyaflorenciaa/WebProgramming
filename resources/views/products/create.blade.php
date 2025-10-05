<!-- resources/views/products/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">List a New Item for Sale</h1>
    
    <!-- ERROR DISPLAY SECTION -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">Please check the fields below and correct the errors.</span>
        </div>
    @endif

    <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column: Media & Condition -->
            <div class="space-y-6">
                
                <!-- Product Images (Max 10 Images) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Images</label>
                    <div class="flex items-center space-x-2">
                        <!-- Hidden File Input - USED FOR TRIGGERING DIALOG ONLY -->
                        <input type="file" name="images_temp[]" id="images" multiple accept="image/*" 
                               class="hidden" onchange="handleFileSelection(this, 'image-list', 10, 'images')">
                        
                        <!-- Custom Styled Button (Label) -->
                        <label for="images" class="cursor-pointer bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-150 shadow-sm text-sm font-medium">
                            Choose Files
                        </label>
                        
                        <!-- Hidden File Input - USED FOR FORM SUBMISSION (REQUIRED for validation) -->
                        <input type="file" name="images[]" id="images-queue" multiple class="hidden" required>
                    </div>
                    <p class="text-sm text-gray-500 mt-2" id="image-hint">Max 10 images (2MB each)</p>
                    <ul id="image-list" class="mt-2 space-y-1 text-sm text-gray-700"></ul>
                    @error('images.*')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Product Video (Max 3 Videos @ 10MB) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Video (Optional)</label>
                    <div class="flex items-center space-x-2">
                        <!-- Hidden File Input - USED FOR TRIGGERING DIALOG ONLY -->
                        <input type="file" name="video_temp[]" id="video" multiple accept="video/*" 
                               class="hidden" onchange="handleFileSelection(this, 'video-list', 3, 'video')">
                        
                        <!-- Custom Styled Button (Label) -->
                        <label for="video" class="cursor-pointer bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-150 shadow-sm text-sm font-medium">
                            Choose File
                        </label>

                        <!-- Hidden File Input - USED FOR FORM SUBMISSION -->
                        <input type="file" name="video[]" id="video-queue" multiple class="hidden">
                    </div>
                    <p class="text-sm text-gray-500 mt-2" id="video-hint">Max 3 videos (10MB each)</p>
                    <ul id="video-list" class="mt-2 space-y-1 text-sm text-gray-700"></ul>

                    @error('video.*')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Condition Dropdown (Using old() to retain input) -->
                <div>
                    <label for="condition" class="block text-sm font-medium text-gray-700 mb-2">Condition</label>
                    <select name="condition" id="condition" required 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('condition') border-red-500 @enderror">
                        <option value="">-- Select Condition --</option>
                        <option value="like_new" {{ old('condition') == 'like_new' ? 'selected' : '' }}>Like New</option>
                        <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Good</option>
                        <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                        <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>Poor</option>
                    </select>
                    @error('condition')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Right Column: Details -->
            <div class="space-y-6">
                
                <!-- Title (Using old() to retain input) -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" name="title" id="title" required value="{{ old('title') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Category Dropdown (DIPLAYING CATEGORIES) -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category_id" id="category_id" required 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Price (Using old() to retain input) -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (IDR)</label>
                    <input type="text" name="price" id="price" required value="{{ old('price') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('price') border-red-500 @enderror"
                           placeholder="e.g., 4.800.000">
                    @error('price')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Brand (Using old() to retain input) -->
                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Brand (Optional)</label>
                    <input type="text" name="brand" id="brand" value="{{ old('brand') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('brand') border-red-500 @enderror">
                    @error('brand')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Description (Using old() to retain input) -->
        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="4" required 
                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mt-8">
            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                List Item for Sale
            </button>
        </div>
    </form>
</div>

<script>
    // Global object to store files for both inputs
    let fileQueues = {
        'images': [],
        'video': []
    };
    
    // Polyfill/reference for DataTransfer object needed to manipulate file lists
    const DataTransfer = window.DataTransfer || ClipboardEvent.prototype.clipboardData;

    /**
     * Handles file selection, tracks files, updates the list, and enforces limits.
     */
    function handleFileSelection(inputElement, listId, maxFiles, queueName) {
        const newFiles = Array.from(inputElement.files);
        let filesToAdd = [];

        const currentCount = fileQueues[queueName].length;
        const totalCount = currentCount + newFiles.length;

        if (totalCount > maxFiles) {
            const allowedToAdd = maxFiles - currentCount;
            if (allowedToAdd <= 0) {
                 // Use a custom message box instead of alert() in production
                 alert(`You have already reached the maximum limit of ${maxFiles} files.`);
                 inputElement.value = null; 
                 return;
            }
            filesToAdd = newFiles.slice(0, allowedToAdd);
            alert(`Only the first ${allowedToAdd} file(s) were added due to the limit of ${maxFiles}.`);
        } else {
            filesToAdd = newFiles;
        }

        filesToAdd.forEach(file => {
            fileQueues[queueName].push(file);
        });
        
        reRenderList(listId, queueName, maxFiles);
        updateQueueInput(queueName);
        inputElement.value = null;
    }

    /**
     * Removes a file from the global queue and updates the list display.
     */
    function removeFile(index, queueName, listId, maxFiles) {
        fileQueues[queueName].splice(index, 1);
        reRenderList(listId, queueName, maxFiles);
        updateQueueInput(queueName);
    }

    /**
     * Re-renders the list after a file is added or removed.
     */
    function reRenderList(listId, queueName, maxFiles) {
        const fileListElement = document.getElementById(listId);
        fileListElement.innerHTML = ''; 

        if (fileQueues[queueName].length > 0) {
            fileQueues[queueName].forEach((file, index) => {
                const listItem = document.createElement('li');
                listItem.classList.add('flex', 'justify-between', 'items-center', 'py-1', 'px-2', 'bg-gray-100', 'rounded-md', 'mt-1');
                
                const fileNameSpan = document.createElement('span');
                fileNameSpan.textContent = file.name;
                
                const deleteButton = document.createElement('button');
                deleteButton.type = 'button';
                deleteButton.innerHTML = '🗑️'; 
                deleteButton.classList.add('text-lg', 'text-red-500', 'hover:text-red-700', 'ml-2');
                
                deleteButton.onclick = function() {
                    removeFile(index, queueName, listId, maxFiles);
                };

                listItem.appendChild(fileNameSpan);
                listItem.appendChild(deleteButton);
                fileListElement.appendChild(listItem);
            });
        }
    }

    /**
     * Updates the hidden file input used for form submission.
     */
    function updateQueueInput(queueName) {
        const dataTransfer = new DataTransfer();
        fileQueues[queueName].forEach(file => {
            dataTransfer.items.add(file);
        });
        
        const queueInputId = queueName + '-queue';
        const queueInput = document.getElementById(queueInputId);
        
        if (queueInput) {
            queueInput.files = dataTransfer.files;
        }
    }
</script>
@endsection