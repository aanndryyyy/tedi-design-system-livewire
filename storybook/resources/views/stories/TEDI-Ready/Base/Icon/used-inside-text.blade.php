@storybook([
    'name' => 'Used Inside Text',
    'order' => 6,
    'status' => 'stable',
    'args' => [
        'name' => 'account_circle',
        'size' => 'inherit',
    ],
    'argTypes' => [
        'name' => [
            'control' => 'text',
            'description' => 'Name of the Material Icon <br /> https://fonts.google.com/icons',
        ],
        'size' => [
            'control' => 'select',
            'options' => [8, 12, 16, 18, 24, 36, 48, 'inherit'],
            'description' => 'Size of the icon in pixels.',
        ],
    ],
])

<div style="display: flex; flex-direction: column; gap: 0.25rem;">
    <tedi:text as="h1" class="display-flex gap-1">
        <tedi:icon :name="$name" :size="$size" />
        This is level 1 heading with inline
        <tedi:icon :name="$name" :size="$size" color="brand" />
        icon
    </tedi:text>
    <tedi:text as="h2" class="display-flex gap-1">
        <tedi:icon :name="$name" :size="$size" />
        This is level 2 heading with inline
        <tedi:icon :name="$name" :size="$size" color="brand" />
        icon
    </tedi:text>
    <tedi:text as="h3" class="display-flex gap-1">
        <tedi:icon :name="$name" :size="$size" />
        This is level 3 heading with inline
        <tedi:icon :name="$name" :size="$size" color="brand" />
        icon
    </tedi:text>
    <tedi:text as="h4" class="display-flex gap-1">
        <tedi:icon :name="$name" :size="$size" />
        This is level 4 heading with inline
        <tedi:icon :name="$name" :size="$size" color="brand" />
        icon
    </tedi:text>
    <tedi:text as="h5" class="display-flex gap-1">
        <tedi:icon :name="$name" :size="$size" />
        This is level 5 heading with inline
        <tedi:icon :name="$name" :size="$size" color="brand" />
        icon
    </tedi:text>
    <tedi:text as="h6" class="display-flex gap-1">
        <tedi:icon :name="$name" :size="$size" />
        This is level 6 heading with inline
        <tedi:icon :name="$name" :size="$size" color="brand" />
        icon
    </tedi:text>
    <tedi:text as="p" class="display-flex gap-1">
        <tedi:icon :name="$name" :size="$size" />
        This is paragraph text with inline
        <tedi:icon :name="$name" :size="$size" color="brand" />
        icon
    </tedi:text>
    <small class="display-flex gap-1">
        <tedi:icon :name="$name" :size="$size" />
        This is small text with inline
        <tedi:icon :name="$name" :size="$size" color="brand" />
        icon
    </small>
</div>
