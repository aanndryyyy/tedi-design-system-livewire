@storybook([
    'name' => 'Headings',
    'order' => 2,
    'status' => 'stable',
])

<div style="display: flex; flex-direction: column;">
    <tedi:row :cols="2" class="border-bottom" style="padding: 14px 16px;">
        <tedi:text as="h1">Heading H1</tedi:text>
    </tedi:row>
    <tedi:row :cols="2" class="border-bottom" style="padding: 14px 16px;">
        <tedi:text as="h2">Heading H2</tedi:text>
    </tedi:row>
    <tedi:row :cols="2" class="border-bottom" style="padding: 14px 16px;">
        <tedi:text as="h3">Heading H3</tedi:text>
    </tedi:row>
    <tedi:row :cols="2" class="border-bottom" style="padding: 14px 16px;">
        <tedi:text as="h4">Heading H4</tedi:text>
    </tedi:row>
    <tedi:row :cols="2" class="border-bottom" style="padding: 14px 16px;">
        <tedi:text as="h5">Heading H5</tedi:text>
    </tedi:row>
    <tedi:row :cols="2" style="padding: 14px 16px;">
        <tedi:text as="h6">Heading H6</tedi:text>
    </tedi:row>
</div>
