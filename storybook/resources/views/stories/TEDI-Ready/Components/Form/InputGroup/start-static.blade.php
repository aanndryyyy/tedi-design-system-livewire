@storybook([
    'name' => 'Start Static',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY?node-id=4968-94396&m=dev',
    'args' => [
        'addons' => true,
        'disabled' => false,
        'invalid' => false,
    ],
    'argTypes' => [
        'addons' => [
            'control' => 'boolean',
            'description' => 'Merges the borders and radii of the addons and the control into a single visual unit. Disable for detached addons such as an action button.',
            'table' => [
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'disabled' => [
            'control' => 'boolean',
            'description' => 'Disables the whole group and propagates it to the control.',
            'table' => [
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'invalid' => [
            'control' => 'boolean',
            'description' => 'Marks the whole group as invalid. Pair with an error feedback text.',
            'table' => [
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
    ],
])

<tedi:input-group :addons="(bool) $addons" :disabled="(bool) $disabled" :invalid="(bool) $invalid">
    <x-slot:label><tedi:form.label for="start-static">Aadress</tedi:form.label></x-slot:label>
    <x-slot:prefix>Tänav</x-slot:prefix>
    <tedi:form-field>
        <tedi:text-field id="start-static" />
    </tedi:form-field>
</tedi:input-group>
