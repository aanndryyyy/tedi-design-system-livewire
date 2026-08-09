{{--
    TEDI Button Group.
    Port of angular/tedi/components/buttons/button-group/button-group.component.{ts,html}

    Angular's selector is the element `tedi-button-group`, so per CONVENTIONS.md
    §4 the root is that literal custom element. `.tedi-button-group` supplies
    `display: flex`, so the unknown element needs no display fallback.

    CONTENT PROJECTION → EXPLICIT ARRAY (CONVENTIONS.md §5). Angular collects its
    items with `contentChildren(ButtonGroupButtonDirective)` and reads each one's
    inputs back to build the mobile dropdown. Blade cannot introspect its own
    slot, so `items` is the primary API — an array of:

        ['value' => '1', 'label' => 'Tabel', 'iconLeft' => 'table', 'disabled' => false]

      value                  required, the identity compared against `value`
      label                  required, visible text / accessible name
      disabled               optional
      iconLeft/iconRight     optional
      icon                   optional, icon-only mode
      variant/size           optional, per-item override of the group's

    `{{ $slot }}` is ALSO rendered, and is not a redundant second API: the
    vendored SCSS has dedicated `.tedi-button-group tedi-tooltip` rules for
    tooltip-wrapped items, and Angular's own IconOnly story wraps each item in a
    <tedi-tooltip>. That composition cannot be expressed as an array entry, so
    slot-written <tedi:button-group-button> children are supported and must pass
    their own `:selected`. The mobile dropdown is built from `items` ONLY — a
    slot-only group has nothing to enumerate and so cannot collapse.

    Angular's `value` + `isSelected()` live here: this component computes each
    `items` entry's `selected` and passes it down explicitly, because the group's
    value cannot reach the child through @aware (see button-group-button.blade.php).

    `variant` and `size` DO reach the child through @aware, with matching
    defaults per CONVENTIONS.md §3.

    NOT PORTED (CONVENTIONS.md §7 #1 + §5): `enableMobileDropdown` and
    `mobileBreakpoint`. Angular derives `isDropdownMode()` from
    `enableMobileDropdown() && breakpointService.isBelowBreakpoint(mobileBreakpoint())`,
    which is resolved in JavaScript against the live viewport. Server-rendered
    Blade has no viewport, and TEDI ships no media query that would toggle
    `--dropdown-mode` from CSS, so neither input is declared (a consumer passing
    one gets a visible stray attribute rather than a silent no-op). The branch is
    selected by the explicit `dropdown-mode` prop instead. As in Angular, BOTH
    the strip and the dropdown render when it is on — `.tedi-button-group--dropdown-mode`
    is what hides the strip.

    DIVERGENCE: the dropdown trigger's icon is 24px at the default size rather
    than Angular's hardcoded 18px. The trigger is composed from <tedi:button>
    via `icon-start` — which is what produces the correct `--pr`-without-`--pl`
    class parity — and that component sizes its icon from the button size. At
    `size="small"` both are 18px.

    Upstream's `appendTo="body"` on the dropdown has no Blade equivalent; see
    the re-parenting divergence documented on dropdown.blade.php (CONVENTIONS.md §11).

    COSMETIC DIVERGENCE: an unselected dropdown item renders `class=""` where
    Angular emits no class attribute. Angular's binding is
    `[class.tedi-dropdown-item--selected]`, and there is no way to reproduce
    "attribute absent" through a Blade component tag: `:class="null"` and
    `:class="''"` both render the empty attribute, and an attribute-bag spread
    (`{{ $bag }}`) is not supported by this package's `tedi:` tag syntax at all —
    the compiler silently leaves the opening tag uncompiled while still compiling
    the closing one, producing "syntax error, unexpected token endif". The empty
    attribute carries no class tokens, so it matches no CSS rule and is invisible
    to the §4 guardrail; duplicating the whole item markup across two @if
    branches was not worth the repetition.

    `output()` (`selectionChange`) is not re-emitted (§7.2).
--}}
@props([
    /** The items. See the shape documented above. */
    'items' => [],
    /** Variant applied to every item; any ButtonVariant works. */
    'variant' => 'primary-button-group',
    /** default|small — applied to every item. */
    'size' => 'default',
    /** When true, several values can be selected and `value` is an array. */
    'multiple' => false,
    /** Selected value(s): a string, or an array when `multiple`. */
    'value' => null,
    /** When true, items share the available horizontal space equally. */
    'stretch' => false,
    /** Accessible name for the group. */
    'ariaLabel' => null,
    /** Renders the collapsed dropdown branch — see the note above. */
    'dropdownMode' => false,
    /** Label for the dropdown trigger. Falls back to the `buttonGroup.menu` translation. */
    'dropdownLabel' => null,
    /** static keeps `dropdownLabel`; selected shows the selected item's label. */
    'dropdownLabelMode' => 'static',
])

@php
    $groupItems = array_values($items);

    // isSelected() from button-group.component.ts.
    $isSelected = function (array $item) use ($multiple, $value): bool {
        $itemValue = $item['value'] ?? null;

        return $multiple
            ? (is_array($value) && in_array($itemValue, $value, true))
            : $value === $itemValue;
    };

    // isButtonGroupVariant(): only the two *-button-group variants get the
    // segmented (small-radius) geometry.
    $isButtonGroupVariant = in_array($variant, ['primary-button-group', 'secondary-button-group'], true);

    // triggerVariant(): a group variant maps to its plain counterpart.
    $triggerVariant = match ($variant) {
        'primary-button-group' => 'primary',
        'secondary-button-group' => 'secondary',
        default => $variant,
    };

    $selectedItem = null;

    foreach ($groupItems as $item) {
        if ($isSelected($item)) {
            $selectedItem = $item;
            break;
        }
    }

    $staticLabel = $dropdownLabelMode === 'static' || $multiple;
    $labelFallback = $dropdownLabel ?? __('tedi::tedi.buttonGroup.menu');

    // triggerLabel() / triggerIcon().
    $triggerLabel = $staticLabel
        ? $labelFallback
        : ($selectedItem['label'] ?? $labelFallback);

    $triggerIcon = $staticLabel
        ? 'menu'
        : ($selectedItem['iconLeft'] ?? $selectedItem['icon'] ?? 'menu');

@endphp

<tedi-button-group {{ $attributes->class([
    'tedi-button-group',
    'tedi-button-group--stretch' => $stretch,
    'tedi-button-group--dropdown-mode' => $dropdownMode,
    'tedi-button-group--segmented' => $isButtonGroupVariant,
])->merge(array_filter([
    'role' => $dropdownMode ? null : 'group',
    'aria-label' => $ariaLabel,
])) }}>
    @foreach ($groupItems as $item)
        <tedi:button-group-button
            :value="$item['value'] ?? ''"
            :label="$item['label'] ?? ''"
            :disabled="$item['disabled'] ?? false"
            :icon-left="$item['iconLeft'] ?? null"
            :icon-right="$item['iconRight'] ?? null"
            :icon="$item['icon'] ?? null"
            :variant="$item['variant'] ?? $variant"
            :size="$item['size'] ?? $size"
            :selected="$isSelected($item)"
        />
    @endforeach

    {{ $slot }}

    @if ($dropdownMode)
        <tedi:dropdown class="tedi-button-group__dropdown" :offset="0">
            <tedi:dropdown-trigger>
                <tedi:button
                    :variant="$triggerVariant"
                    :size="$size"
                    :icon-start="$triggerIcon"
                    :aria-label="$ariaLabel"
                    class="tedi-button-group__dropdown-trigger"
                >{{ $triggerLabel }}</tedi:button>
            </tedi:dropdown-trigger>

            <tedi:dropdown-content>
                @foreach ($groupItems as $item)
                    @php
                        // Angular's `iconLeft() ?? icon()` fallback for the menu item.
                        $leadIcon = $item['iconLeft'] ?? $item['icon'] ?? null;
                    @endphp

                    <tedi:dropdown-item
                        :class="$isSelected($item) ? 'tedi-dropdown-item--selected' : ''"
                        :value="$item['value'] ?? ''"
                        :disabled="$item['disabled'] ?? false"
                    >
                        <x-slot:item-value>
                            <tedi:dropdown-item-value>
                                @if ($leadIcon)
                                    <tedi:icon :name="$leadIcon" color="inherit" :size="18" />
                                @endif

                                <tedi:dropdown-item-value-label>{{ $item['label'] ?? '' }}</tedi:dropdown-item-value-label>

                                @if ($item['iconRight'] ?? null)
                                    <tedi:icon :name="$item['iconRight']" color="inherit" :size="18" />
                                @endif
                            </tedi:dropdown-item-value>
                        </x-slot:item-value>
                    </tedi:dropdown-item>
                @endforeach
            </tedi:dropdown-content>
        </tedi:dropdown>
    @endif
</tedi-button-group>
