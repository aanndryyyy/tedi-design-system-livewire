@storybook([
    'name' => 'With Icons',
    'order' => 2,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.46.70?node-id=31693-133265&m=dev',
    'args' => [
        'ariaLabel' => 'Primary navigation',
    ],
    'argTypes' => [
        'ariaLabel' => ['control' => 'text'],
    ],
])

<tedi:top-nav :aria-label="$ariaLabel">
    <tedi:top-nav-item href="#" icon="home" is-active>Avaleht</tedi:top-nav-item>
    <tedi:top-nav-item href="#" icon="family_restroom">Perekond</tedi:top-nav-item>
    <tedi:top-nav-item href="#" icon="payments">Hüvitised</tedi:top-nav-item>
    <tedi:top-nav-item href="#" icon="work">Töö</tedi:top-nav-item>
    <tedi:top-nav-item href="#" icon="folder_shared">Minu andmed</tedi:top-nav-item>
</tedi:top-nav>
