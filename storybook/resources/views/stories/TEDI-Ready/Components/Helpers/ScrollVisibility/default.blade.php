@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=10758-111106&m=dev',
    'args' => [
        'visibility' => 'hide',
        'toggleVisibility' => false,
        'scrollDistance' => 100,
        'scrollDirection' => 'down',
        'enabled' => true,
    ],
    'argTypes' => [
        'visibility' => [
            'control' => 'radio',
            'options' => ['hide', 'show'],
            'description' => 'Determines whether to hide or show when scrolled past scrollDistance.',
            'table' => ['defaultValue' => ['summary' => 'hide']],
        ],
        'toggleVisibility' => [
            'control' => 'boolean',
            'description' => "Determines if the component's visibility toggles when scrolling the opposite direction after crossing scrollDistance.",
            'table' => ['defaultValue' => ['summary' => 'false']],
        ],
        'scrollDistance' => [
            'control' => 'number',
            'description' => 'Distance in px the user has to scroll for the component to show or hide.',
            'table' => ['defaultValue' => ['summary' => '100']],
        ],
        'scrollDirection' => [
            'control' => 'radio',
            'options' => ['down', 'up'],
            'description' => 'Direction used to calculate scrollDistance. down is measured from the top of the page, up from the bottom.',
            'table' => ['defaultValue' => ['summary' => 'down']],
        ],
        'enabled' => [
            'control' => 'boolean',
            'description' => 'Conditionally enable the functionality.',
            'table' => ['defaultValue' => ['summary' => 'true']],
        ],
    ],
])

@php $lorem = str_repeat('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer eget convallis quam, eu rhoncus turpis. ', 200); @endphp

<div>
    <tedi:scroll-visibility
        animation-direction="up"
        :visibility="$visibility"
        :toggle-visibility="(bool) $toggleVisibility"
        :scroll-distance="$scrollDistance"
        :scroll-direction="$scrollDirection"
        :enabled="(bool) $enabled"
    >
        <nav style="width: 100%; position: fixed; top: 0; background: rgb(0, 72, 130); color: white; height: 48px; align-content: center; text-align: center; z-index: 10">
            Scroll down to hide me
        </nav>
    </tedi:scroll-visibility>

    <tedi:text>{{ $lorem }}</tedi:text>
</div>
