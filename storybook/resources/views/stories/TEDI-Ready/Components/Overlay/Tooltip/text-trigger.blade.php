{{--
    Angular detects a plain-text trigger at runtime and wraps it in a
    `.tedi-tooltip-trigger__text` span. Blade cannot introspect its own slot, so
    that is the explicit `:text="true"` prop — CONVENTIONS.md §5.
--}}
@storybook([
    'name' => 'Text Trigger',
    'order' => 3,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

<p>
    Tooltip works even inside a text. Hover over
    <tedi:tooltip>
        <tedi:tooltip-trigger :text="true">this</tedi:tooltip-trigger>
        <tedi:tooltip-content>
            If tooltip trigger is a text, it will have an underline.
        </tedi:tooltip-content>
    </tedi:tooltip>
    text to see the tooltip.
</p>
