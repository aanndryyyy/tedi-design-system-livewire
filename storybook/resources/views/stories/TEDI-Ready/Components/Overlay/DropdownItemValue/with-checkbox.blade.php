@storybook([
    'name' => 'With Checkbox',
    'order' => 4,
    'status' => 'stable',
])

{{--
    Angular wraps the rows in [tediVerticalSpacing]="0.5"; that directive is not
    part of this port, so the same 0.5rem rhythm comes from TEDI's own utility
    classes.
--}}
<div class="flex flex-column gap-2">
    <tedi:dropdown-item-value type="checkbox" :selected="false">
        <tedi:dropdown-item-value-label>Unchecked option</tedi:dropdown-item-value-label>
    </tedi:dropdown-item-value>
    <tedi:dropdown-item-value type="checkbox" :selected="true">
        <tedi:dropdown-item-value-label>Checked option</tedi:dropdown-item-value-label>
    </tedi:dropdown-item-value>
    <tedi:dropdown-item-value type="checkbox" :selected="false" :disabled="true">
        <tedi:dropdown-item-value-label>Disabled option</tedi:dropdown-item-value-label>
    </tedi:dropdown-item-value>
    <tedi:dropdown-item-value type="checkbox" :selected="true" :disabled="true">
        <tedi:dropdown-item-value-label>Disabled checked option</tedi:dropdown-item-value-label>
    </tedi:dropdown-item-value>
</div>
