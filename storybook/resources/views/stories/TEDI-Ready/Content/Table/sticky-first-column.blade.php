@storybook([
    'name' => 'Sticky First Column',
    'order' => 22,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=11335-186161&m=dev',
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
    $wideColumns = [
        ['key' => 'name', 'header' => 'Name', 'width' => 220],
        ['key' => 'email', 'header' => 'Email', 'width' => 260],
        ['key' => 'role', 'header' => 'Role', 'width' => 200],
        ['key' => 'location', 'header' => 'Location', 'width' => 200],
        ['key' => 'salary', 'header' => 'Salary', 'width' => 160, 'align' => 'right'],
        ['key' => 'status', 'header' => 'Status', 'width' => 160],
    ];
@endphp

<tedi:table :columns="$wideColumns" :rows="$rows" :sticky-first-column="true" />
