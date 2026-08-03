@props([
    'type' => 'empty',
    'title' => 'Nothing to show yet',
    'message' => 'New information will appear here when it becomes available.',
])

@php
    $icons = [
        'empty' => 'fa-inbox',
        'error' => 'fa-exclamation-triangle',
        'loading' => 'fa-spinner fa-spin',
        'success' => 'fa-check-circle',
    ];
@endphp

<div {{ $attributes->class(['ref-ui-state', 'is-' . $type]) }}>
    <span class="ref-ui-state-icon"><i class="fa {{ $icons[$type] ?? $icons['empty'] }}"></i></span>
    <div>
        <h3>{{ $title }}</h3>
        <p>{{ $message }}</p>
    </div>
</div> 
 