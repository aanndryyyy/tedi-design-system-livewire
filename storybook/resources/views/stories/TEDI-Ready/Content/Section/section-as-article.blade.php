@storybook([
    'name' => 'Section As Article',
    'order' => 2,
    'status' => 'stable',
    'args' => [
        'title' => 'Article Title',
    ],
    'argTypes' => [
        'title' => ['control' => 'text'],
    ],
])

<tedi:section as="article" class="article-section">
    <tedi:vertical-spacing>
        <tedi:text as="h1" modifiers="h1">{{ $title }}</tedi:text>
        <p>
            This is a section rendered as an <code>article</code> element. It can be used to define sections of content
            that could stand independently.
        </p>
    </tedi:vertical-spacing>
</tedi:section>
