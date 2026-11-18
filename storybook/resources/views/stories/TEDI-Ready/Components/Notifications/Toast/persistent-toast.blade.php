@storybook([
    'name' => 'Persistent Toast',
    'order' => 4,
    'status' => 'stable',
])

<tedi:toast-container />

<div x-data>
    <tedi:button x-on:click="TediToast.warning('Persistent', 'Stays until closed', { duration: 0 })">
        Show persistent toast
    </tedi:button>
</div>
