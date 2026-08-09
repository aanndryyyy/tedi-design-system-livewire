@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/ze9LXyoxEdGV8vpEdat7Oi/Button-group-buttons?node-id=136-19706&m=dev',
    'args' => [
        'variant' => 'primary-button-group',
        'size' => 'default',
        'multiple' => false,
        'stretch' => false,
        'ariaLabel' => 'Vaate valik',
        'dropdownMode' => false,
        'dropdownLabel' => '',
        'dropdownLabelMode' => 'static',
    ],
    'argTypes' => [
        'variant' => [
            'control' => 'select',
            'options' => ['primary-button-group', 'secondary-button-group', 'primary', 'secondary', 'success', 'danger'],
            'description' => 'Variant applied to every item (each item may override via its own `variant`). Any `ButtonVariant` works; non-group variants show their active colors when selected.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'ButtonVariant'],
                'defaultValue' => ['summary' => 'primary-button-group'],
            ],
        ],
        'size' => [
            'control' => 'select',
            'options' => ['default', 'small'],
            'description' => 'Size of the items.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => "'default' | 'small'"],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'multiple' => [
            'control' => 'boolean',
            // Spelled out rather than written as the TS type: Blast's story
            // parser ends the block at the first `]` immediately followed by
            // `)`, which a bracketed array type inside parentheses produces.
            'description' => 'Allow several values to be toggled on (value becomes an array of strings).',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'stretch' => [
            'control' => 'boolean',
            'description' => 'When true, items share horizontal space equally.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'ariaLabel' => [
            'control' => 'text',
            'description' => 'Accessible name for the group.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'dropdownMode' => [
            'control' => 'boolean',
            'description' => "Renders the collapsed dropdown branch. Replaces Angular's `enableMobileDropdown` + `mobileBreakpoint`, which resolve against the live viewport (CONVENTIONS.md §5, §7).",
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'dropdownLabel' => [
            'control' => 'text',
            'description' => 'Label shown on the dropdown trigger.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'dropdownLabelMode' => [
            'control' => 'select',
            'options' => ['static', 'selected'],
            'description' => '`static` keeps `dropdownLabel`; `selected` shows the selected item\'s label.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => "'static' | 'selected'"],
                'defaultValue' => ['summary' => 'static'],
            ],
        ],
    ],
])

<tedi:button-group
    :variant="$variant"
    :size="$size"
    :multiple="(bool) $multiple"
    :stretch="(bool) $stretch"
    :aria-label="$ariaLabel ?: null"
    :dropdown-mode="(bool) $dropdownMode"
    :dropdown-label="$dropdownLabel ?: null"
    :dropdown-label-mode="$dropdownLabelMode"
    value="2"
    :items="[
        ['value' => '1', 'label' => 'Tabel'],
        ['value' => '2', 'label' => 'Loend'],
        ['value' => '3', 'label' => 'Kalender'],
    ]"
/>
