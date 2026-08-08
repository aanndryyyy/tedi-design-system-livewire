@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'args' => [
        'width' => 1,
        'justifySelf' => '',
        'alignSelf' => '',
    ],
    'argTypes' => [
        'width' => [
            'control' => 'select',
            'options' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'description' => 'Number of column width.',
            'table' => ['category' => 'inputs', 'defaultValue' => ['summary' => '1'], 'type' => ['summary' => 'ColWidth']],
        ],
        'justifySelf' => [
            'control' => 'select',
            'options' => ['', 'start', 'end', 'center', 'stretch'],
            'description' => 'Aligns an item horizontally inside its own grid cell.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'JustifySelf']],
        ],
        'alignSelf' => [
            'control' => 'select',
            'options' => ['', 'start', 'end', 'center', 'stretch'],
            'description' => 'Aligns an item vertically inside its own grid cell.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'AlignSelf']],
        ],
    ],
])

{{-- Breakpoint props (xs–xxl) are not ported (CONTRACT.md §5) — only the base props are exposed. --}}
<tedi:row :cols="12" :gap="1">
    <tedi:col :width="$width" :justify-self="$justifySelf ?: null" :align-self="$alignSelf ?: null" class="example-col">Col 1</tedi:col>
    <tedi:col :width="$width" :justify-self="$justifySelf ?: null" :align-self="$alignSelf ?: null" class="example-col">Col 2</tedi:col>
    <tedi:col :width="$width" :justify-self="$justifySelf ?: null" :align-self="$alignSelf ?: null" class="example-col">Col 3</tedi:col>
    <tedi:col :width="$width" :justify-self="$justifySelf ?: null" :align-self="$alignSelf ?: null" class="example-col">Col 4</tedi:col>
    <tedi:col :width="$width" :justify-self="$justifySelf ?: null" :align-self="$alignSelf ?: null" class="example-col">Col 5</tedi:col>
    <tedi:col :width="$width" :justify-self="$justifySelf ?: null" :align-self="$alignSelf ?: null" class="example-col">Col 6</tedi:col>
    <tedi:col :width="$width" :justify-self="$justifySelf ?: null" :align-self="$alignSelf ?: null" class="example-col">Col 7</tedi:col>
    <tedi:col :width="$width" :justify-self="$justifySelf ?: null" :align-self="$alignSelf ?: null" class="example-col">Col 8</tedi:col>
    <tedi:col :width="$width" :justify-self="$justifySelf ?: null" :align-self="$alignSelf ?: null" class="example-col">Col 9</tedi:col>
    <tedi:col :width="$width" :justify-self="$justifySelf ?: null" :align-self="$alignSelf ?: null" class="example-col">Col 10</tedi:col>
    <tedi:col :width="$width" :justify-self="$justifySelf ?: null" :align-self="$alignSelf ?: null" class="example-col">Col 11</tedi:col>
    <tedi:col :width="$width" :justify-self="$justifySelf ?: null" :align-self="$alignSelf ?: null" class="example-col">Col 12</tedi:col>
</tedi:row>
