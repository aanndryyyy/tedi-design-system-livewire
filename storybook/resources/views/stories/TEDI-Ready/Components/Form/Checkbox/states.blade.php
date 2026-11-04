@storybook([
    'name' => 'States',
    'order' => 12,
    'status' => 'subset',
    'args' => ['pseudoStates' => [
        'hover' => ['#HoverUnselected', '#HoverSelected', '#HoverErrorUnselected', '#HoverErrorSelected'],
        'active' => ['#ActiveUnselected', '#ActiveSelected', '#ActiveErrorUnselected', '#ActiveErrorSelected'],
        'focusVisible' => ['#FocusUnselected', '#FocusSelected', '#FocusErrorUnselected', '#FocusErrorSelected'],
    ]],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

{{--
    All visual states of the checkbox, laid out with upstream's stateMatrix()
    helper (ported below for its "columns" mobile layout). The Hover, Active
    and Focus rows are forced by storybook-addon-pseudo-states: the
    `pseudoStates` arg is what .storybook/preview.js turns into the addon's
    `parameters.pseudo`, copied verbatim from upstream's statePseudoIds().

    Upstream's Indeterminate row is dropped: indeterminate has no static
    markup (see default.blade.php), which is what keeps this story a subset.
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
    $headers = ['Unselected', 'Selected', 'Error unselected', 'Error selected'];

    // stateRows, minus Indeterminate. Each cell: [id, selected, error].
    $stateRows = [
        ['label' => 'Default', 'id' => 'Default'],
        ['label' => 'Hover', 'id' => 'Hover'],
        ['label' => 'Active', 'id' => 'Active'],
        ['label' => 'Focus', 'id' => 'Focus'],
        ['label' => 'Disabled', 'id' => 'Disabled', 'disabled' => true],
        ['label' => 'Mandatory', 'id' => 'Mandatory', 'required' => true, 'error' => false],
    ];
    $stateCell = fn (array $row, bool $selected, bool $error = false) => [
        'id' => $row['id'].($error ? 'Error' : '').($selected ? 'Selected' : 'Unselected'),
        'selected' => $selected,
        'error' => $error,
        'disabled' => $row['disabled'] ?? false,
        'required' => $row['required'] ?? false,
    ];
    $rows = array_map(fn (array $row) => [
        'label' => $row['label'],
        'cells' => [
            $stateCell($row, false),
            $stateCell($row, true),
            ($row['error'] ?? true) ? $stateCell($row, false, true) : null,
            ($row['error'] ?? true) ? $stateCell($row, true, true) : null,
        ],
    ], $stateRows);

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

<div class="state-matrix state-matrix--columns state-matrix--fixed" style="--_columns: {{ count($headers) }}; --_align: center; --_column-width: 10.375rem; --_row-height: 2.5rem; --_column-gap: var(--layout-grid-gutters-16); --_row-gap: var(--layout-grid-gutters-08);">
    <div class="state-matrix__grid">
        @foreach ($items as $item)
            @if ($item['cell'] === null)
                <{{ $item['tag'] }} class="{{ $item['class'] }}" style="{{ $item['style'] }}">{{ $item['text'] }}</{{ $item['tag'] }}>
            @else
                <div class="{{ $item['class'] }}" style="{{ $item['style'] }}">
                    <div>
                        <tedi:form.label color="primary" class="flex align-items-center" :required="$item['cell']['required']">
                            <tedi:checkbox
                                style="margin-right: var(--form-checkbox-radio-inner-spacing)"
                                :id="$item['cell']['id']"
                                :checked="$item['cell']['selected']"
                                :invalid="$item['cell']['error']"
                                :disabled="$item['cell']['disabled']"
                            />
                            Tekst
                        </tedi:form.label>
                        @if ($item['cell']['error'])
                            <tedi:feedback-text text="Kohustuslik väli" type="error" />
                        @endif
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
