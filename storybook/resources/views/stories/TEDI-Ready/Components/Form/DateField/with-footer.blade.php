@storybook([
    'name' => 'With Footer',
    'order' => 13,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

<tedi:row :gap="3" :cols="2">
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-footer-time">Kellaaeg</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-footer-time" :open="true">
                <x-slot:footer><tedi:row justify-items="center"><tedi:col><tedi:button variant="neutral" size="small" type="button" icon-start="schedule">Vali kellaaeg</tedi:button></tedi:col></tedi:row></x-slot:footer>
            </tedi:date-field>
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-footer-save">Kuupäev</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-footer-save" :open="true">
                <x-slot:footer><tedi:row :cols="2" :gap-x="2"><tedi:col><tedi:button class="w-100" variant="secondary" size="small" type="button">Tühista</tedi:button></tedi:col><tedi:col><tedi:button class="w-100" size="small" type="button">Salvesta</tedi:button></tedi:col></tedi:row></x-slot:footer>
            </tedi:date-field>
        </tedi:form-field>
    </tedi:col>
</tedi:row>
