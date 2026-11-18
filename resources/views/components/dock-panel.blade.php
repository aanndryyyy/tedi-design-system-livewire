{{--
    TEDI Dock Panel.
    Port of `DockPanel` in react/src/community/components/dock-panel/dock-panel.tsx
    (CONVENTIONS.md §13; React's community tree).

    A non-modal panel docked to one edge of its workspace. Use it in the
    `panel` slot of `tedi:dock-panel-layout`. Expanded panels cover the
    workspace, and a minimized panel becomes a rail or strip in its own grid
    track. There is no backdrop, focus trap or scroll lock. Opening is the
    application's job: render the panel to show it, and stop rendering it to
    close it. The close button only requests that, by dispatching `tedi:close`.

    MARKUP. The docked panel is upstream's `panel` element, attribute for
    attribute: `role="region"`, `aria-labelledby` pointing at the title,
    `data-placement` / `data-size` / `data-collapsed` / `data-resizable`, and
    the `--dock-panel-*` custom properties that `width`, `height`, `max-width`
    and `max-height` resolve to. The toggle and close controls are
    `tedi:button variant="neutral"` icon-only buttons (React's `Button` with
    `icon`), named by `aria-label` and `title`. The title is a heading at
    `heading-element` (h3, or h4 when `size="small"`). It gets `tabindex="-1"`,
    as React's `Text` gives any element with an id, and core's `text-small`
    while minimized.

    MORE THAN ONE ROOT ELEMENT (a divergence from CONVENTIONS.md §6). Upstream
    returns a fragment: the panel, and, below the md breakpoint, a `Sheet` plus
    `tedi-dock-panel__sheet-spacer`. The vendored layout rules address the
    panel and the spacer with `.tedi-dock-panel-layout > …` child selectors.
    Wrapping them in a root element would break the placement of the minimized
    rail, so this component renders up to three siblings:

      1. the docked panel. It carries `$attributes` and the `tediDockPanel`
         Alpine scope.
      2. a classless `display: contents` wrapper holding the sheet: the
         existing `tedi:sheet-content` / `-header` / `-body` / `-footer`,
         driven by `tediDockPanelSheet`. That is `tediSheet` with dismissal
         rerouted to the panel. Not rendered when `mobile` is false.
      3. the spacer. Not rendered when `mobile` is false.

    The consumer's `class` and `style` are also passed to the sheet panel, as
    upstream passes `className` / `style` to `Sheet.Content`.

    MOBILE PRESENTATION. `mobile` null follows the md breakpoint
    (`matchMedia`, as `tedi:hide-at` does). `mobile` true always uses the sheet,
    and false never does. Upstream's first render is always the docked panel,
    even with `mobile` true, because it decides after mount. So the server always
    renders the docked panel, and `tediDockPanel` switches to the sheet once
    Alpine boots. With `mobile` true, the docked panel is briefly visible before
    that. Switching presentation moves the content, footer and header-action
    nodes between the two shells (upstream's portals into
    `tedi-dock-panel__slot` nodes), so their state survives. Focus inside them
    stays where it was. The panel `id` moves with the content, as upstream's
    does. While the sheet is minimized, `<html>` carries
    `data-tedi-dock-panel-sheet="minimized"`.

    The sheet stays where it is written instead of being portalled to `body`
    (CONVENTIONS.md §11). `.tedi-dock-panel-layout` sets `isolation: isolate`,
    so the fixed sheet's z-index applies inside the layout's stacking context.
    A fixed page header outside the layout can therefore cover it.

    EVENTS, dispatched from the docked panel element and bubbling
    (CONVENTIONS.md §3; upstream's callbacks):
      `tedi:collapsed-change`     detail `{ collapsed }`, upstream's onCollapsedChange
      `tedi:close`                upstream's onClose (the close button is shown when `closable`)
      `tedi:presentation-change`  detail `{ presentation: 'docked'|'sheet' }`, after
                                  init and on each change

    STATE. `collapsed` is the state the server renders. The client changes it
    without waiting for the server, as upstream's uncontrolled mode does. When a
    Livewire re-render brings a different `collapsed` than the previous render,
    the panel adopts it. That is the controlled mode: bind
    `tedi:collapsed-change` back to the property. `minimize-counter` is upstream's
    minimizeCounter: a later change requests minimization while the panel is
    expanded. This includes a change from a Livewire morph or an Alpine
    `x-bind:data-minimize-counter`. The Alpine scope also exposes `toggle()`,
    `minimize()`, `expand()`, `close()` and `requestCollapsed(bool)`.

    RESIZABLE. `resizable` wraps the docked panel as upstream's
    `renderResizable()` does: `tedi-dock-panel__resize-layout` (`data-placement`,
    `data-collapsed`) > `tedi-dock-panel__resize-frame` > `tedi:resizer`
    (`tedi-dock-panel__resizer`, handle on the workspace-facing edge, no
    indicator tab) + the panel. The layout, not the panel, is then the child of
    `tedi:dock-panel-layout`. The server renders upstream's first commit, the
    measurement state: `tedi-dock-panel__resize-measurement`, the panel expanded
    with `data-resizable="false"`, the resizer present but `display: none`, and
    the layout `visibility: hidden` when `collapsed`. `tediDockPanel` then
    measures the panel and its workspace (the layout's offsetParent) and
    starts the resizer at the measured size within the limits.
    `min-width` / `max-width` (sides) and `min-height` / `max-height` (top/bottom)
    are pixel limits. Each defaults to 8% / 92% of the workspace and is capped
    by it; the maximum wins over a larger minimum. A workspace resize updates
    the limits and clamps a saved size only when it falls outside them. The
    resized size survives minimize/restore and Livewire morphs. Like upstream,
    a switch to the sheet and back re-measures, so it starts again from the
    configured size. `max-width` sets no `--dock-panel-max-width` while
    resizable. Without JavaScript a resizable panel stays in the measurement
    state: expanded, or invisible when `collapsed`.

    Upstream also moves the consumer's `zIndex` and the `--dock-panel-gap` /
    `-rail-size` / `-layer` properties from `style` onto the resize layout. Here
    the consumer's `style` stays on the panel, unparsed. Set those on the
    resize layout's parent, or on `tedi:dock-panel-layout`, instead.

    `defaultCollapsed` is folded into `collapsed`, because Blade renders initial
    state either way.
--}}
@props([
    /** Required. Visible heading and accessible name. */
    'title' => null,
    /** Shown instead of the title when the title does not fit on one line. */
    'shortTitle' => null,
    /** Text below the title, hidden while minimized. */
    'description' => null,
    /** Required. Translated control names: ['collapse' => …, 'expand' => …, 'close' => …]. */
    'labels' => null,
    /** left|right|top|bottom — the workspace edge. */
    'placement' => 'right',
    /** default|small — padding density (TEDI Modal tokens). */
    'size' => 'default',
    /** null follows the md breakpoint; true always uses the sheet; false never does. */
    'mobile' => null,
    /** Minimized state, as rendered by the server. */
    'collapsed' => false,
    /** Application-owned counter; a later change requests minimization. */
    'minimizeCounter' => null,
    /** Show the close button, which dispatches `tedi:close` (upstream: passing onClose). */
    'closable' => false,
    /** xs|sm|md|lg|xl preset, a CSS length or a number of pixels. Left/right only. */
    'width' => null,
    /** Enable resizing from the workspace-facing edge (tedi:resizer). */
    'resizable' => false,
    /** Minimum resize width in pixels. Left/right, resizable only. Defaults to 8% of the workspace width. */
    'minWidth' => null,
    /** Maximum width in pixels. Left/right only. Resizable: defaults to 92% of the workspace width. */
    'maxWidth' => null,
    /** xs|sm|md|lg|xl preset, a CSS length, auto or a number of pixels. */
    'height' => null,
    /** Minimum resize height in pixels. Top/bottom, resizable only. Defaults to 8% of the workspace height. */
    'minHeight' => null,
    /** Maximum height in pixels; the body scrolls at it. Resizable top/bottom: defaults to 92% of the workspace height. */
    'maxHeight' => null,
    /** h1–h6. Defaults to h3, or h4 when size is small. */
    'headingElement' => null,
    /** Panel id; generated when omitted. */
    'id' => null,
])

@php
    if (! is_string($title) || $title === '') {
        throw new \InvalidArgumentException('tedi:dock-panel requires a title.');
    }
    if (! is_array($labels) || ! isset($labels['collapse'], $labels['expand'], $labels['close'])) {
        throw new \InvalidArgumentException('tedi:dock-panel requires labels with collapse, expand and close.');
    }

    $presets = ['xs' => '25%', 'sm' => '35%', 'md' => '50%', 'lg' => '65%', 'xl' => '80%'];
    $dimension = fn ($value) => is_int($value) || is_float($value)
        ? $value.'px'
        : ($presets[$value] ?? (string) $value);

    $panelId = $id ?? \Tedi\Livewire\Tedi::id('tedi-dock-panel');
    $titleId = $panelId.'-title';
    $bodyId = $panelId.'-body';
    $isCollapsed = (bool) $collapsed;
    $resizable = (bool) $resizable;
    // Upstream's first commit of a resizable panel is the measurement state, never minimized.
    $panelCollapsed = $isCollapsed && ! $resizable;
    $isSidePanel = $placement === 'left' || $placement === 'right';
    $titleElement = $headingElement ?? ($size === 'small' ? 'h4' : 'h3');
    $toggleLabel = $isCollapsed ? $labels['expand'] : $labels['collapse'];
    $panelToggleLabel = $panelCollapsed ? $labels['expand'] : $labels['collapse'];
    $panelToggleIcon = $panelCollapsed ? 'zoom_out_map' : 'zoom_in_map';

    // Upstream renders content in place only when mobile is false. Otherwise it
    // is portalled through `tedi-dock-panel__slot` nodes so it can move into the sheet.
    $withSheet = $mobile !== false;
    $hasHeaderActions = isset($headerActions) && trim((string) $headerActions) !== '';
    $hasFooter = isset($footer) && trim((string) $footer) !== '';
    $hasDescription = $description !== null && $description !== '';
    $hasShortTitle = $shortTitle !== null && $shortTitle !== '';

    $styles = [];
    if ($width !== null) {
        $styles[] = '--dock-panel-width: '.$dimension($width);
    }
    if (! $resizable && $isSidePanel && $maxWidth !== null) {
        $styles[] = '--dock-panel-max-width: '.$maxWidth.'px';
    }
    if ($height !== null) {
        $styles[] = '--dock-panel-height: '.$dimension($height);
    }
    if ($maxHeight !== null) {
        $styles[] = '--dock-panel-max-height: '.$maxHeight.'px';
    }

    $config = [
        'panelId' => $panelId,
        'mobile' => $mobile === null ? null : (bool) $mobile,
        'collapsed' => $isCollapsed,
        'labels' => ['collapse' => $labels['collapse'], 'expand' => $labels['expand']],
        'resizable' => $resizable,
    ];
    $resizeHandle = ['left' => 'right', 'right' => 'left', 'top' => 'bottom', 'bottom' => 'top'][$placement] ?? 'left';
    $number = fn ($value) => $value === null || $value === '' ? null : $value + 0;
    if ($resizable) {
        $config += [
            'placement' => $placement,
            'resizeHandle' => $resizeHandle,
            'minWidth' => $number($minWidth),
            'maxWidth' => $number($maxWidth),
            'minHeight' => $number($minHeight),
            'maxHeight' => $number($maxHeight),
            // The resize layout's expanded height and max-height (upstream's resizeLayoutStyle).
            'layoutHeight' => $isSidePanel && $height !== null ? $dimension($height) : null,
            'layoutMaxHeight' => $maxHeight !== null ? $maxHeight.'px' : null,
        ];
    }

    $rootAttributes = $attributes->class(['tedi-dock-panel'])->merge(array_filter([
        'id' => $panelId,
        'role' => 'region',
        'aria-labelledby' => $titleId,
        'data-placement' => $placement,
        'data-size' => $size,
        'data-collapsed' => $panelCollapsed ? 'true' : 'false',
        'data-resizable' => 'false',
        // The state the server asked for; data-collapsed is the measurement state's false.
        'data-tedi-dock-panel-collapsed' => $resizable ? ($isCollapsed ? 'true' : 'false') : null,
        'data-minimize-counter' => $minimizeCounter === null ? null : (string) $minimizeCounter,
    ], fn ($value) => $value !== null));
    // ->style([]) would print an empty style="".
    $rootAttributes = $styles ? $rootAttributes->style($styles) : $rootAttributes;

    $fittingTitle = function () use ($title, $shortTitle) {
        return new \Illuminate\Support\HtmlString(
            '<span class="tedi-dock-panel__fitting-title" data-tedi-dock-panel-fit'
            .' data-title="'.e($title).'" data-short-title="'.e($shortTitle).'">'.e($title)
            .'<span aria-hidden="true" class="tedi-dock-panel__title-measure">'.e($title).'</span></span>'
        );
    };
    $titleContent = $hasShortTitle ? $fittingTitle() : $title;
@endphp

@if ($resizable)
<div class="tedi-dock-panel__resize-layout tedi-dock-panel__resize-measurement" data-placement="{{ $placement }}" data-collapsed="false" @if ($isCollapsed) style="visibility: hidden" @endif data-tedi-dock-panel-resize="{{ $panelId }}">
<div class="tedi-dock-panel__resize-frame">
<tedi:resizer class="tedi-dock-panel__resizer" :handle-position="$resizeHandle" style="display: none" />
@endif
<div {{ $rootAttributes }} data-tedi-dock-panel="{{ $panelId }}" x-data="tediDockPanel(@js($config))">
    <div class="tedi-dock-panel__header">
        <div class="tedi-dock-panel__header-row">
            <{{ $titleElement }} id="{{ $titleId }}" tabindex="-1" @class(['tedi-dock-panel__title', 'text-small' => $panelCollapsed]) data-tedi-dock-panel-title>{{ $titleContent }}</{{ $titleElement }}>

            @if ($hasHeaderActions)
                <div class="tedi-dock-panel__header-actions" data-tedi-dock-panel-host="actions" @if ($panelCollapsed) hidden @endif>@if ($withSheet)<div class="tedi-dock-panel__slot" data-tedi-dock-panel-slot="actions">{{ $headerActions }}</div>@else{{ $headerActions }}@endif</div>
            @endif

            <div class="tedi-dock-panel__controls">
                <tedi:button
                    class="tedi-dock-panel__toggle"
                    variant="neutral"
                    :icon-start="$panelToggleIcon"
                    icon-only
                    :title="$panelToggleLabel"
                    :aria-label="$panelToggleLabel"
                    aria-expanded="{{ $panelCollapsed ? 'false' : 'true' }}"
                    aria-controls="{{ $bodyId }}"
                    data-tedi-dock-panel-toggle="docked"
                    x-on:click="toggle()"
                />

                @if ($closable)
                    <tedi:button
                        class="tedi-dock-panel__close"
                        variant="neutral"
                        icon-start="close"
                        icon-only
                        :title="$labels['close']"
                        :aria-label="$labels['close']"
                        x-on:click="close()"
                    />
                @endif
            </div>
        </div>
        @if ($hasDescription)
            <div class="tedi-dock-panel__description" data-tedi-dock-panel-collapsible @if ($panelCollapsed) hidden @endif>{{ $description }}</div>
        @endif
    </div>

    <div id="{{ $bodyId }}" class="tedi-dock-panel__body" data-tedi-dock-panel-collapsible @if ($panelCollapsed) hidden @endif>
        <div class="tedi-dock-panel__scroll-area">
            <div class="tedi-dock-panel__content" data-tedi-dock-panel-host="content">@if ($withSheet)<div class="tedi-dock-panel__slot" data-tedi-dock-panel-slot="content">{{ $slot }}</div>@else{{ $slot }}@endif</div>
        </div>
        @if ($hasFooter)
            <div class="tedi-dock-panel__footer" data-tedi-dock-panel-host="footer">@if ($withSheet)<div class="tedi-dock-panel__slot" data-tedi-dock-panel-slot="footer">{{ $footer }}</div>@else{{ $footer }}@endif</div>
        @endif
    </div>
</div>
@if ($resizable)
</div>
</div>
@endif
@if ($withSheet)
<div style="display: contents" data-tedi-dock-panel-sheet="{{ $panelId }}" x-data="tediDockPanelSheet(@js(['panelId' => $panelId]))">
    <tedi:sheet-content
        :show-overlay="false"
        :trap-focus="false"
        :lock-scroll="false"
        :return-focus="false"
        :padding="$size"
        {{ $attributes->only(['class', 'style']) }}
    >
        <tedi:sheet-header :heading-level="$titleElement" variant="default" :close-button="false">
            <x-slot:title>{{ $titleContent }}</x-slot:title>
            <x-slot:end>
                @if ($hasHeaderActions)
                    <div class="tedi-dock-panel__sheet-actions" data-tedi-dock-panel-host="actions" @if ($isCollapsed) hidden @endif></div>
                @endif
            </x-slot:end>
            <x-slot:actions>
                <tedi:collapse-button
                    class="tedi-dock-panel__sheet-toggle"
                    hide-text
                    state="expanded"
                    :open="! $isCollapsed"
                    :aria-label="$toggleLabel"
                    :aria-controls="$panelId"
                    data-tedi-dock-panel-toggle="sheet"
                />
                @if ($closable)
                    <tedi:closing-button :aria-label="$labels['close']" x-on:click="dockClose()" />
                @endif
            </x-slot:actions>
        </tedi:sheet-header>
        <tedi:sheet-body class="tedi-dock-panel__sheet-body">
            @if ($hasDescription)
                <div class="tedi-dock-panel__description tedi-dock-panel__sheet-description">{{ $description }}</div>
            @endif
            <div data-tedi-dock-panel-host="content"></div>
        </tedi:sheet-body>
        @if ($hasFooter)
            <tedi:sheet-footer full-width>
                <div data-tedi-dock-panel-host="footer"></div>
            </tedi:sheet-footer>
        @endif
    </tedi:sheet-content>
</div>
<div class="tedi-dock-panel__sheet-spacer" style="display: none" data-tedi-dock-panel-spacer="{{ $panelId }}"></div>
@endif
