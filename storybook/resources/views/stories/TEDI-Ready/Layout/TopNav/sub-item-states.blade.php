@storybook([
    'name' => 'Sub Item States',
    'order' => 10,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.46.70?node-id=31693-133265&m=dev',
    'args' => [
        'pseudoStates' => [
            'hover' => ['.tedi-sb-horiz-nav-subitem--hover'],
            'active' => ['.tedi-sb-horiz-nav-subitem--active'],
            'focusVisible' => ['.tedi-sb-horiz-nav-subitem--focus'],
        ],
    ],
    'argTypes' => [
        'pseudoStates' => ['table' => ['disable' => true]],
    ],
])

@php $states = ['Default', 'Hover', 'Active', 'Selected', 'Focus']; @endphp

<div style="padding: 1.5rem; background-color: var(--navigation-horizontal-submenu-background)">
    <tedi:vertical-spacing :size="0.5">
        @foreach ($states as $state)
            <tedi:row>
                <tedi:col :width="2" class="display-flex align-items-center">
                    <tedi:text modifiers="bold" color="white">{{ $state }}</tedi:text>
                </tedi:col>
                <tedi:col>
                    <ul style="list-style: none; margin: 0; padding: 0; width: 240px">
                        <tedi:top-nav-subitem
                            href="#"
                            :is-active="$state === 'Selected'"
                            class="tedi-sb-horiz-nav-subitem--{{ strtolower($state) }}"
                        >Placeholder link</tedi:top-nav-subitem>
                    </ul>
                </tedi:col>
            </tedi:row>
        @endforeach
    </tedi:vertical-spacing>
</div>
