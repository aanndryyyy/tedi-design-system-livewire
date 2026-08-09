@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=11335-186161&m=dev',
    'args' => [
        'size' => 'medium',
        'striped' => false,
        'verticalBorders' => false,
        'borderless' => false,
        'stickyFirstColumn' => false,
        'stickyHeader' => false,
        'fixedLayout' => false,
        'rowHover' => false,
        'interactive' => false,
        'enableRowSelection' => false,
        'selectionMode' => 'multiple',
        'enableColumnFilters' => false,
        'caption' => '',
        'activeRowId' => '',
    ],
    'argTypes' => [
        'size' => [
            'control' => 'inline-radio',
            'options' => ['medium', 'small'],
            'description' => 'Visual size.',
            'table' => ['category' => 'appearance', 'defaultValue' => ['summary' => 'medium'], 'type' => ['summary' => 'TableSize']],
        ],
        'striped' => [
            'control' => 'boolean',
            'description' => 'Alternating row backgrounds.',
            'table' => ['category' => 'appearance', 'defaultValue' => ['summary' => 'false'], 'type' => ['summary' => 'boolean']],
        ],
        'verticalBorders' => [
            'control' => 'boolean',
            'description' => 'Vertical separators between columns.',
            'table' => ['category' => 'appearance', 'defaultValue' => ['summary' => 'false'], 'type' => ['summary' => 'boolean']],
        ],
        'borderless' => [
            'control' => 'boolean',
            'description' => 'Remove the outer border + radius.',
            'table' => ['category' => 'appearance', 'defaultValue' => ['summary' => 'false'], 'type' => ['summary' => 'boolean']],
        ],
        'stickyFirstColumn' => [
            'control' => 'boolean',
            'description' => 'Freeze the first column during horizontal scroll.',
            'table' => ['category' => 'appearance', 'defaultValue' => ['summary' => 'false'], 'type' => ['summary' => 'boolean']],
        ],
        'stickyHeader' => [
            'control' => 'boolean',
            'description' => 'Pin `<thead>` during vertical scroll. Requires `maxHeight`.',
            'table' => ['category' => 'appearance', 'defaultValue' => ['summary' => 'false'], 'type' => ['summary' => 'boolean']],
        ],
        'fixedLayout' => [
            'control' => 'boolean',
            'description' => '`table-layout: fixed` — makes column `size` / `minSize` / `maxSize` authoritative (content wraps instead of stretching the column). Required for max-width caps to hold.',
            'table' => ['category' => 'appearance', 'defaultValue' => ['summary' => 'false'], 'type' => ['summary' => 'boolean']],
        ],
        'rowHover' => [
            'control' => 'boolean',
            'description' => 'Paint a hover background on data rows. Auto-on when `interactive` is set.',
            'table' => ['category' => 'appearance', 'defaultValue' => ['summary' => 'false'], 'type' => ['summary' => 'boolean | undefined']],
        ],
        'interactive' => [
            'control' => 'boolean',
            'description' => 'Adds `role=button`, tabindex and Enter/Space activation to rows. Blade emits the markup only — bind your own wire:click (CONVENTIONS.md §7.2).',
            'table' => ['category' => 'behavior', 'defaultValue' => ['summary' => 'false'], 'type' => ['summary' => 'boolean']],
        ],
        'enableRowSelection' => [
            'control' => 'boolean',
            'description' => 'Adds the selection control column. The selection STATE is the consumer\'s — echo it back through each row\'s `selected` key.',
            'table' => ['category' => 'behavior', 'defaultValue' => ['summary' => 'false'], 'type' => ['summary' => 'boolean']],
        ],
        'selectionMode' => [
            'control' => 'inline-radio',
            'options' => ['multiple', 'single'],
            'description' => '`multiple` (default) renders checkboxes + select-all. `single` renders radios sharing one HTML group and omits select-all.',
            'table' => ['category' => 'behavior', 'defaultValue' => ['summary' => 'multiple'], 'type' => ['summary' => 'TableSelectionMode']],
        ],
        'enableColumnFilters' => [
            'control' => 'boolean',
            'description' => 'Render the per-column filter row below the header. Inert markup here — wire the inputs yourself.',
            'table' => ['category' => 'behavior', 'defaultValue' => ['summary' => 'false'], 'type' => ['summary' => 'boolean']],
        ],
        'caption' => [
            'control' => 'text',
            'description' => 'Caption rendered above the table.',
            'table' => ['category' => 'data', 'type' => ['summary' => 'string | Htmlable']],
        ],
        'activeRowId' => [
            'control' => 'text',
            'description' => 'Highlight the row whose id matches as the active row.',
            'table' => ['category' => 'behavior', 'type' => ['summary' => 'string']],
        ],
    ],
])

@php
    // table-demo-data.ts — the same Estonian sample rows the Angular stories use.
    $seed = [
        ['name' => 'Anna Tamm', 'email' => 'anna.tamm@example.ee', 'role' => 'Engineer', 'location' => 'Tallinn', 'salary' => 4200, 'status' => 'active'],
        ['name' => 'Jüri Kask', 'email' => 'juri.kask@example.ee', 'role' => 'Designer', 'location' => 'Tartu', 'salary' => 3800, 'status' => 'active'],
        ['name' => 'Maria Saar', 'email' => 'maria.saar@example.ee', 'role' => 'Product', 'location' => 'Pärnu', 'salary' => 4600, 'status' => 'active'],
        ['name' => 'Mart Mets', 'email' => 'mart.mets@example.ee', 'role' => 'Engineer', 'location' => 'Tallinn', 'salary' => 4100, 'status' => 'inactive'],
        ['name' => 'Liis Lepp', 'email' => 'liis.lepp@example.ee', 'role' => 'Ops', 'location' => 'Narva', 'salary' => 3600, 'status' => 'active'],
        ['name' => 'Kadri Kask', 'email' => 'kadri.kask@example.ee', 'role' => 'Engineer', 'location' => 'Viljandi', 'salary' => 4000, 'status' => 'active'],
        ['name' => 'Rain Roos', 'email' => 'rain.roos@example.ee', 'role' => 'Designer', 'location' => 'Rakvere', 'salary' => 3900, 'status' => 'inactive'],
    ];

    $columns = [
        ['key' => 'name', 'header' => 'Name'],
        ['key' => 'email', 'header' => 'Email'],
        ['key' => 'role', 'header' => 'Role'],
        ['key' => 'location', 'header' => 'Location'],
    ];

    $rows = [];

    foreach ($seed as $index => $person) {
        $rows[] = ['id' => (string) ($index + 1), 'cells' => $person];
    }
@endphp

@php
    $filterColumns = array_map(fn ($column) => $column + ['filterable' => true], $columns);
@endphp

<tedi:table
    :columns="$enableColumnFilters ? $filterColumns : $columns"
    :rows="$rows"
    :size="$size"
    :striped="(bool) $striped"
    :vertical-borders="(bool) $verticalBorders"
    :borderless="(bool) $borderless"
    :sticky-first-column="(bool) $stickyFirstColumn"
    :sticky-header="(bool) $stickyHeader"
    :fixed-layout="(bool) $fixedLayout"
    :row-hover="(bool) $rowHover"
    :interactive="(bool) $interactive"
    :enable-row-selection="(bool) $enableRowSelection"
    :selection-mode="$selectionMode"
    :enable-column-filters="(bool) $enableColumnFilters"
    :caption="$caption ?: null"
    :active-row-id="$activeRowId ?: null"
    :max-height="$stickyHeader ? 320 : null"
/>
