@storybook([
    'name' => 'Custom Timer For Autoclose',
    'order' => 3,
    'status' => 'stable',
])

<tedi:toast-container />

<tedi:row :gap="2" x-data>
    <tedi:col>
        <tedi:button x-on:click="TediToast.info('2s delay', 'Closes after 2 seconds', { duration: 2000, showProgressBar: true })">Auto close in 2s</tedi:button>
    </tedi:col>
    <tedi:col>
        <tedi:button x-on:click="TediToast.info('10s delay', 'Closes after 10 seconds', { duration: 10000, showProgressBar: true })">Auto close in 10s</tedi:button>
    </tedi:col>
</tedi:row>
