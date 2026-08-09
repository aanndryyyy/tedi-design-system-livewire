@storybook([
    'name' => 'With Select',
    'order' => 6,
    'status' => 'subset',
    'args' => [
        'label' => 'Maakond',
        'placeholder' => 'Placeholder',
        'hintText' => 'Feedback hint text',
        'validText' => '',
        'errorText' => '',
        'required' => true,
        'disabled' => false,
    ],
    'argTypes' => [
        'label' => ['control' => 'text'],
        'placeholder' => ['control' => 'text'],
        'hintText' => ['control' => 'text'],
        'validText' => ['control' => 'text'],
        'errorText' => ['control' => 'text'],
        'required' => ['control' => 'boolean'],
        'disabled' => ['control' => 'boolean'],
    ],
])

{{--
    <tedi:select> is a documented subset: a native <select> styled with
    `tedi-input`. Angular's searchable/multi-select combobox needs CDK Overlay,
    which is not ported — so the options render as native <option>s rather than
    <tedi-select-option> elements.
--}}
<tedi:form-field
    :invalid="(bool) $errorText"
    :valid="(bool) $validText"
    :disabled="(bool) $disabled"
>
    <x-slot:label>
        <tedi:form.label for="storybook-select" :required="(bool) $required">{{ $label }}</tedi:form.label>
    </x-slot:label>

    <tedi:select
        input-id="storybook-select"
        :placeholder="$placeholder"
        :disabled="(bool) $disabled"
        :state="$errorText ? 'error' : ($validText ? 'valid' : 'default')"
        :options="['option1' => 'Option 1', 'option2' => 'Option 2', 'option3' => 'Option 3', 'option4' => 'Option 4']"
    />

    <x-slot:feedback>
        @if ($errorText)
            <tedi:feedback-text type="error" :text="$errorText" />
        @elseif ($validText)
            <tedi:feedback-text type="valid" :text="$validText" />
        @elseif ($hintText)
            <tedi:feedback-text type="hint" :text="$hintText" />
        @endif
    </x-slot:feedback>
</tedi:form-field>
