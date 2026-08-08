@storybook([
    'name' => 'Unordered List',
    'order' => 2,
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

<tedi:list :styled="(bool) $styled" :color="$color">
    <li>Potato</li>
    <li>Caesar salad</li>
    <li>
        Caesar salad
        <tedi:list :styled="(bool) $styled" :color="$color">
            <li>
                Dressing
                <tedi:list :styled="(bool) $styled" :color="$color">
                    <li>Lemon juice</li>
                    <li>Anchovies</li>
                    <li>Parmesan cheese</li>
                    <li>Worcestershire sauce</li>
                    <li>Mustard</li>
                </tedi:list>
            </li>
        </tedi:list>
    </li>
</tedi:list>
