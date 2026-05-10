@props(['type' => 'info', 'label'])

@php
    $classes = match ($type) {
        'success', 'growing', 'healthy' => 'bg-status-success/10 text-status-success border-status-success/20',
        'warning', 'medium' => 'bg-status-warning/10 text-status-warning border-status-warning/20',
        'danger', 'failed', 'low', 'overdue' => 'bg-status-danger/10 text-status-danger border-status-danger/20',
        'info', 'harvested', 'completed' => 'bg-status-info/10 text-status-info border-status-info/20',
        default => 'bg-gray-100 text-gray-800 border-gray-200',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border $classes"]) }}>
    {{ $label }}
</span>
