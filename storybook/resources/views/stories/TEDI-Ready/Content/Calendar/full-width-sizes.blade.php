@storybook([
    'name' => 'Full Width Sizes',
    'order' => 10,
    'status' => 'subset',
    'args' => [],
])

<div style="display: flex; flex-direction: column; gap: 2rem;">
    @foreach ([['label' => 'Medium — 480px', 'width' => 480], ['label' => 'Large — 640px', 'width' => 640], ['label' => 'Extra large — 800px', 'width' => 800]] as $size)
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <tedi:text as="p" color="secondary" modifiers="small">{{ $size['label'] }}</tedi:text>
            <div style="width: 100%; max-width: {{ $size['width'] }}px">
                <tedi:calendar
                    :current-month="date('Y-m-01')"
                    :full-width="true"
                    month-year-select-type="static"
                    :show-navigation="false"
                />
            </div>
        </div>
    @endforeach
</div>
