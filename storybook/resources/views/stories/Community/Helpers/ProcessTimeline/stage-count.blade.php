@storybook([
    'name' => 'Stage Count',
    'order' => 5,
    'status' => 'stable',
    'args' => [],
])

@php
    $submitted = ['color' => 'success', 'text' => 'Esitatud'];

    $groups = [
        '2 stages' => [
            ['state' => 'completed', 'title' => 'Taotlus esitatud', 'badges' => [$submitted]],
            ['state' => 'active', 'title' => 'Menetlus', 'badges' => []],
        ],
        '5 stages' => [
            ['state' => 'completed', 'title' => 'Pakkumine esitatud', 'badges' => [$submitted]],
            ['state' => 'completed', 'title' => 'Kvalifitseerimine', 'badges' => [['color' => 'success', 'text' => 'Kvalifitseeritud']]],
            ['state' => 'active', 'title' => 'Hindamine', 'badges' => []],
            ['state' => 'upcoming', 'title' => 'Läbirääkimised', 'badges' => []],
            ['state' => 'upcoming', 'title' => 'Tulemused', 'badges' => []],
        ],
    ];
@endphp

<tedi:vertical-spacing :size="2">
    @foreach ($groups as $label => $stages)
        <tedi:vertical-spacing :size="0.5">
            <tedi:text as="p" modifiers="bold">{{ $label }}</tedi:text>
            <tedi:process-timeline>
                @foreach ($stages as $i => $s)
                    <tedi:process-timeline-item :state="$s['state']" :index="$i" :last="$loop->last">
                        <x-slot:title>{{ $s['title'] }}</x-slot:title>
                        @foreach ($s['badges'] as $badge)
                            <tedi:status-badge :color="$badge['color']" :text="$badge['text']" :icon="$badge['color'] === 'success' ? 'check' : ''" />
                        @endforeach
                    </tedi:process-timeline-item>
                @endforeach
            </tedi:process-timeline>
        </tedi:vertical-spacing>
    @endforeach
</tedi:vertical-spacing>
