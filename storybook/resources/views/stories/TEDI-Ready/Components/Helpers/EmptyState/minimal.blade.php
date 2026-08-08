@storybook([
    'name' => 'Minimal',
    'order' => 6,
    'status' => 'stable',
    'args' => [
        'type' => 'separate',
        'size' => 'default',
    ],
    'argTypes' => [
        'type' => [
            'control' => 'inline-radio',
            'options' => ['separate', 'attached', 'inside'],
            'description' => 'Container variant.',
        ],
    ],
])

{{--
    Angular passes `icon: null` to hide the icon. Blade's @props defaulting
    treats an explicit `:icon="null"` the same as "not passed" (CONVENTIONS.md
    §3), so an empty string is used instead — see empty-state.blade.php.
--}}
<tedi:empty-state :type="$type" :size="$size" icon="">You have no data to display</tedi:empty-state>
