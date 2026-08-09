@storybook([
    'name' => 'With Valid Feedback',
    'order' => 2,
    'status' => 'stable',
    'args' => [
        'label' => 'Nimi',
        'placeholder' => 'Placeholder',
        'hintText' => '',
        'validText' => 'Feedback valid text',
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
    Angular files FormField's stories under Community/, not TEDI-Ready/, so the
    title here matches — .storybook/main.ts globs community/** into the same
    Storybook.

    Angular's `state` control is replaced by the component's own `valid` /
    `invalid` props: this port has no runtime NgControl to read validity from,
    so validation state is explicit (see form-field.blade.php's docblock).
    Here it is derived from which feedback text is filled in, exactly as the
    Angular story's template chooses its feedback type.
--}}
<tedi:form-field
    :invalid="(bool) $errorText"
    :valid="(bool) $validText"
    :disabled="(bool) $disabled"
>
    <x-slot:label>
        <tedi:form.label for="storybook-input" :required="(bool) $required">{{ $label }}</tedi:form.label>
    </x-slot:label>

    <input
        type="text"
        id="storybook-input"
        class="tedi-input"
        placeholder="{{ $placeholder }}"
        @disabled($disabled)
    >

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
