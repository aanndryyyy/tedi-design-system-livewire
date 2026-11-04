@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.23.39?node-id=2319-64439&m=dev',
    'args' => [
        'position' => 'bottom-start',
        'preventOverflow' => true,
        'hideOnScroll' => false,
        'dropdownRole' => 'menu',
        'ariaHaspopup' => 'menu',
        'closeOnSelect' => true,
        'clipContent' => true,
    ],
    'argTypes' => [
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
        'hideOnScroll' => [
            'control' => 'boolean',
            'description' => 'Does the dropdown hide when the page scrolls?',
            'table' => [
                'category' => 'dropdown',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
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
        'closeOnSelect' => [
            'control' => 'boolean',
            'description' => 'Whether activating this item closes the dropdown. Set `false` for items that should keep the dropdown open after selection (e.g. multi-select checkboxes).',
            'table' => [
                'category' => 'dropdown-item',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'interactiveContent' => [
            'description' => 'Whether the projected content is itself the interactive control, e.g. a button. In a `menu` or `listbox` the item role and the roving tabindex move onto that element, so assistive technology reports one control per item. For navigation links use `dropdownRole="list"` instead, where a widget role would replace the link role.',
            'table' => [
                'category' => 'dropdown-item',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'clipContent' => [
            'control' => 'boolean',
            'description' => 'Whether the item\'s label clips overflowing content for text ellipsis. Set `false` when projecting content with decorations that intentionally sit outside the line box (e.g. status indicator), so they are not cut off.',
            'table' => [
                'category' => 'dropdown-item',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
    ],
])

<tedi:dropdown
    container-id="dropdown-default"
    :position="$position"
    :prevent-overflow="(bool) $preventOverflow"
    :hide-on-scroll="(bool) $hideOnScroll"
>
    <tedi:dropdown-trigger :aria-haspopup="$ariaHaspopup">
        <tedi:button>Trigger</tedi:button>
    </tedi:dropdown-trigger>

    <tedi:dropdown-content :dropdown-role="$dropdownRole">
        <tedi:dropdown-item :close-on-select="(bool) $closeOnSelect" :clip-content="(bool) $clipContent">
            Access to health data
        </tedi:dropdown-item>
        <tedi:dropdown-item :disabled="true">Declaration of intent</tedi:dropdown-item>
        <tedi:dropdown-item :close-on-select="(bool) $closeOnSelect" :clip-content="(bool) $clipContent">
            Contacts
        </tedi:dropdown-item>
    </tedi:dropdown-content>
</tedi:dropdown>
