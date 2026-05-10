<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2.5 bg-agro-green border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-agro-green/90 focus:bg-agro-green/90 active:bg-agro-green focus:outline-none focus:ring-2 focus:ring-agro-green focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow-md']) }}>
    {{ $slot }}
</button>
