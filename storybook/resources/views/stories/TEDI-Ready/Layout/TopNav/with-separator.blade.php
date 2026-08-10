@storybook([
    'name' => 'With Separator',
    'order' => 3,
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
    <tedi:top-nav-item href="#" is-active>Töölaud</tedi:top-nav-item>
    <tedi:top-nav-item href="#">Minu taotlused</tedi:top-nav-item>
    <tedi:top-nav-item href="#">Minu dokumendid</tedi:top-nav-item>
    <tedi:top-nav-item href="#">Koolitused</tedi:top-nav-item>
    <tedi:top-nav-separator />
    <tedi:top-nav-item href="#" icon="settings">Seaded</tedi:top-nav-item>
</tedi:top-nav>
