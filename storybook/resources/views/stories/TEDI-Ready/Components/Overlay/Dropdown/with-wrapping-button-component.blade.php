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
            'options' => ['menu', 'listbox'],
            'description' => 'Role for content, use listbox for list and menu for actions',
            'table' => [
                'category' => 'dropdown-content',
                'type' => ['summary' => 'DropdownRole'],
                'defaultValue' => ['summary' => 'menu'],
            ],
        ],
        'ariaHaspopup' => [
            'control' => 'radio',
            'options' => ['menu', 'listbox', 'true'],
            'description' => 'Defines the aria-haspopup attribute for the trigger, informing assistive technologies whether it opens a menu or listbox. Improves accessibility by describing the type of popup.',
            'table' => [
                'category' => 'dropdown-trigger',
                'type' => ['summary' => 'DropdownTriggerAriaHasPopup'],
                'defaultValue' => ['summary' => 'menu'],
            ],
        ],
    ],
])

{{--
    Angular's demo wraps the trigger directive around an <app-demo-button> that
    renders its own native <button>. In this port <tedi:dropdown-trigger> is
    always such a wrapper (its selector is styled as an element, see
    dropdown-trigger.blade.php), so the interesting case is a button nested
    deeper than one level — the extra <span> here stands in for the demo
    component. The trigger's x-init resolves the inner <button> with upstream's
    own FOCUSABLE_SELECTOR and puts the aria state on it, so there is still a
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
