@storybook([
    'name' => 'Split Card Body',
    'order' => 15,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

{{--
    Angular stacks the two blocks below the sm breakpoint with
    `direction="column"` plus `[sm]="{ direction: 'row' }"`. Breakpoint inputs
    are not ported (CONVENTIONS.md §7), and core's `flex-column flex-sm-row`
    utilities are no substitute: the row rounds the corners it shares with the
    card from its `direction`, not from the computed flex direction. The row
    layout Angular shows from `sm` up is therefore kept static here.
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
