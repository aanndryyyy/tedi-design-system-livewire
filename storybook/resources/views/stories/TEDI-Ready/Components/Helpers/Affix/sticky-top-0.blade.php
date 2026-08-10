@storybook([
    'name' => 'Sticky Top 0',
    'order' => 2,
    'status' => 'subset',
    'args' => [
        'label' => 'This text is Sticky in its container!',
    ],
    'argTypes' => [
        'label' => ['control' => 'text'],
    ],
])

<div style="height: 1500px">
    <div style="height: 600px; margin-top: 100px; border: 1px solid red">
        <tedi:affix :top="0">{{ $label }}</tedi:affix>
    </div>
</div>
