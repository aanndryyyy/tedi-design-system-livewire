{{--
    TEDI Table Header Button.
    Port of angular/tedi/components/content/table/table-header-button/table-header-button.component.ts

    Angular's selector is `button[tedi-table-header-button]` — an ATTRIBUTE
    selector — so per CONVENTIONS.md §4 the root carries the literal
    `tedi-table-header-button` attribute in addition to the class list. Angular
    also pins `type="button"` through a static host binding; that is emitted via
    `merge()` so a consumer can still override it (§6).

    Template order is Angular's verbatim: `<ng-content />` first, the icon last.

    `(click)` is not re-emitted (§7.2). This is the sort affordance for a
    `<tedi:table>` column, and the SORT ITSELF IS THE CONSUMER'S: bind
    `wire:click` / `x-on:click` through `$attributes` (or through a column's
    `sortAttributes` entry when the button is rendered by `<tedi:table>`), and
    echo the resulting direction back in as `sort` / `icon` / `selected`.

    `icon` stays required (matching Angular's `input.required<string>()`), like
    `<tedi:pagination>`'s `page-count`. tests/IntegrityTest.php therefore needs
    a `$requiredProps` entry for this component.
--}}
@props([
    /** Material icon name rendered inside the button. Required. */
    'icon',
    /** Render the icon's "filled" variant. */
    'filled' => false,
    /** Paint the icon in the brand colour to indicate an active sort/filter. */
    'selected' => false,
    /** Disabled state. */
    'disabled' => false,
    /** Size of the icon, in pixels. */
    'iconSize' => 18,
    /** Accessible name override. Required for icon-only usage. */
    'ariaLabel' => null,
])

<button
    tedi-table-header-button
    @disabled($disabled)
    {{ $attributes->class([
        'tedi-table-header-button',
        'tedi-table-header-button--selected' => (bool) $selected,
    ])->merge(array_filter([
        'type' => 'button',
        'aria-label' => $ariaLabel,
    ])) }}
>{{ $slot }}<tedi:icon :name="$icon" :variant="$filled ? 'filled' : 'outlined'" color="inherit" :size="$iconSize" /></button>
