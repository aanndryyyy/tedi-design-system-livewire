@storybook([
    'name' => 'Clear Button On Interaction',
    'order' => 10,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<tedi:form-field :clearable="true" :show-clear-on-interaction="true">
    <x-slot:label>
        <tedi:form.label for="clear-on-interaction">Label</tedi:form.label>
    </x-slot:label>

    <tedi:text-field id="clear-on-interaction" value="Text value" />
</tedi:form-field>
