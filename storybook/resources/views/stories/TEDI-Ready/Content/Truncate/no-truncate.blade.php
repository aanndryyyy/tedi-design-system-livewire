@storybook([
    'name' => 'No Truncate',
    'order' => 2,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=2427-40830&m=dev',
    'args' => [
        'content' => 'This text does not get truncated, because the length is smaller than maxLength property.',
        'maxLength' => 100,
    ],
    'argTypes' => [
        'content' => ['control' => 'text'],
        'maxLength' => ['control' => 'number'],
    ],
])

<tedi:row>
    <tedi:col>
        <tedi:truncate :content="$content" :max-length="$maxLength" />
    </tedi:col>
</tedi:row>
