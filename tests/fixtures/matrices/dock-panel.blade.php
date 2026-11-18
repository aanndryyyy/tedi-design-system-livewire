{{--
    Variant matrix for tedi:dock-panel and tedi:dock-panel-layout, ported from
    @tedi-design-system/react community/components/dock-panel (CONVENTIONS.md §13).

    Harvested by IntegrityTest::test_rendered_matrix_classes_exist_in_stylesheet.
    Covers every placement × size × collapsed state, both shells (docked, and
    the sheet that `mobile` other than false renders beside it), and the
    optional header actions, description, footer and close button.
--}}
@php
    $dockPanelLabels = ['collapse' => 'Minimize panel', 'expand' => 'Restore panel', 'close' => 'Close panel'];
@endphp

@foreach (['left', 'right', 'top', 'bottom'] as $placement)
    @foreach (['default', 'small'] as $size)
        @foreach ([false, true] as $collapsed)
            <tedi:dock-panel-layout style="height: 20rem">
                <p>Workspace</p>
                <x-slot:panel>
                    <tedi:dock-panel
                        title="Details"
                        :labels="$dockPanelLabels"
                        :placement="$placement"
                        :size="$size"
                        :collapsed="$collapsed"
                        :mobile="$collapsed ? false : null"
                    >
                        <p>Content</p>
                    </tedi:dock-panel>
                </x-slot:panel>
            </tedi:dock-panel-layout>
        @endforeach
    @endforeach
@endforeach

<tedi:dock-panel-layout style="height: 20rem">
    <p>Workspace</p>
    <x-slot:panel>
        <tedi:dock-panel
            title="Details of the selected item"
            short-title="Item details"
            description="Additional information about this item."
            :labels="$dockPanelLabels"
            closable
            width="md"
            height="auto"
            :max-height="512"
        >
            <p>Content</p>
            <x-slot:header-actions><tedi:button variant="secondary" size="small">Example action</tedi:button></x-slot:header-actions>
            <x-slot:footer><tedi:button variant="secondary">Cancel</tedi:button></x-slot:footer>
        </tedi:dock-panel>
    </x-slot:panel>
</tedi:dock-panel-layout>

{{-- Resizable: the measurement state the server renders, per placement. --}}
@foreach (['left', 'right', 'top', 'bottom'] as $placement)
    <tedi:dock-panel-layout style="height: 20rem">
        <p>Workspace</p>
        <x-slot:panel>
            <tedi:dock-panel title="Details" :labels="$dockPanelLabels" :placement="$placement" resizable :mobile="false">
                <p>Content</p>
            </tedi:dock-panel>
        </x-slot:panel>
    </tedi:dock-panel-layout>
@endforeach
