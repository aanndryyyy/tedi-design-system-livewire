{{--
    Variant matrix for the components ported from @tedi-design-system/react
    (CONVENTIONS.md §13).

    Harvested by IntegrityTest::test_rendered_matrix_classes_exist_in_stylesheet,
    which renders this file and asserts every tedi-* class it emits has a rule
    in dist/tedi.css. So the point is COVERAGE of each prop union, not a
    good-looking page: every interpolated class name must appear at least once.
--}}

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
    <tedi:heading-with-icon :element="$element" name="info">Pealkiri</tedi:heading-with-icon>
@endforeach
<tedi:heading-with-icon>Ilma ikoonita</tedi:heading-with-icon>

{{-- Content / Section --}}
@foreach (['section', 'article', 'aside', 'div'] as $as)
    <tedi:section :as="$as">Sisu</tedi:section>
@endforeach

{{-- Helpers / StretchContent --}}
@foreach (['both', 'horizontal', 'vertical'] as $direction)
    <tedi:stretch-content :direction="$direction"><p>Venitatud</p></tedi:stretch-content>
@endforeach

{{-- Helpers / Affix — every offset on the scale, on every side, in both modes --}}
@foreach (['0', '0.5', '1', '1.5', '2', 'unset'] as $offset)
    <tedi:affix position="fixed" :top="$offset">Fikseeritud</tedi:affix>
    <tedi:affix position="fixed" :bottom="$offset">Fikseeritud</tedi:affix>
    <tedi:affix position="fixed" :left="$offset">Fikseeritud</tedi:affix>
    <tedi:affix position="fixed" :right="$offset">Fikseeritud</tedi:affix>
    <tedi:affix :bottom="$offset">Kleepuv</tedi:affix>
@endforeach
<tedi:affix>Kleepuv vaikimisi</tedi:affix>

{{-- Helpers / ScrollVisibility --}}
@foreach (['left', 'right', 'up', 'down', 'center'] as $animationDirection)
    <tedi:scroll-visibility :animation-direction="$animationDirection">Peidetav</tedi:scroll-visibility>
@endforeach

{{-- Content / Truncate --}}
<tedi:truncate :content="str_repeat('Pikk tekst. ', 40)" :max-length="60" />
<tedi:truncate :content="str_repeat('Pikk tekst. ', 40)" :max-length="60" :expandable="false" />
<tedi:truncate content="Lühike" />

{{-- Navigation / HashTrigger --}}
<tedi:hash-trigger id="ptk-1">Peatükk</tedi:hash-trigger>

{{-- Layout / TopNav — both fits, both item branches, active and disabled --}}
<tedi:top-nav aria-label="Peamenüü" open-key="teenused">
    <tedi:top-nav-item href="/">Avaleht</tedi:top-nav-item>
    <tedi:top-nav-item href="/kontakt" is-active>Kontakt</tedi:top-nav-item>
    <tedi:top-nav-item href="/arhiiv" disabled>Arhiiv</tedi:top-nav-item>
    <tedi:top-nav-separator />
    <tedi:top-nav-item key="teenused" icon="apps" is-active>Teenused</tedi:top-nav-item>
    <tedi:top-nav-item key="abi" disabled>Abi</tedi:top-nav-item>

    <x-slot:submenu>
        <tedi:top-nav-submenu for="teenused">
            <tedi:top-nav-group title="Registrid" icon="folder" heading-level="h2">
                <tedi:top-nav-subitem href="/a" is-active>Äriregister</tedi:top-nav-subitem>
                <tedi:top-nav-subitem href="/b">Kinnistusraamat</tedi:top-nav-subitem>
            </tedi:top-nav-group>
            <tedi:top-nav-group>
                <tedi:top-nav-subitem href="/c">Ilma pealkirjata</tedi:top-nav-subitem>
            </tedi:top-nav-group>
        </tedi:top-nav-submenu>
    </x-slot:submenu>
</tedi:top-nav>

<tedi:top-nav submenu-fit="content" aria-label="Sisumenüü" max-width="none">
    <tedi:top-nav-item key="a">Avatav
        <x-slot:submenu>
            <tedi:top-nav-group title="Rühm">
                <tedi:top-nav-subitem href="/x">X</tedi:top-nav-subitem>
            </tedi:top-nav-group>
        </x-slot:submenu>
    </tedi:top-nav-item>
</tedi:top-nav>

{{-- Form / FileUpload — every state modifier, both list shapes, read-only --}}
@php
    $twoFiles = [['name' => 'aruanne.pdf'], ['name' => 'vigane.pdf', 'is_valid' => false]];
@endphp
<tedi:file-upload name="failid" label="Manused" :files="$twoFiles" />
<tedi:file-upload name="failid" :files="$twoFiles" :helper="['text' => 'Viga', 'type' => 'error']" />
<tedi:file-upload name="failid" :files="$twoFiles" :helper="['text' => 'Korras', 'type' => 'valid']" />
<tedi:file-upload name="failid" :files="$twoFiles" :helper="['text' => 'Vihje', 'type' => 'hint']" />
<tedi:file-upload name="failid" :files="$twoFiles" disabled />
<tedi:file-upload name="failid" :files="[['name' => 'ainus.pdf', 'is_loading' => true]]" size="small" />
<tedi:file-upload name="failid" :files="$twoFiles" read-only />
<tedi:file-upload name="failid" />

{{-- Form / MultiValueField — both directions, both icon shapes --}}
@foreach (['stack', 'row'] as $tagsDirection)
    @foreach (['primary', 'secondary', 'danger'] as $tagColor)
        <tedi:multi-value-field
            name="v"
            label="Väärtused"
            :values="['Harjumaa', 'Tartumaa', 'Pärnumaa']"
            :tags-direction="$tagsDirection"
            :tag-color="$tagColor"
            :visible-count="2"
            icon="expand_more"
        />
    @endforeach
@endforeach
<tedi:multi-value-field name="v" :values="['A']" icon="expand_more" icon-is-button :icon-button-attributes="['aria-expanded' => 'false']" />
<tedi:multi-value-field name="v" :values="[]" />
<tedi:multi-value-field name="v" :values="['A']" disabled />

{{-- Form / DateTimeField — all four panel layouts --}}
<tedi:date-time-field :open="true" display="01.01.2026 10:00" />
<tedi:date-time-field :open="true" mode="range" />
<tedi:date-time-field :open="true" layout="multi-step" />
<tedi:date-time-field :open="true" layout="multi-step" step="time" value="2026-01-01 10:00" />
<tedi:date-time-field :open="true" :available-times="['09:00', '09:30']" time-variant="grid" />
<tedi:date-time-field />
