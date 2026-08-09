@storybook([
    'name' => 'With Icons',
    'order' => 3,
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

@php
    $actions = [
        'edit' => 'Edit',
        'content_copy' => 'Duplicate',
        'delete' => 'Delete',
    ];
@endphp

<tedi:dropdown
    container-id="dropdown-with-icons"
    :position="$position"
    :prevent-overflow="(bool) $preventOverflow"
>
    <tedi:dropdown-trigger :aria-haspopup="$ariaHaspopup">
        <tedi:button>Actions</tedi:button>
    </tedi:dropdown-trigger>

    <tedi:dropdown-content :dropdown-role="$dropdownRole">
        @foreach ($actions as $icon => $label)
            <tedi:dropdown-item>
                <x-slot:item-value>
                    <tedi:dropdown-item-value>
                        <x-slot:icon><tedi:icon :name="$icon" :size="18" /></x-slot:icon>
                        <tedi:dropdown-item-value-label>{{ $label }}</tedi:dropdown-item-value-label>
                    </tedi:dropdown-item-value>
                </x-slot:item-value>
            </tedi:dropdown-item>
        @endforeach
    </tedi:dropdown-content>
</tedi:dropdown>
