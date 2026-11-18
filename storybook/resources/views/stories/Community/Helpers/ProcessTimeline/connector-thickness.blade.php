@storybook([
    'name' => 'Connector Thickness',
    'order' => 4,
    'status' => 'stable',
    'args' => [],
])

@php
    $stages = [
        ['state' => 'completed', 'title' => 'Pakkumine esitatud', 'badges' => [['color' => 'success', 'text' => 'Esitatud']]],
        ['state' => 'completed', 'title' => 'Kvalifitseerimine', 'badges' => [['color' => 'success', 'text' => 'Kvalifitseeritud']]],
        ['state' => 'upcoming', 'title' => 'Tulemused', 'badges' => []],
    ];
@endphp

<tedi:vertical-spacing :size="2">
    @foreach ([1, 2] as $thickness)
        <tedi:vertical-spacing :size="0.5">
            <tedi:text as="p" modifiers="bold">{{ $thickness }}px</tedi:text>
            <tedi:process-timeline :connector-thickness="$thickness">
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
