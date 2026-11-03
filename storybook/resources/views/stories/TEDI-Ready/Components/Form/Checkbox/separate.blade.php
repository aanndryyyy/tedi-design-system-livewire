@storybook([
    'name' => 'Separate',
    'order' => 6,
    'status' => 'stable',
    'args' => [],
])

<tedi:row :cols="1" :gap-y="4">
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:checkbox />
        Nõustun tingimustega
    </tedi:form.label>
    <tedi:form.label color="primary" :required="true" class="flex align-items-center">
        <tedi:checkbox style="margin-right: var(--form-checkbox-radio-inner-spacing)" />
        Nõustun tingimustega
    </tedi:form.label>
    <tedi:form.label color="primary" class="flex align-items-center gap-2">
        <tedi:checkbox />
        <tedi:icon name="stethoscope" :size="16" />
        Analüüsid
    </tedi:form.label>
    <div class="flex align-items-center gap-1">
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:checkbox />
            Analüüsid
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
            <tedi:checkbox />
            Analüüsid
        </tedi:form.label>
        <tedi:feedback-text text="Valik tagab vereanalüüside võtmise ning tulemuste tõlgendamise" />
    </div>
</tedi:row>
