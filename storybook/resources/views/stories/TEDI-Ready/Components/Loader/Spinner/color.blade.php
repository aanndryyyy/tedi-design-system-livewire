@storybook([
    'name' => 'Color',
    'order' => 3,
    'status' => 'stable',
    'args' => [
        'size' => 48,
        'label' => 'Loading...',
    ],
    'argTypes' => [
        'size' => [
            'control' => 'radio',
            'options' => [10, 16, 48],
            'description' => 'Size of the spinner in px.',
        ],
        'label' => [
            'control' => 'text',
            'description' => 'Provides a text label for screen readers to announce the spinners purpose or status.',
        ],
    ],
])

<div style="display: flex; align-items: center; gap: 1.5rem;">
    <tedi:spinner :size="$size" color="primary" :label="$label ?: null" />
    <div style="background: var(--general-surface-brand-primary); border-radius: 4px; padding: 24px;">
        <tedi:spinner :size="$size" color="secondary" :label="$label ?: null" />
    </div>
    <div style="background: var(--general-status-danger-background-secondary); border-radius: 4px; padding: 24px;">
        <tedi:spinner :size="$size" color="secondary" :label="$label ?: null" />
    </div>
    <div style="background: var(--general-status-success-background-secondary); border-radius: 4px; padding: 24px;">
        <tedi:spinner :size="$size" color="secondary" :label="$label ?: null" />
    </div>
</div>
