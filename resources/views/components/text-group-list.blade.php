{{--
    TEDI Text Group List — STYLED ONLY INSIDE <tedi:table-card>.
    Port of react/src/tedi/components/content/text-group/text-group-list/text-group-list.tsx
    (CONVENTIONS.md §13).

    Label / value pairs in ONE `<dl>`, one `div.tedi-text-group__row` per pair.
    It exists in this package because `<tedi:table-card>` is built on it, and
    so that the table card's `children` (React's "child row-groups") can be
    written the way upstream's stories write them.

    WHY ONLY INSIDE A TABLE CARD. Its stylesheet is React's
    text-group.module.scss, whose class names collide with the Angular
    text-group this package ships as `<tedi:text-group>`. It is therefore loaded
    scoped under `.tedi-table-card` (table-card.composition.scss, CONVENTIONS.md
    §13.7). Outside a table card this renders an unstyled `<dl>`. For a
    standalone label / value pair use `<tedi:text-group>`; React's own
    `TextGroup` / `TextGroup.List` stories are not ported for the same reason.

    `items` is an array of ['label' => …, 'value' => …] with the optional
    per-row overrides `labelAlign`, `valueAlign`, `labelWidth` and `colSpan`.
    A string label is wrapped in a label (React's `<Label>`, here
    `<tedi:form.label>`, sized by `label-size`); a string value is escaped. Pass any Htmlable
    (`new HtmlString(…)`, a rendered view) to emit markup as-is.

    Upstream quirks ported from the code, not the prop docs:
      * a numeric `labelWidth` is a PERCENT (`resolveLabelWidth`);
      * a numeric `rowGap` is PIXELS. (`<tedi:table-card>` converts its own
        numeric `rowGap` to rem before passing it down, as upstream does.)

    DROPPED CLASS (CONVENTIONS.md §4): upstream also emits
    `tedi-text-group--vertical`, for which neither React's nor Angular's
    stylesheet has a rule. Restore it if TEDI ever writes one.

    Breakpoint props (`sm`, `md`, …) are not ported (CONVENTIONS.md §7).
--}}
@props([
    /** Label / value pairs: [['label' => …, 'value' => …, 'labelAlign' => …, 'valueAlign' => …, 'labelWidth' => …, 'colSpan' => …], …]. */
    'items' => [],
    /** horizontal|vertical */
    'type' => 'vertical',
    /** left|right. Vertical layout only supports left. */
    'labelAlign' => 'left',
    /** left|right — horizontal only. `right` grows the value flush against the trailing edge. */
    'valueAlign' => 'left',
    /** Label column width: any CSS length, or a number read as a percent. */
    'labelWidth' => 'auto',
    /** Equal-width columns (CSS grid). 1 keeps the plain stacked list. */
    'columns' => 1,
    /** Vertical gap between rows: any CSS length, or a number read as pixels. */
    'rowGap' => null,
    /** start|center — vertical alignment of label against value. Horizontal only. */
    'rowAlign' => 'start',
    /** default|small — size of the label a string `label` is wrapped in. */
    'labelSize' => 'default',
])

@use('Illuminate\Contracts\Support\Htmlable')
@php
    $resolveLabelWidth = fn ($width) => is_int($width) || is_float($width) ? $width.'%' : (string) $width;
    $render = fn ($value) => $value instanceof Htmlable ? $value->toHtml() : e((string) ($value ?? ''));

    $listStyle = ['--label-width: '.$resolveLabelWidth($labelWidth)];
    if ($columns > 1) {
        $listStyle[] = '--tedi-text-group-columns: '.$columns;
    }
    if ($rowGap !== null) {
        $listStyle[] = '--tedi-text-group-list-gap: '.(is_int($rowGap) || is_float($rowGap) ? $rowGap.'px' : $rowGap);
    }
@endphp

<dl
    {{ $attributes->class([
        'tedi-text-group',
        'tedi-text-group--list',
        'tedi-text-group--horizontal' => $type === 'horizontal',
        'tedi-text-group--columns' => $columns > 1,
        'tedi-text-group--row-align-center' => $type === 'horizontal' && $rowAlign === 'center',
    ])->merge(['style' => implode('; ', $listStyle)]) }}
>
    @foreach ($items as $item)
        @php
            $rowLabelAlign = $item['labelAlign'] ?? $labelAlign;
            $rowValueAlign = $item['valueAlign'] ?? $valueAlign;
            $rowStyle = [];
            if (array_key_exists('labelWidth', $item) && $item['labelWidth'] !== null) {
                $rowStyle[] = '--label-width: '.$resolveLabelWidth($item['labelWidth']);
            }
            if ($columns > 1 && ($item['colSpan'] ?? 0) > 1) {
                $rowStyle[] = 'grid-column: span '.min($item['colSpan'], $columns);
            }
            $label = $item['label'] ?? '';
        @endphp
        <div class="tedi-text-group__row" @if ($rowStyle) style="{{ implode('; ', $rowStyle) }}" @endif>
            <dt class="tedi-text-group__label tedi-text-group--align-{{ $rowLabelAlign }}">
                @if (is_string($label))
                    <tedi:form.label :size="$labelSize === 'small' ? 'small' : 'default'">{{ $label }}</tedi:form.label>
                @else
                    {!! $render($label) !!}
                @endif
            </dt>
            <dd class="tedi-text-group__value tedi-text-group__value--align-{{ $rowValueAlign }}">{!! $render($item['value'] ?? '') !!}</dd>
        </div>
    @endforeach
</dl>
