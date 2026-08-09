@storybook([
    'name' => 'Collapsed',
    'order' => 3,
    'status' => 'stable',
])

<tedi:breadcrumbs
    :max-items="4"
    :items-before-collapse="1"
    :items-after-collapse="2"
    :items="[
        ['label' => 'Töölaud', 'href' => '#'],
        ['label' => 'Patsiendid', 'href' => '#'],
        ['label' => 'Anna Tamm', 'href' => '#'],
        ['label' => 'Visiidid', 'href' => '#'],
        ['label' => '2024-05-12', 'href' => '#'],
        ['label' => 'Piirangud'],
    ]"
/>
