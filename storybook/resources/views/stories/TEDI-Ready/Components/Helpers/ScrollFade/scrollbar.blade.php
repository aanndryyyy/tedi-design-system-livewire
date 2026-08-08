@storybook([
    'name' => 'Scrollbar',
    'order' => 2,
    'status' => 'subset',
    'args' => [],
])

<tedi:row :gap="5">
    <tedi:col :width="3">
        <strong>Default Scrollbar</strong>
        <div style="margin-top:16px;max-width:200px;max-height:200px">
            <tedi:scroll-fade scroll-bar="default">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris Lorem ipsum dolor sit amet,
                consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </tedi:scroll-fade>
        </div>
    </tedi:col>
    <tedi:col :width="3">
        <strong>Custom Scrollbar</strong>
        <div style="margin-top:16px;max-width:200px;max-height:200px">
            <tedi:scroll-fade scroll-bar="custom">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris Lorem ipsum dolor sit amet,
                consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </tedi:scroll-fade>
        </div>
    </tedi:col>
</tedi:row>
