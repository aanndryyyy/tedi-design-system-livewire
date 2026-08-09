@storybook([
    'name' => 'Widths',
    'order' => 4,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

<tedi:row :cols="4">
    <tedi:col>
        <tedi:tooltip>
            <tedi:tooltip-trigger :text="true">Small tooltip width</tedi:tooltip-trigger>
            <tedi:tooltip-content max-width="small">
                This is an example for small tooltip. The quick brown fox jumps over the lazy dog.
            </tedi:tooltip-content>
        </tedi:tooltip>
    </tedi:col>
    <tedi:col>
        <tedi:tooltip>
            <tedi:tooltip-trigger :text="true">Medium tooltip width</tedi:tooltip-trigger>
            <tedi:tooltip-content max-width="medium">
                This is an example for medium tooltip. The quick brown fox jumps over the lazy dog.
            </tedi:tooltip-content>
        </tedi:tooltip>
    </tedi:col>
    <tedi:col>
        <tedi:tooltip>
            <tedi:tooltip-trigger :text="true">Large tooltip width</tedi:tooltip-trigger>
            <tedi:tooltip-content max-width="large">
                This is an example for large tooltip. The quick brown fox jumps over the lazy dog.
            </tedi:tooltip-content>
        </tedi:tooltip>
    </tedi:col>
    <tedi:col>
        <tedi:tooltip>
            <tedi:tooltip-trigger :text="true">Tooltip with no width limit</tedi:tooltip-trigger>
            <tedi:tooltip-content max-width="none">
                This is an example for no max width tooltip. The quick brown fox jumps over the lazy dog.
            </tedi:tooltip-content>
        </tedi:tooltip>
    </tedi:col>
</tedi:row>
