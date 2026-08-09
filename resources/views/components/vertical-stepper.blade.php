{{--
    TEDI Vertical Stepper (community).
    Port of angular/community/components/navigation/vertical-stepper/vertical-stepper.component.{ts,html}

    Ported from the `community/` tree rather than `tedi/` — see CONVENTIONS.md
    §12. Angular's selector is the element `tedi-vertical-stepper`, so per §4's
    element-selector rule the root below is that literal custom element. The
    vendored SCSS gives `.tedi-vertical-stepper` no `display`, matching Angular:
    the visible layout is a plain `<nav>`/`<div role="list">` inside it, and the
    host exists only to `counter-reset: step-number` for the items' indicators.

    `compact` and `enumerated` reach `tedi:vertical-stepper-item` through
    @aware, which is how Angular's `inject(VerticalStepperComponent)` +
    `computed()` pair is mapped (CONVENTIONS.md §3). Both @aware fallbacks in
    the item equal the defaults declared here, as §3 requires.

    DROPPED CLASS: `tedi-vertical-stepper--compact`. Angular emits it on the
    host, but the vendored `vertical-stepper.component.scss` is three lines long
    and styles only `.tedi-vertical-stepper` itself — nothing matches the
    modifier. The compact appearance comes entirely from
    `tedi-vertical-stepper-item--compact` on each item, which is emitted, so
    dropping is behaviourally identical (CONVENTIONS.md §4). Restore it if TEDI
    ever ships the rule.
--}}
@props([
    /** Accessible label for the navigation landmark. */
    'ariaLabel' => null,
    /** Collapses the steps: smaller indicators, tighter padding. Passed to every item. */
    'compact' => false,
    /** Prefixes each top-level step's title with its number. Only visible in `compact`. */
    'enumerated' => false,
])

<tedi-vertical-stepper {{ $attributes->class([
    'tedi-vertical-stepper',
]) }}>
    <nav @if ($ariaLabel) aria-label="{{ $ariaLabel }}" @endif>
        <div role="list">
            {{ $slot }}
        </div>
    </nav>
</tedi-vertical-stepper>
