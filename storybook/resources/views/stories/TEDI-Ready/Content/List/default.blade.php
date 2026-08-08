@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/file/jWiRIXhHRxwVdMSimKX2FF/TEDI-Design-System-(draft)?type=design&node-id=2137-19322&m=dev',
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
    <li>Caesar salad</li>
    <li>
        Caesar salad
        <tedi:list :styled="(bool) $styled" :color="$color">
            <li>Dressing</li>
        </tedi:list>
    </li>
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
