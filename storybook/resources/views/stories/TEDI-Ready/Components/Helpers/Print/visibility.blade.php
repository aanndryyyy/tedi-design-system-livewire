@storybook([
    'name' => 'Visibility',
    'order' => 2,
    'status' => 'subset',
])

{{-- Upstream's "Printed anyway" button carries `tediPrint="show"` on the button
     itself, cancelling the `no-print` the button sets on that same element.
     A `tedi:print` wrapper cannot do that (core's rule is
     `.no-print:not(.show-print)`), so the class goes on the button directly. --}}
<div style="display: grid; gap: 8px;">
    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
        <tedi:button>Hidden when printing (default)</tedi:button>
        <tedi:button class="show-print">Printed anyway</tedi:button>
    </div>
    <tedi:print visibility="hide">
        <p>This paragraph prints on screen only.</p>
    </tedi:print>
    <tedi:print visibility="show">
        <p>
            This paragraph has no <code>no-print</code> to cancel, so
            <code>show</code> changes nothing for it.
        </p>
    </tedi:print>
</div>
