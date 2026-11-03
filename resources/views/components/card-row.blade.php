{{--
    TEDI Card Row.
    Port of angular/src/tedi/components/content/card/card-row/card-row.component.{ts,html}

    `direction` lays the cells out in a row (the default) or a column. Upstream
    asks for it instead of flex-direction utility classes because the row rounds
    the corners it shares with the card from it: `card-row.component.scss` keys
    the horizontal vs. vertical `card-corners` mixin on `tedi-card-row--column`.

    Breakpoint inputs (xs–xxl) are not ported (CONVENTIONS.md §7), so the
    direction cannot change per breakpoint here.
--}}
@props([
    /** row|column — direction the cells are laid out in. */
    'direction' => 'row',
])

<div {{ $attributes->class([
    'tedi-card-row',
    'tedi-card-row--column' => $direction === 'column',
]) }}>
    {{ $slot }}
</div>
