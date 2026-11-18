@storybook([
    'name' => 'With Icon',
    'order' => 2,
    'status' => 'stable',
])

<tedi:toast-container />

<div x-data>
    <tedi:button x-on:click="TediToast.info('With Icon', 'Using a custom icon', { icon: 'info' })">With icon</tedi:button>
</div>
