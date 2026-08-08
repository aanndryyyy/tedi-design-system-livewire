@storybook([
    'name' => 'Icon Background Colors',
    'order' => 5,
    'status' => 'stable',
])

<div style="display: flex; align-items: center; gap: 1.5rem;">
    <tedi:icon name="vaccines" color="white" background="brand-primary" :size="24" />
    <tedi:icon name="info" color="white" background="brand-primary" :size="16" />
    <tedi:icon name="vaccines" color="brand" background="brand-secondary" :size="24" />
    <tedi:icon name="info" color="brand" background="brand-secondary" :size="16" />
    <div style="display: flex; gap: 1rem; background: var(--general-icon-background-brand-primary); border-radius: 4px; padding: 16px;">
        <tedi:icon name="vaccines" color="brand" background="primary" :size="24" />
        <tedi:icon name="info" color="brand" background="primary" :size="16" />
        <tedi:icon name="vaccines" color="white" background="secondary" :size="24" />
        <tedi:icon name="info" color="white" background="secondary" :size="16" />
    </div>
</div>
