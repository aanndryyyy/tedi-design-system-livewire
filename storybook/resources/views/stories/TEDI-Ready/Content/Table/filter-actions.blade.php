@storybook([
    'name' => 'Filter Actions',
    'order' => 14,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=11335-186161&m=dev',
    'args' => [
        'maxHeight' => 480,
        'filterPopoverWidth' => 'small',
        'filterActions' => ['cancel', 'apply'],
    ],
    'argTypes' => [
        'maxHeight' => [
            'control' => 'number',
            'description' => 'Caps the scroll container height. Number = px, string = any CSS length.',
            'table' => ['category' => 'appearance', 'type' => ['summary' => 'number | string']],
        ],
        'filterPopoverWidth' => [
            'control' => 'text',
            'description' => "Width of the column filter popover: a preset, or any CSS length. 'none' sizes the panel to its content. A single column overrides it through `filterable: { popoverWidth }`.",
            'table' => ['category' => 'appearance', 'defaultValue' => ['summary' => '"small"'], 'type' => ['summary' => 'PopoverWidth']],
        ],
        'filterActions' => [
            'control' => 'object',
            'description' => 'Footer buttons of the column filter popover and modal, in display order. `apply` commits the draft, `clear` removes the filter, `cancel` closes and keeps the applied filter. A single column overrides it through `filterable: { actions }`.',
            'table' => ['category' => 'behavior', 'defaultValue' => ['summary' => '["clear", "apply"]'], 'type' => ['summary' => "('apply' | 'clear' | 'cancel') array"]],
        ],
    ],
])

