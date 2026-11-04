@storybook([
    'name' => 'Placement',
    'order' => 6,
    'status' => 'stable',
])

<style>
    .floating-placement-side {
        position: absolute; top: 50%; right: 0; translate: 0 -50%;
        display: flex; align-items: center; justify-content: center;
        width: var(--button-md-icon-size); height: 12rem;
    }
</style>

<div style="position: relative; height: 20rem; border: 1px solid var(--general-border-primary);">
    <tedi:floating-button
        position="absolute"
        variant="secondary"
        :placement="['vertical' => 'top', 'horizontal' => 'left']"
        :offset="['top' => 16, 'left' => 16]"
    >Üleval vasakul</tedi:floating-button>
    <tedi:floating-button
        position="absolute"
        variant="secondary"
        :placement="['vertical' => 'bottom', 'horizontal' => 'center']"
        :offset="['bottom' => 16]"
    >All keskel</tedi:floating-button>
    <div class="floating-placement-side">
        <tedi:floating-button position="static" axis="vertical">Anna tagasiside</tedi:floating-button>
    </div>
</div>
