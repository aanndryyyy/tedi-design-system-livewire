@storybook([
    'name' => 'Cards Example',
    'order' => 2,
    'status' => 'stable',
    'args' => [
        'lorem' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab ad expedita iste itaque laborum magnam non nulla tempora ullam! A consequuntur dicta et incidunt nisi pariatur sapiente, temporibus unde voluptatem?',
    ],
    'argTypes' => [
        'lorem' => ['table' => ['disable' => true]],
    ],
])

{{-- The middle card is deliberately NOT stretched, so the three columns show
     what the wrapper actually does to a row of unequal cards. --}}
<tedi:row>
    <tedi:col>
        <tedi:stretch-content>
            <tedi:card>
                <tedi:card-header background="brand-primary">
                    <tedi:text as="h2" modifiers="h2">Card with longer content</tedi:text>
                </tedi:card-header>
                <tedi:card-content>
                    <tedi:stretch-content>
                        <tedi:row cols="1" :gap="4">
                            <tedi:col>
                                <tedi:vertical-spacing><p>{{ $lorem }}</p><p>{{ $lorem }}</p></tedi:vertical-spacing>
                            </tedi:col>
                            <tedi:col width="auto">
                                <tedi:button>Click me</tedi:button>
                            </tedi:col>
                        </tedi:row>
                    </tedi:stretch-content>
                </tedi:card-content>
            </tedi:card>
        </tedi:stretch-content>
    </tedi:col>

    <tedi:col>
        <tedi:card>
            <tedi:card-header background="brand-primary">
                <tedi:text as="h2" modifiers="h2">Card that is not stretched</tedi:text>
            </tedi:card-header>
            <tedi:card-content>
                <tedi:row cols="1" :gap="4">
                    <tedi:col>
                        <tedi:vertical-spacing><p>{{ $lorem }}</p></tedi:vertical-spacing>
                    </tedi:col>
                    <tedi:col width="auto">
                        <tedi:button>Click me</tedi:button>
                    </tedi:col>
                </tedi:row>
            </tedi:card-content>
        </tedi:card>
    </tedi:col>

    <tedi:col>
        <tedi:stretch-content>
            <tedi:card>
                <tedi:card-header background="brand-primary">
                    <tedi:text as="h2" modifiers="h2">Card where content is also stretched</tedi:text>
                </tedi:card-header>
                <tedi:card-content>
                    <tedi:stretch-content>
                        <tedi:row cols="1" :gap="4">
                            <tedi:col>
                                <tedi:vertical-spacing><p>{{ $lorem }}</p></tedi:vertical-spacing>
                            </tedi:col>
                            <tedi:col width="auto">
                                <tedi:button>Click me</tedi:button>
                            </tedi:col>
                        </tedi:row>
                    </tedi:stretch-content>
                </tedi:card-content>
            </tedi:card>
        </tedi:stretch-content>
    </tedi:col>
</tedi:row>
