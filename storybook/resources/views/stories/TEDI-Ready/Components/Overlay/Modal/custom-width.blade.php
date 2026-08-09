@storybook([
    'name' => 'Custom width',
    'order' => 5,
    'status' => 'subset',
    'args' => [
        'width' => '800px',
        'position' => 'left',
    ],
    'argTypes' => [
        'width' => [
            'control' => 'text',
            'description' => 'Custom CSS width value (e.g. \'800px\', \'50vw\', \'60%\'). Anything outside the `xs`–`xl` preset set lands as an inline `width` on the dialog instead of a modifier class.',
            'table' => [
                'category' => 'ModalConfig',
                'defaultValue' => ['summary' => "'sm'"],
                'type' => ['summary' => "'xs' | 'sm' | 'md' | 'lg' | 'xl' | string"],
            ],
        ],
        'position' => [
            'control' => 'select',
            'options' => ['center', 'top', 'left', 'right'],
            'description' => 'Position of the modal.',
            'table' => [
                'category' => 'ModalConfig',
                'defaultValue' => ['summary' => "'center'"],
                'type' => ['summary' => "'center' | 'top' | 'bottom' | 'left' | 'right'"],
            ],
        ],
    ],
])

<tedi:modal :open="true" :width="$width" :position="$position">
    <tedi:modal-header>
        <h1>Width: {{ $width }}</h1>
    </tedi:modal-header>
    <tedi:modal-content>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="custom-width-field">Label</tedi:form.label>
            </x-slot:label>

            <tedi:text-field id="custom-width-field" />
        </tedi:form-field>
    </tedi:modal-content>
    <tedi:modal-footer>
        <tedi:button variant="secondary" x-on:click="hide()">Cancel</tedi:button>
        <tedi:button x-on:click="hide()">Continue</tedi:button>
    </tedi:modal-footer>
</tedi:modal>
