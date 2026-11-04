@storybook([
    'name' => 'Structure',
    'order' => 3,
    'status' => 'stable',
    'args' => [],
])

{{--
    `tedi:label-row` has no Angular story file of its own; it is only ever
    exercised from within label.stories.ts, so it is ported here under that
    file's `title`.
--}}
<tedi:row :cols="1" :gap-y="3">
    <tedi:col>
        <tedi:form.label for="ingredient-1">Toimeaine</tedi:form.label>
    </tedi:col>
    <tedi:col>
        <tedi:form.label for="ingredient-2" :required="true">Toimeaine</tedi:form.label>
    </tedi:col>
    <tedi:col>
        <tedi:label-row>
            <tedi:form.label for="ingredient-3">Toimeaine</tedi:form.label>
            <tedi:info-tooltip>Vihje sisu</tedi:info-tooltip>
        </tedi:label-row>
    </tedi:col>
    <tedi:col>
        <tedi:label-row>
            <tedi:form.label for="ingredient-4" :required="true">Toimeaine</tedi:form.label>
            <tedi:info-tooltip>Vihje sisu</tedi:info-tooltip>
        </tedi:label-row>
    </tedi:col>
</tedi:row>
