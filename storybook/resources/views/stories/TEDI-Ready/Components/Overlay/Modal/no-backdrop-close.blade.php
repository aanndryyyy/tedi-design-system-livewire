@storybook([
    'name' => 'No backdrop close',
    'order' => 9,
    'status' => 'subset',
    'args' => [
        'closeOnBackdropClick' => false,
    ],
    'argTypes' => [
        'closeOnBackdropClick' => [
            'control' => 'boolean',
            'description' => 'Whether clicking the backdrop closes the modal.',
            'table' => [
                'category' => 'ModalConfig',
                'defaultValue' => ['summary' => 'true'],
                'type' => ['summary' => 'boolean'],
            ],
        ],
    ],
])

<tedi:modal :open="true" width="md" :close-on-backdrop-click="(bool) $closeOnBackdropClick">
    <tedi:modal-header>
        <h1>No backdrop close</h1>
    </tedi:modal-header>
    <tedi:modal-content>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="no-backdrop-field">Label</tedi:form.label>
            </x-slot:label>

            <tedi:text-field id="no-backdrop-field" />
        </tedi:form-field>
    </tedi:modal-content>
    <tedi:modal-footer>
        <tedi:button variant="secondary" x-on:click="hide()">Cancel</tedi:button>
        <tedi:button x-on:click="hide()">Continue</tedi:button>
    </tedi:modal-footer>
</tedi:modal>
