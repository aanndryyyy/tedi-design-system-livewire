@storybook([
    'name' => 'Separate',
    'order' => 6,
    'status' => 'stable',
    'args' => [],
])

{{--
    Angular's version also shows a tooltip-trigger row (tedi-tooltip is CDK
    Overlay positioned, out of scope per CONVENTIONS.md §7 item 3) — dropped.
--}}
<tedi:row :cols="1" :gap-y="4">
    <div>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:checkbox />
            Text
        </tedi:form.label>
        <tedi:feedback-text text="Hint text" />
    </div>
    <div>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:checkbox :invalid="true" />
            Text
        </tedi:form.label>
        <tedi:feedback-text text="Feedback text" type="error" />
    </div>
    <tedi:form.label color="primary" :required="true" class="flex align-items-center gap-2">
        <tedi:checkbox />
        Text
    </tedi:form.label>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:checkbox />
        <tedi:icon name="stethoscope" :size="16" />
        Text
    </tedi:form.label>
    <div>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:checkbox />
            Text
        </tedi:form.label>
        <tedi:feedback-text text="Description" />
    </div>
</tedi:row>
