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
    Angular's input is named `tediVerticalSpacing` because the directive's
    selector is the input. As a Blade component the name is just `size`.
--}}
<tedi:vertical-spacing :size="$size">
    <h1>Vertical spacing </h1>
    <p>The <i>VerticalSpacingDirective</i> is a custom Angular directive designed to add vertical
    spacing (margin-bottom) between child elements of a container. It is particularly useful for
    maintaining consistent spacing in layouts without manually applying styles to each child element.</p>
    <p>Apply the directive to a container element using the  <b>[tediVerticalSpacing]</b> attribute.</p>
    <p>Set the value of  <i>tediVerticalSpacing</i> to define the spacing size in  <b>em</b> units.</p>
    <p>The directive applies the margin-bottom to all direct child elements except the last one.</p>
    <p>Note: The directive does not affect nested (grandchild) elements.</p>
</tedi:vertical-spacing>
