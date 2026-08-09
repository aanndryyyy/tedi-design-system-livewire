@storybook([
    'name' => 'Single Value Filter',
    'order' => 3,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

@php
    $teenusOptions = [
        ['label' => 'Optometristi vastuvõtt', 'value' => '1'],
        ['label' => 'Silmaarsti vastuvõtt', 'value' => '2'],
        ['label' => 'Hambaarsti vastuvõtt', 'value' => '3'],
    ];

    $raviasutusOptions = [
        ['label' => 'Fertilitas', 'value' => '1'],
        ['label' => 'Ida-Tallinna Keskhaigla', 'value' => '2'],
        ['label' => 'Lääne-Tallinna Keskhaigla', 'value' => '3'],
        ['label' => 'Põhja-Eesti Regionaalhaigla', 'value' => '4'],
        ['label' => 'Tallinna Lastehaigla', 'value' => '5'],
        ['label' => 'Tartu Ülikooli Kliinikum', 'value' => '6'],
    ];
@endphp

<div style="background: var(--general-surface-primary); padding: 24px;">
    <tedi:row cols="1" :gap-y="3">
        <tedi:col class="flex flex-column gap-2">
            <tedi:text as="h5" modifiers="h5">Separate</tedi:text>
            <div class="flex flex-wrap gap-2">
                <tedi:filter text="Vastuvõtud" :selected="true" />
                <tedi:filter text="Analüüsid" :selected="true" />
                <tedi:filter text="Uuringud" />
                <tedi:filter text="Vaktsineerimised" />
            </div>
            <div class="flex flex-wrap gap-2">
                <tedi:filter text="Vastuvõtud" variant="secondary" :selected="true" />
                <tedi:filter text="Analüüsid" variant="secondary" :selected="true" />
                <tedi:filter text="Uuringud" variant="secondary" />
                <tedi:filter text="Vaktsineerimised" variant="secondary" />
            </div>
            <div class="flex flex-wrap gap-2">
                <tedi:filter text="Vastuvõtud" variant="secondary" :selected="true">
                    <x-slot:prepend><tedi:icon name="medical_services" :size="18" color="inherit" /></x-slot:prepend>
                </tedi:filter>
                <tedi:filter text="Analüüsid" variant="secondary">
                    <x-slot:prepend><tedi:icon name="science" :size="18" color="inherit" /></x-slot:prepend>
                </tedi:filter>
                <tedi:filter text="Uuringud" variant="secondary">
                    <x-slot:prepend><tedi:icon name="biotech" :size="18" color="inherit" /></x-slot:prepend>
                </tedi:filter>
                <tedi:filter text="Vaktsineerimised" variant="secondary">
                    <x-slot:prepend><tedi:icon name="vaccines" :size="18" color="inherit" /></x-slot:prepend>
                </tedi:filter>
            </div>
        </tedi:col>

        <tedi:col class="flex flex-column gap-2">
            <tedi:text as="h5" modifiers="h5">Grouped</tedi:text>
            <div class="flex flex-wrap gap-2">
                <tedi:filter-group :managed="true">
                    <tedi:filter text="Kooskõlastatud" value="ok" />
                    <tedi:filter text="Tagasilükatud" value="rejected" />
                </tedi:filter-group>
                <tedi:filter-group :managed="true">
                    <tedi:filter text="Kooskõlastatud" value="ok" :selected="true" />
                    <tedi:filter text="Tagasilükatud" value="rejected" />
                </tedi:filter-group>
            </div>
            <div class="flex flex-wrap gap-2">
                <tedi:filter-group :managed="true">
                    <tedi:filter text="Kooskõlastatud" value="ok" variant="secondary" />
                    <tedi:filter text="Tagasilükatud" value="rejected" variant="secondary" />
                </tedi:filter-group>
                <tedi:filter-group :managed="true">
                    <tedi:filter text="Kooskõlastatud" value="ok" variant="secondary" :selected="true" />
                    <tedi:filter text="Tagasilükatud" value="rejected" variant="secondary" />
                </tedi:filter-group>
            </div>
            <div class="flex flex-wrap gap-2">
                <tedi:filter-group :managed="true">
                    <tedi:filter text="Analüüsid" value="analuusid" />
                    <tedi:filter text="Doonorlus" value="doonorlus" />
                    <tedi:filter text="Uuringud" value="uuringud" />
                    <tedi:filter text="Vaktsineerimised" value="vaktsineerimised" />
                </tedi:filter-group>
            </div>
        </tedi:col>

        <tedi:col class="flex flex-column gap-2">
            <tedi:text as="h5" modifiers="h5">Dropdown label + value</tedi:text>
            <div class="flex flex-wrap gap-2">
                <tedi:filter text="Teenus" :options="$teenusOptions" :preserve-label="true" :show-clear="true" />
            </div>
            <div class="flex flex-wrap gap-2">
                <tedi:filter text="Teenus" variant="secondary" :options="$teenusOptions" :preserve-label="true" :show-clear="true" />
            </div>
        </tedi:col>

        <tedi:col class="flex flex-column gap-2">
            <tedi:text as="h5" modifiers="h5">Dropdown value</tedi:text>
            <div class="flex flex-wrap gap-2">
                <tedi:filter text="Raviasutus" :options="$raviasutusOptions" />
                <tedi:filter text="Teenus" :options="$teenusOptions" />
            </div>
            <div class="flex flex-wrap gap-2">
                <tedi:filter text="Raviasutus" variant="secondary" :options="$raviasutusOptions" />
                <tedi:filter text="Teenus" variant="secondary" :options="$teenusOptions" />
            </div>
        </tedi:col>
    </tedi:row>
</div>
