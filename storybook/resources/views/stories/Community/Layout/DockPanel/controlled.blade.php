@storybook([
    'name' => 'Controlled',
    'order' => 10,
    'status' => 'subset',
    'args' => [],
])

{{-- Upstream's ControlledExample. The application state lives in this Alpine
     scope: `counter` reaches the panel as `data-minimize-counter` (the
     attribute the `minimize-counter` prop renders, here bound live), and
     `tedi:collapsed-change` keeps `panelCollapsed` in step. Open / restore
     calls the panel scope's `requestCollapsed(false)` when it is already
     open. With Livewire, bind the event to a property and render
     `collapsed` / `minimize-counter` from it instead. --}}
<div style="display: grid; gap: var(--tedi-dimensions-08)" x-data="{
        open: false,
        panelCollapsed: false,
        counter: 0,
        message: 'No header action yet.',
        trigger: null,
        openPanel(el) {
            this.trigger = el;
            if (this.open) {
                const panel = this.$root.querySelector('.tedi-dock-panel');
                if (panel) Alpine.$data(panel).requestCollapsed(false);
                return;
            }
            this.panelCollapsed = false;
            this.open = true;
        },
        closeExample() {
            this.open = false;
            this.$nextTick(() => this.trigger && this.trigger.focus());
        },
    }">
    <div style="display: flex; flex-wrap: wrap; gap: var(--tedi-dimensions-04)">
        <tedi:button variant="secondary" x-on:click="openPanel($el)">Open / restore panel</tedi:button>
        <tedi:button variant="secondary" x-bind:disabled="! open" x-on:click="counter++">Minimize panel</tedi:button>
    </div>
    <template x-if="open">
            <tedi:dock-panel-layout
                style="height: 36rem; border: 1px solid var(--general-border-primary); background: var(--general-surface-secondary)"
                x-on:tedi:close="closeExample()"
                x-on:tedi:collapsed-change="panelCollapsed = $event.detail.collapsed"
            >
                <div style="display: contents" x-data="{ count: 0 }">
                    <div style="padding: var(--tedi-dimensions-10)">
                        <p>Expanded panels cover this workspace. Minimized panels reserve their own strip beside it.</p>
                        <tedi:button variant="secondary" x-on:click="count++">Workspace counter: <span x-text="count">0</span></tedi:button>
                    </div>
                    <div style="position: absolute; right: var(--tedi-dimensions-10); bottom: var(--tedi-dimensions-10)">
                        <tedi:button variant="secondary" x-on:click="count++">Background action: <span x-text="count">0</span></tedi:button>
                    </div>
                </div>
                <x-slot:panel>
                    <tedi:dock-panel
                        title="Details"
                        :minimize-counter="0"
                        x-bind:data-minimize-counter="counter"
                        :labels="['collapse' => 'Minimize panel', 'expand' => 'Restore panel', 'close' => 'Close panel']"
                        closable
                    >
                        <div style="display: grid; gap: var(--tedi-dimensions-10)" x-data="{ count: 0 }">
                            <p style="margin: 0">Application content, not part of DockPanel.</p>
                            <tedi:form-field><x-slot:label><tedi:form.label for="dock-panel-controlled-value">Example value</tedi:form.label></x-slot:label> <tedi:text-field id="dock-panel-controlled-value" value="Edit me, then minimize and restore" /></tedi:form-field>
                            <div><tedi:button variant="secondary" x-on:click="count++">Content counter: <span x-text="count">0</span></tedi:button></div>
                            <p style="margin: 0">Changing the placement, dimensions or density does not reset this content.</p>
                        </div>
                        <x-slot:header-actions>
                            <tedi:button variant="secondary" size="small" x-on:click="message = 'Header action clicked.'">Example action</tedi:button>
                        </x-slot:header-actions>
                        <x-slot:footer>
                            <div style="display: flex; flex-wrap: wrap; gap: var(--tedi-dimensions-05); align-items: center; justify-content: flex-end" x-data="{ saves: 0 }">
                                <span role="status" style="margin-right: auto" x-text="saves > 0 ? 'Saved (demo): ' + saves : message"></span>
                                <tedi:button variant="secondary" x-on:click="closeExample()">Cancel</tedi:button>
                                <tedi:button x-on:click="saves++">Save</tedi:button>
                            </div>
                        </x-slot:footer>
                    </tedi:dock-panel>
                </x-slot:panel>
            </tedi:dock-panel-layout>
        </template>
</div>
