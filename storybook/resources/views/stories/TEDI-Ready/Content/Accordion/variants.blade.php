@storybook([
    'name' => 'Variants',
    'order' => 2,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

@php
    $contentExample = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
@endphp

<div style="display: flex; flex-direction: column; gap: 1rem;">
    <tedi:accordion>
        <tedi:accordion-item>
            <tedi:accordion-item-header>
                <x-slot:title>Pealkiri</x-slot:title>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>

    <tedi:accordion>
        <tedi:accordion-item>
            <tedi:accordion-item-header>
                <x-slot:title>Pealkiri</x-slot:title>
                <x-slot:after-title>
                    <tedi:status-badge color="success" text="Kinnitatud" />
                </x-slot:after-title>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>

    <tedi:accordion>
        <tedi:accordion-item>
            <tedi:accordion-item-header>
                <x-slot:title>Pealkiri</x-slot:title>
                <x-slot:before-title>
                    <tedi:icon name="description" color="secondary" :size="18" />
                </x-slot:before-title>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>

    <tedi:accordion>
        <tedi:accordion-item>
            <tedi:accordion-item-header>
                <x-slot:title>Pealkiri</x-slot:title>
                <x-slot:before-title>
                    <tedi:icon name="account_circle" color="brand" background="brand-secondary" :size="16" />
                </x-slot:before-title>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>

    <tedi:accordion>
        <tedi:accordion-item>
            <tedi:accordion-item-header :show-expand-label="false">
                <x-slot:title>Pealkiri</x-slot:title>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>

    <tedi:accordion>
        <tedi:accordion-item>
            <tedi:accordion-item-header expand-action-position="start" :show-expand-label="false">
                <x-slot:title>Pealkiri</x-slot:title>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>

    <tedi:accordion>
        <tedi:accordion-item>
            <tedi:accordion-item-header :show-expand-label="false">
                <x-slot:title>Pealkiri</x-slot:title>
                <x-slot:end-description>
                    <tedi:text color="tertiary" modifiers="small">Kirjeldus</tedi:text>
                </x-slot:end-description>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>

    <tedi:accordion>
        <tedi:accordion-item>
            <tedi:accordion-item-header :show-expand-label="false">
                <x-slot:title>Pealkiri</x-slot:title>
                <x-slot:start-description>
                    <tedi:text color="tertiary">Kirjeldus</tedi:text>
                </x-slot:start-description>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>

    <tedi:accordion>
        <tedi:accordion-item>
            <tedi:accordion-item-header :show-expand-label="false">
                <x-slot:title>Pealkiri</x-slot:title>
                <x-slot:start-description>
                    <tedi:text color="tertiary">Kirjeldus</tedi:text>
                </x-slot:start-description>
                <x-slot:end-description>
                    <tedi:text color="tertiary" modifiers="small">Kirjeldus</tedi:text>
                </x-slot:end-description>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>

    <tedi:accordion>
        <tedi:accordion-item>
            <tedi:accordion-item-header :header-clickable="false" expand-action-position="start" open-text="Pealkiri" close-text="Pealkiri">
                <x-slot:end-action>
                    <tedi:button variant="secondary">Vali</tedi:button>
                </x-slot:end-action>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>

    <tedi:accordion>
        <tedi:accordion-item :selected="true">
            <tedi:accordion-item-header :header-clickable="false" expand-action-position="start" open-text="Pealkiri" close-text="Pealkiri">
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
