@storybook([
    'name' => 'Examples',
    'order' => 8,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

{{--
    Angular's story drives every filter from a FormControl and derives the tag
    row from those controls at runtime. Reactive forms do not port
    (CONVENTIONS.md §7.2 / filter-group.blade.php), so the selection here is the
    server-rendered state a Livewire component would supply through wire:model,
    and the tag row is the matching hardcoded composition.
--}}
@php
    $uuringOptions = [
        ['label' => 'Vereanalüüs', 'value' => '1'],
        ['label' => 'Röntgen', 'value' => '2'],
        ['label' => 'Ultraheli', 'value' => '3'],
        ['label' => 'MRT', 'value' => '4'],
    ];

    $raviasutusOptions = [
        ['label' => 'Fertilitas', 'value' => 'fertilitas'],
        ['label' => 'Ida-Tallinna Keskhaigla', 'value' => 'itk'],
        ['label' => 'Lääne-Tallinna Keskhaigla', 'value' => 'ltk'],
        ['label' => 'Põhja-Eesti Regionaalhaigla', 'value' => 'perh'],
        ['label' => 'Tallinna Lastehaigla', 'value' => 'tlh'],
        ['label' => 'Tartu Ülikooli Kliinikum', 'value' => 'tuk'],
    ];

    $teenusOptions = [
        ['label' => 'Optometristi vastuvõtt', 'value' => '1'],
        ['label' => 'Silmaarsti vastuvõtt', 'value' => '2'],
        ['label' => 'Hambaarsti vastuvõtt', 'value' => '3'],
    ];

    $aegAlatesOptions = [
        ['label' => 'Viimane nädal', 'value' => '1'],
        ['label' => 'Viimane kuu', 'value' => '2'],
        ['label' => 'Viimane aasta', 'value' => '3'],
    ];

    $raviasutusValues = ['ltk', 'perh'];
    $kategooriaValues = ['vastuvotud', 'analuusid'];
@endphp

<div style="background: var(--general-surface-primary); padding: 24px;">
    <tedi:row cols="1" :gap-y="3">
        <tedi:col>
            <tedi:text as="h1" modifiers="h1" color="secondary">Taotlused</tedi:text>
        </tedi:col>

        <tedi:col class="flex flex-wrap gap-2 align-items-center">
            <tedi:filter text="Vastuvõtud" variant="secondary" :selected="true" />
            <tedi:filter text="Analüüsid" variant="secondary" :selected="true" />
            <tedi:filter text="Uuringud" variant="secondary" />

            <tedi:separator axis="vertical" size="24px" />

            <tedi:filter text="Uuring" variant="secondary" :options="$uuringOptions" :show-clear="true" />
            <tedi:filter
                text="Raviasutus"
                variant="secondary"
                :allow-multiple="true"
                :options="$raviasutusOptions"
                :value="$raviasutusValues"
                :show-search="true"
                :show-select-all="true"
                :show-clear="true"
            />
            <tedi:filter text="Teenus" variant="secondary" :options="$teenusOptions" :show-clear="true" />
            <tedi:filter text="Aeg alates" variant="secondary" :options="$aegAlatesOptions" :show-clear="true" />

            <tedi:separator axis="vertical" size="24px" />

            <tedi:button variant="neutral" icon-start="refresh" class="text-nowrap">Tühjenda filtrid</tedi:button>
        </tedi:col>

        <tedi:col class="flex flex-wrap gap-1">
            <tedi:tag :closable="true">Vastuvõtud</tedi:tag>
            <tedi:tag :closable="true">Analüüsid</tedi:tag>
            <tedi:tag :closable="true">Raviasutus: Lääne-Tallinna Keskhaigla</tedi:tag>
            <tedi:tag :closable="true">Raviasutus: Põhja-Eesti Regionaalhaigla</tedi:tag>
        </tedi:col>

        <tedi:col>
            <tedi:text as="p" color="tertiary">64 tulemust</tedi:text>
        </tedi:col>

        <tedi:col><tedi:separator /></tedi:col>

        <tedi:col>
            <tedi:text as="h1" modifiers="h1" color="secondary">Andmed</tedi:text>
        </tedi:col>

        <tedi:col class="flex flex-wrap gap-2 align-items-center">
            <tedi:filter-group label="Tüüp" :managed="true">
                <tedi:filter text="Kõik" value="all" variant="secondary" :selected="true" />
                <tedi:filter text="Aktiivsed" value="active" variant="secondary" />
                <tedi:filter text="Lõpetatud" value="done" variant="secondary" />
            </tedi:filter-group>

            <tedi:separator axis="vertical" size="24px" />

            <tedi:filter text="Teenus" variant="secondary" :options="$teenusOptions" :show-clear="true" />
            <tedi:filter
                text="Raviasutus"
                variant="secondary"
                :allow-multiple="true"
                :options="$raviasutusOptions"
                :value="$raviasutusValues"
                :show-search="true"
                :show-select-all="true"
                :show-clear="true"
            />
        </tedi:col>

        <tedi:col class="flex flex-wrap gap-1">
            <tedi:tag :closable="true">Tüüp: Kõik</tedi:tag>
            <tedi:tag :closable="true">Raviasutus: Lääne-Tallinna Keskhaigla</tedi:tag>
            <tedi:tag :closable="true">Raviasutus: Põhja-Eesti Regionaalhaigla</tedi:tag>
        </tedi:col>

        <tedi:col><tedi:separator /></tedi:col>

        <tedi:col>
            <tedi:text as="h1" modifiers="h1" color="secondary">Menetlusdokumendid</tedi:text>
        </tedi:col>

        <tedi:col class="flex flex-wrap gap-2 align-items-center">
            {{-- group-allow-multiple mirrors the group's allow-multiple onto each
                 child; it is what keeps them aria-pressed rather than role="radio". --}}
            <tedi:filter-group label="Kategooria" :managed="true" :allow-multiple="true">
                <tedi:filter text="Vastuvõtud" value="vastuvotud" variant="secondary" :selected="true" :group-allow-multiple="true" />
                <tedi:filter text="Analüüsid" value="analuusid" variant="secondary" :selected="true" :group-allow-multiple="true" />
                <tedi:filter text="Uuringud" value="uuringud" variant="secondary" :group-allow-multiple="true" />
                <tedi:filter text="Vaktsineerimised" value="vaktsineerimised" variant="secondary" :group-allow-multiple="true" />
            </tedi:filter-group>

            <tedi:separator axis="vertical" size="24px" />

            <tedi:filter text="Teenus" variant="secondary" :options="$teenusOptions" :show-clear="true" />
        </tedi:col>

        <tedi:col class="flex flex-wrap gap-1">
            <tedi:tag :closable="true">Kategooria: Vastuvõtud</tedi:tag>
            <tedi:tag :closable="true">Kategooria: Analüüsid</tedi:tag>
        </tedi:col>

        <tedi:col><tedi:separator /></tedi:col>

        <tedi:col>
            <tedi:text as="h1" modifiers="h1" color="secondary">Taotlused</tedi:text>
        </tedi:col>

        <tedi:col class="flex flex-wrap gap-2 align-items-center">
            <tedi:filter-group label="Tüüp" :managed="true">
                <tedi:filter text="Kõik" value="all" :selected="true" />
                <tedi:filter text="Aktiivsed" value="active" />
                <tedi:filter text="Lõpetatud" value="done" />
            </tedi:filter-group>

            <tedi:separator axis="vertical" size="24px" />

            <tedi:filter text="Uuring" :options="$uuringOptions" :show-clear="true" />
        </tedi:col>

        <tedi:col class="flex flex-wrap gap-1">
            <tedi:tag :closable="true">Tüüp: Kõik</tedi:tag>
        </tedi:col>
    </tedi:row>
</div>
