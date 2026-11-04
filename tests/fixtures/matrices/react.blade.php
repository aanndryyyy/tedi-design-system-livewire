{{--
    Variant matrix for the components ported from @tedi-design-system/react
    (CONVENTIONS.md §13).

    Harvested by IntegrityTest::test_rendered_matrix_classes_exist_in_stylesheet,
    which renders this file and asserts every tedi-* class it emits has a rule
    in dist/tedi.css. So the point is COVERAGE of each prop union, not a
    good-looking page: every interpolated class name must appear at least once.
--}}

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
<tedi:file-upload name="failid" accept=".pdf,.txt" :max-size="1024" />
<tedi:file-upload name="failid" accept=".pdf,.txt" :max-size="1024" :show-restrictions="false" />

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
<tedi:multi-value-field name="v" label="Väärtused" label-modifiers="nowrap" :values="['A']" show-clear-on-interaction />

{{-- Form / DateTimeField — all four panel layouts --}}
<tedi:date-time-field :open="true" display="01.01.2026 10:00" />
<tedi:date-time-field :open="true" mode="range" />
<tedi:date-time-field :open="true" layout="multi-step" />
<tedi:date-time-field :open="true" layout="multi-step" step="time" value="2026-01-01 10:00" />
<tedi:date-time-field :open="true" :available-times="['09:00', '09:30']" time-variant="grid" />
<tedi:date-time-field />
<tedi:date-time-field display="01.01.2026 10:00" :clearable="false" />

{{-- Overlay / Sheet (react/src/tedi/components/overlays/sheet) --}}
@foreach ([['default', 'default', 'start'], ['small', 'card', 'center'], ['default', 'none', 'start']] as [$padding, $radius, $titlePosition])
    <tedi:sheet open>
        <tedi:sheet-trigger><tedi:button>Ava</tedi:button></tedi:sheet-trigger>
        <tedi:sheet-content :padding="$padding" :radius="$radius">
            <tedi:sheet-header title="Seaded" :title-position="$titlePosition" collapsible>
                <x-slot:start>A</x-slot:start>
            </tedi:sheet-header>
            <tedi:sheet-body>Sisu</tedi:sheet-body>
            <tedi:sheet-footer full-width>
                <tedi:sheet-closer><tedi:button variant="secondary">Tühista</tedi:button></tedi:sheet-closer>
                <x-slot:end>B</x-slot:end>
            </tedi:sheet-footer>
        </tedi:sheet-content>
    </tedi:sheet>
@endforeach
<tedi:sheet collapsed :close-on-backdrop-click="false">
    <tedi:sheet-content :show-overlay="false" :show-handle="false" :trap-focus="false" :snap-points="[0.4, 0.9]">
        <tedi:sheet-header title="Vali" variant="default" />
        <tedi:sheet-body padding="none">Sisu</tedi:sheet-body>
        @foreach (['left', 'center', 'right'] as $align)
            <tedi:sheet-footer :align="$align">C</tedi:sheet-footer>
        @endforeach
    </tedi:sheet-content>
</tedi:sheet>

{{-- Navigation / CardStepper (react/src/tedi/components/navigation/card-stepper) --}}
@php
    $cardSteps = [
        ['title' => 'Isikuandmed', 'state' => 'completed'],
        ['title' => 'Kontakt', 'description' => 'Kirjeldus', 'state' => 'error', 'sub_steps' => [['title' => 'A', 'current' => true]]],
        ['title' => 'Lisa', 'disabled' => true],
        ['title' => 'Ülevaade'],
    ];
@endphp
<tedi:card-stepper :steps="$cardSteps" :active-step="1" show-status-icon />
<tedi:card-stepper :steps="$cardSteps" :active-step="0" show-navigation counter-position="top" description-position="top" />
<tedi:card-stepper :steps="$cardSteps" :active-step="3" :show-step-number="false" :show-progress="false" :show-step-list="false">
    <x-slot:bottom>Lisa</x-slot:bottom>
</tedi:card-stepper>
