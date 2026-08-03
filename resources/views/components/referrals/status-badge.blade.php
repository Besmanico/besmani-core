@props(['status'])

@php
    $labels = [
        'pending' => 'Pending',
        'accepted' => 'Accepted',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'rejected' => 'Rejected',
        'cancelled' => 'Cancelled',
        'disputed' => 'Disputed',
        'settled' => 'Settled',
    ];
@endphp

<span {{ $attributes->class(['ref-status-badge', 'is-' . $status]) }}>
    <span class="ref-status-dot" aria-hidden="true"></span>
    {{ $labels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}
</span> 
   