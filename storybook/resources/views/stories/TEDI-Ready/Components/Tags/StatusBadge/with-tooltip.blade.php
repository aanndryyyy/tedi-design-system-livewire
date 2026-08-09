{{--
    An icon-only badge carries no accessible text of its own, so the tooltip is
    what supplies its meaning.

    Two things Angular does at runtime and Blade must be told (CONVENTIONS.md §5,
    and the table in tooltip-trigger.blade.php's header):

      * The sr-only description. Angular reads the tooltip content's textContent
        and wires `aria-describedby` to it; here it is the `description` /
        `description-id` / `described-by` trio.
      * The focus affordance. Angular adds `tedi-tooltip-trigger--focus` and
        `tabindex="0"` to a projected child that is not natively focusable. A
        badge renders a <div>, so those are written here by hand — the documented
        consumer pattern, since a server-rendered template cannot reach into the
        element the consumer wrote.
--}}
@storybook([
    'name' => 'With Tooltip',
    'order' => 5,
    'status' => 'stable',
])

<tedi:tooltip
    description="Icon-only badges should always have a tooltip to provide context and ensure accessibility."
    description-id="status-badge-tooltip"
>
    <tedi:tooltip-trigger described-by="status-badge-tooltip">
        <tedi:status-badge
            icon="warning"
            color="warning"
            class="tedi-tooltip-trigger--focus"
            tabindex="0"
        />
    </tedi:tooltip-trigger>
    <tedi:tooltip-content>
        Icon-only badges should always have a tooltip to provide context and ensure accessibility.
    </tedi:tooltip-content>
</tedi:tooltip>
