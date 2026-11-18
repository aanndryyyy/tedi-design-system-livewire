@storybook([
    'name' => 'Size',
    'order' => 2,
    'status' => 'subset',
    'args' => [],
])

{{-- Upstream's PanelExample: the demo, not DockPanel, owns mounting. Each
     choice is its own `x-if`, so switching choices remounts the panel, where
     upstream re-renders the same one with new props; a Blade panel's props
     are server-rendered. Close (and Cancel) unmount it and return focus to
     the button that opened it. --}}
<div style="display: grid; gap: var(--tedi-dimensions-10)" x-data="{
        active: null,
        panelCollapsed: false,
        trigger: null,
        openExample(key, el, collapsed = false) {
            this.trigger = el;
            if (this.active === key) {
                const panel = this.$root.querySelector('.tedi-dock-panel');
                if (panel) Alpine.$data(panel).requestCollapsed(collapsed);
                return;
            }
            this.panelCollapsed = collapsed;
            this.active = key;
        },
        closeExample() {
            this.active = null;
            this.$nextTick(() => this.trigger && this.trigger.focus());
        },
    }">
    <div style="display: flex; flex-wrap: wrap; gap: var(--tedi-dimensions-04)">
        <tedi:button variant="secondary" x-bind:aria-expanded="active === 'small' && ! panelCollapsed" x-on:click="openExample('small', $el, false)">Open small DockPanel</tedi:button>
        <tedi:button variant="secondary" x-bind:aria-expanded="active === 'default' && ! panelCollapsed" x-on:click="openExample('default', $el, false)">Open default DockPanel</tedi:button>
    </div>
    <template x-if="active === 'small'">
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
                        title="Small DockPanel" size="small" width="sm" height="auto" :max-height="512"
                        :labels="['collapse' => 'Minimize panel', 'expand' => 'Restore panel', 'close' => 'Close panel']" closable
                    >
                        <div style="display: grid; gap: var(--tedi-dimensions-10)">
                            <tedi:form-field><x-slot:label><tedi:form.label for="dock-panel-size-small-name">Name</tedi:form.label></x-slot:label> <tedi:text-field id="dock-panel-size-small-name" value="Example item" /></tedi:form-field>
                            <tedi:form-field><x-slot:label><tedi:form.label for="dock-panel-size-small-description">Description</tedi:form.label></x-slot:label> <tedi:text-field id="dock-panel-size-small-description" value="Application-provided content" /></tedi:form-field>
                        </div>
                        <x-slot:footer>
                            <div style="display: flex; flex-wrap: wrap; gap: var(--tedi-dimensions-05); align-items: center; justify-content: flex-end" x-data="{ saves: 0 }">
                                <span role="status" style="margin-right: auto" x-text="saves > 0 ? 'Saved (demo): ' + saves : ''"></span>
                                <tedi:button variant="secondary" x-on:click="closeExample()">Cancel</tedi:button>
                                <tedi:button x-on:click="saves++">Save</tedi:button>
                            </div>
                        </x-slot:footer>
                    </tedi:dock-panel>
                </x-slot:panel>
            </tedi:dock-panel-layout>
        </template>
        <template x-if="active === 'default'">
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
                        title="Default DockPanel" size="default" width="sm" height="auto" :max-height="512"
                        :labels="['collapse' => 'Minimize panel', 'expand' => 'Restore panel', 'close' => 'Close panel']" closable
                    >
                        <div style="display: grid; gap: var(--tedi-dimensions-10)">
                            <tedi:form-field><x-slot:label><tedi:form.label for="dock-panel-size-default-name">Name</tedi:form.label></x-slot:label> <tedi:text-field id="dock-panel-size-default-name" value="Example item" /></tedi:form-field>
                            <tedi:form-field><x-slot:label><tedi:form.label for="dock-panel-size-default-description">Description</tedi:form.label></x-slot:label> <tedi:text-field id="dock-panel-size-default-description" value="Application-provided content" /></tedi:form-field>
                        </div>
                        <x-slot:footer>
                            <div style="display: flex; flex-wrap: wrap; gap: var(--tedi-dimensions-05); align-items: center; justify-content: flex-end" x-data="{ saves: 0 }">
                                <span role="status" style="margin-right: auto" x-text="saves > 0 ? 'Saved (demo): ' + saves : ''"></span>
                                <tedi:button variant="secondary" x-on:click="closeExample()">Cancel</tedi:button>
                                <tedi:button x-on:click="saves++">Save</tedi:button>
                            </div>
                        </x-slot:footer>
                    </tedi:dock-panel>
                </x-slot:panel>
            </tedi:dock-panel-layout>
        </template>
</div>
