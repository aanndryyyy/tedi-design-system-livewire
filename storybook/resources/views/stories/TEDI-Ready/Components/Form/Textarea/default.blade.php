@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.65.83?node-id=3486-37618&m=dev',
    'args' => [
        'size' => 'default',
        'characterLimit' => null,
        'inputClass' => '',
        'placeholder' => '',
        'resizable' => true,
        'autoGrow' => false,
        'minRows' => 3,
        'maxRows' => 12,
        'height' => '7.5rem',
        'maxHeight' => '',
    ],
    'argTypes' => [
        'size' => [
            'description' => 'Size of the form field.',
            'control' => ['type' => 'radio'],
            'options' => ['default', 'small'],
            'table' => [
                'category' => 'Form Field inputs',
                'type' => ['summary' => 'InputSize'],
                'defaultValue' => ['summary' => 'default'],
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
        'inputClass' => [
            'control' => 'text',
            'description' => 'Custom CSS classes for the field.',
            'table' => [
                'category' => 'Form Field inputs',
                'type' => ['summary' => 'string | null'],
                'defaultValue' => ['summary' => 'null'],
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
            'description' => 'Minimum number of visible rows while auto-growing.',
            'table' => [
                'category' => 'Textarea inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '3'],
            ],
        ],
        'maxRows' => [
            'control' => 'number',
            'description' => 'Maximum number of visible rows before scrolling, while auto-growing.',
            'table' => [
                'category' => 'Textarea inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '12'],
            ],
        ],
        'height' => [
            'control' => 'text',
            'description' => 'Fixed height (e.g. `7.5rem`, `200`). Applied only when `autoGrow` is off; set to an empty string to fall back to the native `rows` attribute.',
            'table' => [
                'category' => 'Textarea inputs',
                'type' => ['summary' => 'string | number'],
                'defaultValue' => ['summary' => '7.5rem'],
            ],
        ],
        'maxHeight' => [
            'control' => 'text',
            'description' => 'Maximum height before the field scrolls (e.g. `200px`, `12rem`). Limits both `autoGrow` growth and manual resizing.',
            'table' => [
                'category' => 'Textarea inputs',
                'type' => ['summary' => 'string | number | null'],
            ],
        ],
    ],
])

{{--
    Angular's `height`/`maxHeight` accept `undefined` to switch the style off.
    Blade resolves @props defaults with isset(), so `:height="null"` falls back
    to the default instead (CONVENTIONS.md §3) — the empty string is the escape
    hatch, which is why the controls normalise '' rather than passing null.
--}}
<tedi:form-field
    :textarea="true"
    :size="$size"
    :character-limit="$characterLimit ?: null"
    :input-class="$inputClass ?: null"
>
    <x-slot:label>
        <tedi:form.label for="default">Label</tedi:form.label>
    </x-slot:label>

    <tedi:textarea
        id="default"
        rows="5"
        :placeholder="$placeholder"
        :resizable="(bool) $resizable"
        :auto-grow="(bool) $autoGrow"
        :min-rows="$minRows"
        :max-rows="$maxRows"
        :height="$height"
        :max-height="$maxHeight"
    />
</tedi:form-field>
