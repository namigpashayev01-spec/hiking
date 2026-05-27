@props(['status'])

@php
    $map = [
        'pending'  => ['badge-pending', __('Pending')],
        'approved' => ['badge-approved', __('Approved')],
        'rejected' => ['badge-rejected', __('Rejected')],
    ];
    [$class, $label] = $map[$status] ?? ['badge-pending', $status];
@endphp

<span class="badge {{ $class }}">{{ $label }}</span>
