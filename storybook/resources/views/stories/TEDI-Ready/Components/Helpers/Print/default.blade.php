@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'args' => [
        'visibility' => 'hide',
        'breakBefore' => '',
        'breakAfter' => '',
        'breakInside' => '',
    ],
    'argTypes' => [
        'visibility' => [
            'control' => 'radio',
            'options' => ['', 'show', 'hide'],
            'description' => 'Controls the visibility of the content when printing. show keeps the content visible during printing, hide removes it. Empty emits neither class.',
        ],
        'breakBefore' => [
            'control' => 'select',
            'options' => ['', 'auto', 'avoid', 'avoid-column', 'avoid-page', 'avoid-region'],
            'description' => 'Determines how page, column, or region breaks behave before the element. Uses CSS break-before values.',
        ],
        'breakAfter' => [
            'control' => 'select',
            'options' => ['', 'auto', 'avoid', 'avoid-column', 'avoid-page', 'avoid-region'],
            'description' => 'Determines how page, column, or region breaks behave after the element. Uses CSS break-after values.',
        ],
        'breakInside' => [
            'control' => 'select',
            'options' => ['', 'auto', 'avoid', 'avoid-column', 'avoid-page', 'avoid-region'],
            'description' => 'Determines how page, column, or region breaks behave inside the element. Uses CSS break-inside values.',
        ],
    ],
])

{{-- Nothing here changes on screen — open the browser's print preview to see
     the effect. Blade cannot clone a child the way React does, so each Print
     is a wrapper element carrying the classes. --}}
<tedi:vertical-spacing>
    <tedi:print
        :visibility="$visibility ?: null"
        :break-before="$breakBefore ?: null"
        :break-after="$breakAfter ?: null"
        :break-inside="$breakInside ?: null"
    >
        <p>Demo paragraph</p>
    </tedi:print>

    <tedi:print
        :visibility="$visibility ?: null"
        :break-before="$breakBefore ?: null"
        :break-after="$breakAfter ?: null"
        :break-inside="$breakInside ?: null"
    >
        <tedi:button>Buttons are not printed by default</tedi:button>
    </tedi:print>
</tedi:vertical-spacing>
