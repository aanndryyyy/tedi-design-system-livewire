@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/file/jWiRIXhHRxwVdMSimKX2FF/TEDI-Design-System-(draft)?type=design&node-id=2137-19827&mode=dev',
    'args' => [
        'label' => 'My family physician',
        'name' => 'assignment_ind',
        'element' => 'h4',
        'headingColor' => 'brand',
        'iconColor' => 'brand',
        'iconSize' => 24,
    ],
    'argTypes' => [
        'label' => [
            'control' => 'text',
            'description' => 'Heading text. React projects this as children.',
        ],
        'name' => [
            'control' => 'text',
            'description' => 'Material Symbols icon name. Leave empty to render the heading without an icon.',
        ],
        'element' => [
            'control' => 'radio',
            'options' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            'description' => 'Semantic heading tag. It sets the element only — the visual size comes from the layout rule, not from a typography modifier.',
            'table' => ['defaultValue' => ['summary' => 'h4']],
        ],
        'headingColor' => [
            'control' => 'select',
            'options' => ['primary', 'secondary', 'tertiary', 'brand', 'success', 'warning', 'danger', 'white'],
            'description' => 'Heading text color.',
            'table' => ['defaultValue' => ['summary' => 'primary']],
        ],
        'iconColor' => [
            'control' => 'select',
            'options' => ['primary', 'secondary', 'tertiary', 'brand', 'success', 'warning', 'danger', 'white'],
            'description' => 'Icon color.',
            'table' => ['defaultValue' => ['summary' => 'primary']],
        ],
        'iconSize' => [
            'control' => 'select',
            'options' => [8, 12, 16, 18, 22, 24, 36, 48],
            'description' => 'Icon size token.',
            'table' => ['defaultValue' => ['summary' => '24']],
        ],
    ],
])

<tedi:heading-with-icon
    :name="$name ?: null"
    :element="$element"
    :heading-color="$headingColor"
    :icon-color="$iconColor"
    :icon-size="$iconSize"
>{{ $label }}</tedi:heading-with-icon>
