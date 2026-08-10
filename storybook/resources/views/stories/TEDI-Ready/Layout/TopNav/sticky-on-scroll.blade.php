@storybook([
    'name' => 'Sticky On Scroll',
    'order' => 13,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.46.70?node-id=31693-133265&m=dev',
    'args' => [],
    'argTypes' => [],
])

<div style="height: 600px; overflow-y: auto; border: 1px dashed var(--general-border-primary)">
    <div style="padding: 1.5rem">
        <tedi:text>Scroll down — once the nav reaches the top of the viewport it sticks via <code>tedi:affix</code>.</tedi:text>
    </div>

    <tedi:affix :top="0">
        <tedi:top-nav aria-label="Primary navigation">
            <tedi:top-nav-item href="#" is-active>Avaleht</tedi:top-nav-item>
            <tedi:top-nav-item href="#">Perekond</tedi:top-nav-item>
            <tedi:top-nav-item href="#">Hüvitised ja toetused</tedi:top-nav-item>
            <tedi:top-nav-item href="#">Töö ja töösuhted</tedi:top-nav-item>
            <tedi:top-nav-item href="#">Minu andmed</tedi:top-nav-item>
        </tedi:top-nav>
    </tedi:affix>

    <div style="padding: 1.5rem">
        <tedi:vertical-spacing :size="1">
            @for ($i = 1; $i <= 30; $i++)
                <tedi:text>Scroll content row {{ $i }}. Scroll the page to see how the nav above behaves with the chosen stickiness strategy.</tedi:text>
            @endfor
        </tedi:vertical-spacing>
    </div>
</div>
