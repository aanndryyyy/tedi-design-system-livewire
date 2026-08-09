{{--
    TEDI Button Group Button.
    Port of angular/tedi/components/buttons/button-group/button-group-button/button-group-button.directive.ts

    Angular's selector is the ATTRIBUTE `button[tedi-button-group-button]`, so
    per CONVENTIONS.md §4 the root is a literal `<button tedi-button-group-button>`
    — the attribute is carried, not just the class.

    Because `.tedi-button-group > .tedi-button` is a DIRECT-CHILD selector (it is
    what supplies the connected strip geometry — collapsed borders and flattened
    joining corners), this element must be an immediate child of
    <tedi:button-group>, with no wrapper other than <tedi:tooltip> (which the
    vendored SCSS handles explicitly, and which is display:contents).

    DOM SURGERY → EXPLICIT PROPS (CONVENTIONS.md §5). The Angular directive
    rewrites its own host at runtime in two ways, neither of which Blade can do:

      - `wrapLabel()` (ngAfterViewInit) hunts for bare text child nodes and wraps
        them in `<span class="tedi-button-group-button__label">`, so the gap to an
        adjacent icon comes from the label's padding while the icon stays flush
        (`.tedi-button.tedi-button-group-button { gap: 0 }`). Here `label` is an
        explicit prop and the span is rendered directly.
      - `syncIcon()` (three effects) instantiates `<tedi-icon>` components and
        splices them in as firstChild / appendChild. Here `icon-left`,
        `icon-right` and `icon` are explicit props and the icons are rendered
        in place.

    `selected` is the explicit-prop translation of Angular's
    `computed(() => parent.isSelected(this.value()))` (§5). The group's `value`
    cannot reach this component through @aware: Laravel resolves the component's
    OWN `value` attribute first, so every item would compare its value with
    itself — the identical trap documented on dropdown-item.blade.php.
    <tedi:button-group> computes `selected` for each `:items` entry; a
    slot-written item passes it explicitly.

    `variant` and `size` DO cascade through @aware, which is Angular's
    `this.variant() ?? parent.variant()` exactly: this component's own attribute
    wins, else the group's, else the shared default. The @aware fallbacks
    therefore equal <tedi:button-group>'s @props defaults, per CONVENTIONS.md §3.
    Both are also declared in @props so they are consumed rather than leaking
    into the DOM as stray attributes (§6).

    The `--icon-only` / `--pl` / `--pr` modifiers come from BaseButtonDirective,
    which this directive composes via `hostDirectives` and which counts the
    host's element children after the icons have been injected. That count is
    fully determined by the icon props here, so it is computed from them the same
    way button.blade.php does.

    `output()` (`clicked`) is not re-emitted (CONVENTIONS.md §7.2) — bind
    `wire:click` / `x-on:click` through $attributes. Angular also toggles the
    parent's `value` on click; that state round-trip is the consumer's, since
    Blade renders once.

    DELIBERATE DIVERGENCE: `type="button"` is emitted. Angular's directive sets
    no type, so an item inside a <form> defaults to `type="submit"` and submits
    the form when toggled — almost certainly an upstream oversight for a control
    whose whole purpose is toggling. Every other button in this port
    (button.blade.php, horizontal-stepper-item) sets the type explicitly, so
    this follows the house rule rather than the upstream omission.

    NB for editors: the slash-star comments inside @props below are ordinary
    template text at compile time, NOT Blade comments — a component tag or a
    directive written in one is really compiled. A tedi component tag and an
    at-aware directive in those comments produced a "syntax error, unexpected
    token as" here until they were reworded. Only this Blade docblock is
    stripped before compilation, so tags and directives are safe in it alone
    (and never write a Blade-comment terminator inside one, which ends it early).
--}}
@aware([
    'variant' => 'primary-button-group',
    'size' => 'default',
])
@props([
    /** Identity contributing to the group's selected value. Required. */
    'value',
    /** Visible text, and the accessible name in icon-only mode. Required. */
    'label',
    /** Disables interaction via the native disabled attribute. */
    'disabled' => false,
    /** Icon rendered before the label. */
    'iconLeft' => null,
    /** Icon rendered after the label. */
    'iconRight' => null,
    /** Icon-only mode: renders just this icon, with `label` as the accessible name. */
    'icon' => null,
    /** Whether this item is selected. The parent button-group computes it for its items entries. */
    'selected' => false,
    /** Inherited from the parent button-group; set here to override for one item. */
    'variant' => 'primary-button-group',
    /** Inherited from the parent button-group; set here to override for one item. */
    'size' => 'default',
])

@php
    // BaseButtonDirective.classes(): it counts the host's element children after
    // syncIcon() has injected the icons and wrapLabel() has wrapped the text.
    // icon-only mode is the single-icon-no-label case; otherwise a leading or
    // trailing icon drops the padding modifier on that side.
    $isIconOnly = (bool) $icon;
    $iconFirst = $isIconOnly || (bool) $iconLeft;
    $iconLast = $isIconOnly || (bool) $iconRight;
@endphp

<button
    tedi-button-group-button
    type="button"
    @disabled($disabled)
    {{ $attributes->class([
        'tedi-button',
        'tedi-button-group-button',
        'tedi-button--'.$variant,
        'tedi-button--'.$size,
        'tedi-button--icon-only' => $isIconOnly,
        'tedi-button--pl' => ! $iconFirst,
        'tedi-button--pr' => ! $iconLast,
    ])->merge(array_filter([
        // Load-bearing: `.tedi-button-group .tedi-button[aria-pressed="true"]`
        // carries the entire selected appearance, so this is always emitted.
        'aria-pressed' => $selected ? 'true' : 'false',
        'aria-label' => $isIconOnly ? $label : null,
    ])) }}
>
    @if ($isIconOnly)
        <tedi:icon :name="$icon" color="inherit" :size="18" />
    @else
        @if ($iconLeft)
            <tedi:icon :name="$iconLeft" color="inherit" :size="18" />
        @endif

        <span class="tedi-button-group-button__label">{{ $label }}</span>

        @if ($iconRight)
            <tedi:icon :name="$iconRight" color="inherit" :size="18" />
        @endif
    @endif
</button>
