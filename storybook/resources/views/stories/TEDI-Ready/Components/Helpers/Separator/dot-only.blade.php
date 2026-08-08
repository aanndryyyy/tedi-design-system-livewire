@storybook([
    'name' => 'Dot Only',
    'order' => 10,
    'status' => 'stable',
    'args' => [],
])

<tedi:row :gap="3">
    <div style="display:flex;align-items:center;gap:1rem">
        <tedi:separator color="secondary" variant="dot-only" dot-size="extra-small" />
        <tedi:separator color="secondary" variant="dot-only" dot-size="small" />
        <tedi:separator color="secondary" variant="dot-only" dot-size="medium" />
        <tedi:separator color="secondary" variant="dot-only" dot-size="large" />
    </div>
    <div style="display:flex;align-items:center;gap:1rem">
        <tedi:separator color="secondary" variant="dot-only" dot-size="extra-small" :dot-filled="false" :thickness="1" />
        <tedi:separator color="secondary" variant="dot-only" dot-size="small" :dot-filled="false" :thickness="1" />
        <tedi:separator color="secondary" variant="dot-only" dot-size="medium" :dot-filled="false" :thickness="1" />
        <tedi:separator color="secondary" variant="dot-only" dot-size="large" :dot-filled="false" :thickness="1" />
    </div>
    <div style="display:flex;align-items:center;gap:1rem">
        <tedi:separator color="secondary" variant="dot-only" dot-size="extra-small" :dot-filled="false" :thickness="2" />
        <tedi:separator color="secondary" variant="dot-only" dot-size="small" :dot-filled="false" :thickness="2" />
        <tedi:separator color="secondary" variant="dot-only" dot-size="medium" :dot-filled="false" :thickness="2" />
        <tedi:separator color="secondary" variant="dot-only" dot-size="large" :dot-filled="false" :thickness="2" />
    </div>
</tedi:row>
