@storybook([
    'name' => 'Card States Secondary',
    'order' => 15,
    'status' => 'stable',
    'args' => ['pseudoStates' => [
        'hover' => ['#RadioSecondaryUnselectedHover', '#RadioSecondarySelectedHover', '#RadioSecondaryGroupedHover'],
        'active' => ['#RadioSecondaryUnselectedActive', '#RadioSecondarySelectedActive', '#RadioSecondaryGroupedActive'],
        'focusVisible' => ['#RadioSecondaryUnselectedFocus', '#RadioSecondarySelectedFocus', '#RadioSecondaryGroupedFocus'],
    ]],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

{{--
    Every state of the secondary radio card, laid out with upstream's
    stateMatrix() helper (ported below for its "columns" mobile layout). The
    Hover, Active and Focus rows are forced by storybook-addon-pseudo-states;
    the selector map is upstream's cardStatesPseudo("secondary") verbatim.

    Upstream's cell() rules, kept as-is: the Unselected column is `bordered`
    (on the card when it stands alone, on the group in an error cell);
    Selected and Grouped are checked; Grouped and Error cells are two-card
    groups where only the first card of a Grouped cell carries the id; Error
    cells carry no id and no `invalid`.
--}}
<style>
    .state-matrix__grid {
        display: grid;
        grid-template-columns: max-content repeat(
                var(--_columns),
                minmax(min-content, max-content)
            );
        gap: var(--layout-grid-gutters-16) var(--layout-grid-gutters-24);
        align-items: var(--_align);
    }

    .state-matrix__grid > * {
        grid-row: var(--d-row);
        grid-column: var(--d-col);
    }

    .state-matrix__grid > .state-matrix__mobile-only {
        display: none;
    }

    .state-matrix__error > :first-child {
        width: max-content;
    }

    @media (min-width: 48rem) {
        .state-matrix--fixed .state-matrix__grid {
            grid-template-columns:
                minmax(var(--_column-width), max-content)
                repeat(var(--_columns), minmax(var(--_column-width), max-content));
            grid-auto-rows: minmax(var(--_row-height), auto);
            gap: var(--_row-gap) var(--_column-gap);
        }
    }

    @media (max-width: 47.98rem) {
        .state-matrix--rows .state-matrix__grid {
            grid-template-columns: repeat(var(--_columns), minmax(0, 1fr));
        }

        .state-matrix--columns .state-matrix__grid {
            grid-template-columns: max-content minmax(0, 1fr);
        }

        .state-matrix__grid > * {
            grid-row: var(--m-row);
            grid-column: var(--m-col);
        }

        .state-matrix__grid > .state-matrix__mobile-only {
            display: block;
        }

        .state-matrix__grid > .state-matrix__desktop-only {
            display: none;
        }

        .state-matrix__row-label,
        .state-matrix__section-title {
            margin-top: var(--layout-grid-gutters-08);
        }
    }
</style>

@php
    $variant = 'secondary';
    $columns = ['Unselected' => 'Unselected', 'Selected' => 'Selected', 'Grouped' => 'Grouped'];
    $headers = array_values($columns);

    $rows = [];
    foreach (['Default' => false, 'Hover' => false, 'Active' => false, 'Focus' => false, 'Disabled' => true, 'Error' => false] as $state => $disabled) {
        $cells = [];
        foreach (array_keys($columns) as $column) {
            $id = 'RadioSecondary'.$column.$state;
            $isError = $state === 'Error';
            $selected = $column === 'Selected' || $column === 'Grouped';
            $cells[] = [
                // name is the cell id, as upstream's card(id, …) passes it.
                'name' => $id,
                'id' => $isError ? null : $id,
                'selected' => $selected,
                'disabled' => $disabled,
                'bordered' => $column === 'Unselected',
                'grouped' => $column === 'Grouped',
                'pair' => $column === 'Grouped' || $isError,
                'error' => $isError
                    ? ($selected ? 'Valitud väärtus ei sobi ajavahemikuga' : 'Kohustuslik on valida vähemalt 1 väärtus')
                    : null,
            ];
        }
        $rows[] = ['label' => $state, 'cells' => $cells];
    }

    // Port of upstream's stateMatrix() for mobileLayout "columns" — the only
    // layout these stories use. Each item carries its desktop (--d-*) and
    // mobile (--m-*) grid position; a null cell is upstream's "" (no cell).
    $place = function (?array $desktop, ?array $mobile, string $class = ''): array {
        return [
            'class' => trim(implode(' ', [
                $class,
                $desktop ? '' : 'state-matrix__mobile-only',
                $mobile ? '' : 'state-matrix__desktop-only',
            ])),
            'style' => trim(implode(' ', [
                $desktop ? "--d-row: {$desktop[0]}; --d-col: {$desktop[1]};" : '',
                $mobile ? "--m-row: {$mobile[0]}; --m-col: {$mobile[1]};" : '',
            ])),
        ];
    };

    $columnRows = [];
    foreach ($headers as $c => $header) {
        $next = 0;
        foreach ($rows as $r => $row) {
            $columnRows[$c][$r] = $row['cells'][$c] !== null ? $next++ : null;
        }
    }
    $sectionStart = [];
    foreach ($headers as $c => $header) {
        $sectionStart[$c] = 1;
        for ($i = 0; $i < $c; $i++) {
            $sectionStart[$c] += count(array_filter($columnRows[$i], fn ($row) => $row !== null)) + 1;
        }
    }
    $mobileRow = fn ($c, $r) => $columnRows[$c][$r] === null ? null : $sectionStart[$c] + $columnRows[$c][$r] + 1;

    $items = [['tag' => 'span', 'text' => '', 'cell' => null] + $place([1, 1], null)];
    foreach ($headers as $c => $header) {
        $items[] = ['tag' => 'strong', 'text' => $header, 'cell' => null]
            + $place([1, $c + 2], [$sectionStart[$c], '1 / -1'], $c > 0 ? 'state-matrix__section-title' : '');
    }
    foreach ($rows as $r => $row) {
        foreach ($headers as $c => $header) {
            $desktop = $c === 0 ? [$r + 2, 1] : null;
            $mobile = $mobileRow($c, $r) === null ? null : [$mobileRow($c, $r), 1];
            if ($desktop || $mobile) {
                $items[] = ['tag' => 'strong', 'text' => $row['label'], 'cell' => null] + $place($desktop, $mobile);
            }
        }
        foreach ($row['cells'] as $c => $cell) {
            $mobile = $mobileRow($c, $r) === null ? null : [$mobileRow($c, $r), 2];
            $items[] = ['tag' => 'div', 'text' => '', 'cell' => $cell] + $place([$r + 2, $c + 2], $mobile);
        }
    }
@endphp

<div class="state-matrix state-matrix--columns" style="--_columns: {{ count($headers) }}; --_align: center;">
    <div class="state-matrix__grid">
        @foreach ($items as $item)
            @if ($item['cell'] === null)
                <{{ $item['tag'] }} class="{{ $item['class'] }}" style="{{ $item['style'] }}">{{ $item['text'] }}</{{ $item['tag'] }}>
            @elseif (! $item['cell']['pair'])
                <div class="{{ $item['class'] }}" style="{{ $item['style'] }}">
                    <div>
                        <tedi:radio-card :variant="$variant" :bordered="$item['cell']['bordered']">
                            <tedi:radio :name="$item['cell']['name']" :id="$item['cell']['id']" :checked="$item['cell']['selected']" :disabled="$item['cell']['disabled']" />
                            Tekst
                        </tedi:radio-card>
                    </div>
                </div>
            @else
                <div class="{{ $item['class'] }}" style="{{ $item['style'] }}">
                    <div class="flex flex-column gap-1 state-matrix__error">
                        <tedi:radio-card-group :grouped="$item['cell']['grouped']" :bordered="$item['cell']['bordered']">
                            <tedi:radio-card :variant="$variant">
                                <tedi:radio :name="$item['cell']['name']" :id="$item['cell']['id']" :checked="$item['cell']['selected']" :disabled="$item['cell']['disabled']" />
                                Tekst
                            </tedi:radio-card>
                            <tedi:radio-card :variant="$variant">
                                <tedi:radio :name="$item['cell']['name']" :disabled="$item['cell']['disabled']" />
                                Tekst
                            </tedi:radio-card>
                        </tedi:radio-card-group>
                        @if ($item['cell']['error'] !== null)
                            <tedi:feedback-text :text="$item['cell']['error']" type="error" />
                        @endif
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
