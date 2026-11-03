@storybook([
    'name' => 'Keep Mounted',
    'order' => 10,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.102.102?node-id=65376-125297&m=dev',
    'args' => [],
])

{{-- This port always keeps the panel mounted (CONVENTIONS.md §8), so
     upstream's keepMounted prop is implied rather than passed. --}}
<tedi:sheet>
    <tedi:sheet-trigger><tedi:button icon-start="edit_note">Märkmed</tedi:button></tedi:sheet-trigger>
    <tedi:sheet-content>
            <tedi:sheet-header title="Märkmed" variant="default" />
            <tedi:sheet-body>
                <tedi:form-field>
                    <x-slot:label><tedi:form.label for="sheet-persisted-note">Märkus</tedi:form.label></x-slot:label>
                    <tedi:text-field id="sheet-persisted-note" placeholder="Kirjuta midagi…" />
                </tedi:form-field>
            </tedi:sheet-body>
        </tedi:sheet-content>
</tedi:sheet>
