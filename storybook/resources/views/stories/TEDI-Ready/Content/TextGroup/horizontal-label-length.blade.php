@storybook([
    'name' => 'Horizontal Label Length',
    'order' => 4,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<div class="flex flex-column gap-4">
    <div class="flex flex-column gap-1">
        <tedi:text-group type="horizontal" label-width="132px">
            <x-slot:label>Patsient</x-slot:label>
            <x-slot:value>
                <tedi:icon :size="16" name="person_filled" color="tertiary" />
                Mari Maasikas
            </x-slot:value>
        </tedi:text-group>
        <tedi:text-group type="horizontal" label-width="132px">
            <x-slot:label>Aadress</x-slot:label>
            <x-slot:value>
                <tedi:icon :size="16" name="location_on" color="tertiary" />
                Tulbi tn 4, Tallinn, 23562, Eesti
            </x-slot:value>
        </tedi:text-group>
    </div>

    <div class="flex flex-column gap-1">
        <tedi:text-group type="horizontal" label-width="164px">
            <x-slot:label>Vaktsiin</x-slot:label>
            <x-slot:value>Mari Maasikas</x-slot:value>
        </tedi:text-group>
        <tedi:text-group type="horizontal" label-width="164px">
            <x-slot:label>Järgmine vaktsineerimine</x-slot:label>
            <x-slot:value>Immuniseerimine lõpetatud</x-slot:value>
        </tedi:text-group>
    </div>

    <div class="flex flex-column gap-1">
        <tedi:text-group type="horizontal" label-width="196px">
            <x-slot:label>Tervishoiuteenuse osutaja</x-slot:label>
            <x-slot:value>SA Põhja-Eesti Regionaalhaigla</x-slot:value>
        </tedi:text-group>
        <tedi:text-group type="horizontal" label-width="196px">
            <x-slot:label>Tervishoiutöötaja</x-slot:label>
            <x-slot:value>Mart Mets</x-slot:value>
        </tedi:text-group>
        <tedi:text-group type="horizontal" label-width="196px">
            <x-slot:label>Dokumendi loomise aeg</x-slot:label>
            <x-slot:value>16.08.2023 14:51:48</x-slot:value>
        </tedi:text-group>
    </div>
</div>
