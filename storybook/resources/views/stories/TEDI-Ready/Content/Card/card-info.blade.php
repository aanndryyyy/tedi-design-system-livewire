@storybook([
    'name' => 'Card Info',
    'order' => 4,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<div style="display: flex; flex-direction: column; gap: 1rem;">
    <tedi:card :padding="['vertical' => 0.75, 'horizontal' => 1.25]">
        <tedi:card-content background="brand-tertiary">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <tedi:icon name="assignment_late" background="primary" />
                <div>
                    <tedi:text as="p" modifiers="bold">Pealkiri</tedi:text>
                    <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
                </div>
            </div>
        </tedi:card-content>
    </tedi:card>
    <tedi:card :padding="['vertical' => 0.75, 'horizontal' => 1.25]">
        <tedi:card-content
            background="brand-tertiary"
            background-image="card-background-example.svg"
            background-size="75px"
            background-position="right center"
            background-repeat="no-repeat"
        >
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <tedi:icon name="assignment_late" background="primary" />
                <div>
                    <tedi:text as="p" modifiers="bold">Pealkiri</tedi:text>
                    <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
                </div>
            </div>
        </tedi:card-content>
    </tedi:card>
    <tedi:card border="accent" :padding="['vertical' => 0.75, 'horizontal' => 1.25]">
        <tedi:card-content background="accent">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <tedi:icon name="assignment_late" background="primary" />
                <div>
                    <tedi:text as="p" modifiers="bold">Pealkiri</tedi:text>
                    <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
                </div>
            </div>
        </tedi:card-content>
    </tedi:card>
    <tedi:card border="neutral-primary" :padding="['vertical' => 0.75, 'horizontal' => 1.25]">
        <tedi:card-content background="neutral-primary">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <tedi:icon name="calendar_today" background="primary" variant="filled" />
                <tedi:text as="p" color="secondary">Haigusleht: <strong>118.</strong> päev</tedi:text>
            </div>
        </tedi:card-content>
    </tedi:card>
</div>
