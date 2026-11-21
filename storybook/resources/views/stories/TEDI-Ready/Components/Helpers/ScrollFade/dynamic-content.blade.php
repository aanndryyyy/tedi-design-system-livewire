@storybook([
    'name' => 'Dynamic Content',
    'order' => 6,
    'status' => 'subset',
    'args' => [
        'scrollBar' => 'custom',
        'fadeSize' => 20,
        'fadePosition' => 'both',
        'ariaLabel' => '',
    ],
    'argTypes' => [
        'scrollBar' => [
            'control' => 'radio',
            'options' => ['default', 'custom'],
            'description' => 'Scrollbar style.',
        ],
        'fadeSize' => [
            'control' => 'radio',
            'options' => [0, 10, 20],
            'description' => 'Size of the fade gradient in percentages.',
        ],
        'fadePosition' => [
            'control' => 'radio',
            'options' => ['top', 'bottom', 'both'],
            'description' => 'Which edges show the fade.',
        ],
        'ariaLabel' => [
            'control' => 'text',
            'description' => 'Accessible label for the scrollable region. Falls back to a translated default.',
        ],
    ],
])

{{--
    Upstream's item list is component state; here it is an Alpine x-data
    around the scroll fade. Adding items past the 150px cap must turn the
    bottom fade on without a scroll.
--}}
<div class="flex flex-column gap-3" x-data="{ items: [1, 2, 3, 4, 5, 6] }">
    <div class="flex gap-3">
        <tedi:button x-on:click="items.push(items.length + 1)">Add item</tedi:button>
        <tedi:button variant="secondary" x-on:click="items.pop()">Remove item</tedi:button>
    </div>
    <div style="max-width: 200px; max-height: 150px;">
        <tedi:scroll-fade :scroll-bar="$scrollBar" :fade-size="$fadeSize" :fade-position="$fadePosition" :aria-label="$ariaLabel ?: null">
            <div class="flex flex-column gap-3">
                <template x-for="item in items" :key="item">
                    <p x-text="'Item ' + item"></p>
                </template>
            </div>
        </tedi:scroll-fade>
    </div>
</div>
