@storybook([
    'name' => 'Size',
    'order' => 3,
    'status' => 'subset',
    'args' => [
        'size' => 'small',
    ],
    'argTypes' => [
        'size' => [
            'control' => 'select',
            'options' => ['default', 'small'],
            'description' => 'Modal size variant. Controls padding and heading size. Close button defaults to match this variant unless `close-button-size` is set explicitly on `tedi:modal-header`.',
            'table' => [
                'category' => 'ModalConfig',
                'defaultValue' => ['summary' => "'default'"],
                'type' => ['summary' => "'default' | 'small'"],
            ],
        ],
    ],
])

<tedi:modal :open="true" width="sm" :size="$size">
    <tedi:modal-header>
        <h1>{{ ucfirst($size) }} modal</h1>
    </tedi:modal-header>
    <tedi:modal-content>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="size-field">Label</tedi:form.label>
            </x-slot:label>

            <tedi:text-field id="size-field" />
        </tedi:form-field>
    </tedi:modal-content>
    <tedi:modal-footer>
        <tedi:button variant="secondary" x-on:click="hide()">Cancel</tedi:button>
        <tedi:button x-on:click="hide()">Continue</tedi:button>
    </tedi:modal-footer>
</tedi:modal>
