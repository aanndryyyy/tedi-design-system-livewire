@storybook([
    'name' => 'Docked peek',
    'order' => 8,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.102.102?node-id=65376-125297&m=dev',
    'args' => [],
])

<tedi:sheet :close-on-backdrop-click="false">
    <tedi:sheet-trigger><tedi:button icon-start="calendar_month">Vali aeg</tedi:button></tedi:sheet-trigger>
    <tedi:sheet-content :show-overlay="false" :trap-focus="false" :lock-scroll="false">
            <tedi:sheet-header title="Vali aeg" variant="brand" collapsible />
            <tedi:sheet-body>
                <tedi:vertical-spacing :size="1">
                    <tedi:form-field>
                        <x-slot:label><tedi:form.label for="sheet-docked-date">Kuupäev</tedi:form.label></x-slot:label>
                        <tedi:date-field input-id="sheet-docked-date" />
                    </tedi:form-field>
                    <tedi:form-field>
                        <x-slot:label><tedi:form.label for="sheet-docked-time">Aeg</tedi:form.label></x-slot:label>
                        <tedi:time-field input-id="sheet-docked-time" />
                    </tedi:form-field>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-docked-reminder" name="sheet-docked-reminder" value="reminder" /> Lisa meeldetuletus</tedi:form.label>
                </tedi:vertical-spacing>
            </tedi:sheet-body>
            <tedi:sheet-footer full-width>
                <tedi:sheet-closer><tedi:button variant="secondary">Tühista</tedi:button></tedi:sheet-closer>
                <tedi:button>Rakenda</tedi:button>
            </tedi:sheet-footer>
        </tedi:sheet-content>
</tedi:sheet>
