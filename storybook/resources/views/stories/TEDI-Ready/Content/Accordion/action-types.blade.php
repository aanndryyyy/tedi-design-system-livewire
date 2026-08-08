@storybook([
    'name' => 'Action Types',
    'order' => 3,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

@php
    $contentExample = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
@endphp

<div style="display: flex; flex-direction: column; gap: 1rem;">
    <tedi:text as="h4">Clickable header</tedi:text>
    <div style="display: flex; gap: 0.5rem;">
        <tedi:accordion style="flex: 1;">
            <tedi:accordion-item>
                <tedi:accordion-item-header>
                    <x-slot:title>Pealkiri 1</x-slot:title>
                </tedi:accordion-item-header>
                <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
            </tedi:accordion-item>
        </tedi:accordion>
        <tedi:accordion style="flex: 1;">
            <tedi:accordion-item :default-expanded="true">
                <tedi:accordion-item-header>
                    <x-slot:title>Pealkiri 2</x-slot:title>
                </tedi:accordion-item-header>
                <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
            </tedi:accordion-item>
        </tedi:accordion>
    </div>

    <tedi:text as="h4">Separate button at start</tedi:text>
    <div style="display: flex; gap: 0.5rem;">
        <tedi:accordion style="flex: 1;">
            <tedi:accordion-item>
                <tedi:accordion-item-header :header-clickable="false" expand-action-position="start" open-text="Pealkiri 3" close-text="Pealkiri 3" />
                <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
            </tedi:accordion-item>
        </tedi:accordion>
        <tedi:accordion style="flex: 1;">
            <tedi:accordion-item :default-expanded="true">
                <tedi:accordion-item-header :header-clickable="false" expand-action-position="start" open-text="Pealkiri 4" close-text="Pealkiri 4" />
                <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
            </tedi:accordion-item>
        </tedi:accordion>
    </div>

    <tedi:text as="h4">Arrow without label</tedi:text>
    <div style="display: flex; gap: 0.5rem;">
        <tedi:accordion style="flex: 1;">
            <tedi:accordion-item>
                <tedi:accordion-item-header :show-expand-label="false">
                    <x-slot:title>Pealkiri 5</x-slot:title>
                </tedi:accordion-item-header>
                <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
            </tedi:accordion-item>
        </tedi:accordion>
        <tedi:accordion style="flex: 1;">
            <tedi:accordion-item :default-expanded="true">
                <tedi:accordion-item-header :show-expand-label="false">
                    <x-slot:title>Pealkiri 6</x-slot:title>
                </tedi:accordion-item-header>
                <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
            </tedi:accordion-item>
        </tedi:accordion>
    </div>

    <tedi:text as="h4">Icon arrow at start</tedi:text>
    <div style="display: flex; gap: 0.5rem;">
        <tedi:accordion style="flex: 1;">
            <tedi:accordion-item>
                <tedi:accordion-item-header :show-expand-label="false" expand-action-position="start">
                    <x-slot:title>Pealkiri 7</x-slot:title>
                </tedi:accordion-item-header>
                <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
            </tedi:accordion-item>
        </tedi:accordion>
        <tedi:accordion style="flex: 1;">
            <tedi:accordion-item :default-expanded="true">
                <tedi:accordion-item-header :show-expand-label="false" expand-action-position="start">
                    <x-slot:title>Pealkiri 8</x-slot:title>
                </tedi:accordion-item-header>
                <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
            </tedi:accordion-item>
        </tedi:accordion>
    </div>

    <tedi:text as="h4">Custom action</tedi:text>
    <div style="display: flex; gap: 0.5rem;">
        <tedi:accordion style="flex: 1;">
            <tedi:accordion-item>
                <tedi:accordion-item-header :header-clickable="false" expand-action-position="start" open-text="Pealkiri 9" close-text="Pealkiri 9">
                    <x-slot:end-action>
                        <tedi:button variant="secondary">Vali</tedi:button>
                    </x-slot:end-action>
                </tedi:accordion-item-header>
                <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
            </tedi:accordion-item>
        </tedi:accordion>
        <tedi:accordion style="flex: 1;">
            <tedi:accordion-item :default-expanded="true" :selected="true">
                <tedi:accordion-item-header :header-clickable="false" expand-action-position="start" open-text="Pealkiri 10" close-text="Pealkiri 10">
                    <x-slot:end-action>
                        <tedi:button variant="primary">
                            <tedi:icon name="done" />
                            Valitud
                        </tedi:button>
                    </x-slot:end-action>
                </tedi:accordion-item-header>
                <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
            </tedi:accordion-item>
        </tedi:accordion>
    </div>

    <tedi:text as="h4">Selected state</tedi:text>
    <div style="display: flex; gap: 0.5rem;">
        <tedi:accordion style="flex: 1;">
            <tedi:accordion-item :selected="true">
                <tedi:accordion-item-header :header-clickable="false" expand-action-position="start" open-text="Pealkiri 11" close-text="Pealkiri 11">
                    <x-slot:end-action>
                        <tedi:button variant="primary">
                            <tedi:icon name="done" />
                            Valitud
                        </tedi:button>
                    </x-slot:end-action>
                </tedi:accordion-item-header>
                <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
            </tedi:accordion-item>
        </tedi:accordion>
        <tedi:accordion style="flex: 1;">
            <tedi:accordion-item :default-expanded="true" :selected="true">
                <tedi:accordion-item-header :header-clickable="false" expand-action-position="start" open-text="Pealkiri 12" close-text="Pealkiri 12">
                    <x-slot:end-action>
                        <tedi:button variant="primary">
                            <tedi:icon name="done" />
                            Valitud
                        </tedi:button>
                    </x-slot:end-action>
                </tedi:accordion-item-header>
                <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
            </tedi:accordion-item>
        </tedi:accordion>
    </div>
</div>
