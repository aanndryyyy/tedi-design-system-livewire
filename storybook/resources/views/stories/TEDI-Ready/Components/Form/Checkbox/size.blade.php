@storybook([
    'name' => 'Size',
    'order' => 2,
    'status' => 'stable',
    'args' => [],
])

{{--
    Default size is used on desktop, large size is applied automatically on
    mobile screen sizes. Use in tables where the checkbox has no text.
    Otherwise, prefer using default size.
--}}
<tedi:row :cols="2" :gap-y="3">
    <div>Default</div>
    <tedi:checkbox aria-label="Vaikimisi" />
    <div>Large</div>
    <tedi:checkbox size="large" aria-label="Suur" />
</tedi:row>
