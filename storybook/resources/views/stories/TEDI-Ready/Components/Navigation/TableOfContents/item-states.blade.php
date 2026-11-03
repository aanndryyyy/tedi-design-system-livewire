{{--
    Angular forces the Hover row with storybook-addon-pseudo-states, targeting
    `.toc-item-states__hover` on the link (storybook/CONTRACT.md §5).
--}}
@storybook([
    'name' => 'Item States',
    'order' => 2,
    'status' => 'stable',
    'args' => ['pseudoStates' => ['hover' => '.toc-item-states__hover']],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

@php
    $variants = [
        ['header' => 'Default', 'numbered' => false, 'icon' => false, 'separator' => false, 'bordered' => false],
        ['header' => 'With number', 'numbered' => true, 'icon' => false, 'separator' => false, 'bordered' => false],
        ['header' => 'With icon', 'numbered' => false, 'icon' => true, 'separator' => false, 'bordered' => false],
        ['header' => 'With separator', 'numbered' => false, 'icon' => false, 'separator' => true, 'bordered' => false],
        ['header' => 'With border', 'numbered' => false, 'icon' => false, 'separator' => false, 'bordered' => true],
    ];
    $states = [
        ['id' => 'default', 'label' => 'Default', 'hover' => false],
        ['id' => 'hover', 'label' => 'Hover', 'hover' => true],
        ['id' => 'selected', 'label' => 'Selected', 'hover' => false],
    ];
@endphp

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(12rem, 1fr)); gap: 1.5rem; padding: 1rem;">
    @foreach ($variants as $variant)
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <tedi:text as="p" modifiers="bold">{{ $variant['header'] }}</tedi:text>
            <tedi:table-of-contents
                heading=""
                :sticky="false"
                :numbered="$variant['numbered']"
                :bordered="$variant['bordered']"
                active-id="selected"
                :aria-label="$variant['header'].' states'"
            >
                @foreach ($states as $state)
                    <tedi:table-of-contents-item :item-id="$state['id']" :separator="$variant['separator']">
                        <tedi:link
                            href="#"
                            :underline="false"
                            :icon-start="$variant['icon'] ? 'mail' : null"
                            :class="$state['hover'] ? 'toc-item-states__hover' : null"
                        >{{ $state['label'] }}</tedi:link>
                    </tedi:table-of-contents-item>
                @endforeach
                @if ($variant['bordered'])
                    {{-- 'bordered' drops the divider under the last item (the card supplies
                         that edge in real use). This inert trailing row keeps the Selected
                         state above it non-last, so it shows its real divider like the design. --}}
                    <tedi:table-of-contents-item item-id="filler" aria-hidden="true" />
                @endif
            </tedi:table-of-contents>
        </div>
    @endforeach
</div>
