@storybook([
    'name' => 'No close button',
    'order' => 10,
    'status' => 'subset',
    'args' => [
        'showClose' => false,
    ],
    'argTypes' => [
        'showClose' => [
            'control' => 'boolean',
            'description' => 'Whether to show a close button in the header. Set via `show-close` on `tedi:modal-header`.',
            'table' => [
                'category' => 'ModalConfig',
                'defaultValue' => ['summary' => 'true'],
                'type' => ['summary' => 'boolean'],
            ],
        ],
    ],
])

<tedi:modal :open="true" width="md">
    <tedi:modal-header :show-close="(bool) $showClose">
        <h1>No close button</h1>
    </tedi:modal-header>
    <tedi:modal-content>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="no-close-field">Label</tedi:form.label>
            </x-slot:label>

            <tedi:text-field id="no-close-field" />
        </tedi:form-field>
    </tedi:modal-content>
    <tedi:modal-footer>
        <tedi:button variant="secondary" x-on:click="hide()">Cancel</tedi:button>
        <tedi:button x-on:click="hide()">Continue</tedi:button>
    </tedi:modal-footer>
</tedi:modal>
