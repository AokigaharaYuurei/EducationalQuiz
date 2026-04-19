@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-[#E84400] dark:focus:border-[#E84400] focus:ring-[#E84400] dark:focus:ring-[#E84400] rounded-md shadow-sm']) }}>
