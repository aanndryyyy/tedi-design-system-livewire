@storybook([
    'name' => 'With Icon',
    'order' => 4,
    'status' => 'stable',
])

<tedi:button-group aria-label="Ikoonidega" value="2" :items="[
    ['value' => '1', 'label' => 'Tabel', 'iconLeft' => 'table'],
    ['value' => '2', 'label' => 'Loend', 'iconLeft' => 'list'],
    ['value' => '3', 'label' => 'Kalender', 'iconLeft' => 'calendar_month'],
]" />
