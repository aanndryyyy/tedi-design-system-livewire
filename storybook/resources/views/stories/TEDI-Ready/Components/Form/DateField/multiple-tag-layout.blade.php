@storybook([
    'name' => 'Multiple Tag Layout',
    'order' => 6,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

@php
    $labels = ['08.06.2026', '09.06.2026', '10.06.2026', '11.06.2026', '12.06.2026', '13.06.2026'];
    $tags = [];
    foreach ($labels as $i => $label) {
        $tags[] = ['id' => (string) $i, 'label' => $label];
    }
    $values = ['2026-06-08', '2026-06-09', '2026-06-10', '2026-06-11', '2026-06-12', '2026-06-13'];
@endphp

{{--
    Angular measures the tag widths in JS to decide how many fit on one row.
    That measurement is an explicit `visible-tag-count` prop here
    (CONVENTIONS.md §5); leaving it unset renders every tag and keeps the
    `tedi-date-input--tags-measuring` state, which is Angular's first paint.
--}}
<tedi:row :cols="1" :gap="3">
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-tags-wrap">Mitmerealine (vaikimisi)</tedi:form.label>
            </x-slot:label>
            <tedi:date-field
                input-id="date-tags-wrap"
                mode="multiple"
                :multi-row="true"
                :value="$values"
                :tags="$tags"
            />
            <x-slot:feedback>
                <tedi:feedback-text text="Sildid murduvad uutele ridadele; välja kõrgus kasvab." />
            </x-slot:feedback>
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-tags-single">Üherealine + loendur</tedi:form.label>
            </x-slot:label>
            <tedi:date-field
                input-id="date-tags-single"
                mode="multiple"
                :multi-row="false"
                tag-ellipsis="start"
                :visible-tag-count="2"
                :value="$values"
                :tags="$tags"
            />
            <x-slot:feedback>
                <tedi:feedback-text text="Sildid püsivad ühel real; ülejääk koondub +N loendurisse. Kitsad sildid lühenevad algusest (…06.2026)." />
            </x-slot:feedback>
        </tedi:form-field>
    </tedi:col>
</tedi:row>
