@storybook([
    'name' => 'Short title',
    'order' => 14,
    'status' => 'subset',
    'args' => [],
])

{{-- Upstream's HeaderFitExample. `mobile` is a server-rendered prop, so each
     presentation, short-title and (for the sheet) width combination is its
     own `x-if`; in the docked panel the narrow width is the wrapper's, as
     upstream does it, and is bound live. --}}
<div style="display: grid; gap: var(--tedi-dimensions-10)" x-data="{
        presentation: 'docked',
        narrow: true,
        withShortTitle: true,
        trigger: null,
        select(next, el) { this.trigger = el; this.presentation = next; },
        closeExample() {
            this.presentation = null;
            this.reported = null;
            this.$nextTick(() => this.trigger && this.trigger.focus());
        },
    }">
    <div style="display: flex; flex-wrap: wrap; gap: var(--tedi-dimensions-04)">
        <tedi:button variant="secondary" x-bind:aria-pressed="presentation === 'docked'" x-on:click="select('docked', $el)">Docked panel</tedi:button>
        <tedi:button variant="secondary" x-bind:aria-pressed="presentation === 'sheet'" x-on:click="select('sheet', $el)">Bottom sheet</tedi:button>
        <tedi:button variant="secondary" x-on:click="narrow = ! narrow" x-text="narrow ? 'Use full width' : 'Use narrow width (400 px)'">Use full width</tedi:button>
        <tedi:button variant="secondary" x-on:click="withShortTitle = ! withShortTitle" x-text="withShortTitle ? 'Remove short title' : 'Add short title'">Remove short title</tedi:button>
    </div>
    <template x-if="presentation === 'docked' && withShortTitle === true">
            <div x-bind:style="{ width: narrow ? '25rem' : '100%', maxWidth: '100%' }">
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
                        title="Details of the selected item and its related documents" short-title="Item details"
                        :mobile="false"
                        width="100%"
                        :labels="['collapse' => 'Minimize panel', 'expand' => 'Restore panel', 'close' => 'Close panel']"
                        closable
                    >
                        <div style="display: grid; gap: var(--tedi-dimensions-10)" x-data="{ count: 0 }">
                            <p style="margin: 0">Application content, not part of DockPanel.</p>
                            <tedi:form-field><x-slot:label><tedi:form.label for="dock-panel-short-docked-value">Example value</tedi:form.label></x-slot:label> <tedi:text-field id="dock-panel-short-docked-value" value="Edit me, then minimize and restore" /></tedi:form-field>
                            <div><tedi:button variant="secondary" x-on:click="count++">Content counter: <span x-text="count">0</span></tedi:button></div>
                            <p style="margin: 0">Changing the placement, dimensions or density does not reset this content.</p>
                        </div>
                        <x-slot:header-actions><tedi:status-badge color="warning">Draft</tedi:status-badge></x-slot:header-actions>
                    </tedi:dock-panel>
                    </x-slot:panel>
                </tedi:dock-panel-layout>
            </div>
        </template>
    <template x-if="presentation === 'docked' && withShortTitle === false">
            <div x-bind:style="{ width: narrow ? '25rem' : '100%', maxWidth: '100%' }">
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
                        title="Details of the selected item and its related documents"
                        :mobile="false"
                        width="100%"
                        :labels="['collapse' => 'Minimize panel', 'expand' => 'Restore panel', 'close' => 'Close panel']"
                        closable
                    >
                        <div style="display: grid; gap: var(--tedi-dimensions-10)" x-data="{ count: 0 }">
                            <p style="margin: 0">Application content, not part of DockPanel.</p>
                            <tedi:form-field><x-slot:label><tedi:form.label for="dock-panel-short-docked-value">Example value</tedi:form.label></x-slot:label> <tedi:text-field id="dock-panel-short-docked-value" value="Edit me, then minimize and restore" /></tedi:form-field>
                            <div><tedi:button variant="secondary" x-on:click="count++">Content counter: <span x-text="count">0</span></tedi:button></div>
                            <p style="margin: 0">Changing the placement, dimensions or density does not reset this content.</p>
                        </div>
                        <x-slot:header-actions><tedi:status-badge color="warning">Draft</tedi:status-badge></x-slot:header-actions>
                    </tedi:dock-panel>
                    </x-slot:panel>
                </tedi:dock-panel-layout>
            </div>
        </template>
    <template x-if="presentation === 'sheet' && withShortTitle === true && narrow === true">
            <div x-on:tedi:close="closeExample()" x-on:tedi:presentation-change="reported = $event.detail.presentation">
                <tedi:dock-panel
                        title="Details of the selected item and its related documents" short-title="Item details"
                        :mobile="true"
                        width="100%" style="max-width: 25rem"
                        :labels="['collapse' => 'Minimize panel', 'expand' => 'Restore panel', 'close' => 'Close panel']"
                        closable
                    >
                        <div style="display: grid; gap: var(--tedi-dimensions-10)" x-data="{ count: 0 }">
                            <p style="margin: 0">Application content, not part of DockPanel.</p>
                            <tedi:form-field><x-slot:label><tedi:form.label for="dock-panel-short-sheet-value">Example value</tedi:form.label></x-slot:label> <tedi:text-field id="dock-panel-short-sheet-value" value="Edit me, then minimize and restore" /></tedi:form-field>
                            <div><tedi:button variant="secondary" x-on:click="count++">Content counter: <span x-text="count">0</span></tedi:button></div>
                            <p style="margin: 0">Changing the placement, dimensions or density does not reset this content.</p>
                        </div>
                        <x-slot:header-actions><tedi:status-badge color="warning">Draft</tedi:status-badge></x-slot:header-actions>
                    </tedi:dock-panel>
            </div>
        </template>
    <template x-if="presentation === 'sheet' && withShortTitle === true && narrow === false">
            <div x-on:tedi:close="closeExample()" x-on:tedi:presentation-change="reported = $event.detail.presentation">
                <tedi:dock-panel
                        title="Details of the selected item and its related documents" short-title="Item details"
                        :mobile="true"
                        width="100%"
                        :labels="['collapse' => 'Minimize panel', 'expand' => 'Restore panel', 'close' => 'Close panel']"
                        closable
                    >
                        <div style="display: grid; gap: var(--tedi-dimensions-10)" x-data="{ count: 0 }">
                            <p style="margin: 0">Application content, not part of DockPanel.</p>
                            <tedi:form-field><x-slot:label><tedi:form.label for="dock-panel-short-sheet-value">Example value</tedi:form.label></x-slot:label> <tedi:text-field id="dock-panel-short-sheet-value" value="Edit me, then minimize and restore" /></tedi:form-field>
                            <div><tedi:button variant="secondary" x-on:click="count++">Content counter: <span x-text="count">0</span></tedi:button></div>
                            <p style="margin: 0">Changing the placement, dimensions or density does not reset this content.</p>
                        </div>
                        <x-slot:header-actions><tedi:status-badge color="warning">Draft</tedi:status-badge></x-slot:header-actions>
                    </tedi:dock-panel>
            </div>
        </template>
    <template x-if="presentation === 'sheet' && withShortTitle === false && narrow === true">
            <div x-on:tedi:close="closeExample()" x-on:tedi:presentation-change="reported = $event.detail.presentation">
                <tedi:dock-panel
                        title="Details of the selected item and its related documents"
                        :mobile="true"
                        width="100%" style="max-width: 25rem"
                        :labels="['collapse' => 'Minimize panel', 'expand' => 'Restore panel', 'close' => 'Close panel']"
                        closable
                    >
                        <div style="display: grid; gap: var(--tedi-dimensions-10)" x-data="{ count: 0 }">
                            <p style="margin: 0">Application content, not part of DockPanel.</p>
                            <tedi:form-field><x-slot:label><tedi:form.label for="dock-panel-short-sheet-value">Example value</tedi:form.label></x-slot:label> <tedi:text-field id="dock-panel-short-sheet-value" value="Edit me, then minimize and restore" /></tedi:form-field>
                            <div><tedi:button variant="secondary" x-on:click="count++">Content counter: <span x-text="count">0</span></tedi:button></div>
                            <p style="margin: 0">Changing the placement, dimensions or density does not reset this content.</p>
                        </div>
                        <x-slot:header-actions><tedi:status-badge color="warning">Draft</tedi:status-badge></x-slot:header-actions>
                    </tedi:dock-panel>
            </div>
        </template>
    <template x-if="presentation === 'sheet' && withShortTitle === false && narrow === false">
            <div x-on:tedi:close="closeExample()" x-on:tedi:presentation-change="reported = $event.detail.presentation">
                <tedi:dock-panel
                        title="Details of the selected item and its related documents"
                        :mobile="true"
                        width="100%"
                        :labels="['collapse' => 'Minimize panel', 'expand' => 'Restore panel', 'close' => 'Close panel']"
                        closable
                    >
                        <div style="display: grid; gap: var(--tedi-dimensions-10)" x-data="{ count: 0 }">
                            <p style="margin: 0">Application content, not part of DockPanel.</p>
                            <tedi:form-field><x-slot:label><tedi:form.label for="dock-panel-short-sheet-value">Example value</tedi:form.label></x-slot:label> <tedi:text-field id="dock-panel-short-sheet-value" value="Edit me, then minimize and restore" /></tedi:form-field>
                            <div><tedi:button variant="secondary" x-on:click="count++">Content counter: <span x-text="count">0</span></tedi:button></div>
                            <p style="margin: 0">Changing the placement, dimensions or density does not reset this content.</p>
                        </div>
                        <x-slot:header-actions><tedi:status-badge color="warning">Draft</tedi:status-badge></x-slot:header-actions>
                    </tedi:dock-panel>
            </div>
        </template>
</div>
