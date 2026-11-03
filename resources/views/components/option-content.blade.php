{{--
    TEDI Option Content.
    Port of angular/src/tedi/components/helpers/option-content/option-content.component.{ts,html}

    Angular 9 renamed DropdownItemValue to OptionContent (#491) and moved it from
    overlay/dropdown to helpers. The port follows: the tags are now
    `tedi:option-content`, `tedi:option-content-label` and
    `tedi:option-content-meta`, emitting `tedi-option-content*` classes. The old
    `tedi:dropdown-item-value*` tags are removed, with no alias.

    Both `--horizontal` and `--vertical` are emitted the way Angular does (two
    separate [class.…] bindings on mutually exclusive conditions), and both have
    rules in the vendored SCSS.

    The checkbox is the same `<input type="checkbox" tedi-checkbox>` Angular
    renders — the literal attribute this component's own SCSS descendant
    selectors key on (`> input[tedi-checkbox][type="checkbox"]`). It is written
    out rather than delegated to `tedi:checkbox` because that component adds no
    classes at these defaults, and a nested <tedi:…> tag carrying a conditional
    attribute is not parsed by Blade's component tag compiler. Angular cancels the indicator's
    click so the browser cannot toggle it out of sync with `selected`; here the
    SCSS's `pointer-events: none` on `__checkbox` / `__radio` already prevents
    the click from ever reaching it, so no handler is needed.

    `indeterminate` has no HTML attribute — it is a DOM property — so it is set
    with a one-line `x-init`. Without Alpine on the page an indeterminate
    checkbox renders unchecked, which is what Angular's SSR output does too.

    Angular's untargeted trailing `<ng-content />` is the `after` slot; the
    icon projection (`select="tedi-icon, [tediIcon]"`) is the `icon` slot. The
    default slot is the label/meta projection and renders inside
    `.tedi-option-content__content`, matching Angular's template order.
--}}
@props([
    /** Type of item value — controls the selection indicator: default | checkbox | radio. */
    'type' => 'default',
    /** Layout: horizontal (side-by-side) or vertical (stacked). */
    'layout' => 'horizontal',
    /** Whether the item is selected (controls checkbox/radio state). */
    'selected' => false,
    /** Whether the checkbox is in indeterminate state. */
    'indeterminate' => false,
    /** Whether the item is disabled. */
    'disabled' => false,
])

<tedi-option-content {{ $attributes->class([
    'tedi-option-content',
    'tedi-option-content--vertical' => $layout === 'vertical',
    'tedi-option-content--horizontal' => $layout === 'horizontal',
    'tedi-option-content--checkbox' => $type === 'checkbox',
    'tedi-option-content--radio' => $type === 'radio',
]) }}>
    @if ($type === 'checkbox')
        <input
            type="checkbox"
            tedi-checkbox
            class="tedi-option-content__checkbox"
            tabindex="-1"
            aria-hidden="true"
            @checked($selected)
            @disabled($disabled)
            @if ($indeterminate) x-init="$el.indeterminate = true" @endif
        />
    @elseif ($type === 'radio')
        <input
            type="radio"
            class="tedi-option-content__radio"
            tabindex="-1"
            aria-hidden="true"
            @checked($selected)
            @disabled($disabled)
        />
    @endif

    {{ $icon ?? '' }}

    <div class="tedi-option-content__content">
        {{ $slot }}
    </div>

    {{ $after ?? '' }}
</tedi-option-content>
