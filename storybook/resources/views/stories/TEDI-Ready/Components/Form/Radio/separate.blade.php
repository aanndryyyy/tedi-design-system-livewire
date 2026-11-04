@storybook([
    'name' => 'Separate',
    'order' => 5,
    'status' => 'stable',
    'args' => [],
])

<tedi:row :cols="1" :gap-y="4">
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:radio name="separate-demo" />
        Tekst
    </tedi:form.label>
    <tedi:form.label color="primary" :required="true" class="flex align-items-center">
        <tedi:radio style="margin-right: var(--form-checkbox-radio-inner-spacing)" name="separate-required" />
        Tekst
    </tedi:form.label>
    <div class="flex align-items-center gap-1">
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio name="separate-tooltip" />
            Tekst
        </tedi:form.label>
        <tedi:tooltip>
            <tedi:tooltip-trigger>
                <tedi:info-button />
            </tedi:tooltip-trigger>
            <tedi:tooltip-content>
                Tooltip text
            </tedi:tooltip-content>
        </tedi:tooltip>
    </div>
    <div>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio name="separate-desc" />
            Tekst
        </tedi:form.label>
        <tedi:feedback-text text="Kirjeldus" />
    </div>
</tedi:row>
