@storybook([
    'name' => 'Custom Label And Feedback Text',
    'order' => 8,
    'status' => 'stable',
    'args' => [
        'inputId' => 'example-custom',
    ],
])

{{--
    The number field renders neither its own label nor its own feedback text
    here: both are supplied by the consumer as siblings, exactly as the Angular
    story does, so `label` and `feedbackText` are deliberately not passed.
--}}
<div>
    <tedi:form.label :for="$inputId">Label</tedi:form.label>
    <tedi:number-field :input-id="$inputId" />
    <tedi:feedback-text text="Error message" type="error" />
</div>
