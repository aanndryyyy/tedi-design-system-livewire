@storybook([
    'name' => 'Trigger on a Wrapping Button Component',
    'order' => 5,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.23.39?node-id=2319-64439&m=dev',
    'args' => [
        'position' => 'bottom-start',
        'preventOverflow' => true,
        'dropdownRole' => 'menu',
        'ariaHaspopup' => 'menu',
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
    Angular's demo wraps the trigger directive around an `app-demo-button` that
    renders its own native button. In this port `tedi:dropdown-trigger` is
    always such a wrapper (its selector is styled as an element, see
    dropdown-trigger.blade.php), so the interesting case is a button nested
    deeper than one level — the extra span here stands in for the demo
    component. `tediDropdown.initTrigger` resolves the inner button with
    upstream's own selector and puts the aria state on it, so there is still a
    single tab stop with the right announcements.
--}}
<tedi:dropdown
    container-id="dropdown-wrapping-button"
    :position="$position"
    :prevent-overflow="(bool) $preventOverflow"
>
    <tedi:dropdown-trigger :aria-haspopup="$ariaHaspopup">
        <span><tedi:button>Actions</tedi:button></span>
    </tedi:dropdown-trigger>

    <tedi:dropdown-content :dropdown-role="$dropdownRole">
        <tedi:dropdown-item>Access to health data</tedi:dropdown-item>
        <tedi:dropdown-item :disabled="true">Declaration of intent</tedi:dropdown-item>
        <tedi:dropdown-item>Contacts</tedi:dropdown-item>
    </tedi:dropdown-content>
</tedi:dropdown>
