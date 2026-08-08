@storybook([
    'name' => 'Inherited Text Color',
    'order' => 6,
    'status' => 'stable',
])

<div class="tedi-vertical-spacing" style="--vertical-spacing-internal: 1em;">
    <tedi:text as="p" color="brand">
        This paragraph is brand-colored. <tedi:text as="span" color="inherit" modifiers="bold">This bold span inherits the brand color from its parent.</tedi:text>
    </tedi:text>
    <tedi:text as="p" color="danger">
        This paragraph is danger-colored. <tedi:text as="span" color="inherit" modifiers="italic">This italic span inherits the danger color from its parent.</tedi:text>
    </tedi:text>
    <tedi:text as="p" class="bg bg-primary" color="white">
        This paragraph sits on a primary background and uses white text. <tedi:text as="span" color="inherit" modifiers="bold">This bold span inherits the white color from its parent.</tedi:text>
    </tedi:text>
</div>
