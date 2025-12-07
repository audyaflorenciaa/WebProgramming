@props(['disabled' => false])

<!-- <input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) }}> -->
<input {{ $attributes->merge(['class' => 
    'bg-white border-gray-300 text-gray-900 placeholder-gray-500 rounded-md shadow-sm 
     focus:ring-blue-500 focus:border-blue-500']) }} />