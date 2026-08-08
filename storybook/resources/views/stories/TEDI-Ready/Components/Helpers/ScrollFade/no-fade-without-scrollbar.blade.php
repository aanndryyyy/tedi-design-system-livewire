@storybook([
    'name' => 'No Fade Without Scrollbar',
    'order' => 5,
    'status' => 'subset',
    'args' => [],
])

<tedi:row>
    <tedi:col :width="3">
        <div style="margin-top:16px;max-width:200px;max-height:400px">
            <tedi:scroll-fade>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris Lorem ipsum dolor sit amet,
                consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </tedi:scroll-fade>
        </div>
    </tedi:col>
</tedi:row>
