@storybook([
    'name' => 'Checkbox with Meta (Vertical)',
    'order' => 8,
    'status' => 'stable',
])

<div class="flex flex-column gap-2">
    <tedi:option-content type="checkbox" layout="vertical" :selected="true">
        <tedi:option-content-label>Access to health data</tedi:option-content-label>
        <tedi:option-content-meta>Doctors will be able to see your health data</tedi:option-content-meta>
    </tedi:option-content>
    <tedi:option-content type="checkbox" layout="vertical" :selected="false">
        <tedi:option-content-label>Access to medications</tedi:option-content-label>
        <tedi:option-content-meta>Doctors will be able to see your medications</tedi:option-content-meta>
    </tedi:option-content>
</div>
