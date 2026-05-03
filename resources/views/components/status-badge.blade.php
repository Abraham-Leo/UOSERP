{{-- resources/views/components/status-badge.blade.php --}}
@props(['status', 'map' => []])

@php
$default = [
    'new'         => 'primary',    'draft'        => 'secondary',
    'open'        => 'primary',    'sent'         => 'primary',
    'in_progress' => 'warning',    'active'       => 'warning',
    'released'    => 'info',       'partial'      => 'info',
    'shipped'     => 'info',       'acknowledged' => 'info',
    'invoiced'    => 'success',    'received'     => 'success',
    'complete'    => 'success',    'completed'    => 'success',
    'won'         => 'success',    'paid'         => 'success',
    'closed'      => 'success',    'approved'     => 'success',
    'cancelled'   => 'secondary',  'inactive'     => 'secondary',
    'lost'        => 'danger',     'overdue'      => 'danger',
    'late'        => 'danger',     'expired'      => 'warning',
    'on_hold'     => 'warning',    'review'       => 'warning',
    'mrb'         => 'purple',     'pending'      => 'warning',
];
$colors = array_merge($default, $map);
$color  = $colors[$status] ?? 'secondary';
$label  = ucfirst(str_replace('_', ' ', $status));
@endphp

<span class="badge badge-{{ $color }}">{{ $label }}</span>
