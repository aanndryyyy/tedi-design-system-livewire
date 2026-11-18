@storybook([
    'name' => 'Accessibility: no visible label',
    'order' => 17,
    'status' => 'stable',
    'args' => [],
])

<tedi:search
    input-id="search-accessible"
    label="Otsi tooteid või teenuseid"
    :hide-label="true"
    placeholder="Otsi tooteid või teenuseid..."
/>
