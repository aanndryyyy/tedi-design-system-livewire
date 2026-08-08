@storybook([
    'name' => 'Structure',
    'order' => 3,
    'status' => 'stable',
    'args' => [],
])

{{--
    Angular's info-tooltip rows are dropped: tedi-info-tooltip wraps
    tedi-tooltip, which is CDK Overlay positioned and out of scope
    (CONVENTIONS.md §7 item 3; info-tooltip itself also isn't ported — see
    README "Not in this phase"). The <tedi:label-row> row below still
    demonstrates the layout wrapper (it has no props of its own to fake),
    pairing the label with a static <tedi:info-button> instead of the
    tooltip-triggering one Angular uses.

    <tedi:label-row> has no Angular story file of its own (grepped
    tedi/components/form/label-row); it's only ever exercised from within
    label.stories.ts, so it's ported here under that file's `title`.
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
            <tedi:form.label for="ingredient-3" :required="true">Toimeaine</tedi:form.label>
            <tedi:info-button aria-label="Vihje" />
        </tedi:label-row>
    </tedi:col>
</tedi:row>
