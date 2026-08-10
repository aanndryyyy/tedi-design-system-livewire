@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'args' => [
        'as' => 'section',
        'title' => 'Page title',
    ],
    'argTypes' => [
        'as' => [
            'control' => 'radio',
            'options' => ['section', 'article', 'aside', 'div'],
            'description' => 'Defines the HTML element to render.',
            'table' => ['defaultValue' => ['summary' => 'section']],
        ],
        'title' => ['control' => 'text'],
    ],
])

<tedi:section :as="$as" class="default-section">
    <tedi:vertical-spacing>
        <tedi:text as="h1" modifiers="h1">{{ $title }}</tedi:text>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer eget convallis quam, eu rhoncus turpis.
            Vestibulum venenatis leo eget felis accumsan, in finibus metus tristique. Curabitur ac quam eu justo consequat
            efficitur quis eget purus. Donec blandit, augue in vehicula tempor, erat nulla tincidunt tellus, ut tincidunt
            purus dolor sed augue.
        </p>
    </tedi:vertical-spacing>
</tedi:section>
