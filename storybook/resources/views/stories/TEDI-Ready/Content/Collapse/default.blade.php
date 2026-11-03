@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.0.4-(work-in-progress)?node-id=15433-138256&m=dev',
    'args' => [
        'openText' => 'Open',
        'closeText' => 'Close',
        'defaultOpen' => false,
        'hideCollapseText' => false,
        'arrowType' => 'default',
        'size' => 'default',
        'inverted' => false,
    ],
    'argTypes' => [
        'size' => [
            'control' => 'inline-radio',
            'options' => ['default', 'small'],
            'description' => 'Visual size of the toggle button. `small` uses 14px text and a 24px icon button.',
            'table' => [
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'inverted' => [
            'control' => 'boolean',
            'description' => 'Render with inverted colors (white text/icon, tinted state backgrounds) for use on a brand-coloured surface.',
            'table' => [
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'openText' => [
            'control' => 'text',
            'description' => 'The title for the collapsible section. Rendered inside the toggle button.',
            'table' => [
                'type' => ['summary' => 'string'],
            ],
        ],
        'closeText' => [
            'control' => 'text',
            'description' => 'Text shown on the toggle button when the content is expanded.',
            'table' => [
                'type' => ['summary' => 'string'],
            ],
        ],
        'defaultOpen' => [
            'control' => 'boolean',
            'description' => 'Optional prop to set the collapse open by default.',
            'table' => [
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'hideCollapseText' => [
            'control' => 'boolean',
            'description' => 'To show or hide the openText and closeText',
            'table' => [
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'arrowType' => [
            'control' => 'radio',
            'options' => ['default', 'secondary'],
            'description' => 'You are able to toggle different arrow styles. Arrow type \'secondary\' will add a circle over the icon.',
            'table' => [
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
    ],
])

<tedi:collapse
    :open-text="$openText ?: null"
    :close-text="$closeText ?: null"
    :default-open="(bool) $defaultOpen"
    :hide-collapse-text="(bool) $hideCollapseText"
    :arrow-type="$arrowType"
    :size="$size"
    :inverted="(bool) $inverted"
>
    Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora rerum
    perspiciatis consectetur blanditiis maxime, optio minus amet similique!
    Et, saepe placeat. Omnis obcaecati corrupti repellat enim asperiores sunt
    quam laudantium voluptate optio deserunt distinctio harum dolores, iure
    unde nemo reprehenderit!
</tedi:collapse>
