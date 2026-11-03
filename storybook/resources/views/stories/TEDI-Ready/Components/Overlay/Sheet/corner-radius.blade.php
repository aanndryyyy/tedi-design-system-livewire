@storybook([
    'name' => 'Corner radius',
    'order' => 3,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.102.102?node-id=65376-125297&m=dev',
    'args' => [],
])

{{-- Upstream's fourth example switches the radius at the md breakpoint
     (radius="none" md={{ radius: 'card' }}). Breakpoint props are not ported
     (CONVENTIONS.md §7 item 1), so it is left out. --}}
<div style="display: flex; flex-wrap: wrap; align-items: flex-start; gap: 1rem">
    <tedi:vertical-spacing :size="0.25">
        <tedi:text as="p" modifiers="small" color="secondary">24px</tedi:text>
        <div>
            <tedi:sheet>
            <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
            <tedi:sheet-content radius="default">
            <tedi:sheet-header title="Seaded" variant="default" />
            <tedi:sheet-body>
                <tedi:vertical-spacing :size="0.5">
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-radius-default-lengths" name="sheet-radius-default-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-radius-default-angles" name="sheet-radius-default-angles" value="angles" /> Näita nurki</tedi:form.label>
                </tedi:vertical-spacing>
            </tedi:sheet-body>
        </tedi:sheet-content>
            </tedi:sheet>
        </div>
    </tedi:vertical-spacing>
    <tedi:vertical-spacing :size="0.25">
        <tedi:text as="p" modifiers="small" color="secondary">4px</tedi:text>
        <div>
            <tedi:sheet>
            <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
            <tedi:sheet-content radius="card">
            <tedi:sheet-header title="Seaded" variant="default" />
            <tedi:sheet-body>
                <tedi:vertical-spacing :size="0.5">
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-radius-card-lengths" name="sheet-radius-card-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-radius-card-angles" name="sheet-radius-card-angles" value="angles" /> Näita nurki</tedi:form.label>
                </tedi:vertical-spacing>
            </tedi:sheet-body>
        </tedi:sheet-content>
            </tedi:sheet>
        </div>
    </tedi:vertical-spacing>
    <tedi:vertical-spacing :size="0.25">
        <tedi:text as="p" modifiers="small" color="secondary">0px</tedi:text>
        <div>
            <tedi:sheet>
            <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
            <tedi:sheet-content radius="none">
            <tedi:sheet-header title="Seaded" variant="default" />
            <tedi:sheet-body>
                <tedi:vertical-spacing :size="0.5">
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-radius-none-lengths" name="sheet-radius-none-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-radius-none-angles" name="sheet-radius-none-angles" value="angles" /> Näita nurki</tedi:form.label>
                </tedi:vertical-spacing>
            </tedi:sheet-body>
        </tedi:sheet-content>
            </tedi:sheet>
        </div>
    </tedi:vertical-spacing>
</div>
