@storybook([
    'name' => 'Group Variants',
    'order' => 11,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.46.70?node-id=31693-133265&m=dev',
    'args' => [],
    'argTypes' => [],
])

<div style="padding: 1.5rem; background-color: var(--navigation-horizontal-submenu-background)">
    <tedi:vertical-spacing :size="1.5">
        <tedi:row>
            <tedi:col :width="2" class="display-flex align-items-center">
                <tedi:text modifiers="bold" color="white">Default</tedi:text>
            </tedi:col>
            <tedi:col>
                <tedi:top-nav-group title="Abielu">
                    <tedi:top-nav-subitem href="#">Abiellumine</tedi:top-nav-subitem>
                    <tedi:top-nav-subitem href="#">Abielu lahutamine</tedi:top-nav-subitem>
                    <tedi:top-nav-subitem href="#">Kooselu registreerimine</tedi:top-nav-subitem>
                </tedi:top-nav-group>
            </tedi:col>
        </tedi:row>

        <tedi:row>
            <tedi:col :width="2" class="display-flex align-items-center">
                <tedi:text modifiers="bold" color="white">With icon</tedi:text>
            </tedi:col>
            <tedi:col>
                <tedi:top-nav-group title="Abielu" icon="favorite_border">
                    <tedi:top-nav-subitem href="#">Abiellumine</tedi:top-nav-subitem>
                    <tedi:top-nav-subitem href="#">Abielu lahutamine</tedi:top-nav-subitem>
                    <tedi:top-nav-subitem href="#">Kooselu registreerimine</tedi:top-nav-subitem>
                </tedi:top-nav-group>
            </tedi:col>
        </tedi:row>
    </tedi:vertical-spacing>
</div>
