{{--
    The tooltip-wrapped composition is why <tedi:button-group> keeps its default
    slot alongside :items — the vendored SCSS has dedicated
    `.tedi-button-group tedi-tooltip` rules for exactly this, and an array entry
    cannot express the wrapper. Slot-written items pass their own :selected,
    since the group computes that only for :items entries.
--}}
@storybook([
    'name' => 'Icon Only',
    'order' => 5,
    'status' => 'stable',
])

@php
    $items = [
        ['value' => 'table', 'label' => 'Tabel', 'icon' => 'table'],
        ['value' => 'list', 'label' => 'Loend', 'icon' => 'list'],
        ['value' => 'calendar', 'label' => 'Kalender', 'icon' => 'calendar_month'],
    ];
    $selected = 'table';
@endphp

<tedi:button-group aria-label="Vaate valik">
    @foreach ($items as $item)
        <tedi:tooltip>
            <tedi:tooltip-trigger>
                <tedi:button-group-button
                    :value="$item['value']"
                    :label="$item['label']"
                    :icon="$item['icon']"
                    :selected="$item['value'] === $selected"
                />
            </tedi:tooltip-trigger>
            <tedi:tooltip-content>{{ $item['label'] }}</tedi:tooltip-content>
        </tedi:tooltip>
    @endforeach
</tedi:button-group>
