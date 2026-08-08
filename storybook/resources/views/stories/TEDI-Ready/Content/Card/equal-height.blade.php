@storybook([
    'name' => 'Equal Height',
    'order' => 17,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

@php
    $loremText = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab ad expedita iste itaque laborum magnam non nulla tempora ullam! A consequuntur dicta et incidunt nisi pariatur sapiente, temporibus unde voluptatem?';
@endphp

<tedi:row cols="1" gap="4">
    <tedi:card>
        <tedi:card-header background="brand-primary">
            <tedi:text as="h2" color="white">Pikema sisuga kaart</tedi:text>
        </tedi:card-header>
        <tedi:card-content style="display: flex; flex-direction: column; justify-content: space-between; gap: 0.75rem;">
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <tedi:text as="p">{{ $loremText }}</tedi:text>
                <tedi:text as="p">{{ $loremText }}</tedi:text>
            </div>
            <div>
                <tedi:button>Vaata lähemalt</tedi:button>
            </div>
        </tedi:card-content>
    </tedi:card>
    <tedi:col>
        <tedi:card>
            <tedi:card-header background="brand-primary">
                <tedi:text as="h2" color="white">Venitamata kaart</tedi:text>
            </tedi:card-header>
            <tedi:card-content style="display: flex; flex-direction: column; justify-content: space-between; gap: 0.75rem;">
                <tedi:text as="p">{{ $loremText }}</tedi:text>
                <div>
                    <tedi:button>Vaata lähemalt</tedi:button>
                </div>
            </tedi:card-content>
        </tedi:card>
    </tedi:col>
    <tedi:card>
        <tedi:card-header background="brand-primary">
            <tedi:text as="h2" color="white">Venitatud sisuga kaart</tedi:text>
        </tedi:card-header>
        <tedi:card-content style="display: flex; flex-direction: column; justify-content: space-between; gap: 0.75rem;">
            <tedi:text as="p">{{ $loremText }}</tedi:text>
            <div>
                <tedi:button>Vaata lähemalt</tedi:button>
            </div>
        </tedi:card-content>
    </tedi:card>
</tedi:row>
