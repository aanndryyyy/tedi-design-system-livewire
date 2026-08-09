@storybook([
    'name' => 'Content Examples',
    'order' => 2,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.56.78?m=dev&node-id=5797-117364',
    'args' => [],
    'argTypes' => [],
])

@php
    $polarBear = 'Jääkaru (Ursus maritimus) on suur karu, kes elab Arktikas ja selle lähialadel.';
    $triggerButton = 'tedi-button tedi-button--primary tedi-button--default tedi-button--pl tedi-button--pr';
@endphp

<tedi:row :gap="3">
    <tedi:col>
        <tedi:popover container-id="popover-content-1" labelled-by="popover-content-1_title">
            <x-slot:trigger>
                <tedi:popover-trigger tag="button" :class="$triggerButton">Buttons &amp; heading</tedi:popover-trigger>
            </x-slot:trigger>

            <tedi:popover-content title="Pealkiri" :show-close="true">
                <p>{{ $polarBear }}</p>
                <div style="display: flex; gap: 0.5rem;">
                    <tedi:button variant="secondary">Tühista</tedi:button>
                    <tedi:button>Esita</tedi:button>
                </div>
            </tedi:popover-content>
        </tedi:popover>
    </tedi:col>

    <tedi:col>
        <tedi:popover container-id="popover-content-2">
            <x-slot:trigger>
                <tedi:popover-trigger tag="button" :class="$triggerButton">Buttons</tedi:popover-trigger>
            </x-slot:trigger>

            <tedi:popover-content :show-close="true">
                <p>{{ $polarBear }}</p>
                <div style="display: flex; gap: 0.5rem;">
                    <tedi:button variant="secondary">Tühista</tedi:button>
                    <tedi:button>Esita</tedi:button>
                </div>
            </tedi:popover-content>
        </tedi:popover>
    </tedi:col>

    <tedi:col>
        <tedi:popover container-id="popover-content-3">
            <x-slot:trigger>
                <tedi:popover-trigger tag="button" :class="$triggerButton">Link</tedi:popover-trigger>
            </x-slot:trigger>

            <tedi:popover-content>
                <p>{{ $polarBear }}</p>
                <tedi:link href="#" icon-end="north_east" style="margin-left: auto;">Loe rohkem</tedi:link>
            </tedi:popover-content>
        </tedi:popover>
    </tedi:col>

    <tedi:col>
        <tedi:popover container-id="popover-content-4">
            <x-slot:trigger>
                <tedi:popover-trigger tag="button" :class="$triggerButton">Text</tedi:popover-trigger>
            </x-slot:trigger>

            <tedi:popover-content>{{ $polarBear }}</tedi:popover-content>
        </tedi:popover>
    </tedi:col>
</tedi:row>
