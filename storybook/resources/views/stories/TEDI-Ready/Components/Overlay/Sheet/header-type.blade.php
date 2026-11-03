@storybook([
    'name' => 'Header type',
    'order' => 5,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.102.102?node-id=65376-125297&m=dev',
    'args' => [],
])

<tedi:vertical-spacing :size="2">
    <div>
        <tedi:vertical-spacing :size="0.75">
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
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-default-lengths" name="sheet-type-default-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-default-angles" name="sheet-type-default-angles" value="angles" /> Näita nurki</tedi:form.label>
                            </tedi:vertical-spacing>
                        </tedi:sheet-body>
                    </tedi:sheet-content>
                        </tedi:sheet>
                    </div>
                </tedi:vertical-spacing>
            </div>
        </tedi:vertical-spacing>
    </div>
    <div>
        <tedi:vertical-spacing :size="0.75">
            <div><tedi:text as="p" modifiers="bold">Collapsible</tedi:text></div>
            <div style="display: flex; flex-wrap: wrap; align-items: flex-start; gap: 1rem">
                <tedi:vertical-spacing :size="0.25">
                    <tedi:text as="p" modifiers="small" color="secondary">Heading left</tedi:text>
                    <div>
                        <tedi:sheet>
                        <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
                        <tedi:sheet-content>
                        <tedi:sheet-header title="Seaded" variant="default" collapsible />
                        <tedi:sheet-body>
                            <tedi:vertical-spacing :size="0.5">
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-collapsible-lengths" name="sheet-type-collapsible-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-collapsible-angles" name="sheet-type-collapsible-angles" value="angles" /> Näita nurki</tedi:form.label>
                            </tedi:vertical-spacing>
                        </tedi:sheet-body>
                    </tedi:sheet-content>
                        </tedi:sheet>
                    </div>
                </tedi:vertical-spacing>
                <tedi:vertical-spacing :size="0.25">
                    <tedi:text as="p" modifiers="small" color="secondary">Heading center</tedi:text>
                    <div>
                        <tedi:sheet>
                        <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
                        <tedi:sheet-content>
                        <tedi:sheet-header title="Seaded" variant="default" title-position="center" collapsible />
                        <tedi:sheet-body>
                            <tedi:vertical-spacing :size="0.5">
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-collapsible-center-lengths" name="sheet-type-collapsible-center-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-collapsible-center-angles" name="sheet-type-collapsible-center-angles" value="angles" /> Näita nurki</tedi:form.label>
                            </tedi:vertical-spacing>
                        </tedi:sheet-body>
                    </tedi:sheet-content>
                        </tedi:sheet>
                    </div>
                </tedi:vertical-spacing>
            </div>
        </tedi:vertical-spacing>
    </div>
    <div>
        <tedi:vertical-spacing :size="0.75">
            <div style="display: flex; flex-wrap: wrap; align-items: flex-start; gap: 1rem">
                <tedi:vertical-spacing :size="0.25">
                    <tedi:text as="p" modifiers="small" color="secondary">Heading center</tedi:text>
                    <div>
                        <tedi:sheet>
                        <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
                        <tedi:sheet-content>
                        <tedi:sheet-header title="Seaded" variant="default" title-position="center" />
                        <tedi:sheet-body>
                            <tedi:vertical-spacing :size="0.5">
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-center-lengths" name="sheet-type-center-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-center-angles" name="sheet-type-center-angles" value="angles" /> Näita nurki</tedi:form.label>
                            </tedi:vertical-spacing>
                        </tedi:sheet-body>
                    </tedi:sheet-content>
                        </tedi:sheet>
                    </div>
                </tedi:vertical-spacing>
            </div>
        </tedi:vertical-spacing>
    </div>
    <div>
        <tedi:vertical-spacing :size="0.75">
            <div><tedi:text as="p" modifiers="bold">Without closing button</tedi:text></div>
            <div style="display: flex; flex-wrap: wrap; align-items: flex-start; gap: 1rem">
                <tedi:vertical-spacing :size="0.25">
                    <tedi:text as="p" modifiers="small" color="secondary">Heading left</tedi:text>
                    <div>
                        <tedi:sheet>
                        <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
                        <tedi:sheet-content>
                        <tedi:sheet-header title="Seaded" variant="default" :close-button="false" />
                        <tedi:sheet-body>
                            <tedi:vertical-spacing :size="0.5">
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-no-close-lengths" name="sheet-type-no-close-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-no-close-angles" name="sheet-type-no-close-angles" value="angles" /> Näita nurki</tedi:form.label>
                            </tedi:vertical-spacing>
                        </tedi:sheet-body>
                    </tedi:sheet-content>
                        </tedi:sheet>
                    </div>
                </tedi:vertical-spacing>
                <tedi:vertical-spacing :size="0.25">
                    <tedi:text as="p" modifiers="small" color="secondary">Heading center</tedi:text>
                    <div>
                        <tedi:sheet>
                        <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
                        <tedi:sheet-content>
                        <tedi:sheet-header title="Seaded" variant="default" title-position="center" :close-button="false" />
                        <tedi:sheet-body>
                            <tedi:vertical-spacing :size="0.5">
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-no-close-center-lengths" name="sheet-type-no-close-center-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-no-close-center-angles" name="sheet-type-no-close-center-angles" value="angles" /> Näita nurki</tedi:form.label>
                            </tedi:vertical-spacing>
                        </tedi:sheet-body>
                    </tedi:sheet-content>
                        </tedi:sheet>
                    </div>
                </tedi:vertical-spacing>
            </div>
        </tedi:vertical-spacing>
    </div>
    <div>
        <tedi:vertical-spacing :size="0.75">
            <div><tedi:text as="p" modifiers="bold">Without handle</tedi:text></div>
            <div style="display: flex; flex-wrap: wrap; align-items: flex-start; gap: 1rem">
                <tedi:vertical-spacing :size="0.25">
                    <tedi:text as="p" modifiers="small" color="secondary">Heading left</tedi:text>
                    <div>
                        <tedi:sheet>
                        <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
                        <tedi:sheet-content :show-handle="false">
                        <tedi:sheet-header title="Seaded" variant="default" />
                        <tedi:sheet-body>
                            <tedi:vertical-spacing :size="0.5">
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-no-handle-lengths" name="sheet-type-no-handle-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-no-handle-angles" name="sheet-type-no-handle-angles" value="angles" /> Näita nurki</tedi:form.label>
                            </tedi:vertical-spacing>
                        </tedi:sheet-body>
                    </tedi:sheet-content>
                        </tedi:sheet>
                    </div>
                </tedi:vertical-spacing>
                <tedi:vertical-spacing :size="0.25">
                    <tedi:text as="p" modifiers="small" color="secondary">Heading center</tedi:text>
                    <div>
                        <tedi:sheet>
                        <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
                        <tedi:sheet-content :show-handle="false">
                        <tedi:sheet-header title="Seaded" variant="default" title-position="center" />
                        <tedi:sheet-body>
                            <tedi:vertical-spacing :size="0.5">
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-no-handle-center-lengths" name="sheet-type-no-handle-center-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-no-handle-center-angles" name="sheet-type-no-handle-center-angles" value="angles" /> Näita nurki</tedi:form.label>
                            </tedi:vertical-spacing>
                        </tedi:sheet-body>
                    </tedi:sheet-content>
                        </tedi:sheet>
                    </div>
                </tedi:vertical-spacing>
            </div>
        </tedi:vertical-spacing>
    </div>
    <div>
        <tedi:vertical-spacing :size="0.75">
            <div><tedi:text as="p" modifiers="bold">With custom button</tedi:text></div>
            <div style="display: flex; flex-wrap: wrap; align-items: flex-start; gap: 1rem">
                <tedi:vertical-spacing :size="0.25">
                    <tedi:text as="p" modifiers="small" color="secondary">Secondary closing button</tedi:text>
                    <div>
                        <tedi:sheet>
                        <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="tune">Seaded</tedi:button></tedi:sheet-trigger>
                        <tedi:sheet-content>
                        <tedi:sheet-header title="Seaded" variant="default" title-position="center" :close-button="false"><x-slot:actions><tedi:sheet-closer><tedi:button variant="secondary" icon-start="close" icon-only aria-label="Sulge" title="Sulge" /></tedi:sheet-closer></x-slot:actions> </tedi:sheet-header>
                        <tedi:sheet-body>
                            <tedi:vertical-spacing :size="0.5">
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-custom-close-lengths" name="sheet-type-custom-close-lengths" value="lengths" checked /> Näita pikkusi</tedi:form.label>
                                <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-type-custom-close-angles" name="sheet-type-custom-close-angles" value="angles" /> Näita nurki</tedi:form.label>
                            </tedi:vertical-spacing>
                        </tedi:sheet-body>
                    </tedi:sheet-content>
                        </tedi:sheet>
                    </div>
                </tedi:vertical-spacing>
            </div>
        </tedi:vertical-spacing>
    </div>
    <div>
        <tedi:vertical-spacing :size="0.75">
            <div><tedi:text as="p" modifiers="bold">With slot</tedi:text></div>
            <div style="display: flex; flex-wrap: wrap; align-items: flex-start; gap: 1rem">
                <tedi:vertical-spacing :size="0.25">
                    <tedi:text as="p" modifiers="small" color="secondary">Right slot</tedi:text>
                    <div>
                        <tedi:sheet>
                        <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="notifications">Teated</tedi:button></tedi:sheet-trigger>
                        <tedi:sheet-content>
                        <tedi:sheet-header title="Teated" variant="default"><x-slot:end><tedi:tag>2 uut teadet</tedi:tag></x-slot:end> </tedi:sheet-header>
                        <tedi:sheet-body>
                            <tedi:vertical-spacing :size="0.75">
                                <tedi:text-group type="vertical"><x-slot:label>Täna, 09:12</x-slot:label> <x-slot:value>Teie taotlus on menetlusse võetud</x-slot:value> </tedi:text-group>
                                <tedi:text-group type="vertical"><x-slot:label>Eile, 16:40</x-slot:label> <x-slot:value>Uus dokument on allkirjastamiseks valmis</x-slot:value> </tedi:text-group>
                            </tedi:vertical-spacing>
                        </tedi:sheet-body>
                    </tedi:sheet-content>
                        </tedi:sheet>
                    </div>
                </tedi:vertical-spacing>
                <tedi:vertical-spacing :size="0.25">
                    <tedi:text as="p" modifiers="small" color="secondary">Left slot</tedi:text>
                    <div>
                        <tedi:sheet>
                        <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="notifications">Teated</tedi:button></tedi:sheet-trigger>
                        <tedi:sheet-content>
                        <tedi:sheet-header title="Teated" variant="default"><x-slot:start><tedi:tag>2</tedi:tag></x-slot:start> </tedi:sheet-header>
                        <tedi:sheet-body>
                            <tedi:vertical-spacing :size="0.75">
                                <tedi:text-group type="vertical"><x-slot:label>Täna, 09:12</x-slot:label> <x-slot:value>Teie taotlus on menetlusse võetud</x-slot:value> </tedi:text-group>
                                <tedi:text-group type="vertical"><x-slot:label>Eile, 16:40</x-slot:label> <x-slot:value>Uus dokument on allkirjastamiseks valmis</x-slot:value> </tedi:text-group>
                            </tedi:vertical-spacing>
                        </tedi:sheet-body>
                    </tedi:sheet-content>
                        </tedi:sheet>
                    </div>
                </tedi:vertical-spacing>
                <tedi:vertical-spacing :size="0.25">
                    <tedi:text as="p" modifiers="small" color="secondary">Left and right slots</tedi:text>
                    <div>
                        <tedi:sheet>
                        <tedi:sheet-trigger><tedi:button variant="secondary" icon-start="notifications">Teated</tedi:button></tedi:sheet-trigger>
                        <tedi:sheet-content>
                        <tedi:sheet-header title="Teated" variant="default" title-position="center"><x-slot:start><tedi:sheet-closer><tedi:button variant="neutral" size="small" icon-start="arrow_back" icon-only aria-label="Tagasi" title="Tagasi" /></tedi:sheet-closer></x-slot:start> <x-slot:end><tedi:tag>2</tedi:tag></x-slot:end> </tedi:sheet-header>
                        <tedi:sheet-body>
                            <tedi:vertical-spacing :size="0.75">
                                <tedi:text-group type="vertical"><x-slot:label>Täna, 09:12</x-slot:label> <x-slot:value>Teie taotlus on menetlusse võetud</x-slot:value> </tedi:text-group>
                                <tedi:text-group type="vertical"><x-slot:label>Eile, 16:40</x-slot:label> <x-slot:value>Uus dokument on allkirjastamiseks valmis</x-slot:value> </tedi:text-group>
                            </tedi:vertical-spacing>
                        </tedi:sheet-body>
                    </tedi:sheet-content>
                        </tedi:sheet>
                    </div>
                </tedi:vertical-spacing>
            </div>
        </tedi:vertical-spacing>
    </div>
</tedi:vertical-spacing>
