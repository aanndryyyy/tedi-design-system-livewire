{{--
    TEDI Dropdown Item.
    Port of angular/tedi/components/overlay/dropdown/dropdown-item/dropdown-item.component.{ts,html}

    Angular's selector is `li[tedi-dropdown-item]` and the vendored SCSS keys on
    exactly that, so the root is a literal `<li tedi-dropdown-item>`
    (CONVENTIONS.md §4). Angular's host emits no classes at all — only `role`,
    `aria-selected`, `aria-disabled` and `tabindex` — and neither does this
    port. (`.tedi-dropdown-item`, `--selected` and `--disabled` DO have rules in
    the vendored SCSS; they are the class-based alternative the React/select
    ports use, and emitting them here would not be Angular parity.)

    `dropdown-role` reaches this component from <tedi:dropdown-content> via
    @aware, with the same 'menu' default (CONVENTIONS.md §3).

    `selected` is the explicit-prop translation of Angular's
    `isSelected() === dropdown.value() === value()` (CONVENTIONS.md §5): the
    dropdown's `value` cannot reach the item through @aware, because Laravel
    resolves the item's own `value` attribute first and every item would then
    compare its value with itself. See dropdown.blade.php for the full note.

    `custom item value` detection (Angular's `contentChild(DropdownItemValueComponent)`)
    becomes the named `item-value` slot, per §3's `<ng-content select=…>` rule:
    given, it is rendered verbatim; omitted, the default slot is wrapped in
    <tedi:dropdown-item-value> + <tedi:dropdown-item-value-label> exactly as
    Angular's fallback branch does.

    `output()` (`itemSelect`) is not re-emitted (§7.2) — bind wire:click or
    x-on:click yourself. `close-on-select` still works: it decides whether this
    item closes the dropdown (and returns focus to the trigger) on click.

    Keyboard activation reaches those handlers the same way a mouse does:
    Enter/Space in `tediDropdown.menuKeydown` calls `item.click()` rather than
    reimplementing Angular's `onItemSelect()`, so a consumer's wire:click and
    the `close-on-select` binding below both fire from the keyboard.
--}}
@aware([
    'dropdownRole' => 'menu',
])
@props([
    /** Item value. Used with `selected` to mirror Angular's listbox selection. */
    'value' => null,
    /** Is item disabled? */
    'disabled' => false,
    /** Whether the item's label clips overflowing content (for text ellipsis). */
    'clipContent' => true,
    /** Whether selecting this item closes the dropdown. */
    'closeOnSelect' => true,
    /** Whether this item is the selected one (listbox role only). */
    'selected' => false,
])

<li
    tedi-dropdown-item
    {{ $attributes->merge(array_filter([
        'role' => $dropdownRole === 'menu' ? 'menuitem' : 'option',
        'aria-selected' => $dropdownRole === 'listbox' ? ($selected ? 'true' : 'false') : null,
        'aria-disabled' => $disabled ? 'true' : null,
        'tabindex' => $dropdownRole === 'menu' ? '-1' : ($disabled ? null : '-1'),
    ])) }}
    @if (! $disabled && $closeOnSelect)
        x-on:click="hide(true)"
    @endif
    @if ($disabled)
        {{-- Angular's @HostListener('mousedown'): a disabled item keeps its
             roving tabindex (in menus) so it stays discoverable, but must not
             take focus on a mouse press — that focus would paint mouse-focus
             styling on a non-interactive item. --}}
        x-on:mousedown.prevent
    @endif
>
    @isset($itemValue)
        {{ $itemValue }}
    @else
        <tedi:dropdown-item-value>
            <tedi:dropdown-item-value-label :clip-content="$clipContent">{{ $slot }}</tedi:dropdown-item-value-label>
        </tedi:dropdown-item-value>
    @endisset
</li>
