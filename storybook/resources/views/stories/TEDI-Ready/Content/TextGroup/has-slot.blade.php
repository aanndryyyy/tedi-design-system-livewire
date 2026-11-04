@storybook([
    'name' => 'Has Slot',
    'order' => 4,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

{{--
    `tedi-text-group-value` is a flex row that takes any content, so a trailing
    info tooltip, status badge or tag sits inline beside the text with the
    design system's inner spacing already applied.
--}}
<tedi:vertical-spacing :size="1">
    <tedi:text-group type="vertical">
        <x-slot:label>Ligipääs</x-slot:label>
        <x-slot:value>
            Arstile ja esindajale nähtav
            <tedi:info-tooltip aria-label="Lisainfo ligipääsu kohta">
                Dokumenti näevad sinu raviarst ja esindajaks määratud isik.
            </tedi:info-tooltip>
        </x-slot:value>
    </tedi:text-group>
    <tedi:text-group type="vertical">
        <x-slot:label>Nimi</x-slot:label>
        <x-slot:value>
            Mari Maasikas
            <tedi:status-badge color="success" text="Ligipääs lubatud" />
        </x-slot:value>
    </tedi:text-group>
</tedi:vertical-spacing>
