@storybook([
    'name' => 'With Radio',
    'order' => 5,
    'status' => 'stable',
])

<div class="flex flex-column gap-2">
    <tedi:option-content type="radio" :selected="false">
        <tedi:option-content-label>Unselected option</tedi:option-content-label>
    </tedi:option-content>
    <tedi:option-content type="radio" :selected="true">
        <tedi:option-content-label>Selected option</tedi:option-content-label>
    </tedi:option-content>
    <tedi:option-content type="radio" :selected="false" :disabled="true">
        <tedi:option-content-label>Disabled option</tedi:option-content-label>
    </tedi:option-content>
    <tedi:option-content type="radio" :selected="true" :disabled="true">
        <tedi:option-content-label>Disabled selected option</tedi:option-content-label>
    </tedi:option-content>
</div>
