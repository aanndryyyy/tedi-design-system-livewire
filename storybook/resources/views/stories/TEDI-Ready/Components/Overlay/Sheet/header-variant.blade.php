@storybook([
    'name' => 'Header variant',
    'order' => 4,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.102.102?node-id=65376-125297&m=dev',
    'args' => [],
])

<div style="display: flex; flex-wrap: wrap; align-items: flex-start; gap: 1rem">
    <tedi:vertical-spacing :size="0.25">
        <tedi:text as="p" modifiers="small" color="secondary">Default</tedi:text>
        <div>
            <tedi:sheet>
            <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
            <tedi:sheet-content>
            <tedi:sheet-header title="Seaded" variant="default" />
            <tedi:sheet-body>
                <tedi:vertical-spacing :size="0.5">
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-variant-default-lengths" name="sheet-variant-default-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-variant-default-angles" name="sheet-variant-default-angles" value="angles" /> Näita nurki</tedi:form.label>
                </tedi:vertical-spacing>
            </tedi:sheet-body>
        </tedi:sheet-content>
            </tedi:sheet>
        </div>
    </tedi:vertical-spacing>
    <tedi:vertical-spacing :size="0.25">
        <tedi:text as="p" modifiers="small" color="secondary">Brand</tedi:text>
        <div>
            <tedi:sheet>
            <tedi:sheet-trigger><tedi:button icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
            <tedi:sheet-content>
            <tedi:sheet-header title="Seaded" variant="brand" />
            <tedi:sheet-body>
                <tedi:vertical-spacing :size="0.5">
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-variant-brand-lengths" name="sheet-variant-brand-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-variant-brand-angles" name="sheet-variant-brand-angles" value="angles" /> Näita nurki</tedi:form.label>
                </tedi:vertical-spacing>
            </tedi:sheet-body>
        </tedi:sheet-content>
            </tedi:sheet>
        </div>
    </tedi:vertical-spacing>
</div>
