@storybook([
    'name' => 'Autocomplete: hide on scroll',
    'order' => 15,
    'status' => 'stable',
    'args' => [],
])

{{--
    Angular starts with an empty field and refilters on every keystroke. A
    static render has no server to refilter on (see autocomplete.blade.php),
    and with an empty match list the panel would never have rows to scroll —
    so this story passes the whole list up front. Focus the field to open it,
    then scroll the page (closes) or the list (stays open).
--}}
@php
    $people = [
        ['label' => 'Mari Maasikas', 'description' => 'Tootejuht'],
        ['label' => 'Marelle Mets', 'description' => 'Disainer'],
        ['label' => 'Marjanne Meri', 'description' => 'Arendaja'],
        ['label' => 'Mart Mesi', 'description' => 'Analüütik'],
        ['label' => 'Martin Saar', 'description' => 'Arendaja'],
        ['label' => 'Margit Mänd', 'description' => 'Testija'],
        ['label' => 'Marko Mägi', 'description' => 'Tootejuht'],
        ['label' => 'Maris Metsis', 'description' => 'Disainer'],
        ['label' => 'Marta Mölder', 'description' => 'Analüütik'],
        ['label' => 'Marek Muru', 'description' => 'Arendaja'],
        ['label' => 'Kalle Kask', 'description' => 'Testija'],
        ['label' => 'Kati Kuusk', 'description' => 'Tootejuht'],
        ['label' => 'Tõnu Tamm', 'description' => 'Disainer'],
        ['label' => 'Liisa Lepp', 'description' => 'Arendaja'],
        ['label' => 'Jaan Järv', 'description' => 'Analüütik'],
        ['label' => 'Piret Pärn', 'description' => 'Testija'],
        ['label' => 'Siim Sepp', 'description' => 'Arendaja'],
        ['label' => 'Anu Aasa', 'description' => 'Tootejuht'],
        ['label' => 'Rein Rand', 'description' => 'Disainer'],
        ['label' => 'Tiiu Tuul', 'description' => 'Analüütik'],
    ];
@endphp

<tedi:search
    input-id="search-hide-on-scroll"
    label="Otsi"
    bind-description="description"
    placeholder="Hakka nime trükkima…"
    :hide-on-scroll="true"
    :suggestions="$people"
/>
