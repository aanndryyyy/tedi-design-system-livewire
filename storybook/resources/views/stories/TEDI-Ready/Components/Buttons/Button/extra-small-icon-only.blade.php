@storybook([
    'name' => 'Extra Small Icon Only',
    'order' => 11,
    'status' => 'stable',
])

{{-- The extra small size is only available for icon-only buttons. It does not grow on mobile. --}}
<div style="display: flex; align-items: center; gap: 1rem;">
    <tedi:button size="extra-small" icon-only icon-start="close" aria-label="Close" />
    <tedi:button variant="secondary" size="extra-small" icon-only icon-start="edit" aria-label="Edit" />
    <tedi:button variant="neutral" size="extra-small" icon-only icon-start="more_vert" aria-label="More" />
    <tedi:button size="extra-small" icon-only icon-start="close" aria-label="Close" disabled />
</div>
