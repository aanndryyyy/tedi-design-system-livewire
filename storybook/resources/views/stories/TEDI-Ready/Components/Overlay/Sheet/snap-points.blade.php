@storybook([
    'name' => 'Snap points',
    'order' => 9,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.102.102?node-id=65376-125297&m=dev',
    'args' => [],
])

<tedi:sheet>
    <tedi:sheet-trigger><tedi:button icon-start="layers">Kihid</tedi:button></tedi:sheet-trigger>
    <tedi:sheet-content :snap-points="[0.4, 0.9]">
            <tedi:sheet-header title="Kihid" variant="default" collapsible />
            <tedi:sheet-body>
                <tedi:vertical-spacing :size="0.5">
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="snap-layer-0" name="snap-layer-0" value="Hübriidkaart" /> Hübriidkaart</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="snap-layer-1" name="snap-layer-1" value="Reljeef" /> Reljeef</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="snap-layer-2" name="snap-layer-2" value="Katastriüksused" /> Katastriüksused</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="snap-layer-3" name="snap-layer-3" value="Kitsendused" /> Kitsendused</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="snap-layer-4" name="snap-layer-4" value="Aadressandmed" /> Aadressandmed</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="snap-layer-5" name="snap-layer-5" value="Hübriidkaart" /> Hübriidkaart</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="snap-layer-6" name="snap-layer-6" value="Reljeef" /> Reljeef</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="snap-layer-7" name="snap-layer-7" value="Katastriüksused" /> Katastriüksused</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="snap-layer-8" name="snap-layer-8" value="Kitsendused" /> Kitsendused</tedi:form.label>
                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="snap-layer-9" name="snap-layer-9" value="Aadressandmed" /> Aadressandmed</tedi:form.label>
                </tedi:vertical-spacing>
            </tedi:sheet-body>
        </tedi:sheet-content>
</tedi:sheet>
