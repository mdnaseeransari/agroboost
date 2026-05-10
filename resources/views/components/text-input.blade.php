@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 focus:border-agro-green focus:ring-agro-green/50 rounded-xl shadow-sm transition duration-200']) }}>
