@storybook([
    'name' => 'With Radio',
    'order' => 5,
    'status' => 'stable',
])

<div class="flex flex-column gap-2">
    <tedi:dropdown-item-value type="radio" :selected="false">
        <tedi:dropdown-item-value-label>Unselected option</tedi:dropdown-item-value-label>
    </tedi:dropdown-item-value>
    <tedi:dropdown-item-value type="radio" :selected="true">
        <tedi:dropdown-item-value-label>Selected option</tedi:dropdown-item-value-label>
    </tedi:dropdown-item-value>
    <tedi:dropdown-item-value type="radio" :selected="false" :disabled="true">
        <tedi:dropdown-item-value-label>Disabled option</tedi:dropdown-item-value-label>
    </tedi:dropdown-item-value>
    <tedi:dropdown-item-value type="radio" :selected="true" :disabled="true">
        <tedi:dropdown-item-value-label>Disabled selected option</tedi:dropdown-item-value-label>
    </tedi:dropdown-item-value>
</div>
