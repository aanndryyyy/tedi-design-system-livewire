@storybook([
    'name' => 'With Icons',
    'order' => 8,
    'status' => 'stable',
])

<div class="example-list">
    <tedi:row :cols="2" class="padding-14-16 border-bottom">
        <b>Multiple icons</b>
        <tedi:link href="#">
            <tedi:icon name="notifications" />
            This text contains
            <tedi:icon name="notifications" />
            multiple icons
        </tedi:link>
    </tedi:row>
    <tedi:row :cols="2" class="padding-14-16 border-bottom">
        <b>Long Text Icon Inline</b>
        <div style="max-width: 200px;">
            <tedi:link href="#">
                <tedi:icon name="notifications" />
                This is very long text with inline icon
            </tedi:link>
        </div>
    </tedi:row>
    <tedi:row :cols="2" class="padding-14-16">
        <b>Long Text Icon Flexed</b>
        <div style="max-width: 200px;">
            <tedi:link href="#" style="display: inline-flex;">
                <tedi:icon name="notifications" />
                This is very long text with flexed icon
            </tedi:link>
        </div>
    </tedi:row>
</div>
