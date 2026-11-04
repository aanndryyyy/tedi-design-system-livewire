@storybook([
    'name' => 'Multiple Tag Layout',
    'order' => 6,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

@php
    // Angular: six consecutive days from the fixed reference date 15.06.2026.
    $labels = ['15.06.2026', '16.06.2026', '17.06.2026', '18.06.2026', '19.06.2026', '20.06.2026'];
    $tags = [];
    foreach ($labels as $i => $label) {
        $tags[] = ['id' => (string) $i, 'label' => $label];
    }
    $values = ['2026-06-15', '2026-06-16', '2026-06-17', '2026-06-18', '2026-06-19', '2026-06-20'];
@endphp

{{--
    Angular measures the tag widths in JS to decide how many fit on one row.
    That measurement is an explicit `visible-tag-count` prop here
    (CONVENTIONS.md §5); leaving it unset renders every tag and keeps the
    `tedi-date-input--tags-measuring` state, which is Angular's first paint.
--}}
<tedi:row :cols="1" :gap="3">
    <tedi:col>
        <tedi:text as="p">Multiple rows (default)</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-tags-wrap">Kuupäevad</tedi:form.label>
            </x-slot:label>
            <tedi:date-field
                input-id="date-tags-wrap"
                mode="multiple"
                :multi-row="true"
                :value="$values"
                :tags="$tags"
            />
            <x-slot:feedback>
                <tedi:feedback-text text="Tags wrap onto new lines and the field grows in height." />
            </x-slot:feedback>
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:text as="p">Single row</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-tags-single">Kuupäevad</tedi:form.label>
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
                <tedi:feedback-text text="Overflow collapses into a +N counter; narrow tags are truncated from the start." />
            </x-slot:feedback>
        </tedi:form-field>
    </tedi:col>
</tedi:row>
