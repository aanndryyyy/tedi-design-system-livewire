@storybook([
    'name' => 'Presentation change',
    'order' => 15,
    'status' => 'subset',
    'args' => [],
])

{{-- Upstream's PresentationChangeExample. `tedi:presentation-change` stands in
     for onPresentationChange; the html attribute is read with a
     MutationObserver, as upstream's useRootMarker does. --}}
<div style="display: grid; gap: var(--tedi-dimensions-10)" x-data="{
        presentation: 'docked',
        reported: null,
        marker: null,
        trigger: null,
        select(next, el) { this.trigger = el; this.presentation = next; },
        closeExample() {
            this.presentation = null;
            this.reported = null;
            this.$nextTick(() => this.trigger && this.trigger.focus());
        },
        init() {
            const root = document.documentElement;
            const read = () => { this.marker = root.getAttribute('data-tedi-dock-panel-sheet'); };
            read();
            this._observer = new MutationObserver(read);
            this._observer.observe(root, { attributes: true, attributeFilter: ['data-tedi-dock-panel-sheet'] });
        },
        destroy() { this._observer && this._observer.disconnect(); },
    }">
    <div style="display: flex; flex-wrap: wrap; gap: var(--tedi-dimensions-04)">
        <tedi:button variant="secondary" x-bind:aria-pressed="presentation === 'docked'" x-on:click="select('docked', $el)">Docked panel</tedi:button>
        <tedi:button variant="secondary" x-bind:aria-pressed="presentation === 'sheet'" x-on:click="select('sheet', $el)">Bottom sheet</tedi:button>
    </div>
    <dl style="display: grid; grid-template-columns: auto 1fr; gap: var(--tedi-dimensions-04); margin: 0">
        <dt>onPresentationChange</dt>
        <dd style="margin: 0" aria-live="polite" x-text="reported ?? 'not called'">not called</dd>
        <dt>html attribute</dt>
        <dd style="margin: 0" aria-live="polite" x-text="marker ? 'data-tedi-dock-panel-sheet=&quot;' + marker + '&quot;' : 'not set'">not set</dd>
    </dl>
    <template x-if="presentation === 'docked'">
        <tedi:dock-panel-layout
            style="height: 20rem; border: 1px solid var(--general-border-primary); background: var(--general-surface-secondary)"
            x-on:tedi:close="closeExample()"
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
                        :mobile="false"
                        x-on:tedi:presentation-change="reported = $event.detail.presentation"
                        :labels="['collapse' => 'Minimize panel', 'expand' => 'Restore panel', 'close' => 'Close panel']"
                        closable
                    >
                        <div style="display: grid; gap: var(--tedi-dimensions-10)" x-data="{ count: 0 }">
                            <p style="margin: 0">Application content, not part of DockPanel.</p>
                            <tedi:form-field><x-slot:label><tedi:form.label for="dock-panel-presentation-docked-value">Example value</tedi:form.label></x-slot:label> <tedi:text-field id="dock-panel-presentation-docked-value" value="Edit me, then minimize and restore" /></tedi:form-field>
                            <div><tedi:button variant="secondary" x-on:click="count++">Content counter: <span x-text="count">0</span></tedi:button></div>
                            <p style="margin: 0">Changing the placement, dimensions or density does not reset this content.</p>
                        </div>
                    </tedi:dock-panel>
            </x-slot:panel>
        </tedi:dock-panel-layout>
    </template>
    <template x-if="presentation === 'sheet'">
        <div x-on:tedi:close="closeExample()">
            <tedi:dock-panel
                        title="Details"
                        :mobile="true"
                        x-on:tedi:presentation-change="reported = $event.detail.presentation"
                        :labels="['collapse' => 'Minimize panel', 'expand' => 'Restore panel', 'close' => 'Close panel']"
                        closable
                    >
                        <div style="display: grid; gap: var(--tedi-dimensions-10)" x-data="{ count: 0 }">
                            <p style="margin: 0">Application content, not part of DockPanel.</p>
                            <tedi:form-field><x-slot:label><tedi:form.label for="dock-panel-presentation-sheet-value">Example value</tedi:form.label></x-slot:label> <tedi:text-field id="dock-panel-presentation-sheet-value" value="Edit me, then minimize and restore" /></tedi:form-field>
                            <div><tedi:button variant="secondary" x-on:click="count++">Content counter: <span x-text="count">0</span></tedi:button></div>
                            <p style="margin: 0">Changing the placement, dimensions or density does not reset this content.</p>
                        </div>
                    </tedi:dock-panel>
        </div>
    </template>
</div>
