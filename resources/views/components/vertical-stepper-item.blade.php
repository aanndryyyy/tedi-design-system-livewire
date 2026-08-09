{{--
    TEDI Vertical Stepper Item (community).
    Port of angular/community/components/navigation/vertical-stepper/vertical-stepper-item/vertical-stepper-item.component.{ts,html}

    Ported from the `community/` tree — see CONVENTIONS.md §12. Angular's
    selector is the element `tedi-vertical-stepper-item`, so per §4 the root is
    that literal custom element; `.tedi-vertical-stepper-item` supplies
    `display: grid`, which the sub-item's `grid-template-columns: subgrid`
    depends on.

    Two Angular runtime behaviours become explicit props (CONVENTIONS.md §5),
    because Blade cannot introspect its own slot:

    | Angular | Blade |
    |---|---|
    | the parent item's `effect()` sets `subItem` on each `contentChildren()` | `:sub-item="true"` on the nested item |
    | `hasSubItems()` counts `contentChildren()` | presence of the `sub-items` slot |

    `sub-item` is a prop rather than @aware precisely because the child declares
    it itself — @aware would resolve the child's own value and never the
    parent's (CONVENTIONS.md §3). `compact` / `enumerated` are the opposite
    case: the item owns no prop of those names upstream (they are `computed()`
    reads of the stepper), so they come through @aware, with fallbacks equal to
    `tedi:vertical-stepper`'s defaults.

    The sub-items sit behind `x-show`, not `@if`, per CONVENTIONS.md §8 — they
    are in the DOM with their real class list even while collapsed. They must
    also stay *direct grid items* of this element for `subgrid` and the
    `:first-child` / `:last-child` line rules, so the wrapper carries
    `data-tedi-contents` (`display: contents`, defined in
    resources/scss/_alpine.scss) rather than being a box of its own.

    DIVERGENCES:
    - Angular's `itemSelect` output is not re-emitted (CONVENTIONS.md §7.2) —
      bind `wire:click` / `x-on:click` through $attributes on this root.
    - `route` + RouterLink become a plain `href`. Angular's `routerLinkActive`
      auto-selection has no server-side equivalent; pass `:selected` yourself.
      Angular's `opened` effect (auto-open when a sub-item is selected) is the
      same case: set `:opened="true"` alongside the selected sub-item.
    - `[item-title]` / `[item-description]` content projection become the
      `item-title` and `description` slots.
--}}
@aware([
    'compact' => false,
    'enumerated' => false,
])
@props([
    /** Step label. Ignored when the `item-title` slot is used. */
    'title' => null,
    /** Renders the title as a link to this URL instead of a button. */
    'href' => null,
    /** Renders the completed (check icon) state. */
    'completed' => false,
    /** Renders the error state. */
    'error' => false,
    /** Marks this step as the current one (aria-current="step"). */
    'selected' => false,
    /** Prevents the step from being clicked or focused. */
    'disabled' => false,
    /** Muted, non-interactive step used for informational rows. */
    'informative' => false,
    /** Set on steps nested in a parent step's `sub-items` slot — see the note above. */
    'subItem' => false,
    /** Initial expanded state of the `sub-items` slot. */
    'opened' => false,
    /** Inherited from `tedi:vertical-stepper`. */
    'compact' => false,
    /** Inherited from `tedi:vertical-stepper`. */
    'enumerated' => false,
])

@php
    $hasSubItems = isset($subItems) && $subItems->isNotEmpty();
    $hasTitleSlot = isset($itemTitle) && $itemTitle->isNotEmpty();

    // statusIcon in vertical-stepper-item.component.html renders only for
    // sub-items or when the stepper is not compact — a compact top-level step
    // shows its state inside the indicator instead.
    $showStatusIcon = ($subItem || ! $compact) && ($completed || $error);
    $showIndicatorIcon = ! $selected && $compact && ! $subItem && ($completed || $error);
@endphp

<tedi-vertical-stepper-item
    @if ($hasSubItems) x-data="{ opened: {{ $opened ? 'true' : 'false' }} }" @endif
    {{ $attributes->class([
        'tedi-vertical-stepper-item',
        'tedi-vertical-stepper-item--completed' => (bool) $completed,
        'tedi-vertical-stepper-item--error' => (bool) $error,
        'tedi-vertical-stepper-item--selected' => (bool) $selected,
        'tedi-vertical-stepper-item--disabled' => (bool) $disabled,
        'tedi-vertical-stepper-item--informative' => (bool) $informative,
        'tedi-vertical-stepper-item--sub-item' => (bool) $subItem,
        'tedi-vertical-stepper-item--compact' => (bool) $compact,
        'tedi-vertical-stepper-item--enumerated' => (bool) $enumerated,
    ])->merge(['role' => 'listitem']) }}
>
    <div class="tedi-vertical-stepper-item__indicator">
        @if ($showIndicatorIcon)
            <tedi:icon :name="$error ? 'exclamation' : 'check'" color="white" :size="16" />
        @endif
    </div>
    <div class="tedi-vertical-stepper-item__line"></div>
    <div class="tedi-vertical-stepper-item__title">
        @if ($hasSubItems)
            {{-- Angular packs these three with no whitespace between them; here
                 they are laid out normally, because `__toggle` is a flex
                 container and whitespace-only text between flex items does not
                 generate a box. --}}
            <button
                type="button"
                class="tedi-vertical-stepper-item__toggle"
                @disabled($disabled)
                aria-expanded="{{ $opened ? 'true' : 'false' }}"
                x-bind:aria-expanded="opened ? 'true' : 'false'"
                x-on:click="opened = ! opened"
            >
                <span>{{ $title }}</span>

                @if ($showStatusIcon)
                    <tedi:icon
                        :name="$error ? 'error' : 'check'"
                        :color="$error ? 'danger' : 'success'"
                        :size="16"
                        class="tedi-vertical-stepper-item__status-icon"
                        :label="$error ? __('tedi::tedi.vertical-stepper.error') : __('tedi::tedi.vertical-stepper.completed')"
                    />
                @endif

                <tedi:icon
                    name="keyboard_arrow_down"
                    :color="$disabled ? 'tertiary' : 'secondary'"
                    class="tedi-vertical-stepper-item__toggle-icon"
                    x-bind:class="opened && 'tedi-vertical-stepper-item__toggle-icon--opened'"
                />
            </button>
        @else
            @if ($hasTitleSlot)
                {{ $itemTitle }}
            @elseif ($href)
                <a
                    @if (! $disabled) href="{{ $href }}" @endif
                    @if ($disabled) aria-disabled="true" @endif
                    @if ($selected) aria-current="step" @endif
                >{{ $title }}</a>
            @else
                <button
                    type="button"
                    @disabled($disabled)
                    @if ($selected) aria-current="step" @endif
                >{{ $title }}</button>
            @endif

            @if ($showStatusIcon)
                <tedi:icon
                    :name="$error ? 'error' : 'check'"
                    :color="$error ? 'danger' : 'success'"
                    :size="16"
                    class="tedi-vertical-stepper-item__status-icon"
                    :label="$error ? __('tedi::tedi.vertical-stepper.error') : __('tedi::tedi.vertical-stepper.completed')"
                />
            @endif
        @endif
    </div>
    <div class="tedi-vertical-stepper-item__description">{{ $description ?? '' }}</div>
    @if ($hasSubItems)
        <span data-tedi-contents x-show="opened" @if (! $opened) x-cloak @endif>{{ $subItems }}</span>
    @endif
</tedi-vertical-stepper-item>
