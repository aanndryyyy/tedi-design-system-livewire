@storybook([
    'name' => 'Split Card Body',
    'order' => 15,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

{{--
    Angular's tip stacks the two blocks below the sm breakpoint via
    `class="flex-column flex-sm-row"` utility classes. This package ports no
    utility-class layer (see README), so tedi-card-row's row layout is kept
    static here instead of responsive.
--}}
<tedi:card>
    <tedi:card-row>
        <tedi:card-content>
            <tedi:text as="p">Vasak</tedi:text>
            <tedi:text as="p">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. In
                convallis mollis augue, vitae aliquet elit congue a. Donec
                vitae sagittis odio, et maximus nulla. Quisque metus augue,
                euismod non auctor sed, consequat in ligula.
            </tedi:text>
        </tedi:card-content>
        <tedi:card-content background="secondary">
            <tedi:text as="p">Parem</tedi:text>
        </tedi:card-content>
    </tedi:card-row>
</tedi:card>
