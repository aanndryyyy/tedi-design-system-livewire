{{--
    Variant matrix for tedi:resizer and tedi:split-pane, ported from
    @tedi-design-system/react community/components/map-components (CONVENTIONS.md §13).

    Harvested by IntegrityTest::test_rendered_matrix_classes_exist_in_stylesheet.
    Covers every handle position × show-indicator, every direction ×
    highlighted side, and the close button. The resizer's `direction-*` classes
    are unprefixed, so the harvest skips them; MapComponentsTest covers them.
--}}
@foreach (['right', 'left', 'top', 'bottom'] as $handlePosition)
    @foreach ([false, true] as $showIndicator)
        <tedi:resizer :handle-position="$handlePosition" :show-indicator="$showIndicator">
            <p>Content</p>
        </tedi:resizer>
    @endforeach
@endforeach

@foreach (['horizontal', 'vertical'] as $direction)
    @foreach ([null, 'first', 'second'] as $highlightedSide)
        <div style="height: 20rem">
            <tedi:split-pane :direction="$direction" :highlighted-side="$highlightedSide" :closable="$highlightedSide !== null">
                <x-slot:first><p>First</p></x-slot:first>
                <x-slot:second><p>Second</p></x-slot:second>
            </tedi:split-pane>
        </div>
    @endforeach
@endforeach
