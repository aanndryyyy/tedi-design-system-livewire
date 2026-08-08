@storybook([
    'name' => 'With Icon Card',
    'order' => 4,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

@php
    $contentExample = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
@endphp

<div style="display: flex; flex-direction: column; gap: 1rem;">
    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
        <tedi:accordion>
            <tedi:accordion-item :show-icon-card="true">
                <x-slot:icon-card>
                    <span class="tedi-accordion-icon-card">
                        <tedi:icon name="business_center" color="secondary" :size="24" />
                        <tedi:text color="secondary" modifiers="bold">Kategooria</tedi:text>
                    </span>
                </x-slot:icon-card>
                <tedi:accordion-item-header>
                    <x-slot:title>Pealkiri</x-slot:title>
                </tedi:accordion-item-header>
                <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
            </tedi:accordion-item>
        </tedi:accordion>
        <tedi:accordion>
            <tedi:accordion-item :default-expanded="true" :show-icon-card="true">
                <x-slot:icon-card>
                    <span class="tedi-accordion-icon-card">
                        <tedi:icon name="business_center" color="secondary" :size="24" />
                        <tedi:text color="secondary" modifiers="bold">Kategooria</tedi:text>
                    </span>
                </x-slot:icon-card>
                <tedi:accordion-item-header>
                    <x-slot:title>Pealkiri</x-slot:title>
                </tedi:accordion-item-header>
                <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
            </tedi:accordion-item>
        </tedi:accordion>
    </div>

    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
        <tedi:accordion>
            <tedi:accordion-item :show-icon-card="true">
                <x-slot:icon-card>
                    <span class="tedi-accordion-icon-card">
                        <tedi:icon name="business_center" color="secondary" :size="24" />
                        <tedi:text color="secondary" modifiers="bold">Kategooria</tedi:text>
                    </span>
                </x-slot:icon-card>
                <tedi:accordion-item-header :show-expand-label="false">
                    <x-slot:title>Pealkiri</x-slot:title>
                </tedi:accordion-item-header>
                <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
            </tedi:accordion-item>
        </tedi:accordion>
        <tedi:accordion>
            <tedi:accordion-item :default-expanded="true" :show-icon-card="true">
                <x-slot:icon-card>
                    <span class="tedi-accordion-icon-card">
                        <tedi:icon name="business_center" color="secondary" :size="24" />
                        <tedi:text color="secondary" modifiers="bold">Kategooria</tedi:text>
                    </span>
                </x-slot:icon-card>
                <tedi:accordion-item-header :show-expand-label="false">
                    <x-slot:title>Pealkiri</x-slot:title>
                </tedi:accordion-item-header>
                <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
            </tedi:accordion-item>
        </tedi:accordion>
    </div>

    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
        <tedi:accordion>
            <tedi:accordion-item :show-icon-card="true" :selected="true">
                <x-slot:icon-card>
                    <span class="tedi-accordion-icon-card">
                        <tedi:icon name="business_center" color="secondary" :size="24" />
                        <tedi:text color="secondary" modifiers="bold">Kategooria</tedi:text>
                    </span>
                </x-slot:icon-card>
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
        <tedi:accordion style="flex: 1;">
            <tedi:accordion-item :default-expanded="true" :show-icon-card="true" :selected="false">
                <x-slot:icon-card>
                    <span class="tedi-accordion-icon-card">
                        <tedi:icon name="business_center" color="secondary" :size="24" />
                        <tedi:text color="secondary" modifiers="bold">Kategooria</tedi:text>
                    </span>
                </x-slot:icon-card>
                <tedi:accordion-item-header :header-clickable="false" expand-action-position="start" open-text="Pealkiri" close-text="Pealkiri">
                    <x-slot:end-action>
                        <tedi:button variant="secondary">Vali</tedi:button>
                    </x-slot:end-action>
                </tedi:accordion-item-header>
                <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
            </tedi:accordion-item>
        </tedi:accordion>
    </div>

    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
        <tedi:accordion>
            <tedi:accordion-item :show-icon-card="true" :selected="true">
                <x-slot:icon-card>
                    <span class="tedi-accordion-icon-card">
                        <tedi:icon name="business_center" color="secondary" :size="24" />
                        <tedi:text color="secondary" modifiers="bold">Kategooria</tedi:text>
                    </span>
                </x-slot:icon-card>
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
        <tedi:accordion>
            <tedi:accordion-item :default-expanded="true" :show-icon-card="true" :selected="true">
                <x-slot:icon-card>
                    <span class="tedi-accordion-icon-card">
                        <tedi:icon name="business_center" color="secondary" :size="24" />
                        <tedi:text color="secondary" modifiers="bold">Kategooria</tedi:text>
                    </span>
                </x-slot:icon-card>
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
</div>
