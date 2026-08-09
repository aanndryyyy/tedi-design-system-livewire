{{--
    Angular's WithHint story: an untranslated custom label.
--}}
@storybook([
    'name' => 'With Hint',
    'order' => 3,
    'status' => 'subset',
    'args' => [
        'name' => 'file',
        'label' => 'Custom hint here',
    ],
    'argTypes' => [
        'name' => ['control' => 'text'],
        'label' => ['control' => 'text', 'description' => 'The text label displayed for the file dropzone, providing context for users'],
    ],
])

<tedi:file-dropzone :name="$name" :label="$label" />
