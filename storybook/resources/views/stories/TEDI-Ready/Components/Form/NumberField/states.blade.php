@storybook([
    'name' => 'States',
    'order' => 3,
    'status' => 'stable',
])

<tedi:row :cols="1" :gap-y="3">
    <tedi:row :cols="2" align-items="center">
        <b>Default</b>
        <tedi:number-field label="Label" input-id="default" />
    </tedi:row>
    <tedi:row :cols="2" align-items="center">
        <b>Min value</b>
        <tedi:number-field label="Label" input-id="min-value" :min="1" :value="1" />
    </tedi:row>
    <tedi:row :cols="2" align-items="center">
        <b>Max value</b>
        <tedi:number-field label="Label" input-id="max-value" :max="1" :value="1" />
    </tedi:row>
    <tedi:row :cols="2" align-items="center">
        <b>Disabled</b>
        <tedi:number-field label="Label" input-id="disabled" :value="1" :disabled="true" />
    </tedi:row>
    <tedi:row :cols="2" align-items="center">
        <b>Error</b>
        <tedi:number-field
            label="Label"
            input-id="error"
            :value="1"
            :invalid="true"
            :feedback-text="['text' => 'Feedback text', 'type' => 'error', 'position' => 'left']"
        />
    </tedi:row>
</tedi:row>