{{--
    Upstream's FilterActions is `...Filters` with `filterActions` set to
    cancel + apply, so this is the Filters story with that default.

    The filter STATE is the consumer's (see this directory's README): here it
    is seeded like upstream's — Tööalguse kuupäev within 2019 and Tõendi
    staatus "Kehtiv" — and the rows are filtered in PHP, so the two active
    triggers render selected and the tag cloud shows their chips. In an app
    the filter controls carry `wire:model` and the footer buttons a
    `wire:click` (through `filterActionAttributes`) or a listener for the
    `tedi-table:filter-action` event; nothing here persists a change.
--}}
@php
    // table-demo-data.ts — filterablePeople: 28 rows cycling the six seeds.
    $seed = [
        ['name' => 'Mari Maasikas', 'jobStart' => '21.08.2019', 'age' => 25, 'visits' => 6, 'status' => 'Kehtiv'],
        ['name' => 'Kalle Kapsapea', 'jobStart' => '14.03.2020', 'age' => 35, 'visits' => 13, 'status' => 'Kehtiv'],
        ['name' => 'Mart Mägi', 'jobStart' => '02.01.2018', 'age' => 43, 'visits' => 26, 'status' => 'Kehtiv'],
        ['name' => 'Meelis Mets', 'jobStart' => '10.07.2021', 'age' => 64, 'visits' => 26, 'status' => 'Kehtetu'],
        ['name' => 'Kadri Kask', 'jobStart' => '30.11.2022', 'age' => 32, 'visits' => 4, 'status' => 'Aegumas'],
        ['name' => 'Liis Linn', 'jobStart' => '21.08.2019', 'age' => 21, 'visits' => 13, 'status' => 'Aegunud'],
    ];
    $certStatuses = ['Kehtiv', 'Kehtetu', 'Aegumas', 'Aegunud'];
    $statusColor = ['Kehtiv' => 'success', 'Aegumas' => 'warning', 'Kehtetu' => 'danger', 'Aegunud' => 'neutral'];

    // The controlled filter state upstream seeds the story with.
    $jobStartFilter = ['from' => '2019-01-01', 'to' => '2019-12-31'];
    $statusFilter = ['Kehtiv'];

    $rows = [];

    for ($index = 0; $index < 28; $index++) {
        $person = $seed[$index % count($seed)];
        $round = intdiv($index, count($seed));
        $person['name'] = $round === 0 ? $person['name'] : $person['name'].' '.($round + 1);

        $start = \DateTime::createFromFormat('d.m.Y', $person['jobStart'])->format('Y-m-d');

        if ($start < $jobStartFilter['from'] || $start > $jobStartFilter['to'] || ! in_array($person['status'], $statusFilter, true)) {
            continue;
        }

        $status = $person['status'];
        $person['status'] = new \Illuminate\Support\HtmlString(\Illuminate\Support\Facades\Blade::render(
            '<tedi:status-badge :color="$color" :text="$text" />',
            ['color' => $statusColor[$status], 'text' => $status],
        ));

        $rows[] = ['id' => (string) ($index + 1), 'cells' => $person];
    }

    $filterTemplate = fn (string $blade, array $data = []) => new \Illuminate\Support\HtmlString(\Illuminate\Support\Facades\Blade::render($blade, $data));

    $textFilter = $filterTemplate(<<<'BLADE'
<div style="display: flex; flex-direction: column; gap: var(--tedi-dimensions-02);">
    <tedi:form.label size="small" for="name-filter">Nimi</tedi:form.label>
    <tedi:form-field size="small">
        <tedi:text-field id="name-filter" type="text" />
    </tedi:form-field>
</div>
BLADE);

    $dateFilter = $filterTemplate(<<<'BLADE'
<div style="display: flex; flex-direction: column; gap: var(--tedi-dimensions-02);">
    <tedi:form.label size="small" for="jobStart-filter">Tööalguse kuupäev</tedi:form.label>
    <tedi:form-field size="small">
        <tedi:date-field input-id="jobStart-filter" size="small" mode="range" placeholder="pp.kk.aaaa – pp.kk.aaaa" :value="$value" />
    </tedi:form-field>
</div>
BLADE, ['value' => $jobStartFilter]);

    $statusFilterTemplate = $filterTemplate(<<<'BLADE'
<div style="display: flex; flex-direction: column; gap: var(--tedi-dimensions-04);">
    @foreach ($options as $option)
        <label style="display: inline-flex; gap: var(--tedi-dimensions-04); align-items: center;">
            <tedi:checkbox :id="'status-filter-'.$loop->index" :value="$option" :checked="in_array($option, $selected, true)" />
            <span>{{ $option }}</span>
        </label>
    @endforeach
</div>
BLADE, ['options' => $certStatuses, 'selected' => $statusFilter]);

    $columns = [
        ['key' => 'name', 'header' => 'Nimi', 'sortable' => true, 'filterable' => true, 'filterTemplate' => $textFilter],
        ['key' => 'jobStart', 'header' => 'Tööalguse kuupäev', 'sortable' => true, 'filterable' => ['popoverWidth' => '19rem'], 'filterTemplate' => $dateFilter, 'filterActive' => true],
        ['key' => 'age', 'header' => 'Vanus', 'sortable' => true],
        ['key' => 'visits', 'header' => 'Külastused', 'sortable' => true],
        ['key' => 'status', 'header' => 'Tõendi staatus', 'sortable' => true, 'filterable' => true, 'filterTemplate' => $statusFilterTemplate, 'filterActive' => true],
    ];

    // One removable chip per active column filter, labelled "Header: value".
    $chips = [
        'Tööalguse kuupäev: 01.01.2019 – 31.12.2019',
        'Tõendi staatus: '.implode(', ', $statusFilter),
    ];
@endphp

<div style="display: flex; flex-wrap: wrap; gap: var(--tedi-dimensions-04); align-items: center; margin-bottom: var(--tedi-dimensions-04);">
    @foreach ($chips as $chip)
        <tedi:tag :closable="true">{{ $chip }}</tedi:tag>
    @endforeach
    <tedi:button variant="neutral" size="small" type="button" icon-start="refresh">Tühjenda filtrid</tedi:button>
</div>

<tedi:table
    id="tedi-table-filter-actions"
    :columns="$columns"
    :rows="$rows"
    :max-height="$maxHeight ?: null"
    :filter-popover-width="$filterPopoverWidth ?: 'small'"
    :filter-actions="is_array($filterActions) ? $filterActions : ['cancel', 'apply']"
/>
