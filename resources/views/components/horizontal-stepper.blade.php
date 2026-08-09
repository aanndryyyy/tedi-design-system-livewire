{{--
    TEDI Horizontal Stepper.
    Port of angular/tedi/components/navigation/horizontal-stepper/horizontal-stepper.component.{ts,html}

    Angular's selector is the element `tedi-horizontal-stepper`, so per
    CONVENTIONS.md §4's element-selector rule the root below is that literal
    custom element rather than a <div>. `.tedi-horizontal-stepper` supplies
    `display: flex`, so the unknown element needs no display fallback.

    Angular registers its <tedi-horizontal-stepper-item> children via
    contentChildren() and pushes each one's 1-based position into the item's
    internal `_stepNumber` signal. Blade cannot introspect its own slot, so per
    CONVENTIONS.md §5 that becomes an explicit `step-number` prop on
    <tedi:horizontal-stepper-item>. Nothing else flows parent → child, so this
    pair uses no @aware.

    `compact` looks like a breakpoint prop but is NOT the §7 case: Angular does
    not resolve it through BreakpointService: it emits
    `tedi-horizontal-stepper--compact-{bp}` and the vendored SCSS wraps the
    collapsed layout in `media-breakpoint-down($bp)`. The breakpoint work is
    therefore pure CSS and ports as-is.
--}}
@props([
    /** Accessible label for the navigation landmark. */
    'ariaLabel' => null,
    /** default|transparent */
    'background' => 'default',
    /**
     * Collapse labels so only indicators plus the selected step's label are visible.
     * true — always collapsed. false — never. A breakpoint ('sm'|'md'|'lg'|'xl'|'xxl')
     * — collapsed below that breakpoint.
     */
    'compact' => 'sm',
])

@php
    // `true` must not fall into the string branch — `'…--compact-'.true` would
    // concatenate to `tedi-horizontal-stepper--compact-1`.
    $compactBreakpoint = in_array($compact, ['sm', 'md', 'lg', 'xl', 'xxl'], true) ? $compact : null;
@endphp

<tedi-horizontal-stepper {{ $attributes->class([
    'tedi-horizontal-stepper',
    'tedi-horizontal-stepper--transparent' => $background === 'transparent',
    'tedi-horizontal-stepper--compact' => $compact === true,
    'tedi-horizontal-stepper--compact-'.$compactBreakpoint => $compactBreakpoint !== null,
])->merge(array_filter([
    'role' => 'navigation',
    'aria-label' => $ariaLabel,
])) }}>
    {{ $slot }}
</tedi-horizontal-stepper>
