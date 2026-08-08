@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.53.75?node-id=4442-91315&m=dev',
    'args' => [
        'borderless' => false,
    ],
    'argTypes' => [
        'borderless' => [
            'control' => 'boolean',
            'description' => 'Removes border from card.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'false'],
                'type' => ['summary' => 'boolean'],
            ],
        ],
    ],
])

<tedi:card :borderless="(bool) $borderless">
    <tedi:card-content>
        <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
    </tedi:card-content>
</tedi:card>
