@storybook([
    'name' => 'With Actions',
    'order' => 3,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.68.85?node-id=53155-151142&m=dev',
    'args' => [],
])

@php
    use Illuminate\Support\Facades\Blade;
    use Illuminate\Support\HtmlString;

    $badge = fn (string $color, string $text) => new HtmlString(
        Blade::render('<tedi:status-badge :color="$color" :text="$text" />', ['color' => $color, 'text' => $text])
    );

    // Upstream's first card is an edit-in-place demo held in React state
    // (EditableAppointmentCard). Its read-only state is shown here; swapping
    // the values for inputs is a consumer round-trip (e.g. Livewire).
    $appointmentRows = [
        ['label' => 'Kuupäev', 'value' => '22.03.2029 – 29.03.2029'],
        ['label' => 'Kellaaeg', 'value' => '11:14'],
        ['label' => 'Kestus', 'value' => '6 min'],
        ['label' => 'Asukoht', 'value' => 'Tallinn'],
    ];

    $location = new HtmlString(Blade::render(<<<'BLADE'
        <tedi:dropdown container-id="table-card-location">
            <tedi:dropdown-trigger>
                <tedi:button variant="neutral" size="small" icon-end="expand_more">Tallinn</tedi:button>
            </tedi:dropdown-trigger>
            <tedi:dropdown-content>
                <tedi:dropdown-item>Tallinn</tedi:dropdown-item>
                <tedi:dropdown-item>Tartu</tedi:dropdown-item>
                <tedi:dropdown-item>Pärnu</tedi:dropdown-item>
            </tedi:dropdown-content>
        </tedi:dropdown>
        BLADE));

    $people = [
        ['name' => 'Meelis Mägi', 'vanus' => 23, 'visits' => 7, 'status' => $badge('warning', 'Aegumas')],
        ['name' => 'Madis Tamm', 'vanus' => 12, 'visits' => 14, 'status' => $badge('success', 'Kehtiv')],
        ['name' => 'Kadi Kuusk', 'vanus' => 43, 'visits' => 24, 'status' => $badge('success', 'Kehtiv')],
    ];

    $idRows = [
        ['label' => 'Eesnimi', 'value' => 'Mari'],
        ['label' => 'Sünniaeg', 'value' => '15.08.1987'],
        ['label' => 'Perenimi', 'value' => 'Maasikas'],
        ['label' => 'Isikukood', 'value' => '41234567891'],
        ['label' => 'Dokumendi number', 'value' => 'AS0000226'],
        ['label' => 'Sugu', 'value' => 'Naine'],
    ];
@endphp

<div style="max-width: 360px">
    <tedi:vertical-spacing :size="1">
        <tedi:table-card layout="vertical" :rows="$appointmentRows">
            <x-slot:actions>
                <tedi:button variant="neutral" icon-start="edit" style="width: 100%;">Muuda</tedi:button>
            </x-slot:actions>
        </tedi:table-card>

        <tedi:table-card
            layout="vertical"
            :rows="[
                ['label' => 'Teenus', 'value' => 'Ortopeedia'],
                ['label' => 'Arst', 'value' => 'Pille Paunküla'],
                ['label' => 'Maksumus', 'value' => '45.50 €/h'],
                ['label' => 'Asukoht', 'value' => $location],
            ]"
        />

        <tedi:table-card
            layout="vertical"
            :rows="[
                ['label' => 'Periood', 'value' => '01.02 - 14.01.2024'],
                ['label' => 'Liik', 'value' => 'Haigusleht'],
                ['label' => 'Pikkus', 'value' => '14 päeva'],
                ['label' => 'Hüvitis', 'value' => '120.34 €', 'bold' => true],
            ]"
        >
            <x-slot:actions>
                <tedi:button variant="neutral" icon-only icon-start="edit" aria-label="Muuda" title="Muuda" />
                <tedi:button variant="neutral" icon-only icon-start="share" aria-label="Jaga" title="Jaga" />
                <tedi:button variant="neutral" icon-only icon-start="lock" aria-label="Lukusta" title="Lukusta" />
                <tedi:button variant="neutral" icon-only icon-start="more_vert" aria-label="Rohkem" title="Rohkem" />
            </x-slot:actions>
        </tedi:table-card>

        @foreach ($people as $person)
            <tedi:table-card
                :title="$person['name']"
                title-modifiers="h6"
                :selectable="true"
                layout="horizontal"
                label-align="left"
                value-align="left"
                :rows="[
                    ['label' => 'Vanus', 'value' => (string) $person['vanus']],
                    ['label' => 'Külastuste arv', 'value' => (string) $person['visits']],
                    ['label' => 'Tõendi staatus', 'value' => $person['status']],
                ]"
            />
        @endforeach

        <tedi:table-card
            layout="vertical"
            :rows="[
                ['label' => 'Periood', 'value' => '01.02 - 14.01.2024'],
                ['label' => 'Liik', 'value' => 'Haigusleht'],
                ['label' => 'Pikkus', 'value' => '14 päeva'],
                ['label' => 'Staatus', 'value' => $badge('brand', 'Ülekanne tehtud')],
                ['label' => 'Hüvitis', 'value' => '120.34 €', 'bold' => true],
            ]"
        >
            <x-slot:actions>
                <tedi:button variant="neutral" style="width: 100%;">Vaata</tedi:button>
                <tedi:button variant="neutral" style="width: 100%;">Muuda</tedi:button>
                <tedi:button variant="neutral" style="width: 100%;">Rohkem</tedi:button>
            </x-slot:actions>
        </tedi:table-card>

        <tedi:table-card title="Pass" title-element="h4" title-modifiers="h4" layout="vertical" :columns="2" :rows="$idRows">
            <x-slot:end-slot>
                <tedi:status-badge color="success" text="Kehtib 13.08.2027" />
            </x-slot:end-slot>
            <x-slot:actions>
                <tedi:button variant="secondary" style="width: 100%;">Kuva pilt</tedi:button>
                <tedi:button variant="secondary" style="width: 100%;">Tegevused</tedi:button>
            </x-slot:actions>
        </tedi:table-card>

        <tedi:table-card layout="vertical" :rows="[['label' => '', 'value' => 'Käibedeklaratsiooni esitamise tähtaeg on 5 päeva pärast 10.10.2025']]">
            <x-slot:title>
                {{-- Upstream: <Label as="span" isSmall isBold>. Angular's label has no bold modifier; core's text-bold stands in. --}}
                <tedi:form.label as="span" size="small" class="text-bold">Eesti Maksu- ja Tolliamet</tedi:form.label>
            </x-slot:title>
            <x-slot:end-slot>
                <tedi:status-badge color="danger" text="Täitmata" />
            </x-slot:end-slot>
            <x-slot:actions>
                <tedi:button variant="neutral" icon-start="share" style="width: 100%;">Jaga</tedi:button>
                <tedi:button variant="neutral" icon-start="calendar_today" style="width: 100%;">Lisa kalendrisse</tedi:button>
            </x-slot:actions>
        </tedi:table-card>
    </tedi:vertical-spacing>
</div>
