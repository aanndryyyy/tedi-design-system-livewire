@storybook([
    'name' => 'Checkbox Trigger',
    'order' => 6,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.23.39?node-id=2319-64439&m=dev',
    'args' => [
        'position' => 'bottom-start',
        'preventOverflow' => true,
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
    ],
])

{{--
    Upstream's `WithCheckboxTrigger`. The checkbox is the trigger itself: it keeps
    its native role and its checked / mixed state, Enter opens the menu, and Space
    uses the checkbox's own native click. That click is cancelled
    (`$event.preventDefault()`) so the selection changes only through the menu actions,
    and it still bubbles to the trigger wrapper, which toggles the menu.
    Angular's `(itemSelect)` outputs become `@click` on each item (§7.2) — the
    shorthand, because the item already writes its own `x-on:click` close
    handler and a second attribute of the same name would be dropped.
--}}
<div x-data="{ count: 3, total: 10, pageSize: 5 }">
    <tedi:dropdown
        container-id="dropdown-checkbox-trigger"
        :position="$position"
        :prevent-overflow="(bool) $preventOverflow"
    >
        <tedi:dropdown-trigger>
            <tedi:checkbox
                aria-label="Select rows"
                x-on:click="$event.preventDefault()"
                x-bind:checked="count === total"
                x-effect="$el.indeterminate = count > 0 && count < total"
            />
        </tedi:dropdown-trigger>

        <tedi:dropdown-content dropdown-role="menu">
            <tedi:dropdown-item @click="count = pageSize">Select page</tedi:dropdown-item>
            <tedi:dropdown-item @click="count = total">Select all</tedi:dropdown-item>
            <tedi:dropdown-item @click="count = 0">Clear selection</tedi:dropdown-item>
        </tedi:dropdown-content>
    </tedi:dropdown>

    <p><span x-text="count"></span> of <span x-text="total"></span> rows selected</p>
</div>
