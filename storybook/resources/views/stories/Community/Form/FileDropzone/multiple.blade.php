{{--
    Angular's Multiple story. Its `defaultFiles` are empty File objects, so they
    list at 0 B; the sizes here are real so the formatter has something to show.
--}}
@storybook([
    'name' => 'Multiple',
    'order' => 5,
    'status' => 'subset',
    'args' => [
        'inputId' => 'file-dropzone-multiple',
        'name' => 'file-multiple',
        'accept' => '.jpg,.png,.pdf',
        'multiple' => true,
        'files' => [
            ['name' => 'image1.jpg', 'size' => 284672],
            ['name' => 'image2.png', 'size' => 1048576],
            ['name' => 'document.pdf', 'size' => 943718],
        ],
    ],
    'argTypes' => [
        'inputId' => ['control' => 'text'],
        'name' => ['control' => 'text'],
        'accept' => ['control' => 'text'],
        'multiple' => ['control' => 'boolean'],
        'files' => ['control' => 'object', 'description' => 'Files listed under the dropzone. Replaces Angular\'s defaultFiles plus its per-file validation result.'],
    ],
])

<tedi:file-dropzone
    :input-id="$inputId"
    :name="$name"
    :accept="$accept"
    :multiple="(bool) $multiple"
    :files="$files"
/>
