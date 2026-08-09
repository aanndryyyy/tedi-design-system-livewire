@storybook([
    'name' => 'Heading',
    'order' => 3,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.56.78?m=dev&node-id=5797-117364',
    'args' => [],
    'argTypes' => [],
])

@php
    // The Angular story writes `position="top"` on <tedi-popover-content>, which
    // has no such input — it is a no-op there, and 'top' is the popover default
    // anyway, so it is simply omitted here.
    $triggerButton = 'tedi-button tedi-button--secondary tedi-button--default tedi-button--pl tedi-button--pr';
@endphp

<tedi:row :gap="3">
    <tedi:col>
        <tedi:popover container-id="popover-heading-1" labelled-by="popover-heading-1_title">
            <x-slot:trigger>
                <tedi:popover-trigger tag="button" :class="$triggerButton">Heading &amp; close</tedi:popover-trigger>
            </x-slot:trigger>

            <tedi:popover-content max-width="medium" title="Pealkiri" :show-close="true">
                <p>This popover is with title and close button.</p>
                <div style="margin-left: auto; display: flex; gap: 0.5rem;">
                    <tedi:button variant="secondary">Tühista</tedi:button>
                    <tedi:button>Esita</tedi:button>
                </div>
            </tedi:popover-content>
        </tedi:popover>
    </tedi:col>

    <tedi:col>
        <tedi:popover container-id="popover-heading-2" labelled-by="popover-heading-2_title">
            <x-slot:trigger>
                <tedi:popover-trigger tag="button" :class="$triggerButton">Heading</tedi:popover-trigger>
            </x-slot:trigger>

            <tedi:popover-content max-width="medium" title="Pealkiri">
                <p>This popover is with title.</p>
                <div style="margin-left: auto; display: flex; gap: 0.5rem;">
                    <tedi:button variant="secondary">Tühista</tedi:button>
                    <tedi:button>Esita</tedi:button>
                </div>
            </tedi:popover-content>
        </tedi:popover>
    </tedi:col>

    <tedi:col>
        <tedi:popover container-id="popover-heading-3">
            <x-slot:trigger>
                <tedi:popover-trigger tag="button" :class="$triggerButton">Content &amp; close</tedi:popover-trigger>
            </x-slot:trigger>

            <tedi:popover-content max-width="medium" :show-close="true">
                <p>This popover is with content and close button.</p>
                <div style="margin-left: auto; display: flex; gap: 0.5rem;">
                    <tedi:button variant="secondary">Tühista</tedi:button>
                    <tedi:button>Esita</tedi:button>
                </div>
            </tedi:popover-content>
        </tedi:popover>
    </tedi:col>

    <tedi:col>
        <tedi:popover container-id="popover-heading-4">
            <x-slot:trigger>
                <tedi:popover-trigger tag="button" :class="$triggerButton">Only content</tedi:popover-trigger>
            </x-slot:trigger>

            <tedi:popover-content max-width="medium">
                <p>This popover is with content only.</p>
                <div style="margin-left: auto; display: flex; gap: 0.5rem;">
                    <tedi:button variant="secondary">Tühista</tedi:button>
                    <tedi:button>Esita</tedi:button>
                </div>
            </tedi:popover-content>
        </tedi:popover>
    </tedi:col>
</tedi:row>
