@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.30.43?node-id=8048-69789&t=aqojgjkZcOYAN35p-0',
    'args' => [
        'allowMultiple' => false,
        'accordionDefaultExpanded' => false,
        'headerClickable' => true,
        'titleLayout' => 'hug',
        'showExpandLabel' => true,
        'showDefaultExpandAction' => true,
        'expandActionPosition' => 'end',
        'expandActionArrowType' => 'default',
        'expandActionInverted' => false,
        'expandActionUnderline' => false,
        'defaultExpanded' => false,
        'showIconCard' => false,
        'selected' => false,
        'disabled' => false,
    ],
    'argTypes' => [
        'allowMultiple' => [
            'control' => 'boolean',
            'description' => 'Whether multiple accordion items can be opened at once.',
            'table' => ['category' => 'Accordion', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'accordionDefaultExpanded' => [
            'name' => 'defaultExpanded',
            'control' => 'boolean',
            'description' => "Group-level default for items' initial expanded state. Items use this value when they don't specify their own `defaultExpanded`. Per-item overrides (including explicit `false`) take precedence.",
            'table' => ['category' => 'Accordion', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'defaultExpanded' => [
            'control' => 'boolean',
            'description' => 'Whether the accordion item is initially expanded or collapsed.',
            'table' => ['category' => 'Accordion Item', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'showIconCard' => [
            'control' => 'boolean',
            'description' => 'Whether to show the icon card.',
            'table' => ['category' => 'Accordion Item', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'selected' => [
            'control' => 'boolean',
            'description' => "Whether the accordion item is selected. Applies a visual 'selected' state to the accordion item.",
            'table' => ['category' => 'Accordion Item', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'disabled' => [
            'control' => 'boolean',
            'description' => 'Disables the item — the header trigger becomes non-interactive and the expanded state can no longer be toggled by user interaction. The current state is preserved.',
            'table' => ['category' => 'Accordion Item', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'headerClickable' => [
            'control' => 'boolean',
            'description' => 'Defines whether the entire header acts as the toggle trigger. `true` (default): the header is rendered as a button. `false`: the header is rendered as a non-interactive container, and a separate expand action is shown alongside it.',
            'table' => ['category' => 'Accordion Item Header', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'true']],
        ],
        'titleLayout' => [
            'control' => 'radio',
            'options' => ['hug', 'fill'],
            'description' => "Controls how the title stretches. `hug`: wraps tightly around content. `fill`: expands to available space and pushes trailing elements to the end.",
            'table' => ['category' => 'Accordion Item Header', 'type' => ['summary' => "'hug' | 'fill'"], 'defaultValue' => ['summary' => 'hug']],
        ],
        'showExpandLabel' => [
            'control' => 'boolean',
            'description' => 'Whether to show the expand/collapse labels.',
            'table' => ['category' => 'Accordion Item Header', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'true']],
        ],
        'showDefaultExpandAction' => [
            'control' => 'boolean',
            'description' => 'Whether to show the default expand/collapse icon. If false, you can add your own expand icon with slots.',
            'table' => ['category' => 'Accordion Item Header', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'true']],
        ],
        'expandActionPosition' => [
            'control' => 'radio',
            'options' => ['start', 'end'],
            'description' => 'Position of the expand/collapse action.',
            'table' => ['category' => 'Accordion Item Header', 'type' => ['summary' => "'start' | 'end'"], 'defaultValue' => ['summary' => 'end']],
        ],
        'expandActionArrowType' => [
            'control' => 'radio',
            'options' => ['default', 'secondary'],
            'description' => "Chevron style of the default expand action. Only effective when `headerClickable` is `false` and `showExpandLabel` is `false`.",
            'table' => ['category' => 'Accordion Item Header', 'type' => ['summary' => "'default' | 'secondary'"], 'defaultValue' => ['summary' => 'default']],
        ],
        'expandActionInverted' => [
            'control' => 'boolean',
            'description' => 'Use the inverted (light-on-dark) palette for the default expand action. Only effective when `headerClickable` is `false`.',
            'table' => ['category' => 'Accordion Item Header', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'expandActionUnderline' => [
            'control' => 'boolean',
            'description' => "Whether the default expand action's label is underlined. Only effective when `headerClickable` is `false` and `showExpandLabel` is `true`.",
            'table' => ['category' => 'Accordion Item Header', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
    ],
])

{{--
    The accordion item is composed of three parts, each owning its own configuration:
    - tedi:accordion-item: owns the item's state (expanded) and inputs shared by header/content (selected, showIconCard, defaultExpanded).
    - tedi:accordion-item-header: owns header appearance/interaction (titleLayout, headerClickable, expand labels, etc.). Put the title and any extras in the corresponding named slots.
    - tedi:accordion-item-content: owns content styling (contentClass).
--}}
<tedi:accordion :allow-multiple="(bool) $allowMultiple" :default-expanded="(bool) $accordionDefaultExpanded">
    <tedi:accordion-item :default-expanded="(bool) $defaultExpanded" :show-icon-card="(bool) $showIconCard" :selected="(bool) $selected" :disabled="(bool) $disabled">
        <x-slot:icon-card>
            <span class="tedi-accordion-icon-card">
                <tedi:icon name="business_center" color="secondary" :size="24" />
                <tedi:text color="secondary" modifiers="bold">Kategooria</tedi:text>
            </span>
        </x-slot:icon-card>
        <tedi:accordion-item-header
            :header-clickable="(bool) $headerClickable"
            :title-layout="$titleLayout"
            :show-expand-label="(bool) $showExpandLabel"
            :show-default-expand-action="(bool) $showDefaultExpandAction"
            :expand-action-position="$expandActionPosition"
            :expand-action-arrow-type="$expandActionArrowType"
            :expand-action-inverted="(bool) $expandActionInverted"
            :expand-action-underline="(bool) $expandActionUnderline"
        >
            <x-slot:title>Pealkiri</x-slot:title>
            <x-slot:after-title>
                <tedi:status-badge color="success" text="Kinnitatud" />
            </x-slot:after-title>
        </tedi:accordion-item-header>
        <tedi:accordion-item-content>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
            ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
            laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
            voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat
            non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </tedi:accordion-item-content>
    </tedi:accordion-item>
    <tedi:accordion-item>
        <tedi:accordion-item-header expand-action-position="end">
            <x-slot:title>Pealkiri 2</x-slot:title>
        </tedi:accordion-item-header>
        <tedi:accordion-item-content>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
            ut labore et dolore magna aliqua.
        </tedi:accordion-item-content>
    </tedi:accordion-item>
</tedi:accordion>
