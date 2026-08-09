@storybook([
    'name' => 'Keep Open on Select (multi-select)',
    'order' => 6,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.23.39?node-id=2319-64439&m=dev',
    'args' => [
        'position' => 'bottom-start',
        'preventOverflow' => true,
        'dropdownRole' => 'menu',
        'ariaHaspopup' => 'menu',
        'closeOnSelect' => false,
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
        'closeOnSelect' => [
            'control' => 'boolean',
            'description' => 'Whether activating this item closes the dropdown. Set `false` for items that should keep the dropdown open after selection (e.g. multi-select checkboxes).',
            'table' => [
                'category' => 'dropdown-item',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
    ],
])

{{--
    `itemSelect` is an output() and is not re-emitted (CONVENTIONS.md §7.2), so
    the checkbox states here are rendered server-side rather than toggled by the
    story. What the story does demonstrate is `:close-on-select="false"`: those
    items do not close the dropdown when clicked, which is what makes a
    multi-select menu usable. A consumer wires the toggling with wire:click or
    x-on:click on the item.
--}}
@php
    $filters = [
        ['id' => 'active', 'label' => 'Active', 'selected' => true],
        ['id' => 'inactive', 'label' => 'Inactive', 'selected' => false],
        ['id' => 'archived', 'label' => 'Archived', 'selected' => false],
        ['id' => 'drafts', 'label' => 'Drafts', 'selected' => true, 'disabled' => true],
    ];
@endphp

<tedi:dropdown
    container-id="dropdown-keep-open"
    :position="$position"
    :prevent-overflow="(bool) $preventOverflow"
>
    <tedi:dropdown-trigger :aria-haspopup="$ariaHaspopup">
        <tedi:button variant="neutral" icon-start="filter_list">Filters</tedi:button>
    </tedi:dropdown-trigger>

    <tedi:dropdown-content :dropdown-role="$dropdownRole">
        @foreach ($filters as $filter)
            <tedi:dropdown-item
                :value="$filter['id']"
                :disabled="! empty($filter['disabled'])"
                :close-on-select="(bool) $closeOnSelect"
            >
                <x-slot:item-value>
                    <tedi:dropdown-item-value
                        type="checkbox"
                        :selected="$filter['selected']"
                        :disabled="! empty($filter['disabled'])"
                    >
                        <tedi:dropdown-item-value-label>{{ $filter['label'] }}</tedi:dropdown-item-value-label>
                    </tedi:dropdown-item-value>
                </x-slot:item-value>
            </tedi:dropdown-item>
        @endforeach
    </tedi:dropdown-content>
</tedi:dropdown>
