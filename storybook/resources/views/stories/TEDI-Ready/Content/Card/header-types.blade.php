@storybook([
    'name' => 'Header Types',
    'order' => 2,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

{{--
    Angular's story wraps the share/print icon buttons in <tedi-tooltip>.
    Overlay positioning isn't ported (README divergence table), so those
    buttons render without the tooltip wrapper here — everything else in
    this composition gallery is unaffected.
--}}
<div style="display: flex; flex-direction: column; gap: 1rem;">
    <tedi:card>
        <tedi:card-header background="primary">
            <tedi:text as="h3">Pealkiri</tedi:text>
        </tedi:card-header>
    </tedi:card>
    <tedi:card>
        <tedi:card-header background="primary">
            <tedi:text as="h3">Pealkiri</tedi:text>
            <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
        </tedi:card-header>
    </tedi:card>
    <tedi:card>
        <tedi:card-header background="primary">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                <tedi:text as="h3">Pealkiri</tedi:text>
                <tedi:button>Lisa uus</tedi:button>
            </div>
            <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
        </tedi:card-header>
    </tedi:card>
    <tedi:card>
        <tedi:card-header background="primary">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                <tedi:text as="h3">Pealkiri</tedi:text>
                <div style="display: flex; gap: 0.5rem;">
                    <tedi:button variant="secondary" aria-label="Jaga">
                        <tedi:icon name="share" />
                        Jaga
                    </tedi:button>
                    <tedi:button variant="secondary" aria-label="Prindi">
                        <tedi:icon name="print" />
                        Prindi
                    </tedi:button>
                </div>
            </div>
            <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
        </tedi:card-header>
    </tedi:card>
    <tedi:card>
        <tedi:card-header background="primary">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                <tedi:text as="h3">Pealkiri</tedi:text>
                <tedi:link href="#">
                    Vaata tulemust
                    <tedi:icon name="arrow_right_alt" />
                </tedi:link>
            </div>
            <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
        </tedi:card-header>
    </tedi:card>
    <tedi:card>
        <tedi:card-header background="primary">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                <tedi:text as="h3">Pealkiri</tedi:text>
                <tedi:status-badge color="brand" text="Kinnitatud" />
            </div>
            <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
        </tedi:card-header>
    </tedi:card>
    <tedi:card>
        <tedi:card-header background="secondary">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                <tedi:text as="h3">Pealkiri</tedi:text>
                <tedi:button>Lisa uus</tedi:button>
            </div>
            <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
        </tedi:card-header>
    </tedi:card>
    <tedi:card>
        <tedi:card-header background="tertiary">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                <tedi:text as="h3">Pealkiri</tedi:text>
                <tedi:button>Lisa uus</tedi:button>
            </div>
            <tedi:text as="p" color="secondary">Kirjeldus</tedi:text>
        </tedi:card-header>
    </tedi:card>
    <tedi:card>
        <tedi:card-header background="brand-primary">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                <tedi:text as="h3" color="white">Pealkiri</tedi:text>
                <tedi:button variant="primary-inverted">Lisa uus</tedi:button>
            </div>
            <tedi:text as="p" color="white">Kirjeldus</tedi:text>
        </tedi:card-header>
    </tedi:card>
    <tedi:card>
        <tedi:card-header background="brand-secondary">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                <tedi:text as="h3" color="white">Pealkiri</tedi:text>
                <tedi:button variant="primary-inverted">Lisa uus</tedi:button>
            </div>
            <tedi:text as="p" color="white">Kirjeldus</tedi:text>
        </tedi:card-header>
    </tedi:card>
</div>
