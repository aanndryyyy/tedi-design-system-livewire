@storybook([
    'name' => 'With Reactive Forms',
    'order' => 13,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.77.99?node-id=4612-87581&m=dev',
])

{{--
    Angular reactive forms become `wire:model` plus server validation: see
    storybook/resources/views/livewire/file-upload-with-validation.blade.php.
--}}
<livewire:file-upload-with-validation />
