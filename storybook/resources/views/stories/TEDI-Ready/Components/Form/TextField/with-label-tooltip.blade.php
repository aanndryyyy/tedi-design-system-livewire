@storybook([
    'name' => 'With Label Tooltip',
    'order' => 11,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<tedi:row :cols="1" :gap-y="3">
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:label-row>
                    <tedi:form.label for="tooltip-plain" :required="true">Toimeaine</tedi:form.label>
                    <tedi:info-tooltip>Vihje sisu</tedi:info-tooltip>
                </tedi:label-row>
            </x-slot:label>

            <tedi:text-field id="tooltip-plain" />

            <x-slot:feedback>
                <tedi:feedback-text text="Vihjetekst" />
            </x-slot:feedback>
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field icon="search" :clearable="true">
            <x-slot:label>
                <tedi:label-row>
                    <tedi:form.label for="tooltip-box">Otsi</tedi:form.label>
                    <tedi:info-tooltip>Vihje sisu</tedi:info-tooltip>
                </tedi:label-row>
            </x-slot:label>

            <tedi:text-field id="tooltip-box" />
        </tedi:form-field>
    </tedi:col>
</tedi:row>
