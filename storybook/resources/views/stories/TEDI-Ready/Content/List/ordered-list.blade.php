@storybook([
    'name' => 'Ordered List',
    'order' => 3,
    'status' => 'stable',
    'args' => [
        'styled' => true,
        'color' => 'brand',
    ],
    'argTypes' => [
        'styled' => [
            'control' => 'boolean',
            'description' => 'Used for showing or hiding the list bullets.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'true'],
                'type' => ['summary' => 'boolean'],
            ],
        ],
        'color' => [
            'control' => 'select',
            'options' => [
                'primary',
                'secondary',
                'tertiary',
                'brand',
                'brand-dark',
                'success',
                'warning',
                'warning-dark',
                'danger',
                'white',
            ],
            'description' => 'Color of the list bullet.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'brand'],
                'type' => ['summary' => 'BulletColor'],
            ],
        ],
    ],
])

<tedi:list as="ol" :styled="(bool) $styled" :color="$color">
    <li>School homework</li>
    <li>
        Chores
        <tedi:list as="ol" :styled="(bool) $styled" :color="$color">
            <li>Wash dishes</li>
            <li>
                Fold laundry
                <tedi:list as="ol" :styled="(bool) $styled" :color="$color">
                    <li>Iron the sheets</li>
                    <li>Hang dresses</li>
                </tedi:list>
            </li>
        </tedi:list>
    </li>
    <li>Walk the dog</li>
    <li>Water the flowers</li>
</tedi:list>
