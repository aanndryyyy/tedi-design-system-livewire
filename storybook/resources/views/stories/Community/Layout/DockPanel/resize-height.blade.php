@storybook([
    'name' => 'Resize height',
    'order' => 12,
    'status' => 'subset',
    'args' => [
        'title' => 'Resizable details',
        'shortTitle' => '',
        'description' => '',
        'placement' => 'bottom',
        'size' => 'default',
        'resizable' => true,
        'mobile' => 'Automatic',
        'width' => '',
        'height' => 'auto',
        'minWidth' => '',
        'maxWidth' => '',
        'minHeight' => '',
        'maxHeight' => '',
        'collapsed' => false,
        'headingElement' => '',
        'id' => '',
    ],
    'argTypes' => [
        'title' => ['control' => 'text', 'description' => 'Required. Visible heading and accessible name.', 'table' => ['category' => 'Required props', 'type' => ['summary' => 'string']]],
        'shortTitle' => ['control' => 'text', 'description' => 'Optional. Shown instead of title when the full title does not fit on one line. It is then the visible heading and accessible name. Truncated if it does not fit either.', 'table' => ['category' => 'Optional props', 'subcategory' => 'Content', 'type' => ['summary' => 'string']]],
        'description' => ['control' => 'text', 'description' => 'Text below the title, styled like Modal.Header. Hidden while minimized; omitted or empty text adds no spacing.', 'table' => ['category' => 'Optional props', 'subcategory' => 'Content', 'type' => ['summary' => 'string']]],
        'placement' => ['control' => 'select', 'options' => ['left', 'right', 'top', 'bottom'], 'description' => 'Optional. Edge of the positioned workspace.', 'table' => ['category' => 'Optional props', 'subcategory' => 'Layout', 'defaultValue' => ['summary' => 'right']]],
        'size' => ['control' => 'inline-radio', 'options' => ['small', 'default'], 'description' => 'Optional. Panel density, independent of width/height. Uses TEDI Modal padding tokens. The title defaults to h3 for default and h4 for small, unless headingElement is supplied.', 'table' => ['category' => 'Optional props', 'subcategory' => 'Layout', 'defaultValue' => ['summary' => 'default']]],
        'resizable' => ['control' => 'boolean', 'description' => 'Enables dragging the workspace-facing edge, within 8–92% of the workspace by default. Pixel min/max props replace the corresponding defaults. Minimize/restore preserves the resized dimensions.', 'table' => ['category' => 'Optional props', 'subcategory' => 'Layout', 'defaultValue' => ['summary' => 'false']]],
        'mobile' => ['control' => 'inline-radio', 'options' => ['Automatic', 'Mobile', 'Desktop'], 'description' => 'Optional. Automatic follows the md breakpoint; Mobile always uses the Sheet; Desktop never does.', 'table' => ['category' => 'Optional props', 'subcategory' => 'Layout', 'defaultValue' => ['summary' => 'Automatic (below md)']]],
        'width' => ['control' => 'text', 'description' => 'Optional. Left/right expanded width: xs=25%, sm=35%, md=50%, lg=65%, xl=80% of workspace width, or a CSS length. Top/bottom ignore it.', 'table' => ['category' => 'Optional props', 'subcategory' => 'Layout', 'defaultValue' => ['summary' => 'xs (25% of workspace width)']]],
        'height' => ['control' => 'text', 'description' => 'Optional. Every placement: xs=25%, sm=35%, md=50%, lg=65%, xl=80%, a CSS length, or auto. Percentages use the workspace height.', 'table' => ['category' => 'Optional props', 'subcategory' => 'Layout', 'defaultValue' => ['summary' => '100% (side); 50% (top/bottom)']]],
        'minWidth' => ['control' => 'number', 'description' => 'Minimum side-panel resize width in pixels. Defaults to 8% of workspace width.', 'table' => ['category' => 'Optional props', 'subcategory' => 'Layout', 'type' => ['summary' => 'number']]],
        'maxWidth' => ['control' => 'number', 'description' => 'Maximum side-panel width in pixels. Defaults to 92% when resizable; otherwise workspace width.', 'table' => ['category' => 'Optional props', 'subcategory' => 'Layout', 'type' => ['summary' => 'number']]],
        'minHeight' => ['control' => 'number', 'description' => 'Minimum top/bottom resize height in pixels. Defaults to 8% of workspace height.', 'table' => ['category' => 'Optional props', 'subcategory' => 'Layout', 'type' => ['summary' => 'number']]],
        'maxHeight' => ['control' => 'number', 'description' => 'Optional maximum expanded height in pixels. Defaults to 92% of workspace height for top/bottom resizing; otherwise the workspace height. Body content scrolls at this limit.', 'table' => ['category' => 'Optional props', 'subcategory' => 'Layout', 'type' => ['summary' => 'number'], 'defaultValue' => ['summary' => '92% (top/bottom resizing); workspace height otherwise']]],
        'collapsed' => ['control' => false, 'description' => 'Minimized state the server renders; applied when the panel is opened.', 'table' => ['category' => 'Optional props', 'subcategory' => 'State', 'defaultValue' => ['summary' => 'false']]],
        'headingElement' => ['control' => 'select', 'options' => ['', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'], 'description' => 'Optional. Overrides the title heading and expanded text size. Defaults to h3 for default and h4 for small.', 'table' => ['category' => 'Optional props', 'subcategory' => 'Accessibility', 'defaultValue' => ['summary' => 'h3 (default), h4 (small)']]],
        'id' => ['control' => 'text', 'description' => 'Optional. Panel id; a unique id is generated when omitted.', 'table' => ['category' => 'Optional props', 'subcategory' => 'Accessibility', 'type' => ['summary' => 'string']]],
    ],
])

{{-- Upstream's ResizeExample: a workspace-size toggle around PanelExample.
     The workspace's width and height are client-side, so the panel's
     ResizeObserver sees each change. --}}
{{-- Upstream's PanelExample: the demo, not DockPanel, owns mounting. Each
     choice is its own `x-if`, so switching choices remounts the panel, where
     upstream re-renders the same one with new props; a Blade panel's props
     are server-rendered. Close (and Cancel) unmount it and return focus to
     the button that opened it. --}}
<div style="display: grid; gap: var(--tedi-dimensions-10)" x-data="{
        smaller: false,
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
    <div>
        <tedi:button variant="secondary" x-on:click="smaller = ! smaller" x-text="smaller ? 'Restore workspace size' : 'Make workspace smaller'">Make workspace smaller</tedi:button>
    </div>
    <div style="display: flex; flex-wrap: wrap; gap: var(--tedi-dimensions-04)">
        <tedi:button variant="secondary" x-bind:aria-expanded="active === 'default' && ! panelCollapsed" x-on:click="openExample('default', $el, {{ $collapsed ? 'true' : 'false' }})">Open DockPanel</tedi:button>
        <tedi:button variant="secondary" x-show="active !== null" x-on:click="closeExample()">Hide example</tedi:button>
    </div>
    <div x-bind:style="{ width: smaller ? '60%' : '100%' }">
    <template x-if="active === 'default'">
            <tedi:dock-panel-layout
                style="height: 36rem; border: 1px solid var(--general-border-primary); background: var(--general-surface-secondary)"
                x-bind:style="{ height: smaller ? '24rem' : '36rem' }"
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
                        :title="$title"
                        :short-title="$shortTitle ?: null"
                        :description="$description ?: null"
                        :placement="$placement"
                        :size="$size"
                        :resizable="(bool) $resizable"
                        :mobile="['Mobile' => true, 'Desktop' => false][$mobile] ?? null"
                        :width="$width ?: null"
                        :height="$height ?: null"
                        :min-width="$minWidth !== '' && $minWidth !== null ? (int) $minWidth : null"
                        :max-width="$maxWidth !== '' && $maxWidth !== null ? (int) $maxWidth : null"
                        :min-height="$minHeight !== '' && $minHeight !== null ? (int) $minHeight : null"
                        :max-height="$maxHeight !== '' && $maxHeight !== null ? (int) $maxHeight : null"
                        :collapsed="(bool) $collapsed"
                        :heading-element="$headingElement ?: null"
                        :id="$id ?: null"
                        :labels="['collapse' => 'Minimize panel', 'expand' => 'Restore panel', 'close' => 'Close panel']"
                    >
                        <div style="display: grid; gap: var(--tedi-dimensions-10)" x-data="{ count: 0 }">
                            <p style="margin: 0">Application content, not part of DockPanel.</p>
                            <tedi:form-field><x-slot:label><tedi:form.label for="dock-panel-resize-height-value">Example value</tedi:form.label></x-slot:label> <tedi:text-field id="dock-panel-resize-height-value" value="Edit me, then minimize and restore" /></tedi:form-field>
                            <div><tedi:button variant="secondary" x-on:click="count++">Content counter: <span x-text="count">0</span></tedi:button></div>
                            <p style="margin: 0">Changing the placement, dimensions or density does not reset this content.</p>
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
</div>
