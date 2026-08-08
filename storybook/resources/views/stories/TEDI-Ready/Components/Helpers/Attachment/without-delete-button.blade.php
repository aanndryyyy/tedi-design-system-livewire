@storybook([
    'name' => 'Without Delete Button',
    'order' => 6,
    'status' => 'stable',
    'args' => [],
])

{{-- Leave the actions slot empty to render an attachment with no action buttons. --}}
<div style="display:flex;flex-direction:column;gap:.5rem">
    <tedi:attachment name="Kodukülastusakt_Triin.pdf" file-size="0,9 MB" />
    <tedi:attachment name="Kodukülastusakt_Triin.pdf" file-size="0,9 MB" />
</div>
