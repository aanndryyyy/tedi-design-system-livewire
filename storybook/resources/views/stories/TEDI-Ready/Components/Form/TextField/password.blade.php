@storybook([
    'name' => 'Password',
    'order' => 5,
    'status' => 'stable',
    'args' => [],
])

<tedi:form-field>
    <x-slot:label>
        <tedi:form.label for="example-password">Label</tedi:form.label>
    </x-slot:label>
    <tedi:text-field id="example-password" type="password" value="123456789" />
</tedi:form-field>
