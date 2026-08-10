@storybook([
    'name' => 'Menu Open',
    'order' => 5,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.46.70?node-id=31693-133265&m=dev',
    'args' => [
        'ariaLabel' => 'Primary navigation',
    ],
    'argTypes' => [
        'ariaLabel' => ['control' => 'text'],
    ],
])

@php
    $groups = [
        'Abielu' => ['Abiellumine', 'Abielu lahutamine', 'Kooselu registreerimine'],
        'Dokumendid' => ['Perekonnasündmuse tõend ja abieluvõimetõend', 'Rahvastikuregistri väljavõte', 'Teatis ja perekonnaseisundi kinnitatud koopia'],
        'Lapse saamine' => ['Lapsendamine', 'Raseduse planeerimine', 'Viljatus ja kunstlik viljastamine'],
        'Sünnitus' => ['Ennetähtaegse lapse sünd ja toetused', 'Erivajadusega lapse sünd', 'Kodusünnitus', 'Lapsest loobumine'],
        'Abi' => ['Kohaliku omavalitsuse sotsiaalabi', 'Kohaliku omavalitsuse sünnitoetus', 'Lasteaiakoht ja selle taotlemine'],
    ];
@endphp

{{-- `open-key` is what upstream derives from the active toggle item on mount;
     here it is explicit, because the nav cannot read its children. --}}
<tedi:top-nav :aria-label="$ariaLabel" open-key="perekond">
    <tedi:top-nav-item href="#">Avaleht</tedi:top-nav-item>
    <tedi:top-nav-item key="perekond" is-active>Perekond</tedi:top-nav-item>
    <tedi:top-nav-item href="#">Hüvitised ja toetused</tedi:top-nav-item>
    <tedi:top-nav-item href="#">Töö ja töösuhted</tedi:top-nav-item>
    <tedi:top-nav-item href="#">Liiklus ja sõidukid</tedi:top-nav-item>
    <tedi:top-nav-item href="#">Minu andmed</tedi:top-nav-item>

    <x-slot:submenu>
        <tedi:top-nav-submenu for="perekond">
            @foreach ($groups as $title => $links)
                <tedi:top-nav-group :title="$title">
                    @foreach ($links as $link)
                        <tedi:top-nav-subitem href="#">{{ $link }}</tedi:top-nav-subitem>
                    @endforeach
                </tedi:top-nav-group>
            @endforeach
        </tedi:top-nav-submenu>
    </x-slot:submenu>
</tedi:top-nav>
