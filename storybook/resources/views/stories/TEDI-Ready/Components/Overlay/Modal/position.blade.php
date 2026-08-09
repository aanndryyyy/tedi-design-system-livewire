@storybook([
    'name' => 'Position',
    'order' => 2,
    'status' => 'subset',
    'args' => [
        'position' => 'right',
    ],
    'argTypes' => [
        'position' => [
            'control' => 'select',
            'options' => ['center', 'top', 'bottom', 'left', 'right'],
            'description' => 'Position of the modal on screen. `\'left\'` / `\'right\'` create side/drawer modals. `\'top\'` anchors the modal to the top edge with a fixed margin. TEDI ships no CSS for `\'bottom\'`, so it emits no class and the dialog falls back to its static position.',
            'table' => [
                'category' => 'ModalConfig',
                'defaultValue' => ['summary' => "'center'"],
                'type' => ['summary' => "'center' | 'top' | 'bottom' | 'left' | 'right'"],
            ],
        ],
    ],
])

<tedi:modal :open="true" width="md" :position="$position">
    <tedi:modal-header>
        <h1>{{ ucfirst($position) }} modal</h1>
    </tedi:modal-header>
    <tedi:modal-content>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="position-field">Label</tedi:form.label>
            </x-slot:label>

            <tedi:text-field id="position-field" />
        </tedi:form-field>
    </tedi:modal-content>
    <tedi:modal-footer>
        <tedi:button variant="secondary" x-on:click="hide()">Cancel</tedi:button>
        <tedi:button x-on:click="hide()">Continue</tedi:button>
    </tedi:modal-footer>
</tedi:modal>
