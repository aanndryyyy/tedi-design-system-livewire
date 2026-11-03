{{--
    Variant matrix for the components upstream promoted into Angular's
    `src/tedi/` tree: floating-button and table-of-contents (formerly
    `community/`), skeleton, heading-with-icon and truncate (formerly React).
    print emits only core's unprefixed utilities and has no matrix entry.

    Harvested by IntegrityTest::test_rendered_matrix_classes_exist_in_stylesheet,
    which renders this file and asserts every tedi-* class it emits has a rule
    in dist/tedi.css. So the point is COVERAGE of each prop union, not a
    good-looking page: every interpolated class name must appear at least once.
--}}

{{-- Buttons / FloatingButton — variant × size × axis, icon padding, positioning --}}
@foreach (['primary', 'secondary'] as $variant)
    @foreach (['default', 'large'] as $size)
        @foreach (['horizontal', 'vertical'] as $axis)
            <tedi:floating-button position="static" :variant="$variant" :size="$size" :axis="$axis">Tagasiside</tedi:floating-button>
        @endforeach
    @endforeach
@endforeach
<tedi:floating-button position="static" icon-start="add">Lisa</tedi:floating-button>
<tedi:floating-button position="static" icon-end="arrow_forward">Edasi</tedi:floating-button>
<tedi:floating-button position="static" size="large" icon-only icon-start="add" aria-label="Lisa" />
<tedi:floating-button position="static" disabled>Tagasiside</tedi:floating-button>
@foreach (['fixed', 'absolute', 'sticky', 'relative'] as $position)
    <tedi:floating-button :position="$position" :placement="['vertical' => 'bottom', 'horizontal' => 'center']" :offset="['bottom' => 16]" :z-index="1">Tagasiside</tedi:floating-button>
@endforeach

{{-- Navigation / TableOfContents — variant × bordered × sticky × numbered, heading levels, headless --}}
@foreach (['default', 'transparent'] as $variant)
    @foreach ([false, true] as $bordered)
        @foreach ([false, true] as $sticky)
            <tedi:table-of-contents :variant="$variant" :bordered="$bordered" :sticky="$sticky" :numbered="$bordered" active-id="ptk-2-1">
                <tedi:table-of-contents-item item-id="ptk-1" :separator="true">
                    <tedi:link href="#ptk-1" :underline="false">Üldsätted</tedi:link>
                    <x-slot:suffix><tedi:tag>3</tedi:tag></x-slot:suffix>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="ptk-2">
                    <tedi:link href="#ptk-2" :underline="false">Rakendusala</tedi:link>
                    <x-slot:sub-items>
                        <tedi:table-of-contents-item item-id="ptk-2-1">
                            <tedi:link href="#ptk-2-1" :underline="false">Erisused</tedi:link>
                        </tedi:table-of-contents-item>
                    </x-slot:sub-items>
                </tedi:table-of-contents-item>
            </tedi:table-of-contents>
        @endforeach
    @endforeach
@endforeach
@foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $level)
    <tedi:table-of-contents heading="Sisukord" :heading-level="$level" :default-open="false">
        <tedi:table-of-contents-item item-id="a">A</tedi:table-of-contents-item>
    </tedi:table-of-contents>
@endforeach
<tedi:table-of-contents heading="">
    <tedi:table-of-contents-item item-id="a">A</tedi:table-of-contents-item>
</tedi:table-of-contents>
@foreach ([true, false] as $sticky)
    <tedi:table-of-contents-collapsible :sticky="$sticky" :bordered="! $sticky" :numbered="true" active-id="a">
        <tedi:table-of-contents-item item-id="a">A</tedi:table-of-contents-item>
    </tedi:table-of-contents-collapsible>
@endforeach

{{-- Loader / Skeleton --}}
<tedi:skeleton>
    @foreach (['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $height)
        <tedi:skeleton-block :height="$height" :width="60" />
    @endforeach
    <tedi:skeleton-block width="80px" :height="12" />
    <tedi:skeleton-block />
</tedi:skeleton>

{{-- Content / HeadingWithIcon --}}
@foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $element)
    <tedi:heading-with-icon :element="$element" icon="info">Pealkiri</tedi:heading-with-icon>
@endforeach
<tedi:heading-with-icon icon="info" icon-variant="filled" modifiers="h2">Pealkiri</tedi:heading-with-icon>

{{-- Content / Truncate — collapsed, expanded, not expandable --}}
<tedi:truncate text="Lorem ipsum dolor sit amet, consectetur adipiscing elit." :max-length="20" />
<tedi:truncate text="Lorem ipsum dolor sit amet, consectetur adipiscing elit." :max-length="20" :expanded="true" />
<tedi:truncate text="Lorem ipsum dolor sit amet, consectetur adipiscing elit." :max-length="20" :expandable="false" />
