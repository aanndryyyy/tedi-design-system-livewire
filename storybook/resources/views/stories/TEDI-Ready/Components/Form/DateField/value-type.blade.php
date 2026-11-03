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
    explicit `display` and `tags` props (CONVENTIONS.md §5). The values are
    Angular's fixed story dates: 18.06.2026 and 25.06.2026.
--}}
<tedi:row :cols="1" :gap="3">
    <tedi:col>
        <tedi:text as="p">Single date</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-vt-single">Kuupäev</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-vt-single" placeholder="pp.kk.aaaa" />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:text as="p">Single date with a default value</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-vt-single-value">Kuupäev</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="date-vt-single-value" value="2026-06-18" display="18.06.2026" />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:text as="p">Multiple dates</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-vt-multiple">Kuupäevad</tedi:form.label>
            </x-slot:label>
            <tedi:date-field
                input-id="date-vt-multiple"
                mode="multiple"
                :value="['2026-06-18', '2026-06-25']"
                :tags="[['id' => '1', 'label' => '18.06.2026'], ['id' => '2', 'label' => '25.06.2026']]"
            />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:text as="p">Date range</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-vt-range">Vahemik</tedi:form.label>
            </x-slot:label>
            <tedi:date-field
                input-id="date-vt-range"
                mode="range"
                :value="['from' => '2026-06-18', 'to' => '2026-06-25']"
                display="18.06.2026 – 25.06.2026"
            />
        </tedi:form-field>
    </tedi:col>
</tedi:row>
