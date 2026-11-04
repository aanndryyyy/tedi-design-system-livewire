@storybook([
    'name' => 'Scrollable content',
    'order' => 14,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.102.102?node-id=65376-125297&m=dev',
    'args' => [],
])

<tedi:sheet>
    <tedi:sheet-trigger><tedi:button icon-start="edit">Ava vorm</tedi:button></tedi:sheet-trigger>
    <tedi:sheet-content max-height="32rem">
            <tedi:sheet-header title="Andmete sisestamine" variant="default" />
            <tedi:sheet-body>
                <tedi:vertical-spacing :size="1">
                <tedi:form-field>
                    <x-slot:label><tedi:form.label for="sheet-scroll-field-0">Eesnimi</tedi:form.label></x-slot:label>
                    <tedi:text-field id="sheet-scroll-field-0" />
                </tedi:form-field>
                <tedi:form-field>
                    <x-slot:label><tedi:form.label for="sheet-scroll-field-1">Perekonnanimi</tedi:form.label></x-slot:label>
                    <tedi:text-field id="sheet-scroll-field-1" />
                </tedi:form-field>
                <tedi:form-field>
                    <x-slot:label><tedi:form.label for="sheet-scroll-field-2">E-post</tedi:form.label></x-slot:label>
                    <tedi:text-field id="sheet-scroll-field-2" />
                </tedi:form-field>
                <tedi:form-field>
                    <x-slot:label><tedi:form.label for="sheet-scroll-field-3">Telefon</tedi:form.label></x-slot:label>
                    <tedi:text-field id="sheet-scroll-field-3" />
                </tedi:form-field>
                <tedi:form-field>
                    <x-slot:label><tedi:form.label for="sheet-scroll-field-4">Aadress</tedi:form.label></x-slot:label>
                    <tedi:text-field id="sheet-scroll-field-4" />
                </tedi:form-field>
                <tedi:form-field>
                    <x-slot:label><tedi:form.label for="sheet-scroll-field-5">Sihtnumber</tedi:form.label></x-slot:label>
                    <tedi:text-field id="sheet-scroll-field-5" />
                </tedi:form-field>
                <tedi:form-field>
                    <x-slot:label><tedi:form.label for="sheet-scroll-field-6">Linn</tedi:form.label></x-slot:label>
                    <tedi:text-field id="sheet-scroll-field-6" />
                </tedi:form-field>
                <tedi:form-field>
                    <x-slot:label><tedi:form.label for="sheet-scroll-field-7">Maakond</tedi:form.label></x-slot:label>
                    <tedi:text-field id="sheet-scroll-field-7" />
                </tedi:form-field>
                <tedi:form-field>
                    <x-slot:label><tedi:form.label for="sheet-scroll-field-8">Lisainfo</tedi:form.label></x-slot:label>
                    <tedi:text-field id="sheet-scroll-field-8" />
                </tedi:form-field>
                </tedi:vertical-spacing>
            </tedi:sheet-body>
            <tedi:sheet-footer full-width>
                <tedi:sheet-closer><tedi:button variant="secondary">Tühista</tedi:button></tedi:sheet-closer>
                <tedi:button>Rakenda</tedi:button>
            </tedi:sheet-footer>
        </tedi:sheet-content>
</tedi:sheet>
