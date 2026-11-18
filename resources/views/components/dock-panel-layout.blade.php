{{--
    TEDI Dock Panel Layout.
    Port of `DockPanelLayout` in
    react/src/community/components/dock-panel/dock-panel.tsx (CONVENTIONS.md §13).

    The workspace grid that `tedi:dock-panel` sits in. The default slot is the
    underlying workspace (upstream's `children`). The `panel` slot takes one
    `tedi:dock-panel` (upstream's `panel`); leave it out to close the panel.
    Expanded panels overlay the workspace, and minimized panels reserve a grid
    track beside it. The layout needs a defined height, from its parent or its
    own `style` / `class`.

    The panel must be a direct child of this element: the vendored stylesheet
    places the minimized rail and the sheet spacer with
    `.tedi-dock-panel-layout > …` child selectors. So `tedi:dock-panel` renders
    its panel, sheet and spacer as siblings (see its header).
--}}
<div {{ $attributes->class(['tedi-dock-panel-layout']) }}>
    <div class="tedi-dock-panel-layout__content">{{ $slot }}</div>
    {{ $panel ?? '' }}
</div>
