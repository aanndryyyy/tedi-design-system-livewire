@storybook([
    'name' => 'Value Type',
    'order' => 5,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

{{--
    Angular derives the input text and the multiple-mode tags from the value via
    formatDate(); JS callables are not ported, so the formatted strings are the
    explicit `display` and `tags` props (CONVENTIONS.md §5).
--}}
<tedi:row :cols="1" :gap="3">
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-vt-single">Üksik kuupäev</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-vt-single" placeholder="pp.kk.aaaa" />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-vt-single-value">Üksik kuupäev vaikeväärtusega</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-vt-single-value" value="2026-06-08" display="08.06.2026" />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-vt-multiple">Mitu kuupäeva</tedi:form.label>
            </x-slot:label>
            <tedi:date-field
                input-id="date-vt-multiple"
                mode="multiple"
                :value="['2026-06-08', '2026-06-15']"
                :tags="[['id' => '1', 'label' => '08.06.2026'], ['id' => '2', 'label' => '15.06.2026']]"
            />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-vt-range">Vahemik</tedi:form.label>
            </x-slot:label>
            <tedi:date-field
                input-id="date-vt-range"
                mode="range"
                :value="['from' => '2026-06-08', 'to' => '2026-06-15']"
                display="08.06.2026 – 15.06.2026"
            />
        </tedi:form-field>
    </tedi:col>
</tedi:row>
