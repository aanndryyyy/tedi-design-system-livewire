@storybook([
    'name' => 'Footer alignment',
    'order' => 7,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.102.102?node-id=65376-125297&m=dev',
    'args' => [],
])

<div style="display: flex; flex-wrap: wrap; align-items: flex-start; gap: 1rem">
    <tedi:vertical-spacing :size="0.25">
        <tedi:text as="p" modifiers="small" color="secondary">align="left"</tedi:text>
        <div>
            <tedi:sheet>
            <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
            <tedi:sheet-content>
            <tedi:sheet-header title="Seaded" variant="default" />
            <tedi:sheet-body>
                <tedi:vertical-spacing :size="0.5">
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-footer-left-lengths" name="sheet-footer-left-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-footer-left-angles" name="sheet-footer-left-angles" value="angles" /> Näita nurki</tedi:form.label>
                </tedi:vertical-spacing>
            </tedi:sheet-body>
            <tedi:sheet-footer align="left">
                <tedi:sheet-closer><tedi:button variant="secondary">Tühista</tedi:button></tedi:sheet-closer>
                <tedi:button>Rakenda</tedi:button>
            </tedi:sheet-footer>
        </tedi:sheet-content>
            </tedi:sheet>
        </div>
    </tedi:vertical-spacing>
    <tedi:vertical-spacing :size="0.25">
        <tedi:text as="p" modifiers="small" color="secondary">align="center"</tedi:text>
        <div>
            <tedi:sheet>
            <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
            <tedi:sheet-content>
            <tedi:sheet-header title="Seaded" variant="default" />
            <tedi:sheet-body>
                <tedi:vertical-spacing :size="0.5">
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-footer-center-lengths" name="sheet-footer-center-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-footer-center-angles" name="sheet-footer-center-angles" value="angles" /> Näita nurki</tedi:form.label>
                </tedi:vertical-spacing>
            </tedi:sheet-body>
            <tedi:sheet-footer align="center">
                <tedi:sheet-closer><tedi:button variant="secondary">Tühista</tedi:button></tedi:sheet-closer>
                <tedi:button>Rakenda</tedi:button>
            </tedi:sheet-footer>
        </tedi:sheet-content>
            </tedi:sheet>
        </div>
    </tedi:vertical-spacing>
    <tedi:vertical-spacing :size="0.25">
        <tedi:text as="p" modifiers="small" color="secondary">align="right"</tedi:text>
        <div>
            <tedi:sheet>
            <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
            <tedi:sheet-content>
            <tedi:sheet-header title="Seaded" variant="default" />
            <tedi:sheet-body>
                <tedi:vertical-spacing :size="0.5">
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-footer-right-lengths" name="sheet-footer-right-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-footer-right-angles" name="sheet-footer-right-angles" value="angles" /> Näita nurki</tedi:form.label>
                </tedi:vertical-spacing>
            </tedi:sheet-body>
            <tedi:sheet-footer align="right">
                <tedi:sheet-closer><tedi:button variant="secondary">Tühista</tedi:button></tedi:sheet-closer>
                <tedi:button>Rakenda</tedi:button>
            </tedi:sheet-footer>
        </tedi:sheet-content>
            </tedi:sheet>
        </div>
    </tedi:vertical-spacing>
    <tedi:vertical-spacing :size="0.25">
        <tedi:text as="p" modifiers="small" color="secondary">fullWidth</tedi:text>
        <div>
            <tedi:sheet>
            <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
            <tedi:sheet-content>
            <tedi:sheet-header title="Seaded" variant="default" />
            <tedi:sheet-body>
                <tedi:vertical-spacing :size="0.5">
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-footer-full-lengths" name="sheet-footer-full-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-footer-full-angles" name="sheet-footer-full-angles" value="angles" /> Näita nurki</tedi:form.label>
                </tedi:vertical-spacing>
            </tedi:sheet-body>
            <tedi:sheet-footer full-width>
                <tedi:sheet-closer><tedi:button variant="secondary">Tühista</tedi:button></tedi:sheet-closer>
                <tedi:button>Rakenda</tedi:button>
            </tedi:sheet-footer>
        </tedi:sheet-content>
            </tedi:sheet>
        </div>
    </tedi:vertical-spacing>
    <tedi:vertical-spacing :size="0.25">
        <tedi:text as="p" modifiers="small" color="secondary">fullWidth + right</tedi:text>
        <div>
            <tedi:sheet>
            <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
            <tedi:sheet-content>
            <tedi:sheet-header title="Seaded" variant="default" />
            <tedi:sheet-body>
                <tedi:vertical-spacing :size="0.5">
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-footer-full-right-lengths" name="sheet-footer-full-right-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-footer-full-right-angles" name="sheet-footer-full-right-angles" value="angles" /> Näita nurki</tedi:form.label>
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
</div>
