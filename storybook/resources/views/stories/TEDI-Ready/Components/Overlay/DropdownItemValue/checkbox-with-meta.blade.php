@storybook([
    'name' => 'Checkbox with Meta (Vertical)',
    'order' => 8,
    'status' => 'stable',
])

<div class="flex flex-column gap-2">
    <tedi:dropdown-item-value type="checkbox" layout="vertical" :selected="true">
        <tedi:dropdown-item-value-label>Access to health data</tedi:dropdown-item-value-label>
        <tedi:dropdown-item-value-meta>Doctors will be able to see your health data</tedi:dropdown-item-value-meta>
    </tedi:dropdown-item-value>
    <tedi:dropdown-item-value type="checkbox" layout="vertical" :selected="false">
        <tedi:dropdown-item-value-label>Access to medications</tedi:dropdown-item-value-label>
        <tedi:dropdown-item-value-meta>Doctors will be able to see your medications</tedi:dropdown-item-value-meta>
    </tedi:dropdown-item-value>
</div>
