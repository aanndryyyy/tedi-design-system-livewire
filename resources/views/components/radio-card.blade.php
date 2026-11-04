{{--
    TEDI Radio Card.
    Port of angular/src/tedi/components/form/radio-card/{radio-card.component.ts,html}

    Angular's selector is `label[tedi-radio-card]` (attribute-based, not an
    element/class selector). The vendored SCSS targets that literal attribute,
    so it is emitted verbatim on the root <label> alongside the host `class`
    binding and its modifiers.

    Angular's <ng-content select="tedi-feedback-text" /> becomes the named
    `feedback` slot; everything else (the radio input, icon, label text) is
    the default slot.

    `grouped` mirrors Angular's `isGrouped = grouped() || cardGroup.grouped()`,
    per CONVENTIONS.md §3 (`inject(ParentComponent)` → `@aware`): a `grouped`
    attribute passed directly to this card wins, otherwise it inherits the
    nearest ancestor's <x-radio-card-group :grouped="..."> value. @props runs
    first and only sets a local PHP variable (it never writes back into the
    component-data stack @aware reads), so declaring the same-named prop here
    is safe and lets @attributes strip it from the rendered <label>
    automatically. Note this is "nearest explicit value wins", not a true
    boolean OR — an explicit `:grouped="false"` on a card still overrides a
    grouped ancestor, which Angular's OR would not do. Undocumented finer
    edge case, but no consumer sets `grouped="false"` explicitly in practice.

    `bordered` (added upstream in 9.0) works exactly the same way:
    `isBordered = bordered() || cardGroup.bordered()` becomes the card's own
    prop, falling back via @aware to the nearest ancestor's
    `tedi:radio-card-group :bordered`, with the same "nearest explicit value
    wins" caveat as `grouped`. Like Angular, `tedi-radio-card--bordered` is
    emitted whatever the variant and even on grouped cards — the vendored SCSS
    only styles it under `--primary` and grouped cards are always bordered, so
    there it is inert, but the class list stays identical to Angular's.
--}}
@props([
    /** primary|secondary */
    'variant' => 'primary',
    /** Show the radio indicator. When false, the radio is visually hidden but stays functional. */
    'showIndicator' => true,
    /** Show the card as part of a button-group style layout. Prefer setting it on the enclosing group. */
    'grouped' => false,
    /** Give a primary card a border in the unselected state. Can also be set on the enclosing group; no effect on grouped cards, which always have a border. */
    'bordered' => false,
])
@aware(['grouped' => false, 'bordered' => false])

<label
    tedi-radio-card
    {{ $attributes->class([
        'tedi-radio-card',
        'tedi-radio-card--primary' => $variant === 'primary',
        'tedi-radio-card--secondary' => $variant === 'secondary',
        'tedi-radio-card--grouped' => (bool) $grouped,
        'tedi-radio-card--bordered' => (bool) $bordered,
        'tedi-radio-card--hide-indicator' => ! $showIndicator,
    ]) }}
>
    <div class="tedi-radio-card__content">
        {{ $slot }}
    </div>

    {{ $feedback ?? '' }}
</label>
