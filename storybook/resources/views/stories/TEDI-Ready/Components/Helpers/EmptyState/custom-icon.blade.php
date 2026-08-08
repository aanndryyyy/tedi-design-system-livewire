@storybook([
    'name' => 'Custom Icon',
    'order' => 11,
    'status' => 'stable',
    'args' => [
        'type' => 'separate',
        'size' => 'default',
        'icon' => 'shopping_cart_off',
    ],
    'argTypes' => [
        'icon' => [
            'control' => 'text',
            'description' => 'Material icon name rendered above the text.',
        ],
    ],
])

<tedi:empty-state :type="$type" :size="$size" :icon="$icon ?: ''">No products in your cart</tedi:empty-state>
