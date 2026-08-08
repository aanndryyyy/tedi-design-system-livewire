@storybook([
    'name' => 'Types',
    'order' => 2,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<div class="flex flex-column gap-4">
    <tedi:text-group type="vertical">
        <x-slot:label>Nähtavus</x-slot:label>
        <x-slot:value>Nähtav arstile ja esindajale</x-slot:value>
    </tedi:text-group>

    <tedi:text-group type="vertical">
        <x-slot:label>Nähtavus</x-slot:label>
        <x-slot:value>
            <div class="flex flex-column align-items-start">
                Nähtav arstile ja esindajale
                <tedi:status-badge color="brand" text="Esitatud" />
            </div>
        </x-slot:value>
    </tedi:text-group>

    <tedi:text-group type="vertical">
        <x-slot:label>Nähtavus</x-slot:label>
        <x-slot:value>
            <tedi:icon :size="16" name="lock_open" color="tertiary" />
            Nähtav arstile ja esindajale
        </x-slot:value>
    </tedi:text-group>

    <tedi:text-group type="vertical">
        <x-slot:label><b>Nähtavus</b></x-slot:label>
        <x-slot:value>Nähtav arstile ja esindajale</x-slot:value>
    </tedi:text-group>

    <tedi:text-group type="vertical">
        <x-slot:label>Nähtavus</x-slot:label>
        <x-slot:value><b>Nähtav arstile ja esindajale</b></x-slot:value>
    </tedi:text-group>

    <tedi:text-group type="horizontal">
        <x-slot:label>Patsient</x-slot:label>
        <x-slot:value>
            <tedi:icon :size="16" name="person_filled" color="tertiary" />
            Mari Maasikas
        </x-slot:value>
    </tedi:text-group>
</div>
