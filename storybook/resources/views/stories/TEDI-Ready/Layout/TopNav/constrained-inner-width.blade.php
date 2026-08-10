@storybook([
    'name' => 'Constrained Inner Width',
    'order' => 6,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.46.70?node-id=31693-133265&m=dev',
    'args' => [
        'ariaLabel' => 'Primary navigation',
        'maxWidth' => 'lg',
    ],
    'argTypes' => [
        'ariaLabel' => ['control' => 'text'],
        'maxWidth' => [
            'control' => 'select',
            'options' => ['sm', 'md', 'lg', 'xl', 'xxl', 'none'],
        ],
    ],
])

<tedi:top-nav :aria-label="$ariaLabel" :max-width="$maxWidth" open-key="perekond">
    <tedi:top-nav-item href="#">Avaleht</tedi:top-nav-item>
    <tedi:top-nav-item key="perekond" is-active>Perekond</tedi:top-nav-item>
    <tedi:top-nav-item href="#">Hüvitised ja toetused</tedi:top-nav-item>
    <tedi:top-nav-item href="#">Töö ja töösuhted</tedi:top-nav-item>
    <tedi:top-nav-item href="#">Minu andmed</tedi:top-nav-item>

    <x-slot:submenu>
        <tedi:top-nav-submenu for="perekond">
            <tedi:top-nav-group title="Abielu">
                <tedi:top-nav-subitem href="#">Abiellumine</tedi:top-nav-subitem>
                <tedi:top-nav-subitem href="#">Abielu lahutamine</tedi:top-nav-subitem>
            </tedi:top-nav-group>
            <tedi:top-nav-group title="Dokumendid">
                <tedi:top-nav-subitem href="#">Lastega perede nõustamine</tedi:top-nav-subitem>
                <tedi:top-nav-subitem href="#">Lapsendamine</tedi:top-nav-subitem>
            </tedi:top-nav-group>
        </tedi:top-nav-submenu>
    </x-slot:submenu>
</tedi:top-nav>
