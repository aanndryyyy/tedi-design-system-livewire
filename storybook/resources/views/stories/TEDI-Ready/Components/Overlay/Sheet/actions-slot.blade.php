@storybook([
    'name' => 'Actions slot',
    'order' => 2,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.102.102?node-id=65376-125297&m=dev',
    'args' => [],
])

<div style="display: flex; flex-wrap: wrap; align-items: flex-start; gap: 1rem">
    <tedi:vertical-spacing :size="0.25">
        <tedi:text as="p" modifiers="small" color="secondary">With footer</tedi:text>
        <div>
            <tedi:sheet>
            <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
            <tedi:sheet-content>
            <tedi:sheet-header title="Seaded" variant="default" />
            <tedi:sheet-body>
                <tedi:vertical-spacing :size="0.5">
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-actions-footer-lengths" name="sheet-actions-footer-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-actions-footer-angles" name="sheet-actions-footer-angles" value="angles" /> Näita nurki</tedi:form.label>
                </tedi:vertical-spacing>
            </tedi:sheet-body>
            <tedi:sheet-footer full-width>
                <tedi:sheet-closer><tedi:button variant="secondary">Tühista</tedi:button></tedi:sheet-closer>
                <tedi:button>Rakenda</tedi:button>
                <x-slot:end><tedi:button variant="secondary" icon-start="more_vert" icon-only aria-label="Rohkem valikuid" title="Rohkem valikuid" /></x-slot:end>
            </tedi:sheet-footer>
        </tedi:sheet-content>
            </tedi:sheet>
        </div>
    </tedi:vertical-spacing>
    <tedi:vertical-spacing :size="0.25">
        <tedi:text as="p" modifiers="small" color="secondary">Without footer</tedi:text>
        <div>
            <tedi:sheet>
            <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="menu_book">Loe lähemalt</tedi:button></tedi:sheet-trigger>
            <tedi:sheet-content>
            <tedi:sheet-header title="Loe lähemalt" variant="default" />
            <tedi:sheet-body>
                <tedi:text as="p">Orav ehk harilik orav ehk punaorav on oravlaste sugukonda kuuluv näriline. See on ainus Eestis pärismaisena elav orava perekonna liik. Orava karvastiku värvus varieerub ka Eesti ulatuses: Saaremaal on see valdavalt hallikam, mandril punakam.</tedi:text>
            </tedi:sheet-body>
        </tedi:sheet-content>
            </tedi:sheet>
        </div>
    </tedi:vertical-spacing>
</div>
