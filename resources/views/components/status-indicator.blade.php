{{--
    TEDI Status indicator.
    Port of angular/tedi/components/tags/status-indicator/status-indicator.component.ts

    Angular's template is empty — the component is host-classes-only. The
    Blade port is a single decorative <span>.
--}}
@props([
    /** Accessible label. When given, the indicator gets role="img". */
    'label' => null,
    /** success|danger|warning|inactive */
    'type' => 'success',
    /** sm|lg */
    'size' => 'sm',
    /** Whether the indicator has a white border ring. */
    'hasBorder' => false,
    /** default|top-right */
    'position' => 'default',
])

<span
    @if ($label) role="img" aria-label="{{ $label }}" @else aria-hidden="true" @endif
    {{ $attributes->class([
        'tedi-status-indicator',
        'tedi-status-indicator--success' => $type === 'success',
        'tedi-status-indicator--danger' => $type === 'danger',
        'tedi-status-indicator--warning' => $type === 'warning',
        'tedi-status-indicator--inactive' => $type === 'inactive',
        'tedi-status-indicator--sm' => $size === 'sm',
        'tedi-status-indicator--lg' => $size === 'lg',
        'tedi-status-indicator--bordered' => (bool) $hasBorder,
        'tedi-status-indicator--top-right' => $position === 'top-right',
    ]) }}
></span>
