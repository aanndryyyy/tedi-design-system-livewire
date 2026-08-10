@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=2427-40830&m=dev',
    'args' => [
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Alias, maiores! Tempora consequatur eveniet cupiditate. Aspernatur id quia fugiat, consequatur rerum ipsa ipsam ad suscipit provident odio est commodi velit ut quisquam amet, harum nisi molestias excepturi sit perferendis, aliquid at consectetur? Minima quidem cumque eaque eveniet unde esse impedit necessitatibus aut non autem, maxime sed odit repellat distinctio, molestias laudantium saepe dignissimos eius!',
        'maxLength' => 200,
        'ellipsis' => '...',
        'expandable' => true,
    ],
    'argTypes' => [
        'content' => [
            'control' => 'text',
            'description' => 'Text that will be truncated. React projects this as children; here it is a prop, because the server has to slice it.',
        ],
        'maxLength' => [
            'control' => 'number',
            'description' => 'Maximum number of characters to display.',
            'table' => ['defaultValue' => ['summary' => '200']],
        ],
        'ellipsis' => [
            'control' => 'text',
            'description' => 'Custom content to display at the end of truncated text.',
            'table' => ['defaultValue' => ['summary' => '...']],
        ],
        'expandable' => [
            'control' => 'boolean',
            'description' => 'Whether the truncated text should be expandable.',
            'table' => ['defaultValue' => ['summary' => 'true']],
        ],
    ],
])

<tedi:row>
    <tedi:col>
        <tedi:truncate
            :content="$content"
            :max-length="$maxLength"
            :ellipsis="$ellipsis"
            :expandable="(bool) $expandable"
        />
    </tedi:col>
</tedi:row>
