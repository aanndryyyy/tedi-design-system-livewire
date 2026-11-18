@storybook([
    'name' => 'Sortable Custom Header',
    'order' => 12,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=11335-186161&m=dev',
])

@php
    // table-demo-data.ts — the same Estonian sample rows the Angular stories use.
    $seed = [
        ['name' => 'Anna Tamm', 'role' => 'Engineer', 'location' => 'Tallinn'],
        ['name' => 'Jüri Kask', 'role' => 'Designer', 'location' => 'Tartu'],
        ['name' => 'Maria Saar', 'role' => 'Product', 'location' => 'Pärnu'],
        ['name' => 'Mart Mets', 'role' => 'Engineer', 'location' => 'Tallinn'],
        ['name' => 'Liis Lepp', 'role' => 'Ops', 'location' => 'Narva'],
    ];

    $rows = [];

    foreach ($seed as $index => $person) {
        $rows[] = ['id' => (string) ($index + 1), 'cells' => $person];
    }

    // A custom header is any Htmlable — here a Blade snippet with an info
    // popover. Upstream's column also has a filter popover, which is not
    // ported (see this directory's README).
    $roleHeader = new \Illuminate\Support\HtmlString(\Illuminate\Support\Facades\Blade::render(<<<'BLADE'
Roll
<tedi:popover container-id="table-role-help" position="bottom-start" :prevent-overflow="true">
    <x-slot:trigger>
        <tedi:info-button
            tedi-popover-trigger
            aria-label="Rolli selgitus"
            id="table-role-help_trigger"
            tabindex="0"
            aria-haspopup="dialog"
            aria-expanded="false"
            x-ref="trigger"
            x-on:click="toggle()"
            x-bind:aria-expanded="open"
            x-bind:aria-controls="open ? 'table-role-help' : null"
        />
    </x-slot:trigger>

    <tedi:popover-content>Töötaja põhiroll organisatsioonis.</tedi:popover-content>
</tedi:popover>
BLADE));

    // The sort itself is the consumer's (CONVENTIONS.md §7.2). With a custom
    // header the table renders it as-is and adds an icon-only sort button,
    // named from `label` ("Sort by Roll ascending").
    $columns = [
        ['key' => 'name', 'header' => 'Nimi', 'sortable' => true, 'sort' => 'none'],
        ['key' => 'role', 'header' => $roleHeader, 'label' => 'Roll', 'sortable' => true, 'sort' => 'none'],
        ['key' => 'location', 'header' => 'Asukoht', 'sortable' => true, 'sort' => 'none'],
    ];
@endphp

<tedi:table id="tedi-table-sortable-custom-header" :columns="$columns" :rows="$rows" />
