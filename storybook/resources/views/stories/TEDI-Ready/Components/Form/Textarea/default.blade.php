@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.65.83?node-id=3486-37618&m=dev',
    'args' => [
        'size' => 'default',
        'invalid' => false,
        'characterLimit' => '',
        'clearable' => true,
        'showClearOnInteraction' => false,
        'readOnly' => false,
        'disabled' => false,
        'placeholder' => '',
        'resizable' => true,
        'autoGrow' => false,
        'minRows' => 3,
        'maxRows' => '',
        'height' => '',
        'maxHeight' => '',
        'value' => '',
    ],
    'argTypes' => [
        'size' => [
            'description' => 'Size of the field. Falls back to the size of a wrapping form field.',
            'control' => ['type' => 'radio'],
            'options' => ['default', 'small'],
            'table' => [
                'category' => 'Textarea inputs',
                'type' => ['summary' => 'TextareaSize'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'invalid' => [
            'description' => 'Forces the error state on. Combines with the state derived from reactive forms.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Textarea inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'characterLimit' => [
            'description' => 'Maximum number of characters. Shows a live counter that turns into an error state once exceeded.',
            'control' => ['type' => 'number'],
            'table' => [
                'category' => 'Form Field inputs',
                'type' => ['summary' => 'number | undefined'],
            ],
        ],
        'clearable' => [
            'description' => 'Whether the field shows a clear button once it has a value. Set `false` to opt out. Hidden while the textarea is read-only.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Form Field inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'showClearOnInteraction' => [
            'description' => 'Show the clear button only on hover or focus when the field has a value. Requires clearable.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Form Field inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'readOnly' => [
            'description' => 'Makes the field read-only, like the native `readonly` attribute, which is also accepted. The value can still be selected and submitted, but not edited, and a wrapping form field hides its clear button.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Textarea inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'disabled' => [
            'description' => 'Disables the textarea. Combines with the reactive-forms disabled state.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Textarea inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'placeholder' => [
            'control' => 'text',
            'description' => 'Placeholder text shown when the textarea is empty.',
            'table' => [
                'category' => 'Textarea inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
        'resizable' => [
            'description' => 'Whether the user can resize the textarea vertically. Set to `false` to disable resizing.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Textarea inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'autoGrow' => [
            'description' => 'Grows the textarea to fit its content as the user types (CSS `field-sizing`). Disables manual resize while active.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Textarea inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'minRows' => [
            'control' => 'number',
            'description' => 'Rows the field rests at, and the fewest it can ever show. With no `height` set this is what sizes the textarea, in every mode — reach for it first when a field needs to be taller or shorter. Values below 1 are treated as 1; set `rows="1"` for a single-row field.',
            'table' => [
                'category' => 'Textarea inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '3'],
            ],
        ],
        'maxRows' => [
            'control' => 'number',
            'description' => "Most rows the field shows before it scrolls. Caps `autoGrow`'s growth and how far the resize grip can be dragged. Unset, neither has a row limit.",
            'table' => [
                'category' => 'Textarea inputs',
                'type' => ['summary' => 'number | undefined'],
            ],
        ],
        'height' => [
            'control' => 'text',
            'description' => 'Exact resting height (e.g. `7.5rem`, `200`), for the rare field that has to match something other than a whole number of rows. Prefer `minRows`. Ignored while `autoGrow` is on, and still bounded by `minRows` and `maxRows`.',
            'table' => [
                'category' => 'Textarea inputs',
                'type' => ['summary' => 'string | number | undefined'],
            ],
        ],
        'maxHeight' => [
            'control' => 'text',
            'description' => 'Maximum height before the field scrolls (e.g. `200px`, `12rem`). Applied on top of `maxRows`, whichever is smaller.',
            'table' => [
                'category' => 'Textarea inputs',
                'type' => ['summary' => 'string | number | undefined'],
            ],
        ],
        'value' => [
            'control' => 'text',
            'description' => 'Initial content. The form field clear button and character counter follow it.',
            'table' => [
                'category' => 'Textarea inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
    ],
])

{{--
    `value` is added as a control because the form-field clear button and
    counter follow the textarea's value, and the server render only knows the
    value it was given. Empty-string args stand in for Angular's `undefined`
    (CONTRACT.md §3) and are normalised to null here.
--}}
<tedi:form-field
    :size="$size"
    :character-limit="filled($characterLimit) ? (int) $characterLimit : null"
    :clearable="(bool) $clearable"
    :show-clear-on-interaction="(bool) $showClearOnInteraction"
>
    <x-slot:label>
        <tedi:form.label for="default">Label</tedi:form.label>
    </x-slot:label>

    <tedi:textarea
        id="default"
        :value="$value"
        :placeholder="$placeholder ?: null"
        :invalid="(bool) $invalid"
        :readonly="(bool) $readOnly"
        :disabled="(bool) $disabled"
        :resizable="(bool) $resizable"
        :auto-grow="(bool) $autoGrow"
        :min-rows="$minRows"
        :max-rows="filled($maxRows) ? $maxRows : null"
        :height="filled($height) ? $height : null"
        :max-height="filled($maxHeight) ? $maxHeight : null"
    />
</tedi:form-field>
