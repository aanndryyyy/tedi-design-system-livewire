@storybook([
    'name' => 'Sizes',
    'order' => 2,
    'status' => 'stable',
])

@php
    $items = [
        ['value' => '1', 'label' => 'Tabel'],
        ['value' => '2', 'label' => 'Loend'],
        ['value' => '3', 'label' => 'Kalender'],
    ];
    $sizes = ['default' => 'Vaikesuurus', 'small' => 'Väike suurus'];
@endphp

<tedi:row :cols="1" :gap-y="3">
    @foreach ($sizes as $size => $label)
        <tedi:col>
            <tedi:row :cols="12" :gap-y="1" align-items="center">
                <tedi:col :width="2">
                    <tedi:text as="p" modifiers="bold">{{ $size === 'default' ? 'Default' : 'Small' }}</tedi:text>
                </tedi:col>
                <tedi:col :width="10">
                    <tedi:button-group :size="$size" :aria-label="$label" value="2" :items="$items" />
                </tedi:col>
            </tedi:row>
        </tedi:col>
    @endforeach
</tedi:row>
