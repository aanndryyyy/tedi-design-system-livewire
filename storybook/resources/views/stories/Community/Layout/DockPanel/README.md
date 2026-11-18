> **In this port** the React props below map to Blade as follows: `children` → the default slot; `headerActions` / `footer` → the `header-actions` / `footer` slots; `onClose` → `closable` plus the `tedi:close` event; `onCollapsedChange` / `onPresentationChange` → the `tedi:collapsed-change` / `tedi:presentation-change` events; `collapsed` and `defaultCollapsed` → `collapsed`, the server-rendered state (a changed value on a later Livewire render is applied); `className` / `style` → `class` / `style`; `labels` → `:labels`. DockPanelLayout's `panel` → the `panel` slot. With `resizable`, the consumer's `style` stays on the panel; upstream moves `zIndex` and the `--dock-panel-gap` / `--dock-panel-rail-size` / `--dock-panel-layer` properties onto the resize layout, so set those on `tedi:dock-panel-layout` instead.

A reusable **non-modal, docked panel**. Expanded panels cover the workspace; minimized panels do not.
Use **DockPanelLayout** with the workspace as `children` and DockPanel as `panel`.
The layout reserves a separate row or column for the minimized strip, then releases it when expanded or closed.
There is no dimmed backdrop, background click interception, focus trap or body-scroll lock.
Opening is owned by the application: render the panel to show it, remove it to close it.
Minimizing leaves a restore strip and **keeps the content mounted**.
The four inward arrows (`zoom_in_map`) minimize; four outward arrows (`zoom_out_map`) restore the
configured or resized size, **not fullscreen**. Collapsed side titles read bottom-to-top in smaller text.
Click the minimized title, bar or Restore button to restore. Close dismisses the panel without restoring it.

### Required props

| Prop | Purpose |
| --- | --- |
| `title` | Visible heading and accessible name. |
| `children` | Any application-provided React content. |
| `labels` | Translated button names: `{ collapse, expand, close }`. All three strings are required by this API. |

### Optional props

All other DockPanel-specific props are optional. The table below groups them by purpose and shows their types,
defaults and behaviour. Most commonly: `placement`, `size`, `width`, `height`, `maxHeight`,
`footer`, `headerActions`, `onClose` and the minimized-state props.

**Close button:** only providing `onClose` displays an ×. The first example omits it.
The callback requests dismissal; the application handles unsaved changes, removal and focus restoration.
In these examples, closing removes the panel and resets its content when reopened. Minimizing does not.

### Dimensions are not density

`size="small"` and `size="default"` change header/body/footer **padding**, following TEDI Modal.
The title defaults to h3 for default and h4 for small, including its text size when expanded.
Use `headingElement` to override the heading and expanded text size. Minimized titles keep their compact text.
They do not select a width or height and do not shrink the application-provided form controls.

| Preset | Side-panel `width` | `height` (any placement) |
| --- | --- | --- |
| `xs` | 25% of workspace width | 25% of workspace height |
| `sm` | 35% | 35% |
| `md` | 50% | 50% |
| `lg` | 65% | 65% |
| `xl` | 80% | 80% |

Width and height use the **same DockPanel percentage scale**, not fixed-pixel Modal width tokens.
An `xl` side panel leaves 20% of the workspace visible beside it, just as an `xl` top/bottom panel
leaves 20% above or below it. Without resizing enabled, presets resize proportionally with their containing workspace.
You can still use numbers (pixels), CSS lengths and percentages, or `height="auto"`.
Use `width="100%"` or `height="100%"` explicitly when the full available dimension is intended.
Omitted dimensions use side width `xs` (25%), side height 100%, and top/bottom height 50%.
Width is ignored for top/bottom panels, which fill the workspace width.
Custom dimensions remain capped by the workspace but are not restricted to the preset scale.

Enable `resizable` to drag the edge facing the workspace. The configured preset or custom dimension
is measured once to initialize Resizer in pixels. Resizing defaults to 8–92% of the current workspace.
Optional pixel limits (minWidth/maxWidth for sides, minHeight/maxHeight for top/bottom) replace each default independently.
Limits stay within the workspace; the maximum wins if it is smaller than the minimum.
Workspace resizing clamps an invalid saved size and preserves a valid one.
A larger workspace permits further dragging; a saved size within the new limits stays unchanged.
Minimize/restore preserves the resized dimensions and mounted content; closing and remounting
starts again from the configured size.

### Content height and containment

DockPanelLayout needs a defined height, either through its parent or its own style/class.
It is a layout-only companion, not a map component. Its required `children` are the underlying workspace;
its optional `panel` is a DockPanel or a layout-aware wrapper containing it. Omit the panel to close it.
When minimized, it reserves exactly the rail width/height. A custom wrapper must participate in the grid
when collapsed rather than remain absolutely positioned. DockPanel handles this when `resizable` is enabled.
A canvas-based map may need its own documented resize/update call when its container changes size.
`height="auto" maxHeight={512}` limits the expanded side panel to 512 pixels.
At the height limit, the body scrolls independently of the header and footer.
Header controls wrap in narrow panels; very shallow panels scroll the header internally.
A minimized side rail fills the workspace height; restoring it restores the expanded height.
A minimized top/bottom panel is always a compact horizontal strip.

### State and accessibility

Omit `collapsed` for internal state (optionally initialize it with `defaultCollapsed`).
For application-controlled state, pass `collapsed` plus `onCollapsedChange` and apply each requested value.
`defaultCollapsed` is only an initial value, not a command to reset the component.
The minimize/restore button exposes `aria-expanded` and `aria-controls`; hidden content is not tabbable.
Minimize/restore and Close remain grouped, including in the minimized rail.
Example footers contain **Cancel** and **Save** buttons. Cancel closes the example; Save only updates a
local demo status (no backend call). Footer state survives minimization. All actions remain application-owned.
Use `id` to connect external controls, `className`/`style` for styling, and `headingElement` for the heading level.
The required title names the panel.

### Mobile

Below the md breakpoint, DockPanel opens as a TEDI **Sheet**. Use the `mobile` prop to control the presentation:
`true` always displays the Sheet, while `false` always displays the **DockPanel**.
Switching between presentations keeps the content mounted, preserving its state. However, changing `mobile` to or
from `false` remounts the content and resets its state.
In the Sheet presentation, `placement`, `resizable`, `width`, `height` and the min/max props are ignored.
The Sheet adjusts its height to fit its content, up to **90dvh**.
Dragging an expanded Sheet down minimizes it; dragging a minimized Sheet down requests `onClose`.
The minimized Sheet is fixed to the viewport. DockPanelLayout reserves its height automatically. Other layouts can
reserve `var(--sheet-header-height)` while `<html>` has `data-tedi-dock-panel-sheet="minimized"`, or
combine `onPresentationChange` with `onCollapsedChange`.

### Examples

Like the TEDI-Ready Modal documentation, examples begin with **opening buttons** rather than permanently open panels.
**Size**, **Width** and **Height** let you compare variants. **Position** demonstrates all four edges,
including their minimized layouts: use the header controls rather than separate minimized examples.
The remaining examples cover custom dimensions, content height, overflow, controlled state,
external minimization through `minimizeCounter`.
The default preview also provides live Controls. Select a story's Canvas for more preview space.
