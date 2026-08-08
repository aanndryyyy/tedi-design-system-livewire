@storybook([
    'name' => 'Slots',
    'order' => 5,
    'status' => 'subset',
])

{{--
    Angular lays the two pickers out with <tedi-row cols="1" [md]="{ cols: 2 }">.
    Breakpoint props are not ported (CONVENTIONS.md §7 item 1), so this uses the
    base two-column layout directly.
--}}
@php
    $slots = ['09:30', '10:00', '11:30', '15:30', '18:30', '20:30'];
@endphp

<tedi:row :cols="2" :gap-y="3">
    <tedi:col>
        <tedi:text as="p" modifiers="small bold">Without indicator</tedi:text>
        <tedi:time-picker
            variant="slots"
            value="11:30"
            :time-slots="$slots"
            :columns="3"
            :border="true"
        />
    </tedi:col>
    <tedi:col>
        <tedi:text as="p" modifiers="small bold">With indicator</tedi:text>
        <tedi:time-picker
            variant="slots"
            value="11:30"
            :time-slots="$slots"
            :columns="3"
            :show-slot-indicator="true"
            :border="true"
        />
    </tedi:col>
</tedi:row>
