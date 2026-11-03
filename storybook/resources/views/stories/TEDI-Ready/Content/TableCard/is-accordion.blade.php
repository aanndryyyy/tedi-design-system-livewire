@storybook([
    'name' => 'Is Accordion',
    'order' => 4,
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

    $rows = [
        ['label' => 'Vanus', 'value' => '25'],
        ['label' => 'Külastuste arv', 'value' => '6'],
        ['label' => 'Taotluse olek', 'value' => $badge('brand', 'Menetluses')],
    ];
@endphp

<div style="max-width: 360px">
    <tedi:vertical-spacing :size="1">
        <tedi:table-card
            title="Hambaarst"
            subtitle="14.04.2026 15:30"
            :collapsible="true"
            :default-open="false"
            label-size="small"
            label-align="left"
            value-align="left"
            row-align="center"
            :rows="$rows"
        />

        <tedi:table-card
            title="Hambaarst"
            subtitle="14.04.2026 15:30"
            :collapsible="true"
            :default-open="false"
            label-size="small"
            label-align="left"
            value-align="left"
            row-align="center"
            :rows="$rows"
        >
            <x-slot:actions>
                <tedi:button variant="neutral" icon-start="edit" style="width: 100%;">Muuda</tedi:button>
                <tedi:button variant="neutral" icon-start="close" style="width: 100%;">Tühista</tedi:button>
                <tedi:button variant="neutral" icon-start="more_vert" style="width: 100%;">Rohkem</tedi:button>
            </x-slot:actions>
        </tedi:table-card>

        <tedi:table-card
            title="Mari Maasikas"
            subtitle="Vanus: 25"
            :collapsible="true"
            arrow-type="secondary"
            :default-open="false"
            layout="horizontal"
            label-align="left"
            value-align="left"
            label-size="small"
            :rows="$rows"
        >
            <x-slot:end-slot>
                <tedi:status-badge color="success" text="Verifitseeritud" />
            </x-slot:end-slot>
        </tedi:table-card>

        <tedi:table-card
            title="Kadri Kaasik"
            :collapsible="true"
            layout="horizontal"
            label-align="left"
            value-align="left"
            label-size="small"
            :rows="$rows"
        >
            <x-slot:actions>
                <tedi:button variant="neutral" icon-start="edit" style="width: 100%;">Muuda</tedi:button>
            </x-slot:actions>
        </tedi:table-card>

        <tedi:table-card
            title="Kadri Kaasik"
            :collapsible="true"
            layout="vertical"
            :columns="3"
            label-size="small"
            :rows="[
                ['label' => 'Vanus', 'value' => '25'],
                ['label' => 'Külastuste arv', 'value' => '6'],
                ['label' => 'Olek', 'value' => $badge('brand', 'Kehtiv')],
            ]"
        />
    </tedi:vertical-spacing>
</div>
