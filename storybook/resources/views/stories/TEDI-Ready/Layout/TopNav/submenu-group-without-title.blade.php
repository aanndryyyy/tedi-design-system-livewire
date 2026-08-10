@storybook([
    'name' => 'Submenu Group Without Title',
    'order' => 8,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.46.70?node-id=31693-133265&m=dev',
    'args' => [
        'ariaLabel' => 'Primary navigation',
    ],
    'argTypes' => [
        'ariaLabel' => ['control' => 'text'],
    ],
])

<div style="min-height: 24rem">
    <tedi:top-nav :aria-label="$ariaLabel" submenu-fit="content" open-key="perekond">
        <tedi:top-nav-item href="#">Avaleht</tedi:top-nav-item>

        <tedi:top-nav-item key="perekond" is-active>Perekond
            <x-slot:submenu>
                <tedi:top-nav-group>
                    <tedi:top-nav-subitem href="#">Abiellumine</tedi:top-nav-subitem>
                    <tedi:top-nav-subitem href="#">Abielu lahutamine</tedi:top-nav-subitem>
                    <tedi:top-nav-subitem href="#">Kooselu registreerimine</tedi:top-nav-subitem>
                </tedi:top-nav-group>
            </x-slot:submenu>
        </tedi:top-nav-item>

        <tedi:top-nav-item href="#">Hüvitised ja toetused</tedi:top-nav-item>
        <tedi:top-nav-item href="#">Töö ja töösuhted</tedi:top-nav-item>
        <tedi:top-nav-item href="#">Liiklus ja sõidukid</tedi:top-nav-item>
        <tedi:top-nav-item href="#">Minu andmed</tedi:top-nav-item>
    </tedi:top-nav>
</div>
