@storybook([
    'name' => 'Button Crumbs',
    'order' => 6,
    'status' => 'stable',
])

<tedi:breadcrumbs :items="[
    ['label' => 'Töölaud'],
    ['label' => 'Taotlused'],
    ['label' => 'Taotlus nr 506'],
]" />
