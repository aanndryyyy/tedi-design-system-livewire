@storybook([
    'name' => 'Fade Position',
    'order' => 4,
    'status' => 'subset',
    'args' => [],
])

<tedi:row :gap="5">
    <tedi:col :width="3">
        <strong>Top</strong>
        <div style="margin-top:16px;max-width:200px;max-height:200px">
            <tedi:scroll-fade fade-position="top">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris Lorem ipsum dolor sit amet,
                consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </tedi:scroll-fade>
        </div>
    </tedi:col>
    <tedi:col :width="3">
        <strong>Bottom</strong>
        <div style="margin-top:16px;max-width:200px;max-height:200px">
            <tedi:scroll-fade fade-position="bottom">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris Lorem ipsum dolor sit amet,
                consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </tedi:scroll-fade>
        </div>
    </tedi:col>
    <tedi:col :width="3">
        <strong>Both</strong>
        <div style="margin-top:16px;max-width:200px;max-height:200px">
            <tedi:scroll-fade fade-position="both">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris Lorem ipsum dolor sit amet,
                consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </tedi:scroll-fade>
        </div>
    </tedi:col>
</tedi:row>
