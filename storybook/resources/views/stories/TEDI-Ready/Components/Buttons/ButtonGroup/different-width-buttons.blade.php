@storybook([
    'name' => 'Different Width Buttons',
    'order' => 8,
    'status' => 'stable',
])

<tedi:button-group aria-label="Erineva laiusega nupud" value="1" :items="[
    ['value' => '1', 'label' => 'Tabel'],
    ['value' => '2', 'label' => 'Loend'],
    ['value' => '3', 'label' => 'Kalender'],
]" />
