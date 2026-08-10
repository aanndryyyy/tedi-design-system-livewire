@storybook([
    'name' => 'Custom Item Content',
    'order' => 4,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.46.70?node-id=31693-133265&m=dev',
    'args' => [
        'ariaLabel' => 'Primary navigation',
    ],
    'argTypes' => [
        'ariaLabel' => ['control' => 'text'],
    ],
])

{{-- The item's slot takes arbitrary inline content, so a status badge sits
     next to the label. --}}
<tedi:top-nav :aria-label="$ariaLabel">
    <tedi:top-nav-item href="#" is-active>Töölaud</tedi:top-nav-item>
    <tedi:top-nav-item href="#" icon="mail">Sõnumid <tedi:status-badge color="accent" text="3" /></tedi:top-nav-item>
    <tedi:top-nav-item href="#">Taotlused <tedi:status-badge color="brand" text="Uus" /></tedi:top-nav-item>
    <tedi:top-nav-item href="#">Minu andmed</tedi:top-nav-item>
</tedi:top-nav>
