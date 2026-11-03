@storybook([
    'name' => 'Simple Card',
    'order' => 2,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.68.85?node-id=53155-151142&m=dev',
    'args' => [],
])

@php
    use Illuminate\Support\Facades\Blade;
    use Illuminate\Support\HtmlString;

    $benefitRows = [
        ['label' => 'Hüvitise arvutamine', 'value' => 'Tervisekassa ei hüvita'],
        ['label' => 'Kogus', 'value' => '2 päeva'],
        ['label' => 'Ühe päeva hüvitis (€)', 'value' => '-'],
        ['label' => 'Summa (€)', 'value' => '0.00 €', 'bold' => true],
    ];
    $idRows = [
        ['label' => 'Eesnimi', 'value' => 'Mari'],
        ['label' => 'Sünniaeg', 'value' => '15.08.1987'],
        ['label' => 'Perenimi', 'value' => 'Maasikas'],
        ['label' => 'Isikukood', 'value' => '41234567891'],
        ['label' => 'Dokumendi number', 'value' => 'AS0000226'],
        ['label' => 'Sugu', 'value' => 'Naine'],
    ];
    $valid = new HtmlString(Blade::render('<tedi:status-badge color="success" text="Kehtiv" />'));
@endphp

<div style="max-width: 360px">
    <tedi:vertical-spacing :size="1">
        <tedi:table-card :rows="$benefitRows" :summary="['label' => 'Ülekande summa', 'value' => '0.00 €']" label-size="small" />

        <tedi:table-card title="ID kaart" title-element="h4" title-modifiers="h4" layout="vertical" label-size="small" :columns="2" :rows="$idRows">
            <x-slot:end-slot>
                <tedi:status-badge color="success" text="Kehtib 13.08.2027" />
            </x-slot:end-slot>
        </tedi:table-card>

        <tedi:table-card
            title="4. juuli 2026"
            layout="vertical"
            label-size="small"
            :rows="[
                ['label' => 'Päringu teostaja', 'value' => 'EE4800234675'],
                ['label' => 'Päringu nimetus', 'value' => 'Inimeste arv kohalikus omavalitsuses'],
                ['label' => 'Infosüsteem', 'value' => 'Rahvastikuregister'],
            ]"
        />

        <tedi:table-card
            layout="vertical"
            :rows="[
                ['label' => 'Periood', 'value' => '01.02 - 14.01.2024'],
                ['label' => 'Liik', 'value' => 'Haigusleht'],
                ['label' => 'Pikkus', 'value' => '14 päeva'],
                ['label' => 'Hüvitis', 'value' => '120.34 €', 'bold' => true],
                ['label' => 'Tõendi olek', 'value' => $valid],
            ]"
        />

        <tedi:table-card
            layout="vertical"
            :columns="2"
            :rows="[
                ['label' => 'Periood', 'value' => '01.02 - 14.01.2024', 'colSpan' => 2],
                ['label' => 'Liik', 'value' => 'Haigusleht'],
                ['label' => 'Pikkus', 'value' => '14 päeva'],
                ['label' => 'Hüvitis', 'value' => '120.34 €', 'bold' => true],
                ['label' => 'Tõendi olek', 'value' => $valid],
            ]"
        />
    </tedi:vertical-spacing>
</div>
