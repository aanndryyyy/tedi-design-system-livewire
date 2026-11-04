{{--
    TEDI Dropdown Item.
    Port of angular/src/tedi/components/overlay/dropdown/dropdown-item/dropdown-item.component.{ts,html}

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

    Custom option content detection (Angular 9's
    `contentChild(OptionContentComponent)`, formerly `customItemValue`) becomes
    the named `item-value` slot, per §3's `<ng-content select=…>` rule: given,
    it is rendered verbatim; omitted, the default slot is wrapped in
    `tedi:option-content` + `tedi:option-content-label` exactly as Angular's
    fallback branch does. The slot keeps its `item-value` name.

    `output()` (`itemSelect`) is not re-emitted (§7.2) — bind `wire:click` or
    `@click` yourself. Not `x-on:click`: this element already carries its own
    `x-on:click` close handler, and the HTML parser keeps only the first of two
    attributes with the same name, so one of the two would be silently dropped. `close-on-select` still works: it decides whether this
    item closes the dropdown (and returns focus to the trigger) on click.

    Keyboard activation reaches those handlers the same way a mouse does:
    Enter/Space in `tediDropdown.menuKeydown` calls `item.click()` rather than
    reimplementing Angular's `onItemSelect()`, so a consumer's wire:click and
    the `close-on-select` binding below both fire from the keyboard.

    A disabled item cancels clicks in the capture phase, as Angular 9 does, so a
    consumer's own `wire:click` / `x-on:click` on it or on its content never
    fires either.

    Host ARIA follows Angular 9's hostRole() / hostAriaSelected() /
    hostAriaDisabled() / hostTabindex():

    - In a `list` panel the `li` gets none of them; the projected link or button
      is the item and keeps its own semantics and tab stop.
    - With `interactive-content` in a `menu` / `listbox`, the `li` becomes
      `role="none"` and the role, `aria-disabled`, `aria-selected` (listbox) and
      roving tabindex move onto the first projected `a[href], button`. Angular
      does that in an afterRenderEffect; Blade cannot touch slot markup, so a
      one-line `x-init` stamps the server-known values onto it, and
      `tediDropdown` reads the item's state from that element from then on
      (CONVENTIONS.md §11's "the item template's ARIA is load-bearing" now
      means the control's ARIA for these items). Without Alpine the control
      keeps its native semantics, which is what Angular's SSR output has too.
--}}
@aware([
    'dropdownRole' => 'menu',
])
@props([
    /** Item value. Used with `selected` to mirror Angular's listbox selection. */
    'value' => null,
    /** Is item disabled? */
    'disabled' => false,
    /**
     * Whether the projected content is itself the interactive control (a link or
     * button). In a menu or listbox the item role and roving tabindex move onto it.
     */
    'interactiveContent' => false,
    /** Whether the item's label clips overflowing content (for text ellipsis). */
    'clipContent' => true,
    /** Whether selecting this item closes the dropdown. */
    'closeOnSelect' => true,
    /** Whether this item is the selected one (listbox role only). */
    'selected' => false,
])

@php
    $isWidget = $dropdownRole !== 'list';
    $itemRole = match ($dropdownRole) {
        'menu' => 'menuitem',
        'listbox' => 'option',
        default => null,
    };
    // Angular's controlOwnsSemantics(): the projected control, not the `li`,
    // carries the item's ARIA state and focus.
    $controlOwnsSemantics = $interactiveContent || ! $isWidget;
@endphp

<li
    tedi-dropdown-item
    {{ $attributes->merge(array_filter([
        'role' => ! $isWidget ? null : ($controlOwnsSemantics ? 'none' : $itemRole),
        'aria-selected' => ! $controlOwnsSemantics && $dropdownRole === 'listbox' ? ($selected ? 'true' : 'false') : null,
        'aria-disabled' => ! $controlOwnsSemantics && $disabled ? 'true' : null,
        'tabindex' => $controlOwnsSemantics ? null : ($dropdownRole === 'menu' ? '-1' : ($disabled ? null : '-1')),
    ])) }}
    @if ($interactiveContent && $isWidget)
        x-init="(() => {
            const control = $el.querySelector('a[href], button');
            if (! control) return;
            control.setAttribute('role', '{{ $itemRole }}');
            @if ($disabled) control.setAttribute('aria-disabled', 'true'); @endif
            @if ($dropdownRole === 'listbox') control.setAttribute('aria-selected', '{{ $selected ? 'true' : 'false' }}'); @endif
            @if ($dropdownRole === 'menu' || ! $disabled) control.setAttribute('tabindex', '-1'); @endif
        })()"
    @endif
    @if (! $disabled && $closeOnSelect)
        x-on:click="hide(true)"
    @endif
    @if ($disabled)
        {{-- Angular's capture-phase click listener: nothing inside a disabled
             item reacts to a click. --}}
        x-on:click.capture.prevent.stop
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
        <tedi:option-content>
            <tedi:option-content-label :clip-content="$clipContent">{{ $slot }}</tedi:option-content-label>
        </tedi:option-content>
    @endisset
</li>
