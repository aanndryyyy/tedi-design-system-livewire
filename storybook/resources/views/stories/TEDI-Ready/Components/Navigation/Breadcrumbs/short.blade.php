@storybook([
    'name' => 'Short',
    'order' => 2,
    'status' => 'stable',
])

<tedi:breadcrumbs
    variant="short"
    :items="[
        ['label' => 'Töölaud', 'href' => '#', 'underline' => false],
        ['label' => 'Taotlus nr 506'],
    ]"
/>
