{{--
    Override the `icon` prop to reuse the closing-button look for other
    closing-like actions such as delete/remove. Always provide a matching
    `aria-label` — the default label is "close".
--}}
@storybook([
    'name' => 'Custom Icon',
    'order' => 4,
    'status' => 'stable',
])

<div class="flex gap-2">
    <tedi:closing-button icon="delete" aria-label="Kustuta" />
    <tedi:closing-button icon="delete_forever" aria-label="Kustuta jäädavalt" />
</div>
