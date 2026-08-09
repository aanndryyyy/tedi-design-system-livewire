{{--
    TEDI Horizontal Stepper Item.
    Port of angular/tedi/components/navigation/horizontal-stepper/horizontal-stepper-item/horizontal-stepper-item.component.{ts,html}

    Angular's selector is the element `tedi-horizontal-stepper-item`, so per
    CONVENTIONS.md §4's element-selector rule the root below is that literal
    custom element. `.tedi-horizontal-stepper-item` supplies `display: flex`.

    `step-number` replaces the parent's contentChildren() effect, which assigns
    each item its 1-based position (CONVENTIONS.md §5). It is an internal signal
    upstream, not an input(), so it has no Angular default to match; it defaults
    to 1 here rather than Angular's placeholder 0, which never reaches the DOM.

    DROPPED CLASS: `tedi-horizontal-stepper-item--disabled`. Angular emits it,
    but no rule in the vendored SCSS matches it — the disabled appearance comes
    entirely from the native `:disabled` on the inner <button> (`cursor: default`
    plus the `:hover/:active:not(:disabled)` guards). Dropping is therefore
    behaviourally identical, so per CONVENTIONS.md §4 the guardrail wins.
    Restore it if TEDI ever ships the rule.

    DIVERGENCE: Angular's `stepSelect` output is not re-emitted
    (CONVENTIONS.md §7.2) — a consumer binds `wire:click` / `x-on:click` through
    $attributes on this root element. Angular suppresses stepSelect for the
    selected step; a click there still bubbles to the root here. Disabled steps
    do not fire either way, because the native <button disabled> swallows the
    click.
--}}
@props([
    /** Step label. Required. */
    'label',
    /** Optional secondary line under the label. */
    'description' => null,
    /** Renders the completed (check icon) state. Ignored when `error` is set. */
    'completed' => false,
    /** Renders the error (exclamation icon) state. Wins over `completed`. */
    'error' => false,
    /** Marks this step as the current one (arrow-shaped highlight + aria-current). */
    'selected' => false,
    /** Prevents the step from being clicked or focused. */
    'disabled' => false,
    /** This step's 1-based position — see the note above. */
    'stepNumber' => 1,
])

<tedi-horizontal-stepper-item {{ $attributes->class([
    'tedi-horizontal-stepper-item',
    'tedi-horizontal-stepper-item--selected' => $selected,
    'tedi-horizontal-stepper-item--completed' => $completed && ! $error,
    'tedi-horizontal-stepper-item--error' => $error,
]) }}>
    <button
        class="tedi-horizontal-stepper-item__step"
        type="button"
        @disabled($disabled)
        @if ($selected) aria-current="step" @endif
    >
        <span class="tedi-horizontal-stepper-item__indicator">
            @if ($completed && ! $error)
                <tedi:icon name="check" color="white" :size="18" :label="__('tedi::tedi.stepper.completed')" />
            @elseif ($error)
                <tedi:icon name="exclamation" color="white" :size="18" :label="__('tedi::tedi.stepper.error')" />
            @else
                <span class="tedi-horizontal-stepper-item__number">{{ $stepNumber }}</span>
            @endif
        </span>
        <span class="tedi-horizontal-stepper-item__content">
            <span class="tedi-horizontal-stepper-item__label">{{ $label }}</span>
            @if ($description)
                <span class="tedi-horizontal-stepper-item__description">{{ $description }}</span>
            @endif
        </span>
    </button>
</tedi-horizontal-stepper-item>
