@storybook([
    'name' => 'Item States',
    'order' => 9,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.46.70?node-id=31693-133265&m=dev',
    'args' => [
        'pseudoStates' => [
            'hover' => ['.tedi-sb-horiz-nav-item--hover-plain', '.tedi-sb-horiz-nav-item--hover-with-icon', '.tedi-sb-horiz-nav-item--hover-with-submenu'],
            'active' => ['.tedi-sb-horiz-nav-item--active-plain', '.tedi-sb-horiz-nav-item--active-with-icon', '.tedi-sb-horiz-nav-item--active-with-submenu'],
            'focusVisible' => ['.tedi-sb-horiz-nav-item--focus-plain', '.tedi-sb-horiz-nav-item--focus-with-icon', '.tedi-sb-horiz-nav-item--focus-with-submenu'],
        ],
    ],
    'argTypes' => [
        'pseudoStates' => ['table' => ['disable' => true]],
    ],
])

@php
    $states = ['Default', 'Hover', 'Active', 'Selected', 'Focus'];
    $columns = [
        'plain' => ['icon' => null, 'submenu' => false],
        'with-icon' => ['icon' => 'home', 'submenu' => false],
        'with-submenu' => ['icon' => 'home', 'submenu' => true],
    ];
@endphp

{{-- The rows the addon drives are Hover/Active/Focus; Selected is ordinary
     markup (is-active), not a pseudo-class. --}}
<tedi:vertical-spacing :size="0.5">
    @foreach ($states as $state)
        <tedi:row>
            <tedi:col :width="2" class="display-flex align-items-center">
                <tedi:text modifiers="bold">{{ $state }}</tedi:text>
            </tedi:col>
            <tedi:col class="display-flex align-items-center">
                <ul style="display: flex; gap: 1rem; list-style: none; margin: 0; padding: 0">
                    @foreach ($columns as $key => $column)
                        <tedi:top-nav-item
                            href="#"
                            :icon="$column['icon']"
                            :has-submenu="$column['submenu']"
                            :is-active="$state === 'Selected'"
                            class="tedi-sb-horiz-nav-item--{{ strtolower($state) }}-{{ $key }}"
                        >Item</tedi:top-nav-item>
                    @endforeach
                </ul>
            </tedi:col>
        </tedi:row>
    @endforeach
</tedi:vertical-spacing>
