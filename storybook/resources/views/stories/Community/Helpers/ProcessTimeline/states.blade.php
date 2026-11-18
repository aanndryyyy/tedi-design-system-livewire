{{--
    Upstream's row is `cols="1" [sm]="{ cols: 6 }"`. Breakpoint inputs are not
    ported (CONVENTIONS.md §7.1), so the row is six columns at every width.
--}}
@storybook([
    'name' => 'States',
    'order' => 2,
    'status' => 'stable',
    'args' => [],
])

@php
    $states = [
        ['state' => 'completed', 'label' => 'Completed'],
        ['state' => 'active', 'label' => 'Active'],
        ['state' => 'upcoming', 'label' => 'Upcoming'],
    ];
@endphp

<tedi:row :cols="1" :gap-y="3">
    @foreach ($states as $item)
        <tedi:row :cols="6" align-items="center">
            <tedi:col :width="1">
                <tedi:text as="p" modifiers="bold">{{ $item['label'] }}</tedi:text>
            </tedi:col>
            <tedi:col :width="5">
                <tedi:process-timeline orientation="horizontal">
                    <tedi:process-timeline-item :state="$item['state']" last>
                        <x-slot:title>Etapi pealkiri</x-slot:title>
                    </tedi:process-timeline-item>
                </tedi:process-timeline>
            </tedi:col>
        </tedi:row>
    @endforeach
</tedi:row>
