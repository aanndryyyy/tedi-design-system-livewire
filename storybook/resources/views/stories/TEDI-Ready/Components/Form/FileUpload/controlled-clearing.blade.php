@storybook([
    'name' => 'Controlled Clearing',
    'order' => 12,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.77.99?node-id=4612-87581&m=dev',
])

{{--
    Upstream's `[(files)]` signal is a Livewire property here: see
    storybook/resources/views/livewire/file-upload-controlled-clearing.blade.php.
--}}
<livewire:file-upload-controlled-clearing />
