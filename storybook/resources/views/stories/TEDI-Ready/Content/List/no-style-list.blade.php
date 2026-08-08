@storybook([
    'name' => 'No Style List',
    'order' => 4,
    'status' => 'stable',
    'args' => [
        'styled' => false,
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

<tedi:list :styled="(bool) $styled" :color="$color">
    <li>
        Caesar salad
        <tedi:list :styled="(bool) $styled" :color="$color">
            <li>
                Dressing
                <tedi:list :styled="(bool) $styled" :color="$color">
                    <li>Lemon juice</li>
                    <li>Anchovies</li>
                </tedi:list>
            </li>
        </tedi:list>
    </li>
</tedi:list>
