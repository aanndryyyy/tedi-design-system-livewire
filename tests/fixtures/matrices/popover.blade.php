<div class="gx-sec">
    <h2>Popover</h2>
    <p>Port of <code>overlay/popover</code>. The trigger goes in the <code>trigger</code> slot, the content in the default slot. Panels render closed (<code>x-show</code>), so every class below is present in the markup even without Alpine.</p>

    <div class="gx-case">
        <div class="gx-case__label">container: default (arrow) | with border | without arrow | border without arrow</div>
        <div class="gx-case__demo" style="display:flex;gap:.75rem;flex-wrap:wrap">
            <tedi:popover container-id="mx-pop-1">
                <x-slot:trigger>
                    <tedi:popover-trigger :underline="true">Default</tedi:popover-trigger>
                </x-slot:trigger>
                <tedi:popover-content>Jääkaru elab Arktikas.</tedi:popover-content>
            </tedi:popover>

            <tedi:popover container-id="mx-pop-2" :with-border="true" position="bottom">
                <x-slot:trigger>
                    <tedi:popover-trigger :underline="true">With border</tedi:popover-trigger>
                </x-slot:trigger>
                <tedi:popover-content>Jääkaru elab Arktikas.</tedi:popover-content>
            </tedi:popover>

            <tedi:popover container-id="mx-pop-3" :with-arrow="false">
                <x-slot:trigger>
                    <tedi:popover-trigger :underline="true">No arrow</tedi:popover-trigger>
                </x-slot:trigger>
                <tedi:popover-content>Jääkaru elab Arktikas.</tedi:popover-content>
            </tedi:popover>

            <tedi:popover container-id="mx-pop-4" :with-border="true" :with-arrow="false">
                <x-slot:trigger>
                    <tedi:popover-trigger :underline="true">Border, no arrow</tedi:popover-trigger>
                </x-slot:trigger>
                <tedi:popover-content>Jääkaru elab Arktikas.</tedi:popover-content>
            </tedi:popover>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">max-width: none | small | medium | large</div>
        <div class="gx-case__demo" style="display:flex;gap:.75rem;flex-wrap:wrap">
            @foreach (['none', 'small', 'medium', 'large'] as $maxWidth)
                <tedi:popover :container-id="'mx-pop-w-'.$maxWidth">
                    <x-slot:trigger>
                        <tedi:popover-trigger :underline="true">{{ ucfirst($maxWidth) }}</tedi:popover-trigger>
                    </x-slot:trigger>
                    <tedi:popover-content :max-width="$maxWidth">Jääkaru elab Arktikas.</tedi:popover-content>
                </tedi:popover>
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">content branches: title + close | close only | title only | neither</div>
        <div class="gx-case__demo" style="display:flex;gap:.75rem;flex-wrap:wrap">
            <tedi:popover container-id="mx-pop-b1" labelled-by="mx-pop-b1_title">
                <x-slot:trigger>
                    <tedi:popover-trigger tag="button">Title &amp; close</tedi:popover-trigger>
                </x-slot:trigger>
                <tedi:popover-content title="Pealkiri" :show-close="true">Jääkaru elab Arktikas.</tedi:popover-content>
            </tedi:popover>

            <tedi:popover container-id="mx-pop-b2">
                <x-slot:trigger>
                    <tedi:popover-trigger tag="button">Close only</tedi:popover-trigger>
                </x-slot:trigger>
                <tedi:popover-content :show-close="true">Jääkaru elab Arktikas.</tedi:popover-content>
            </tedi:popover>

            <tedi:popover container-id="mx-pop-b3" labelled-by="mx-pop-b3_title">
                <x-slot:trigger>
                    <tedi:popover-trigger tag="button">Title only</tedi:popover-trigger>
                </x-slot:trigger>
                <tedi:popover-content title="Pealkiri">Jääkaru elab Arktikas.</tedi:popover-content>
            </tedi:popover>

            <tedi:popover container-id="mx-pop-b4">
                <x-slot:trigger>
                    <tedi:popover-trigger tag="button">Neither</tedi:popover-trigger>
                </x-slot:trigger>
                <tedi:popover-content>Jääkaru elab Arktikas.</tedi:popover-content>
            </tedi:popover>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">trigger: underlined text | plain text | non-interactive anchor</div>
        <div class="gx-case__demo" style="display:flex;gap:.75rem;flex-wrap:wrap">
            <tedi:popover container-id="mx-pop-t1">
                <x-slot:trigger>
                    <tedi:popover-trigger :underline="true">Underlined</tedi:popover-trigger>
                </x-slot:trigger>
                <tedi:popover-content>Jääkaru elab Arktikas.</tedi:popover-content>
            </tedi:popover>

            <tedi:popover container-id="mx-pop-t2">
                <x-slot:trigger>
                    <tedi:popover-trigger>Plain</tedi:popover-trigger>
                </x-slot:trigger>
                <tedi:popover-content>Jääkaru elab Arktikas.</tedi:popover-content>
            </tedi:popover>

            <tedi:popover container-id="mx-pop-t3">
                <x-slot:trigger>
                    <tedi:popover-trigger :interactive="false">Anchor only</tedi:popover-trigger>
                </x-slot:trigger>
                <tedi:popover-content>Jääkaru elab Arktikas.</tedi:popover-content>
            </tedi:popover>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">position: every OverlayPosition value</div>
        <div class="gx-case__demo" style="display:flex;gap:.75rem;flex-wrap:wrap">
            @foreach (['auto', 'auto-start', 'auto-end', 'top', 'top-start', 'top-end', 'bottom', 'bottom-start', 'bottom-end', 'right', 'right-start', 'right-end', 'left', 'left-start', 'left-end'] as $position)
                <tedi:popover :position="$position" :container-id="'mx-pop-p-'.$position">
                    <x-slot:trigger>
                        <tedi:popover-trigger :underline="true">{{ ucfirst($position) }}</tedi:popover-trigger>
                    </x-slot:trigger>
                    <tedi:popover-content>Jääkaru elab Arktikas.</tedi:popover-content>
                </tedi:popover>
            @endforeach
        </div>
    </div>
</div>
