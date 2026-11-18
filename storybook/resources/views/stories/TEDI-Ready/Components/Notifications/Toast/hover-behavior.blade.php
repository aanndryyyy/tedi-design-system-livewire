@storybook([
    'name' => 'Hover Behavior',
    'order' => 6,
    'status' => 'stable',
])

<tedi:toast-container />

<tedi:row :gap="2" x-data>
    <tedi:col>
        <tedi:button x-on:click="TediToast.info('Pauses', 'Timer stops when hovered', { showProgressBar: true })">Pause on hover</tedi:button>
    </tedi:col>
    <tedi:col>
        <tedi:button x-on:click="TediToast.danger('No Pause', 'Closes even if hovered', { showProgressBar: true, pauseOnHover: false })">No pause on hover</tedi:button>
    </tedi:col>
</tedi:row>
