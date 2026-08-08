@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/file/jWiRIXhHRxwVdMSimKX2FF/TEDI-Design-System-(draft)?type=design&node-id=45-30752&mode=dev',
    'args' => [
        'type' => 'horizontal',
        'labelWidth' => '',
    ],
    'argTypes' => [
        'type' => [
            'control' => 'radio',
            'options' => ['vertical', 'horizontal'],
            'description' => 'Type of text group layout',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'horizontal'],
                'type' => ['summary' => 'TextGroupType'],
            ],
        ],
        'labelWidth' => [
            'control' => 'text',
            'description' => 'Width for the label (e.g., "200px", "30%", etc.)',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
    ],
])

<tedi:text-group :type="$type" :label-width="$labelWidth ?: null">
    <x-slot:label>Nähtavus</x-slot:label>
    <x-slot:value>Nähtav arstile ja esindajale</x-slot:value>
</tedi:text-group>
