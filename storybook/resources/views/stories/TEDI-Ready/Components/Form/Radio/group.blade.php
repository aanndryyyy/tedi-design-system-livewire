@storybook([
    'name' => 'Group',
    'order' => 7,
    'status' => 'stable',
    'args' => [],
])

{{--
    Angular projects the trailing <tedi-feedback-text> into the group through
    <ng-content select="tedi-feedback-text" />; in Blade that is the named
    `subtexts` slot. The per-input `name` Angular repeats is hoisted to the
    group, which propagates it to each <tedi:radio> via @aware.
--}}
<tedi:row :cols="2" :gap-y="3">
    <tedi:radio-group label="Label" direction="vertical" name="group-hint">
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio />
            Text
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio />
            Text
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio />
            Text
        </tedi:form.label>
        <x-slot:subtexts>
            <tedi:feedback-text text="Hint text" />
        </x-slot:subtexts>
    </tedi:radio-group>

    <tedi:radio-group label="Label" direction="vertical" name="group-error">
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio />
            Text
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio />
            Text
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio />
            Text
        </tedi:form.label>
        <x-slot:subtexts>
            <tedi:feedback-text text="Feedback text" type="error" />
        </x-slot:subtexts>
    </tedi:radio-group>

    <tedi:radio-group label="Label" name="group-h-hint">
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio />
            Text
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio />
            Text
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio />
            Text
        </tedi:form.label>
        <x-slot:subtexts>
            <tedi:feedback-text text="Hint text" />
        </x-slot:subtexts>
    </tedi:radio-group>

    <tedi:radio-group label="Label" name="group-h-error">
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio />
            Text
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio />
            Text
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio />
            Text
        </tedi:form.label>
        <x-slot:subtexts>
            <tedi:feedback-text text="Feedback text" type="error" />
        </x-slot:subtexts>
    </tedi:radio-group>
</tedi:row>
