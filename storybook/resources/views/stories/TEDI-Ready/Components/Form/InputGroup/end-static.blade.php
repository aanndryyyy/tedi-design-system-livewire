@storybook([
    'name' => 'End Static',
    'order' => 3,
    'status' => 'stable',
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
    <tedi:form.label for="end-static">Hind</tedi:form.label>
    <tedi:form-field>
        <input type="text" id="end-static" />
    </tedi:form-field>
    <x-slot:suffix>EUR</x-slot:suffix>
</tedi:input-group>
