@storybook([
    'name' => 'Template-based (deprecated)',
    'order' => 13,
    'status' => 'subset',
    'args' => [
        'open' => true,
    ],
    'argTypes' => [
        'open' => [
            'control' => 'boolean',
            'description' => 'Initial open state. Angular\'s `[(open)]` two-way binding is a `model()`; in Blade this seeds the `tediModal` Alpine state, and an outside trigger opens it by dispatching an event the modal listens for.',
            'table' => [
                'category' => 'ModalConfig',
                'defaultValue' => ['summary' => 'false'],
                'type' => ['summary' => 'boolean'],
            ],
        ],
    ],
])

<div x-data>
    <tedi:button variant="secondary" x-on:click="$dispatch('open-template-modal')">Open modal</tedi:button>
</div>

<tedi:modal :open="(bool) $open" x-on:open-template-modal.window="show()">
    <tedi:modal-header>
        <h1>Title</h1>
    </tedi:modal-header>
    <tedi:modal-content>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="tpl-field-1">Label</tedi:form.label>
            </x-slot:label>

            <tedi:text-field id="tpl-field-1" />
        </tedi:form-field>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="tpl-field-2">Label</tedi:form.label>
            </x-slot:label>

            <tedi:text-field id="tpl-field-2" />
        </tedi:form-field>
    </tedi:modal-content>
    <tedi:modal-footer>
        <tedi:button variant="secondary" x-on:click="hide()">Cancel</tedi:button>
        <tedi:button x-on:click="hide()">Continue</tedi:button>
    </tedi:modal-footer>
</tedi:modal>
