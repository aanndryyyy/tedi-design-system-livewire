{{--
    Angular adds `tedi-tooltip-trigger--focus` and `tabindex="0"` to a projected
    element that is not natively focusable. Blade cannot reach into the slot to
    mutate consumer markup, so the span carries them itself — CONVENTIONS.md §5.
--}}
@storybook([
    'name' => 'Custom Content',
    'order' => 5,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

<tedi:tooltip>
    <tedi:tooltip-trigger>
        <span class="tedi-tooltip-trigger--focus" tabindex="0">Trigger</span>
    </tedi:tooltip-trigger>
    <tedi:tooltip-content>
        This <b>tooltip trigger</b> does not have an <u>underline,</u> because it is <i>wrapped in a span.</i>
    </tedi:tooltip-content>
</tedi:tooltip>
