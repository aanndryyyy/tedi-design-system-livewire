@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'args' => [
        'size' => 1.5,
    ],
    'argTypes' => [
        'size' => [
            'control' => 'number',
            'options' => [0, 0.25, 0.5, 0.75, 1, 1.25, 1.5, 1.75, 2, 2.5, 3, 4, 5],
            'description' => 'The size of the vertical spacing. Applied as margin-bottom with em units',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => '0'],
                'type' => ['summary' => 'VerticalSpacingSize'],
            ],
        ],
    ],
])

{{--
    Angular puts the directive on the <h1> itself; the Blade port wraps it,
    which is why the heading below sits inside the component rather than
    carrying it. See the component's header comment for the margin-collapse
    consequence.
--}}
<div>
    <tedi:vertical-spacing-item :size="$size">
        <h1>Vertical spacing item</h1>
    </tedi:vertical-spacing-item>
    <p>
        The <i>VerticalSpacingItemDirective</i> is a custom Angular directive
        designed to add vertical spacing to a single item. Apply the directive to a
        container element using the <b>[tediVerticalSpacingItem]</b> attribute. Set the value of
        <i>tediVerticalSpacingItem</i> to define the spacing size in <b>em</b> units.
    </p>
</div>
