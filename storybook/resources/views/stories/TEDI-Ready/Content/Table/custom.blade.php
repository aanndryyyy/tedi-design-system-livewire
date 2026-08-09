@storybook([
    'name' => 'Custom',
    'order' => 29,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=11335-186161&m=dev',
])

@php
    use Illuminate\Support\Facades\Blade;
    use Illuminate\Support\HtmlString;

    $doctors = [
        ['id' => '1', 'name' => 'Kalle Kask', 'specialty' => 'Dermatovenereoloog', 'note' => '', 'noteType' => ''],
        ['id' => '2', 'name' => 'Mari Maasikas', 'specialty' => 'Kopsuarst', 'note' => 'Vastuvõtt on ajutiselt peatatud', 'noteType' => 'error'],
        ['id' => '3', 'name' => 'Vello Vaarikas', 'specialty' => 'Kõrva-nina-kurguarst', 'note' => 'Järjekord üle 3 kuu', 'noteType' => 'warning'],
    ];

    $initials = fn (string $name) => implode('', array_map(
        fn ($part) => mb_substr($part, 0, 1),
        array_slice(explode(' ', $name), 0, 2)
    ));

    $columns = [
        ['key' => 'name', 'header' => 'Nimi', 'width' => 280],
        ['key' => 'note', 'header' => 'Märkus'],
    ];

    $rows = array_map(fn ($doctor) => [
        'id' => $doctor['id'],
        'cells' => [
            'name' => new HtmlString(
                '<div style="display:flex; align-items:center; gap:12px;">'
                .'<span aria-hidden="true" style="display:inline-flex; align-items:center; justify-content:center;'
                .' width:32px; height:32px; border-radius:50%; background:var(--tedi-primary-100);">'
                .e($initials($doctor['name'])).'</span>'
                .'<div><div>'.e($doctor['name']).'</div>'
                .'<div style="color:var(--general-text-secondary);">'.e($doctor['specialty']).'</div></div></div>'
            ),
            'note' => $doctor['note']
                ? new HtmlString(Blade::render(
                    '<tedi:alert type="'.$doctor['noteType'].'" role="status">'.e($doctor['note']).'</tedi:alert>'
                ))
                : '',
        ],
    ], $doctors);
@endphp

<tedi:table :columns="$columns" :rows="$rows" />
