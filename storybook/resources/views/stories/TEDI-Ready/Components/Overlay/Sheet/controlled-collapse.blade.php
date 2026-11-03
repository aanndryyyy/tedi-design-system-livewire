@storybook([
    'name' => 'Controlled collapse',
    'order' => 12,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.102.102?node-id=65376-125297&m=dev',
    'args' => [],
])

{{-- Upstream drives `open` and `collapsed` from React state outside the
     sheet; here the buttons sit inside its Alpine scope. --}}
<tedi:sheet :close-on-backdrop-click="false">
    <tedi:vertical-spacing :size="1">
        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem">
            <tedi:button variant="neutral" icon-start="edit" x-on:click="show(); $nextTick(() => collapsed = true)">Alusta joonistamist</tedi:button>
            <tedi:button variant="neutral" icon-start="check" x-on:click="collapsed = false" x-bind:disabled="! open">Kinnita joonis</tedi:button>
        </div>
    </tedi:vertical-spacing>
    <tedi:sheet-content :show-overlay="false" :trap-focus="false" :lock-scroll="false">
        <tedi:sheet-header title="Uus ala" variant="brand" collapsible />
        <tedi:sheet-body>
                <tedi:form-field>
                    <x-slot:label><tedi:form.label for="sheet-controlled-collapse-name">Ala nimi</tedi:form.label></x-slot:label>
                    <tedi:text-field id="sheet-controlled-collapse-name" />
                </tedi:form-field>
            </tedi:sheet-body>
        <tedi:sheet-footer full-width>
            <tedi:sheet-closer><tedi:button variant="secondary">Tühista</tedi:button></tedi:sheet-closer>
            <tedi:button>Salvesta</tedi:button>
        </tedi:sheet-footer>
    </tedi:sheet-content>
</tedi:sheet>
