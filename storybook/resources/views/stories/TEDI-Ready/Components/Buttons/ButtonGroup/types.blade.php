@storybook([
    'name' => 'Types',
    'order' => 3,
    'status' => 'stable',
])

@php
    $items = [
        ['value' => '1', 'label' => 'Tabel'],
        ['value' => '2', 'label' => 'Loend'],
        ['value' => '3', 'label' => 'Kalender'],
    ];
@endphp

<tedi:row :cols="1" :gap-y="2">
    <tedi:col>
        <tedi:button-group variant="primary-button-group" aria-label="Esmane vaate valik" value="2" :items="$items" />
    </tedi:col>
    <tedi:col>
        <tedi:button-group variant="secondary-button-group" aria-label="Teisene vaate valik" value="2" :items="$items" />
    </tedi:col>
</tedi:row>
