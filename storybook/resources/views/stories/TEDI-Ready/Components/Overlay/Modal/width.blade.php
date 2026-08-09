@storybook([
    'name' => 'Width',
    'order' => 4,
    'status' => 'subset',
    'args' => [
        'width' => 'md',
    ],
    'argTypes' => [
        'width' => [
            'control' => 'select',
            'options' => ['xs', 'sm', 'md', 'lg', 'xl'],
            'description' => 'Preset widths: `\'xs\'` | `\'sm\'` | `\'md\'` | `\'lg\'` | `\'xl\'`. Each emits a `tedi-modal--<width>` modifier that caps the dialog\'s max-width.',
            'table' => [
                'category' => 'ModalConfig',
                'defaultValue' => ['summary' => "'sm'"],
                'type' => ['summary' => "'xs' | 'sm' | 'md' | 'lg' | 'xl' | string"],
            ],
        ],
    ],
])

<tedi:modal :open="true" :width="$width">
    <tedi:modal-header>
        <h1>Width: {{ $width }}</h1>
    </tedi:modal-header>
    <tedi:modal-content>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="width-field">Label</tedi:form.label>
            </x-slot:label>

            <tedi:text-field id="width-field" />
        </tedi:form-field>
    </tedi:modal-content>
    <tedi:modal-footer>
        <tedi:button variant="secondary" x-on:click="hide()">Cancel</tedi:button>
        <tedi:button x-on:click="hide()">Continue</tedi:button>
    </tedi:modal-footer>
</tedi:modal>
