@storybook([
    'name' => 'Has Children Rows',
    'order' => 5,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.68.85?node-id=53155-151142&m=dev',
    'args' => [],
])

@php
    use Illuminate\Support\Facades\Blade;
    use Illuminate\Support\HtmlString;

    $certificates = ['Puukentsefaliidi vaktsiin', 'COVID-19'];
    $validBadge = new HtmlString(Blade::render('<tedi:status-badge color="success" variant="filled-bordered" text="Kehtiv" />'));
    $mainRows = [
        ['label' => 'Vanus', 'value' => '25'],
        ['label' => 'Külastuste arv', 'value' => '6'],
    ];
@endphp

{{--
    Upstream's children are `Card.Content hasSeparator` + `TextGroup.List`:
    here <tedi:card-content> carrying React's separator class, and
    <tedi:text-group-list>, which is styled inside a table card only.
--}}
<div style="max-width: 360px">
    <tedi:vertical-spacing :size="1">
        <tedi:table-card
            title="Kadri Kaasik"
            :collapsible="true"
            layout="horizontal"
            label-align="left"
            value-align="left"
            label-width="var(--text-group-label-width-sm)"
            :rows="$mainRows"
        >
            @foreach ($certificates as $name)
                <tedi:card-content :padding="1" background="tertiary" class="tedi-card__content--separator">
                    <tedi:vertical-spacing :size="0.5">
                        <tedi:text-group-list
                            type="horizontal"
                            label-align="left"
                            value-align="left"
                            label-width="var(--text-group-label-width-sm)"
                            :items="[
                                ['label' => 'Tõend', 'value' => $name],
                                ['label' => 'Tõendi staatus', 'value' => $validBadge],
                            ]"
                        />
                        <div style="display: flex; justify-content: center;">
                            <tedi:button variant="neutral" style="width: 100%;">Vaata</tedi:button>
                        </div>
                    </tedi:vertical-spacing>
                </tedi:card-content>
            @endforeach
            <x-slot:actions>
                <tedi:button variant="neutral" icon-start="edit" style="width: 100%;">Muuda</tedi:button>
            </x-slot:actions>
        </tedi:table-card>

        <tedi:table-card
            title="Kadri Kaasik"
            :collapsible="true"
            layout="horizontal"
            label-align="left"
            value-align="left"
            label-width="var(--text-group-label-width-sm)"
            :rows="$mainRows"
        >
            @foreach ($certificates as $name)
                <tedi:card-content :padding="1" background="tertiary" class="tedi-card__content--separator">
                    <tedi:text-group-list
                        type="horizontal"
                        label-align="left"
                        value-align="left"
                        label-width="var(--text-group-label-width-sm)"
                        :items="[
                            ['label' => 'Tõend', 'value' => $name],
                            ['label' => 'Olek', 'value' => $validBadge],
                        ]"
                    />
                </tedi:card-content>
            @endforeach
            <x-slot:actions>
                <tedi:button variant="neutral" icon-start="edit" style="width: 100%;">Muuda</tedi:button>
            </x-slot:actions>
        </tedi:table-card>

        <tedi:table-card
            title="Kadri Kaasik"
            :collapsible="true"
            layout="vertical"
            :columns="2"
            :rows="$mainRows"
        >
            @foreach ($certificates as $name)
                <tedi:card-content :padding="1" background="tertiary" class="tedi-card__content--separator">
                    <tedi:text-group-list
                        type="vertical"
                        :columns="2"
                        row-gap="var(--layout-grid-gutters-16)"
                        :items="[
                            ['label' => 'Tõend', 'value' => $name],
                            ['label' => 'Olek', 'value' => $validBadge],
                        ]"
                    />
                </tedi:card-content>
            @endforeach
            <x-slot:actions>
                <tedi:button variant="neutral" icon-start="edit" style="width: 100%;">Muuda</tedi:button>
            </x-slot:actions>
        </tedi:table-card>
    </tedi:vertical-spacing>
</div>
