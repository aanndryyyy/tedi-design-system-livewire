@storybook([
    'name' => 'With Meta Text',
    'order' => 2,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.23.39?node-id=2319-64439&m=dev',
    'args' => [
        'value' => 'tartu',
        'position' => 'bottom-start',
        'preventOverflow' => true,
        'dropdownRole' => 'listbox',
        'ariaHaspopup' => 'listbox',
    ],
    'argTypes' => [
        'value' => [
            'control' => 'text',
            'description' => 'Current value of dropdown (used with listbox)',
            'table' => [
                'category' => 'dropdown',
                'type' => ['summary' => 'string'],
            ],
        ],
        'position' => [
            'control' => 'select',
            'options' => [
                'auto', 'auto-start', 'auto-end',
                'top', 'top-start', 'top-end',
                'bottom', 'bottom-start', 'bottom-end',
                'right', 'right-start', 'right-end',
                'left', 'left-start', 'left-end',
            ],
            'description' => 'The position of the dropdown relative to the trigger element.',
            'table' => [
                'category' => 'dropdown',
                'type' => ['summary' => 'DropdownPosition'],
                'defaultValue' => ['summary' => 'bottom-start'],
            ],
        ],
        'preventOverflow' => [
            'control' => 'boolean',
            'description' => 'Should position to opposite direction when overflowing screen?',
            'table' => [
                'category' => 'dropdown',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'dropdownRole' => [
            'control' => 'radio',
            'options' => ['menu', 'listbox', 'list'],
            'description' => 'How the panel is exposed to assistive technology: `menu` for actions, `listbox` for selectable options, `list` for a plain list of links. `menu` and `listbox` are composite widgets (one tab stop, arrow-key navigation, `menuitem` / `option` items); `list` adds no roles or key handling, so projected links stay links and stay in the tab order. Every role moves focus into the panel when it opens, so the panel is not read last because of where the overlay renders.',
            'table' => [
                'category' => 'dropdown-content',
                'type' => ['summary' => 'DropdownRole'],
                'defaultValue' => ['summary' => 'menu'],
            ],
        ],
        'ariaHaspopup' => [
            'control' => 'radio',
            'options' => ['menu', 'listbox', 'dialog', 'true', 'false'],
            'description' => 'The `aria-haspopup` value for the trigger. Defaults to the content\'s `dropdownRole`, and a plain `list` gets no attribute at all, since ARIA has no token for it. `false` also omits the attribute.',
            'table' => [
                'category' => 'dropdown-trigger',
                'type' => ['summary' => 'DropdownTriggerAriaHasPopup'],
                'defaultValue' => ['summary' => 'dropdownRole'],
            ],
        ],
    ],
])

{{--
    Angular's items derive their selected state by injecting the dropdown and
    comparing `dropdown.value()` with their own `value`. Blade has no such
    channel (see dropdown.blade.php), so the story does the comparison itself
    and passes the result as `:selected` — the explicit-prop translation
    CONVENTIONS.md §5 prescribes.
--}}
@php
    $locations = [
        'tallinn' => ['Tallinn', '3 timeslots'],
        'tartu' => ['Tartu', '5 timeslots'],
        'parnu' => ['Pärnu', '2 timeslots'],
    ];
@endphp

<tedi:dropdown
    container-id="dropdown-with-meta"
    :value="$value"
    :position="$position"
    :prevent-overflow="(bool) $preventOverflow"
>
    <tedi:dropdown-trigger :aria-haspopup="$ariaHaspopup">
        <tedi:button>Select location</tedi:button>
    </tedi:dropdown-trigger>

    <tedi:dropdown-content :dropdown-role="$dropdownRole">
        @foreach ($locations as $key => [$label, $meta])
            <tedi:dropdown-item :value="$key" :selected="$value === $key">
                <x-slot:item-value>
                    <tedi:option-content>
                        <tedi:option-content-label>{{ $label }}</tedi:option-content-label>
                        <tedi:option-content-meta>{{ $meta }}</tedi:option-content-meta>
                    </tedi:option-content>
                </x-slot:item-value>
            </tedi:dropdown-item>
        @endforeach
    </tedi:dropdown-content>
</tedi:dropdown>
