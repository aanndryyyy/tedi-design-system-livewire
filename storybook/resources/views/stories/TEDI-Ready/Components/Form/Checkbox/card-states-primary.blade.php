@storybook([
    'name' => 'Card States Primary',
    'order' => 13,
    'status' => 'stable',
    'args' => ['pseudoStates' => [
        'hover' => ['#PrimaryHoverUnselected', '#PrimaryHoverSelected'],
        'active' => ['#PrimaryActiveUnselected', '#PrimaryActiveSelected'],
        'focusVisible' => ['#PrimaryFocusUnselected', '#PrimaryFocusSelected'],
    ]],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

{{--
    Every state of the primary checkbox card, laid out with upstream's
    stateMatrix() helper (ported below for its "columns" mobile layout). The
    Hover, Active and Focus rows are forced by storybook-addon-pseudo-states;
    the selector map is upstream's cardStatesPseudo("Primary") verbatim.
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
    $variant = 'primary';
    $headers = ['Unselected', 'Selected'];

    // cardStates, then the Error row of two-card groups. Single cells carry
    // [id, selected, disabled]; error cells carry [error => message, selected].
    $rows = [];
    foreach (['Default' => false, 'Hover' => false, 'Active' => false, 'Focus' => false, 'Disabled' => true] as $state => $disabled) {
        $rows[] = [
            'label' => $state,
            'cells' => array_map(fn (bool $selected) => [
                'id' => 'Primary'.$state.($selected ? 'Selected' : 'Unselected'),
                'selected' => $selected,
                'disabled' => $disabled,
                'error' => null,
            ], [false, true]),
        ];
    }
    $rows[] = [
        'label' => 'Error',
        'cells' => [
            ['selected' => false, 'error' => 'Kohustuslik on valida vähemalt 1 väärtus'],
            ['selected' => true, 'error' => 'Kohustuslik on valida vähemalt 2 väärtust'],
        ],
    ];

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
            @elseif ($item['cell']['error'] === null)
                <div class="{{ $item['class'] }}" style="{{ $item['style'] }}">
                    <tedi:checkbox-card :variant="$variant">
                        <tedi:checkbox :id="$item['cell']['id']" :checked="$item['cell']['selected']" :disabled="$item['cell']['disabled']" />
                        Tekst
                    </tedi:checkbox-card>
                </div>
            @else
                <div class="{{ $item['class'] }}" style="{{ $item['style'] }}">
                    <div class="flex flex-column gap-1 state-matrix__error">
                        <tedi:checkbox-card-group>
                            <tedi:checkbox-card :variant="$variant">
                                <tedi:checkbox :checked="$item['cell']['selected']" />
                                Tekst
                            </tedi:checkbox-card>
                            <tedi:checkbox-card :variant="$variant">
                                <tedi:checkbox />
                                Tekst
                            </tedi:checkbox-card>
                        </tedi:checkbox-card-group>
                        <tedi:feedback-text :text="$item['cell']['error']" type="error" />
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
