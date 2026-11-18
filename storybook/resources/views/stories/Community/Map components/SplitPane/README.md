> **In this port** the React props below map to Blade as follows: `first` / `second` → the `first` / `second` named slots; `onClose` → `closable` plus the `tedi:close` event; `onRatioChange` → the `tedi:ratio-change` event (`detail.ratio`).

<a href="https://www.figma.com/design/3DIVbgDcC0R4qgqWhZMfvw/TEDI-Map-Components-1.1.1?node-id=347-72394&t=UN6CIlRIfpadwwCZ-0" target="_BLANK">Figma ↗</a>

`SplitPane` renders two panes separated by a draggable divider. Dragging the divider (mouse, touch,
or keyboard) reallocates the size ratio between the panes, clamped to 20–80%.
