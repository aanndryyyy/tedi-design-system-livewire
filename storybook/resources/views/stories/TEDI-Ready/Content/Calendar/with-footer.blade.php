@storybook([
    'name' => 'With Footer',
    'order' => 13,
    'status' => 'subset',
    'args' => [],
])

<div style="display: flex; flex-direction: column; gap: 1rem;">
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <tedi:calendar :current-month="date('Y-m-01')">
            <x-slot:footer>
                <div style="display: flex; justify-content: center; width: 100%;">
                    <tedi:button variant="neutral" size="small" icon-start="schedule">Select time</tedi:button>
                </div>
            </x-slot:footer>
        </tedi:calendar>
        <tedi:calendar :current-month="date('Y-m-01')">
            <x-slot:footer>
                <div style="display: flex; justify-content: center; width: 100%;">
                    <tedi:button icon-end="arrow_forward">Search times</tedi:button>
                </div>
            </x-slot:footer>
        </tedi:calendar>
    </div>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <tedi:calendar :current-month="date('Y-m-01')">
            <x-slot:footer>
                <div style="display: flex; justify-content: center; width: 100%;">
                    <tedi:button variant="secondary" size="small">Cancel selection</tedi:button>
                </div>
            </x-slot:footer>
        </tedi:calendar>
        <tedi:calendar :current-month="date('Y-m-01')">
            <x-slot:footer>
                <div style="display: flex; gap: 0.5rem; width: 100%;">
                    <tedi:button variant="secondary" size="small" style="flex: 1;">Cancel</tedi:button>
                    <tedi:button size="small" style="flex: 1;">Save</tedi:button>
                </div>
            </x-slot:footer>
        </tedi:calendar>
    </div>
</div>
