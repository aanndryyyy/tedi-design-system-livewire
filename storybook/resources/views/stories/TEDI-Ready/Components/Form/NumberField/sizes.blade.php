@storybook([
    'name' => 'Sizes',
    'order' => 2,
    'status' => 'stable',
])

<div class="example-list">
    <tedi:row :cols="2" align-items="center" class="padding-14-16 border-bottom">
        <b>Default</b>
        <tedi:number-field label="Label" input-id="size-default" />
    </tedi:row>
    <tedi:row :cols="2" align-items="center" class="padding-14-16">
        <b>Small</b>
        <tedi:number-field label="Label" input-id="size-small" size="small" />
    </tedi:row>
</div>
