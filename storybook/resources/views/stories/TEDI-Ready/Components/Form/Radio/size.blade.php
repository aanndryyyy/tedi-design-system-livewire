@storybook([
    'name' => 'Size',
    'order' => 2,
    'status' => 'stable',
    'args' => [],
])

{{--
    Default size is used on desktop, large size is applied automatically on
    mobile screen sizes. Use in tables where the radio has no text.
    Otherwise, prefer using default size.
--}}
<tedi:row :cols="2" :gap-y="3">
    <div>Default</div>
    <tedi:radio name="size-demo" aria-label="Vaikimisi" />
    <div>Large</div>
    <tedi:radio name="size-demo" size="large" aria-label="Suur" />
</tedi:row>
