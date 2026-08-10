@storybook([
    'name' => 'Fixed Example',
    'order' => 3,
    'status' => 'subset',
    'args' => [
        'label' => 'This text is Fixed on bottom of page!',
    ],
    'argTypes' => [
        'label' => ['control' => 'text'],
    ],
])

<div style="height: 1500px">
    <div style="height: 600px; margin-top: 100px; border: 1px solid red">
        <tedi:affix position="fixed" top="unset" :bottom="0" :left="0" :right="0">{{ $label }}</tedi:affix>
    </div>
</div>
