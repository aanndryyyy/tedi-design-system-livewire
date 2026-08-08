@storybook([
    'name' => 'Subtitles',
    'order' => 3,
    'status' => 'stable',
])

<div style="display: flex; flex-direction: column;">
    <tedi:row :cols="2" class="border-bottom" style="padding: 14px 16px;">
        <tedi:text as="p" modifiers="subtitle">Subtitle</tedi:text>
    </tedi:row>
    <tedi:row :cols="2" class="border-bottom" style="padding: 14px 16px;">
        <tedi:text as="p" :modifiers="['small', 'subtitle']">Subtitle small</tedi:text>
    </tedi:row>
    <tedi:row :cols="2" class="border-bottom" style="padding: 14px 16px;">
        <tedi:text as="label">Label</tedi:text>
    </tedi:row>
    <tedi:row :cols="2" style="padding: 14px 16px;">
        <tedi:text as="label" modifiers="bold">Label bold</tedi:text>
    </tedi:row>
</div>
