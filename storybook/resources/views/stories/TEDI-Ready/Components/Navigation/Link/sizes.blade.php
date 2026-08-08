@storybook([
    'name' => 'Sizes',
    'order' => 2,
    'status' => 'stable',
])

<div class="example-list">
    <tedi:row :cols="2" class="padding-14-16 border-bottom">
        <b>Default</b>
        <tedi:link href="#">View result</tedi:link>
    </tedi:row>
    <tedi:row :cols="2" class="padding-14-16">
        <b>Small</b>
        <tedi:link href="#" size="small">View result</tedi:link>
    </tedi:row>
</div>
