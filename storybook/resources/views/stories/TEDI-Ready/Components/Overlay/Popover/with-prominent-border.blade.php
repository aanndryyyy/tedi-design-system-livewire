@storybook([
    'name' => 'With Prominent Border',
    'order' => 6,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.56.78?m=dev&node-id=5797-117364',
    'args' => [],
    'argTypes' => [],
])

@php
    // Angular's cols carry `[lg]="{ width: 6 }"` / `[xxl]="{ width: 4 }"`.
    // Breakpoint props are not ported (CONVENTIONS.md §7), so only the base
    // `width` survives here.
    $polarBear = 'Jääkaru (Ursus maritimus) on suur karu, kes elab Arktikas ja selle lähialadel.';
    $menuRow = 'border-bottom: 1px solid var(--general-border-primary); padding: var(--dropdown-item-padding-y) var(--dropdown-item-padding-x);';
@endphp

<style>
    .story-popover-content--no-padding {
        padding: 0;
    }
    .story-popover-content--menu {
        padding: var(--card-padding-xxs) 0;
    }
</style>

<tedi:row :cols="12" :gap="3">
    <tedi:col :width="12">
        <tedi:popover container-id="popover-border-1" :with-border="true" position="bottom">
            <x-slot:trigger>
                <tedi:popover-trigger :underline="true">Profile menu</tedi:popover-trigger>
            </x-slot:trigger>

            <tedi:popover-content max-width="small" class="story-popover-content--menu">
                <div style="display: flex; flex-direction: column;">
                    <div style="{{ $menuRow }}">
                        <tedi:dropdown-item-value>
                            <tedi:dropdown-item-value-label>Minu profiil</tedi:dropdown-item-value-label>
                        </tedi:dropdown-item-value>
                    </div>
                    <div style="{{ $menuRow }}">
                        <tedi:dropdown-item-value>
                            <tedi:dropdown-item-value-label>Esindatavad</tedi:dropdown-item-value-label>
                        </tedi:dropdown-item-value>
                    </div>
                    <div style="{{ $menuRow }}">
                        <tedi:dropdown-item-value>
                            <tedi:dropdown-item-value-label>Kontaktid</tedi:dropdown-item-value-label>
                        </tedi:dropdown-item-value>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; {{ $menuRow }}">
                        <tedi:form.label for="header-popover-dark-mode">Tume režiim</tedi:form.label>
                        <tedi:toggle input-id="header-popover-dark-mode" />
                    </div>
                    <div style="padding: var(--dropdown-item-padding-y) var(--dropdown-item-padding-x);">
                        <tedi:dropdown-item-value>
                            <tedi:icon name="logout" :size="18" color="secondary" />
                            <tedi:dropdown-item-value-label>Logi välja</tedi:dropdown-item-value-label>
                        </tedi:dropdown-item-value>
                    </div>
                </div>
            </tedi:popover-content>
        </tedi:popover>
    </tedi:col>

    <tedi:col :width="12">
        <tedi:popover container-id="popover-border-2" :with-border="true" position="bottom">
            <x-slot:trigger>
                <tedi:popover-trigger :underline="true">Links menu</tedi:popover-trigger>
            </x-slot:trigger>

            <tedi:popover-content max-width="small">
                <tedi:link href="#" :underline="false">Minu andmed</tedi:link>
                <tedi:link href="#" :underline="false">Esindatavad</tedi:link>
                <tedi:link href="#" :underline="false">Kontaktid</tedi:link>
                <tedi:separator />
                <tedi:link href="#" :underline="false" icon-start="notifications">Riiklikud teated</tedi:link>
                <tedi:separator />
                <tedi:link href="#" :underline="false" icon-start="logout">Logi välja</tedi:link>
            </tedi:popover-content>
        </tedi:popover>
    </tedi:col>

    <tedi:col :width="12">
        <tedi:popover container-id="popover-border-3" :with-border="true" position="bottom">
            <x-slot:trigger>
                <tedi:popover-trigger :underline="true">Representatives</tedi:popover-trigger>
            </x-slot:trigger>

            <tedi:popover-content max-width="small">
                <tedi:search input-id="header-popover-search" label="Otsi isikut" />
                <tedi:separator />
                <button
                    type="button"
                    style="display: flex; align-items: center; gap: 8px; width: 100%; padding: var(--card-padding-xs); border: 0; border-radius: var(--card-radius-rounded); cursor: pointer; text-align: left; background: var(--header-popover-item-selected); color: var(--general-text-white);"
                >
                    <tedi:icon name="person" :size="24" color="white" />
                    <span style="display: flex; flex-direction: column;">
                        <span>Juulia Sarapuu</span>
                        <span style="font-size: var(--body-small-regular-size);">62004122984</span>
                    </span>
                </button>
                <tedi:separator />
                <button
                    type="button"
                    style="display: flex; align-items: center; gap: 8px; width: 100%; padding: var(--card-padding-xs); border: 0; border-radius: var(--card-radius-rounded); cursor: pointer; text-align: left; background: transparent; color: var(--general-text-secondary);"
                >
                    <tedi:icon name="supervised_user_circle" :size="24" color="secondary" />
                    <span style="display: flex; flex-direction: column;">
                        <span>Marta Sarapuu</span>
                        <span style="font-size: var(--body-small-regular-size);">62004122984</span>
                    </span>
                </button>
                <tedi:separator />
                <button
                    type="button"
                    style="display: flex; align-items: center; gap: 8px; width: 100%; padding: var(--card-padding-xs); border: 0; border-radius: var(--card-radius-rounded); cursor: pointer; text-align: left; background: transparent; color: var(--general-text-secondary);"
                >
                    <tedi:icon name="supervised_user_circle" :size="24" color="secondary" />
                    <span style="display: flex; flex-direction: column;">
                        <span>Helgi Sarapuu</span>
                        <span style="font-size: var(--body-small-regular-size);">62004122984</span>
                    </span>
                </button>
            </tedi:popover-content>
        </tedi:popover>
    </tedi:col>

    <tedi:col :width="12">
        <tedi:popover container-id="popover-border-4" :with-border="true" position="bottom">
            <x-slot:trigger>
                <tedi:popover-trigger :underline="true">Empty state</tedi:popover-trigger>
            </x-slot:trigger>

            <tedi:popover-content max-width="small" class="story-popover-content--no-padding">
                <tedi:empty-state type="inside" icon="heart_check" size="small">Sul puuduvad esindatavad</tedi:empty-state>
            </tedi:popover-content>
        </tedi:popover>
    </tedi:col>

    <tedi:col :width="12">
        <tedi:popover container-id="popover-border-5" labelled-by="popover-border-5_title" :with-border="true" position="right">
            <x-slot:trigger>
                <tedi:popover-trigger :underline="true">Right center</tedi:popover-trigger>
            </x-slot:trigger>

            <tedi:popover-content max-width="small" title="Pealkiri" :show-close="true">
                <p>{{ $polarBear }}</p>
            </tedi:popover-content>
        </tedi:popover>
    </tedi:col>

    <tedi:col :width="12">
        <tedi:popover container-id="popover-border-6" labelled-by="popover-border-6_title" :with-border="true" position="top">
            <x-slot:trigger>
                <tedi:popover-trigger :underline="true">Top center</tedi:popover-trigger>
            </x-slot:trigger>

            <tedi:popover-content max-width="small" title="Pealkiri" :show-close="true">
                <p>{{ $polarBear }}</p>
            </tedi:popover-content>
        </tedi:popover>
    </tedi:col>
</tedi:row>
