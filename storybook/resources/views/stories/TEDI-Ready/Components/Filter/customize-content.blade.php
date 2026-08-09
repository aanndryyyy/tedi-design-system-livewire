@storybook([
    'name' => 'Customize Content',
    'order' => 5,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

@php
    $abOptions = [
        ['label' => 'Option A', 'value' => 'a'],
        ['label' => 'Option B', 'value' => 'b'],
    ];
@endphp

<div style="background: var(--general-surface-primary); padding: 24px;">
    <tedi:row cols="1" :gap-y="3">
        <tedi:col class="flex flex-column gap-2">
            <tedi:text as="h5" modifiers="h5">Prepend hidden when selected (default)</tedi:text>

            <tedi:filter-group :managed="true">
                <tedi:filter text="Unread (3)" value="unread" variant="secondary" size="large">
                    <x-slot:prepend><tedi:status-indicator type="danger" /></x-slot:prepend>
                </tedi:filter>
                <tedi:filter text="All" value="all" variant="secondary" size="large" />
            </tedi:filter-group>

            <tedi:filter-group :managed="true">
                <tedi:filter text="Unread (3)" value="unread" variant="secondary" size="large" :selected="true">
                    <x-slot:prepend><tedi:status-indicator type="danger" /></x-slot:prepend>
                </tedi:filter>
                <tedi:filter text="All" value="all" variant="secondary" size="large" />
            </tedi:filter-group>

            <tedi:filter-group :managed="true">
                <tedi:filter text="Submitted" value="submitted" variant="secondary" size="large">
                    <x-slot:prepend><tedi:status-badge text="5" color="brand" /></x-slot:prepend>
                </tedi:filter>
                <tedi:filter text="Requires attention" value="attention" variant="secondary" size="large">
                    <x-slot:prepend><tedi:status-badge text="7" color="danger" /></x-slot:prepend>
                </tedi:filter>
            </tedi:filter-group>

            <tedi:filter-group :managed="true">
                <tedi:filter text="Submitted" value="submitted" variant="secondary" size="large" :selected="true">
                    <x-slot:prepend><tedi:status-badge text="5" color="brand" /></x-slot:prepend>
                </tedi:filter>
                <tedi:filter text="Requires attention" value="attention" variant="secondary" size="large">
                    <x-slot:prepend><tedi:status-badge text="7" color="danger" /></x-slot:prepend>
                </tedi:filter>
            </tedi:filter-group>
        </tedi:col>

        <tedi:col class="flex flex-column gap-2">
            <tedi:text as="h5" modifiers="h5">Prepend visible when selected</tedi:text>

            <tedi:filter-group :managed="true">
                <tedi:filter text="Submitted" value="submitted" variant="secondary" size="large" :hide-prepend-when-selected="false">
                    <x-slot:prepend><tedi:status-badge text="5" color="brand" /></x-slot:prepend>
                </tedi:filter>
                <tedi:filter text="Requires attention" value="attention" variant="secondary" size="large" :hide-prepend-when-selected="false">
                    <x-slot:prepend><tedi:status-badge text="7" color="danger" /></x-slot:prepend>
                </tedi:filter>
            </tedi:filter-group>

            <tedi:filter-group :managed="true">
                <tedi:filter text="Submitted" value="submitted" variant="secondary" size="large" :selected="true" :hide-prepend-when-selected="false">
                    <x-slot:prepend><tedi:status-badge text="5" color="brand" /></x-slot:prepend>
                </tedi:filter>
                <tedi:filter text="Requires attention" value="attention" variant="secondary" size="large" :hide-prepend-when-selected="false">
                    <x-slot:prepend><tedi:status-badge text="7" color="danger" /></x-slot:prepend>
                </tedi:filter>
            </tedi:filter-group>
        </tedi:col>

        <tedi:col class="flex flex-column gap-2">
            <tedi:text as="h5" modifiers="h5">Append</tedi:text>

            <tedi:filter-group :managed="true">
                <tedi:filter text="Submitted" value="submitted" variant="secondary" size="large">
                    <x-slot:append><tedi:status-badge text="5" color="brand" /></x-slot:append>
                </tedi:filter>
                <tedi:filter text="Requires attention" value="attention" variant="secondary" size="large">
                    <x-slot:append><tedi:status-badge text="7" color="danger" /></x-slot:append>
                </tedi:filter>
            </tedi:filter-group>

            <tedi:filter-group :managed="true">
                <tedi:filter text="Submitted" value="submitted" variant="secondary" size="large" :selected="true">
                    <x-slot:append><tedi:status-badge text="5" color="brand" /></x-slot:append>
                </tedi:filter>
                <tedi:filter text="Requires attention" value="attention" variant="secondary" size="large">
                    <x-slot:append><tedi:status-badge text="7" color="danger" /></x-slot:append>
                </tedi:filter>
            </tedi:filter-group>
        </tedi:col>

        <tedi:col class="flex flex-column gap-2">
            <tedi:text as="h5" modifiers="h5">Append with dropdown</tedi:text>
            <tedi:filter text="Requires attention" variant="secondary" size="large" :options="$abOptions">
                <x-slot:append><tedi:status-badge text="7" color="danger" /></x-slot:append>
            </tedi:filter>
        </tedi:col>

        <tedi:col class="flex flex-column gap-2">
            <tedi:text as="h5" modifiers="h5">Prepend icon with append and dropdown</tedi:text>
            <tedi:filter text="Requires attention" variant="secondary" size="large" :options="$abOptions">
                <x-slot:prepend><tedi:icon name="language" :size="18" color="inherit" /></x-slot:prepend>
                <x-slot:append><tedi:status-badge text="7" color="danger" /></x-slot:append>
            </tedi:filter>
        </tedi:col>
    </tedi:row>
</div>
