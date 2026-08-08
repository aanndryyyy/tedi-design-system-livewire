@storybook([
    'name' => 'Value Hidden',
    'order' => 8,
    'status' => 'stable',
    'args' => [
        'value' => 60,
        'showValue' => false,
    ],
])

<tedi:progress-bar :value="$value" :show-value="(bool) $showValue" />
