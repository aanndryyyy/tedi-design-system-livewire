@storybook([
    'name' => 'Stretched',
    'order' => 9,
    'status' => 'stable',
])

<tedi:button-group aria-label="Venitatud" :stretch="true" value="2" :items="[
    ['value' => '1', 'label' => 'Tabel'],
    ['value' => '2', 'label' => 'Loend'],
    ['value' => '3', 'label' => 'Kalender'],
]" />
