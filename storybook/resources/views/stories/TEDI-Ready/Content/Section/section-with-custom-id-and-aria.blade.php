@storybook([
    'name' => 'Section With Custom Id And ARIA',
    'order' => 3,
    'status' => 'stable',
    'args' => [
        'title' => 'Custom Section with ARIA',
        'id' => 'custom-section',
        'role' => 'complementary',
    ],
    'argTypes' => [
        'title' => ['control' => 'text'],
        'id' => [
            'control' => 'text',
            'description' => 'Unique identifier for the section. It is not a declared prop — it reaches the root element through the attribute bag.',
        ],
        'role' => [
            'control' => 'text',
            'description' => 'ARIA role for accessibility. Also passed through the attribute bag.',
        ],
    ],
])

<tedi:section as="section" id="{{ $id }}" role="{{ $role }}" class="custom-section">
    <tedi:vertical-spacing>
        <tedi:text as="h1" modifiers="h1" id="section-heading">{{ $title }}</tedi:text>
        <p>
            This section demonstrates the use of custom <code>id</code> and ARIA attributes for accessibility.
        </p>
    </tedi:vertical-spacing>
</tedi:section>
