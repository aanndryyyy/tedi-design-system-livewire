@storybook([
    'name' => 'All Variants',
    'order' => 9,
    'status' => 'stable',
])

<div style="display: flex; flex-direction: column; gap: 24px;">
    <div>
        <strong style="display: block; margin-bottom: 8px;">Default (Label only)</strong>
        <tedi:dropdown-item-value>
            <tedi:dropdown-item-value-label>Option 1</tedi:dropdown-item-value-label>
        </tedi:dropdown-item-value>
    </div>

    <div>
        <strong style="display: block; margin-bottom: 8px;">Horizontal (Label + Meta)</strong>
        <tedi:dropdown-item-value>
            <tedi:dropdown-item-value-label>Tallinn</tedi:dropdown-item-value-label>
            <tedi:dropdown-item-value-meta>3 timeslots available</tedi:dropdown-item-value-meta>
        </tedi:dropdown-item-value>
    </div>

    <div>
        <strong style="display: block; margin-bottom: 8px;">Vertical (Label + Description)</strong>
        <tedi:dropdown-item-value layout="vertical">
            <tedi:dropdown-item-value-label>Access to health data</tedi:dropdown-item-value-label>
            <tedi:dropdown-item-value-meta>Doctors will be able to see your health data</tedi:dropdown-item-value-meta>
        </tedi:dropdown-item-value>
    </div>

    <div>
        <strong style="display: block; margin-bottom: 8px;">With Checkbox (Multiselect)</strong>
        <tedi:dropdown-item-value type="checkbox" :selected="true">
            <tedi:dropdown-item-value-label>Selected option</tedi:dropdown-item-value-label>
        </tedi:dropdown-item-value>
    </div>

    <div>
        <strong style="display: block; margin-bottom: 8px;">With Radio (Single select)</strong>
        <tedi:dropdown-item-value type="radio" :selected="true">
            <tedi:dropdown-item-value-label>Selected option</tedi:dropdown-item-value-label>
        </tedi:dropdown-item-value>
    </div>

    <div>
        <strong style="display: block; margin-bottom: 8px;">With Leading Icon</strong>
        <tedi:dropdown-item-value>
            <x-slot:icon><tedi:icon name="computer" :size="18" /></x-slot:icon>
            <tedi:dropdown-item-value-label>Desktop</tedi:dropdown-item-value-label>
        </tedi:dropdown-item-value>
    </div>

    <div>
        <strong style="display: block; margin-bottom: 8px;">Full Example (Checkbox + Icon + Vertical)</strong>
        <tedi:dropdown-item-value type="checkbox" layout="vertical" :selected="true">
            <x-slot:icon><tedi:icon name="verified_user" :size="18" /></x-slot:icon>
            <tedi:dropdown-item-value-label>Admin permissions</tedi:dropdown-item-value-label>
            <tedi:dropdown-item-value-meta>Full access to all features and settings</tedi:dropdown-item-value-meta>
        </tedi:dropdown-item-value>
    </div>
</div>
