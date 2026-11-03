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
    <tedi:option-content type="checkbox" :selected="false">
        <tedi:option-content-label>Unchecked option</tedi:option-content-label>
    </tedi:option-content>
    <tedi:option-content type="checkbox" :selected="true">
        <tedi:option-content-label>Checked option</tedi:option-content-label>
    </tedi:option-content>
    <tedi:option-content type="checkbox" :selected="false" :disabled="true">
        <tedi:option-content-label>Disabled option</tedi:option-content-label>
    </tedi:option-content>
    <tedi:option-content type="checkbox" :selected="true" :disabled="true">
        <tedi:option-content-label>Disabled checked option</tedi:option-content-label>
    </tedi:option-content>
</div>
