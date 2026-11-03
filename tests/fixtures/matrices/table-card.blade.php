{{--
    Variant matrix for <tedi:table-card> and <tedi:text-group-list>, ported from
    @tedi-design-system/react content/table-card and
    content/text-group/text-group-list (CONVENTIONS.md §13).

    Harvested by IntegrityTest::test_rendered_matrix_classes_exist_in_stylesheet.
    Every interpolated class (`tedi-text-group--align-*`,
    `tedi-text-group__value--align-*`, the header / separator / actions
    modifiers) must appear at least once.
--}}
@php
    $tableCardRows = [
        ['label' => 'Periood', 'value' => '01.02 - 14.01.2024', 'colSpan' => 2],
        ['label' => 'Liik', 'value' => 'Haigusleht'],
        ['label' => 'Hüvitis', 'value' => '120.34 €', 'bold' => true],
    ];
@endphp

{{-- Layouts × label / value alignment × row alignment × label size --}}
@foreach (['horizontal', 'vertical'] as $layout)
    @foreach (['left', 'right'] as $align)
        <tedi:table-card :layout="$layout" :label-align="$align" :value-align="$align" :rows="$tableCardRows" />
    @endforeach
    <tedi:table-card :layout="$layout" :columns="2" label-size="small" :row-gap="1" :rows="$tableCardRows" />
@endforeach
<tedi:table-card row-align="center" :label-width="40" :rows="$tableCardRows" />

{{-- Header branches: plain title + end slot, selection (title as label and hidden label) --}}
<tedi:table-card title="ID kaart" subtitle="Isikut tõendav dokument" title-modifiers="h4" :rows="$tableCardRows">
    <x-slot:end-slot>Kehtiv</x-slot:end-slot>
</tedi:table-card>
<tedi:table-card title="Meelis Mägi" :selectable="true" :rows="$tableCardRows" />
<tedi:table-card :selectable="true" :default-selected="true" :rows="$tableCardRows" />

{{-- Collapsible: open / closed, both arrow types, summary, actions, children --}}
@foreach (['default', 'secondary'] as $arrowType)
    <tedi:table-card title="Hambaarst" subtitle="14.04.2026" :collapsible="true" :arrow-type="$arrowType" :default-open="false" :rows="$tableCardRows">
        <x-slot:end-slot>Menetluses</x-slot:end-slot>
    </tedi:table-card>
@endforeach
<tedi:table-card title="Kadri Kaasik" :collapsible="true" :rows="$tableCardRows" :summary="['label' => 'Kokku', 'value' => '120.34 €']">
    <tedi:card-content :padding="1" background="tertiary" class="tedi-card__content--separator">
        <tedi:text-group-list type="horizontal" row-align="center" :items="[['label' => 'Tõend', 'value' => 'COVID-19']]" />
    </tedi:card-content>
    <x-slot:actions>Muuda</x-slot:actions>
</tedi:table-card>
