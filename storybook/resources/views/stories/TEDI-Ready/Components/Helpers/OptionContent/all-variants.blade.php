@storybook([
    'name' => 'All Variants',
    'order' => 9,
    'status' => 'stable',
])

<div style="display: flex; flex-direction: column; gap: 24px;">
    <div>
        <strong style="display: block; margin-bottom: 8px;">Default (Label only)</strong>
        <tedi:option-content>
            <tedi:option-content-label>Option 1</tedi:option-content-label>
        </tedi:option-content>
    </div>

    <div>
        <strong style="display: block; margin-bottom: 8px;">Horizontal (Label + Meta)</strong>
        <tedi:option-content>
            <tedi:option-content-label>Tallinn</tedi:option-content-label>
            <tedi:option-content-meta>3 timeslots available</tedi:option-content-meta>
        </tedi:option-content>
    </div>

    <div>
        <strong style="display: block; margin-bottom: 8px;">Vertical (Label + Description)</strong>
        <tedi:option-content layout="vertical">
            <tedi:option-content-label>Access to health data</tedi:option-content-label>
            <tedi:option-content-meta>Doctors will be able to see your health data</tedi:option-content-meta>
        </tedi:option-content>
    </div>

    <div>
        <strong style="display: block; margin-bottom: 8px;">With Checkbox (Multiselect)</strong>
        <tedi:option-content type="checkbox" :selected="true">
            <tedi:option-content-label>Selected option</tedi:option-content-label>
        </tedi:option-content>
    </div>

    <div>
        <strong style="display: block; margin-bottom: 8px;">With Radio (Single select)</strong>
        <tedi:option-content type="radio" :selected="true">
            <tedi:option-content-label>Selected option</tedi:option-content-label>
        </tedi:option-content>
    </div>

    <div>
        <strong style="display: block; margin-bottom: 8px;">With Leading Icon</strong>
        <tedi:option-content>
            <x-slot:icon><tedi:icon name="computer" :size="18" /></x-slot:icon>
            <tedi:option-content-label>Desktop</tedi:option-content-label>
        </tedi:option-content>
    </div>

    <div>
        <strong style="display: block; margin-bottom: 8px;">Full Example (Checkbox + Icon + Vertical)</strong>
        <tedi:option-content type="checkbox" layout="vertical" :selected="true">
            <x-slot:icon><tedi:icon name="verified_user" :size="18" /></x-slot:icon>
            <tedi:option-content-label>Admin permissions</tedi:option-content-label>
            <tedi:option-content-meta>Full access to all features and settings</tedi:option-content-meta>
        </tedi:option-content>
    </div>
</div>
