@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.76.92?node-id=15548-139124&m=dev',
    'args' => [
        'label' => 'Hinnang',
        'type' => 'star',
        'count' => '',
        'value' => 0,
        'itemLabels' => '',
        'icons' => '',
        'disabled' => false,
        'readOnly' => false,
        'ratingsCount' => '',
        'showRatingsCountLabel' => true,
        'readOnlyVariant' => 'summary',
        'name' => '',
        'orientation' => 'horizontal',
    ],
    'argTypes' => [
        'label' => ['control' => 'text'],
        'type' => ['control' => 'radio', 'options' => ['star', 'number', 'icon']],
        'count' => [
            'control' => ['type' => 'number', 'min' => 1],
            'description' => 'Number of items. Defaults to 5 (`star` / `icon`) or 10 (`number`).',
        ],
        'value' => ['control' => ['type' => 'number', 'min' => 0, 'step' => 0.5]],
        'itemLabels' => [
            'control' => 'object',
            'description' => 'Per-item labels (length = `count`): accessible names and captions.',
        ],
        'icons' => [
            'control' => 'object',
            'description' => 'Material Symbol glyph(s): an array for `type="icon"`, a single string for `type="star"`.',
        ],
        'disabled' => ['control' => 'boolean'],
        'readOnly' => ['control' => 'boolean'],
        'ratingsCount' => [
            'control' => ['type' => 'number', 'min' => 0],
            'description' => 'Number of ratings shown in the `readOnly` summary.',
        ],
        'showRatingsCountLabel' => ['control' => 'boolean'],
        'readOnlyVariant' => ['control' => 'radio', 'options' => ['summary', 'scale']],
        'name' => ['control' => 'text'],
        'orientation' => [
            'control' => 'radio',
            'options' => ['horizontal', 'vertical'],
            'description' => 'Breakpoint objects are not ported; `horizontal` or `vertical` only.',
        ],
    ],
])

{{-- '' stands in for Angular's unset inputs (storybook/CONTRACT.md §3). --}}
<tedi:rating
    :label="$label"
    :type="$type"
    :count="$count === '' || $count === null ? null : $count"
    :value="$value"
    :item-labels="$itemLabels ?: null"
    :icons="$icons ?: null"
    :disabled="(bool) $disabled"
    :read-only="(bool) $readOnly"
    :ratings-count="$ratingsCount === '' || $ratingsCount === null ? null : $ratingsCount"
    :show-ratings-count-label="(bool) $showRatingsCountLabel"
    :read-only-variant="$readOnlyVariant"
    :name="$name ?: null"
    :orientation="$orientation"
/>
