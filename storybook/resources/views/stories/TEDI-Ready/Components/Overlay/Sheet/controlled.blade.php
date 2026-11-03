@storybook([
    'name' => 'Controlled',
    'order' => 11,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.102.102?node-id=65376-125297&m=dev',
    'args' => [],
])

{{-- Upstream's parent owns `open` through React state. Here the button sits
     inside the sheet's Alpine scope and calls show(); the Save button sets
     open = false. --}}
<tedi:sheet>
    <tedi:button x-on:click="show()">Ava seaded</tedi:button>
    <tedi:sheet-content>
        <tedi:sheet-header title="Seaded" variant="default" />
        <tedi:sheet-body>
                <tedi:vertical-spacing :size="0.5">
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-controlled-lengths" name="sheet-controlled-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-controlled-angles" name="sheet-controlled-angles" value="angles" /> Näita nurki</tedi:form.label>
                </tedi:vertical-spacing>
            </tedi:sheet-body>
        <tedi:sheet-footer full-width>
            <tedi:sheet-closer><tedi:button variant="secondary">Tühista</tedi:button></tedi:sheet-closer>
            <tedi:button x-on:click="open = false">Salvesta</tedi:button>
        </tedi:sheet-footer>
    </tedi:sheet-content>
</tedi:sheet>
