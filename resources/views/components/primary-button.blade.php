<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#F7733C] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#df6937] focus:bg-[#fff] active:bg-[#fff] focus:outline-none focus:ring-2 focus:ring-[#fff] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
