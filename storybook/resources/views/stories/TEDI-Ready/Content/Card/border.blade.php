@storybook([
    'name' => 'Border',
    'order' => 7,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<tedi:row cols="1" gap="4">
    <tedi:col>
        <tedi:card>
            <tedi:card-content>
                <tedi:text as="p">Äärtega</tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:col>
    <tedi:col>
        <tedi:card :borderless="true">
            <tedi:card-content>
                <tedi:text as="p">Ilma äärteta</tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:col>
</tedi:row>
