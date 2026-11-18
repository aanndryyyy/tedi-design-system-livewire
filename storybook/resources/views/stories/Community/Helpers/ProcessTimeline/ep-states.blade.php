@storybook([
    'name' => 'EP states',
    'order' => 3,
    'status' => 'stable',
    'args' => [],
])

@php
    $submitted = ['color' => 'success', 'text' => 'Esitatud'];
    $qualified = ['color' => 'success', 'text' => 'Kvalifitseeritud'];
    $published = ['color' => 'success', 'text' => 'Avalikustatud'];
    $disqualified = ['color' => 'danger', 'text' => 'Diskvalifitseeritud'];

    $stage = fn (string $state, string $title, array $badges = []) => compact('state', 'title', 'badges');

    $epStates = [
        ['name' => 'Esitatud', 'stages' => [
            $stage('completed', 'Pakkumine esitatud', [$submitted]),
            $stage('upcoming', 'Kvalifitseerimine'),
            $stage('upcoming', 'Tulemused'),
        ]],
        ['name' => 'Kinnituse ootel', 'stages' => [
            $stage('completed', 'Pakkumine esitatud', [$submitted, ['color' => 'warning', 'text' => 'Kinnituse ootel']]),
            $stage('upcoming', 'Kvalifitseerimine'),
            $stage('upcoming', 'Tulemused'),
        ]],
        ['name' => 'Kinnitatud', 'stages' => [
            $stage('completed', 'Pakkumine esitatud', [$submitted, ['color' => 'success', 'text' => 'Kinnitatud']]),
            $stage('upcoming', 'Kvalifitseerimine'),
            $stage('upcoming', 'Tulemused'),
        ]],
        ['name' => 'Kvalifitseeritud', 'stages' => [
            $stage('completed', 'Pakkumine esitatud', [$submitted]),
            $stage('completed', 'Kvalifitseerimine', [$qualified]),
            $stage('upcoming', 'Tulemused'),
        ]],
        ['name' => 'Tulemused', 'stages' => [
            $stage('completed', 'Pakkumine esitatud', [$submitted]),
            $stage('completed', 'Kvalifitseerimine', [$qualified]),
            $stage('completed', 'Tulemused', [$published]),
        ]],
        ['name' => 'Diskvalifitseeritud', 'stages' => [
            $stage('completed', 'Pakkumine esitatud', [$submitted]),
            $stage('completed', 'Kvalifitseerimine', [$disqualified]),
            $stage('completed', 'Tulemused', [$published]),
        ]],
        ['name' => 'Nurjunud', 'stages' => [
            $stage('completed', 'Pakkumine esitatud', [$submitted, ['color' => 'danger', 'text' => 'Nurjunud']]),
            $stage('completed', 'Kvalifitseerimine', [$disqualified]),
            $stage('upcoming', 'Tulemused'),
        ]],
        ['name' => 'Tühistatud', 'stages' => [
            $stage('completed', 'Pakkumine esitatud', [$submitted, ['color' => 'danger', 'text' => 'Tühistatud']]),
            $stage('completed', 'Kvalifitseerimine', [$disqualified]),
            $stage('upcoming', 'Tulemused'),
        ]],
    ];
@endphp

<tedi:vertical-spacing :size="2">
    @foreach ($epStates as $ep)
        <tedi:vertical-spacing :size="0.5">
            <tedi:text as="p" modifiers="bold">{{ $ep['name'] }}</tedi:text>
            <tedi:process-timeline>
                @foreach ($ep['stages'] as $i => $s)
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
