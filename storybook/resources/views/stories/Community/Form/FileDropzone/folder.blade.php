{{--
    Angular's Folder story — `webkitdirectory` on the input.
--}}
@storybook([
    'name' => 'Folder',
    'order' => 6,
    'status' => 'subset',
    'args' => [
        'inputId' => 'file-dropzone-folder',
        'name' => 'file-folder',
        'uploadFolder' => true,
    ],
    'argTypes' => [
        'inputId' => ['control' => 'text'],
        'name' => ['control' => 'text'],
        'uploadFolder' => ['control' => 'boolean', 'description' => 'If true, allows uploading folders instead of just files.'],
    ],
])

<tedi:file-dropzone :input-id="$inputId" :name="$name" :upload-folder="(bool) $uploadFolder" />
