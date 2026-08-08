@storybook([
    'name' => 'Dropdown',
    'order' => 6,
    'status' => 'subset',
])

<div style="width: 200px;">
    <tedi:time-picker
        variant="dropdown"
        value="13:30"
        :time-slots="['12:30', '13:00', '13:30', '14:00', '14:30']"
    />
</div>
