@storybook([
    'name' => 'Fade Size',
    'order' => 3,
    'status' => 'subset',
    'args' => [],
])

<tedi:row :gap="5">
    <tedi:col :width="3">
        <strong>No Fade (0%)</strong>
        <div style="margin-top:16px;max-width:200px;max-height:200px">
            <tedi:scroll-fade :fade-size="0">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris Lorem ipsum dolor sit amet,
                consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </tedi:scroll-fade>
        </div>
    </tedi:col>
    <tedi:col :width="3">
        <strong>Small Fade (10%)</strong>
        <div style="margin-top:16px;max-width:200px;max-height:200px">
            <tedi:scroll-fade :fade-size="10">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris Lorem ipsum dolor sit amet,
                consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </tedi:scroll-fade>
        </div>
    </tedi:col>
    <tedi:col :width="3">
        <strong>Large Fade (20%)</strong>
        <div style="margin-top:16px;max-width:200px;max-height:200px">
            <tedi:scroll-fade :fade-size="20">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris Lorem ipsum dolor sit amet,
                consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </tedi:scroll-fade>
        </div>
    </tedi:col>
</tedi:row>
