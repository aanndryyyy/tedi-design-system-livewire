{{--
    TEDI Sheet Content.
    Port of react/src/tedi/components/overlays/sheet/components/sheet-content/sheet-content.tsx
    (CONVENTIONS.md §13).

    The backdrop (`tedi-sheet__overlay`) and the panel (`tedi-sheet__panel`),
    plus the drag handle. Behaviour is `tediSheet`'s (resources/js/src/sheet.js);
    this template hands it its options through `register()`.

    THE OVERLAY'S INLINE STYLE is upstream's: Floating UI's `FloatingOverlay`
    writes `position: fixed; inset: 0` inline, and upstream overrides its
    `overflow` with `clip`. TEDI's stylesheet positions nothing itself, so
    without these the sheet would render in the document flow.

    ALWAYS MOUNTED. Upstream unmounts the panel while closed unless
    `keepMounted` is set. CONVENTIONS.md §8 requires the panel in the DOM with
    its real class list, so this always behaves as `keepMounted` does: while
    closed the overlay carries `tedi-sheet__overlay--hidden` and the panel is
    `hidden`, and content state survives a close. `keepMounted` is therefore not
    declared. `data-status` follows upstream's transition statuses —
    `unmounted` while closed, `initial` → `open` on the way in, `close` on the
    way out.

    THE DIALOG'S NAME. Upstream's header registers its title with the sheet,
    which then points `aria-labelledby` at it. Blade renders this component's
    slot before its own template, so the slot is already HTML here: the title
    `tedi:sheet-header` rendered is found in it by its `tedi-sheet__title`
    class and its id used. `aria-labelledby` overrides that, and `aria-label`
    applies only when there is neither — as upstream.

    `snap-points` are fractions of the viewport height; the panel's height,
    `min-height` and `max-height` are written inline and then owned by
    `tediSheet`, which recomputes them while dragging and on collapse.

    NOT PORTED. `initialFocus` (the first tabbable element always receives
    focus, upstream's default), `onSnapPointChange` (CONVENTIONS.md §7 item 2),
    the breakpoint form of `radius` (§7 item 1), and Floating UI's portal and
    focus guards (see resources/js/src/sheet.js).
--}}
@props([
    /** Panel id. Auto-generated when omitted. */
    'id' => null,
    /** Show the drag handle; the handle and the header then drag the sheet. */
    'showHandle' => true,
    /** Keep Tab inside the sheet while it is open. */
    'trapFocus' => true,
    /** Return focus to the previously focused element on close. */
    'returnFocus' => true,
    /** Render the dimmed backdrop. */
    'showOverlay' => true,
    /** Lock page scroll while open. */
    'lockScroll' => true,
    /** Rest heights as fractions of the viewport, e.g. [0.4, 0.9]. */
    'snapPoints' => null,
    /** Index into the sorted snap points to open at. Defaults to the tallest. */
    'defaultSnapPoint' => null,
    /** Render visually hidden dismiss buttons before and after the panel (only with trap-focus). */
    'visuallyHiddenDismiss' => true,
    /** Id of the element naming the dialog. Defaults to the header title. */
    'ariaLabelledby' => null,
    /** Accessible name when nothing labels the dialog. */
    'ariaLabel' => null,
    /** Minimum panel height, any CSS length. */
    'minHeight' => null,
    /** Maximum panel height, any CSS length. Ignored with snap points. */
    'maxHeight' => null,
    /** default|small — the sheet-wide padding scale. */
    'padding' => 'default',
    /** default|card|none — the top-corner radius. */
    'radius' => 'default',
])

@aware([
    /** Matches tedi:sheet's default. */
    'open' => false,
    /** Matches tedi:sheet's default. */
    'collapsed' => false,
    /** Matches tedi:sheet's default. */
    'role' => 'dialog',
])

@php
    $id = $id ?? \Tedi\Livewire\Tedi::id('tedi-sheet');
    $isOpen = (bool) $open;
    $isCollapsed = (bool) $collapsed;

    // The header's title id, read back out of the rendered slot.
    $titleId = null;
    if (preg_match_all('/<h[1-6]\b[^>]*>/', (string) $slot, $headings)) {
        foreach ($headings[0] as $heading) {
            if (preg_match('/class="[^"]*\btedi-sheet__title\b/', $heading) && preg_match('/\bid="([^"]+)"/', $heading, $match)) {
                $titleId = $match[1];
                break;
            }
        }
    }
    $labelledBy = $ariaLabelledby ?? $titleId;

    // The initial value of tediSheet's panelStyle getter.
    $snaps = $snapPoints ? array_values($snapPoints) : [];
    sort($snaps);
    $hasSnaps = count($snaps) > 0 && ! $isCollapsed;
    $dynamic = [];
    if ($minHeight !== null && ! $isCollapsed) {
        $dynamic[] = 'min-height: '.$minHeight;
    }
    if ($maxHeight !== null && ! $hasSnaps) {
        $dynamic[] = 'max-height: '.$maxHeight;
    }
    if ($hasSnaps) {
        $index = $defaultSnapPoint ?? count($snaps) - 1;
        $currentSnap = $snaps[max(0, min($index, count($snaps) - 1))];
        $dynamic[] = 'max-height: '.(end($snaps) * 100).'dvh';
        $dynamic[] = 'height: '.($currentSnap * 100).'dvh';
    }

    $radiusOverride = match ($radius) {
        'card' => 'var(--card-radius-rounded)',
        'none' => '0',
        default => null,
    };

    $config = [
        'showHandle' => (bool) $showHandle,
        'trapFocus' => (bool) $trapFocus,
        'returnFocus' => (bool) $returnFocus,
        'lockScroll' => (bool) $lockScroll,
        'snapPoints' => $snaps ?: null,
        'defaultSnapPoint' => $defaultSnapPoint,
        'minHeight' => $minHeight,
        'maxHeight' => $maxHeight,
    ];

    $dismiss = $trapFocus && $visuallyHiddenDismiss;

    $panelStyles = array_merge($radiusOverride !== null ? ['--tedi-sheet-radius: '.$radiusOverride] : [], $dynamic);
    $panelAttributes = $attributes->class([
        'tedi-sheet__panel',
        'tedi-sheet__panel--padding-small' => $padding === 'small',
        'tedi-sheet__panel--draggable' => (bool) $showHandle,
    ]);
    // ->style([]) would print an empty style="".
    $panelAttributes = $panelStyles ? $panelAttributes->style($panelStyles) : $panelAttributes;
@endphp

<div
    @class([
        'tedi-sheet__overlay',
        'tedi-sheet__overlay--no-overlay' => ! $showOverlay,
        'tedi-sheet__overlay--hidden' => ! $isOpen,
    ])
    style="position: fixed; inset: 0; overflow: clip"
    data-status="{{ $isOpen ? 'open' : 'unmounted' }}"
    x-bind:class="{ 'tedi-sheet__overlay--hidden': ! active }"
    x-bind:data-status="status"
>
    @if ($dismiss)
        <button type="button" tabindex="-1" class="sr-only" data-tedi-sheet-dismiss x-show="active" x-on:click="hide()">{{ __('tedi::tedi.sheet.close') }}</button>
    @endif
    <div
        id="{{ $id }}"
        role="{{ $role }}"
        tabindex="-1"
        aria-modal="{{ $trapFocus ? 'true' : 'false' }}"
        @if ($labelledBy) aria-labelledby="{{ $labelledBy }}" @elseif ($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
        @if (! $isOpen) hidden @endif
        data-status="{{ $isOpen ? 'open' : 'unmounted' }}"
        {{ $panelAttributes }}
        x-ref="panel"
        x-init="register(@js($config))"
        x-bind:hidden="! active"
        x-bind:data-status="status"
        x-bind:style="panelStyle"
        x-on:keydown="onPanelKeydown($event)"
        x-on:pointerdown="dragStart($event)"
        x-on:pointermove="dragMove($event)"
        x-on:pointerup="dragEnd($event)"
        x-on:pointercancel="dragCancel($event)"
        x-on:lostpointercapture="dragCancel($event)"
        x-on:contextmenu="resetDrag()"
    >
        @if ($showHandle)
            <div class="tedi-sheet__handle-zone" data-name="sheet-handle" aria-hidden="true"><span class="tedi-sheet__handle"></span></div>
        @endif
        {{ $slot }}
    </div>
    @if ($dismiss)
        <button type="button" tabindex="-1" class="sr-only" data-tedi-sheet-dismiss x-show="active" x-on:click="hide()">{{ __('tedi::tedi.sheet.close') }}</button>
    @endif
</div>
