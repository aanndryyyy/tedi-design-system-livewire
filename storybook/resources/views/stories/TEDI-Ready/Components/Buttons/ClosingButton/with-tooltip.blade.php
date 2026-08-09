{{--
    Wrap the button in a `tedi-tooltip` to show a custom tooltip on hover.
    Set `:show-title="false"` so the native browser tooltip (the `title`
    attribute) does not double the custom one — the `aria-label` is kept.

    `description` / `description-id` / `described-by` are the CONVENTIONS.md §5
    translation of behaviour Angular gets for free: `tedi-tooltip` reads its
    projected content's textContent at runtime to build an sr-only description
    and points the trigger's `aria-describedby` at it. Blade cannot read its own
    slot, so the text is spelled out. The closing button is natively focusable,
    so the trigger needs no synthesized tabindex or focus ring.
--}}
@storybook([
    'name' => 'With Tooltip',
    'order' => 5,
    'status' => 'stable',
])

<div class="flex gap-2">
    <tedi:tooltip description="Sulge" description-id="closing-tooltip-close">
        <tedi:tooltip-trigger described-by="closing-tooltip-close">
            <tedi:closing-button :show-title="false" />
        </tedi:tooltip-trigger>
        <tedi:tooltip-content>Sulge</tedi:tooltip-content>
    </tedi:tooltip>

    <tedi:tooltip description="Kustuta" description-id="closing-tooltip-delete">
        <tedi:tooltip-trigger described-by="closing-tooltip-delete">
            <tedi:closing-button icon="delete" :show-title="false" aria-label="Kustuta" />
        </tedi:tooltip-trigger>
        <tedi:tooltip-content>Kustuta</tedi:tooltip-content>
    </tedi:tooltip>
</div>
