{{--
    The two ways an info button is used in practice: a tooltip for a short
    explanation, a popover for content with its own actions.

    Three places Blade spells out what Angular resolves at runtime:

      * The tooltip's sr-only description (CONVENTIONS.md §5) — `description` /
        `description-id` on the tooltip, `described-by` on the trigger. The info
        button is natively focusable, so the trigger adds no tabindex or focus
        ring, exactly as Angular skips them there.
      * The popover trigger. Angular stacks `tedi-popover-trigger` on the same
        element as `tedi-info-button`; the Blade equivalent applies the wiring to
        `<tedi:info-button>` directly, which is the pattern documented in
        popover-trigger.blade.php's header rather than an invented prop.
      * `labelled-by`. The popover content has no title, so the panel's
        accessible name comes from the trigger — pointed at by id, since sibling
        components share no DI channel here (see popover.blade.php's header).

    The projected `<tedi-icon>` inside the Angular link becomes the link's own
    `icon-end` prop (CONVENTIONS.md §5).
--}}
@storybook([
    'name' => 'Usage With Tooltip And Popover',
    'order' => 4,
    'status' => 'stable',
])

<div class="flex">
    <tedi:text-group type="vertical">
        <x-slot:label>
            <span class="flex align-items-center gap-1">
                Veregrupp
                <tedi:tooltip
                    description="Veregrupp määratakse vereanalüüsiga ning see ei muutu elu jooksul."
                    description-id="info-button-veregrupp"
                >
                    <tedi:tooltip-trigger described-by="info-button-veregrupp">
                        <tedi:info-button />
                    </tedi:tooltip-trigger>
                    <tedi:tooltip-content>
                        Veregrupp määratakse vereanalüüsiga ning see ei muutu elu jooksul.
                    </tedi:tooltip-content>
                </tedi:tooltip>
            </span>
        </x-slot:label>

        <x-slot:value>AB-</x-slot:value>
    </tedi:text-group>

    <tedi:separator axis="vertical" size="auto" :spacing="['x' => 1.5, 'y' => 0.25]" />

    <tedi:text-group type="vertical">
        <x-slot:label>
            <span class="flex align-items-center gap-1">
                Hambaravihüvitise jääk
                <tedi:popover labelled-by="info-button-hambaravi-trigger">
                    <x-slot:trigger>
                        <tedi:info-button
                            id="info-button-hambaravi-trigger"
                            tedi-popover-trigger
                            role="button"
                            aria-haspopup="dialog"
                            aria-expanded="false"
                            x-ref="trigger"
                            x-on:click="toggle()"
                            x-bind:aria-expanded="open"
                        />
                    </x-slot:trigger>

                    <tedi:popover-content max-width="medium">
                        <tedi:text as="p">Hambaravihüvitist saab kasutada jooksva kalendriaasta jooksul. Kasutamata jääk järgmisesse aastasse ei kandu.</tedi:text>
                        <div class="text-right">
                            <tedi:link href="#" :underline="false" icon-end="arrow_forward">Loe rohkem</tedi:link>
                        </div>
                    </tedi:popover-content>
                </tedi:popover>
            </span>
        </x-slot:label>

        <x-slot:value>24€</x-slot:value>
    </tedi:text-group>
</div>
