@storybook([
    'name' => 'Group',
    'order' => 7,
    'status' => 'stable',
    'args' => [],
])

{{--
    Angular projects the trailing <tedi-feedback-text> into the group through
    <ng-content select="tedi-feedback-text" />; in Blade that is the named
    `subtexts` slot.
--}}
<tedi:row :cols="2" :gap-y="3">
    <tedi:checkbox-group label="Label" direction="vertical">
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:checkbox />
            Text
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:checkbox />
            Text
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:checkbox />
            Text
        </tedi:form.label>
        <x-slot:subtexts>
            <tedi:feedback-text text="Hint text" />
        </x-slot:subtexts>
    </tedi:checkbox-group>

    <tedi:checkbox-group label="Label" direction="vertical">
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:checkbox />
            Text
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:checkbox />
            Text
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:checkbox />
            Text
        </tedi:form.label>
        <x-slot:subtexts>
            <tedi:feedback-text text="Feedback text" type="error" />
        </x-slot:subtexts>
    </tedi:checkbox-group>

    <tedi:checkbox-group label="Label">
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:checkbox />
            Text
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:checkbox />
            Text
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:checkbox />
            Text
        </tedi:form.label>
        <x-slot:subtexts>
            <tedi:feedback-text text="Hint text" />
        </x-slot:subtexts>
    </tedi:checkbox-group>

    <tedi:checkbox-group label="Label">
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:checkbox />
            Text
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:checkbox />
            Text
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:checkbox />
            Text
        </tedi:form.label>
        <x-slot:subtexts>
            <tedi:feedback-text text="Feedback text" type="error" />
        </x-slot:subtexts>
    </tedi:checkbox-group>
</tedi:row>
