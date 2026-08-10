@storybook([
    'name' => 'Content Width Mega Menu',
    'order' => 7,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.46.70?node-id=31693-133265&m=dev',
    'args' => [
        'ariaLabel' => 'Primary navigation',
    ],
    'argTypes' => [
        'ariaLabel' => ['control' => 'text'],
    ],
])

{{-- The content fit positions the panel absolutely, so it adds no page height.
     The wrapper reserves room below the nav so the open panel is fully visible
     without scrolling — upstream uses a decorator for the same reason. --}}
<div style="min-height: 24rem">
    <tedi:top-nav :aria-label="$ariaLabel" submenu-fit="content" open-key="perekond">
        <tedi:top-nav-item href="#">Avaleht</tedi:top-nav-item>

        <tedi:top-nav-item key="perekond" is-active>Perekond
            <x-slot:submenu>
                <tedi:top-nav-group title="Abielu">
                    <tedi:top-nav-subitem href="#">Abiellumine</tedi:top-nav-subitem>
                    <tedi:top-nav-subitem href="#">Abielu lahutamine</tedi:top-nav-subitem>
                    <tedi:top-nav-subitem href="#">Kooselu registreerimine</tedi:top-nav-subitem>
                </tedi:top-nav-group>
                <tedi:top-nav-group title="Dokumendid">
                    <tedi:top-nav-subitem href="#">Lastega perede nõustamine</tedi:top-nav-subitem>
                    <tedi:top-nav-subitem href="#">Lapsendamine</tedi:top-nav-subitem>
                    <tedi:top-nav-subitem href="#">Terviseprobleemiga laps</tedi:top-nav-subitem>
                </tedi:top-nav-group>
            </x-slot:submenu>
        </tedi:top-nav-item>

        <tedi:top-nav-item href="#">Hüvitised ja toetused</tedi:top-nav-item>
        <tedi:top-nav-item href="#">Töö ja töösuhted</tedi:top-nav-item>
        <tedi:top-nav-item href="#">Liiklus ja sõidukid</tedi:top-nav-item>
        <tedi:top-nav-item href="#">Minu andmed</tedi:top-nav-item>
    </tedi:top-nav>
</div>
