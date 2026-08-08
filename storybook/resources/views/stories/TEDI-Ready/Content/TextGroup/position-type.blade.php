@storybook([
    'name' => 'Position Type',
    'order' => 3,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<div class="flex flex-column gap-3">
    <tedi:text-group type="vertical">
        <x-slot:label>Nähtavus</x-slot:label>
        <x-slot:value>Nähtav arstile ja esindajale</x-slot:value>
    </tedi:text-group>
    <tedi:text-group type="horizontal">
        <x-slot:label>Nähtavus</x-slot:label>
        <x-slot:value>Nähtav arstile ja esindajale</x-slot:value>
    </tedi:text-group>
</div>
