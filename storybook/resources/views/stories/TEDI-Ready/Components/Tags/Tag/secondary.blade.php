@storybook([
    'name' => 'Secondary',
    'order' => 4,
    'status' => 'stable',
    'args' => [
        'type' => 'secondary',
    ],
    'argTypes' => [
        'type' => [
            'control' => 'select',
            'options' => ['primary', 'secondary', 'danger'],
            'description' => 'The type of the tag.',
        ],
    ],
])

<tedi:row :cols="'auto'" :gap="2">
    <tedi:col>
        <tedi:tag :type="$type">Tag</tedi:tag>
    </tedi:col>
    <tedi:col>
        <tedi:tag :type="$type" :closable="true">Tag</tedi:tag>
    </tedi:col>
    <tedi:col>
        <tedi:tag :type="$type" :loading="true">taotlus_scan_lk_1.pdf</tedi:tag>
    </tedi:col>
    <tedi:col style="max-width: 150px;">
        <tedi:tag :type="$type" :closable="true">Tag with a very long text but little room</tedi:tag>
    </tedi:col>
    <tedi:col style="max-width: 150px;">
        <tedi:tag :type="$type" :loading="true">Tag with a very long text but little room</tedi:tag>
    </tedi:col>
</tedi:row>
