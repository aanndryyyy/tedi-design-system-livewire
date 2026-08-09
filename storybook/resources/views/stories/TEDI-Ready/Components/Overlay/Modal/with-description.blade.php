@storybook([
    'name' => 'With header description',
    'order' => 8,
    'status' => 'subset',
    'args' => [
        'description' => 'This modal has additional description text in the header.',
    ],
    'argTypes' => [
        'description' => [
            'control' => 'text',
            'description' => 'Text rendered under the heading. Angular selects it from projected content carrying the `tedi-modal-description` attribute; in Blade it is the header\'s `description` slot, and you still write the attribute on your own element.',
            'table' => ['category' => 'Story'],
        ],
    ],
])

<tedi:modal :open="true" width="md">
    <tedi:modal-header>
        <h1>With description</h1>
        <x-slot:description>
            <p tedi-modal-description>{{ $description }}</p>
        </x-slot:description>
    </tedi:modal-header>
    <tedi:modal-content>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="description-field">Label</tedi:form.label>
            </x-slot:label>

            <tedi:text-field id="description-field" />
        </tedi:form-field>
    </tedi:modal-content>
    <tedi:modal-footer>
        <tedi:button variant="secondary" x-on:click="hide()">Cancel</tedi:button>
        <tedi:button x-on:click="hide()">Continue</tedi:button>
    </tedi:modal-footer>
</tedi:modal>
