@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'args' => [
        'tediPrint' => '',
        'breakBefore' => '',
        'breakAfter' => '',
        'breakInside' => '',
    ],
    'argTypes' => [
        'tediPrint' => [
            'description' => 'Controls the visibility of the element when printing.',
            'control' => ['type' => 'radio', 'labels' => ['' => 'none']],
            'options' => ['', 'show', 'hide'],
            'table' => [
                'type' => ['summary' => "PrintVisibility | ''"],
                'defaultValue' => ['summary' => "''"],
            ],
        ],
        'breakBefore' => [
            'description' => 'Sets `break-before` on the element when printing.',
            'control' => 'select',
            'options' => ['auto', 'avoid', 'avoid-column', 'avoid-page', 'avoid-region'],
            'table' => ['type' => ['summary' => 'PrintBreak']],
        ],
        'breakAfter' => [
            'description' => 'Sets `break-after` on the element when printing.',
            'control' => 'select',
            'options' => ['auto', 'avoid', 'avoid-column', 'avoid-page', 'avoid-region'],
            'table' => ['type' => ['summary' => 'PrintBreak']],
        ],
        'breakInside' => [
            'description' => 'Sets `break-inside` on the element when printing.',
            'control' => 'select',
            'options' => ['auto', 'avoid', 'avoid-column', 'avoid-page', 'avoid-region'],
            'table' => ['type' => ['summary' => 'PrintBreak']],
        ],
    ],
])

{{-- Nothing here changes on screen — open the browser's print preview to see
     the effect. The directive ports as a wrapper element carrying the classes. --}}
<tedi:print
    :visibility="$tediPrint"
    :break-before="$breakBefore ?: null"
    :break-after="$breakAfter ?: null"
    :break-inside="$breakInside ?: null"
>
    <p>This section is controlled by the tediPrint directive.</p>
</tedi:print>
<p>This section is always printed.</p>
