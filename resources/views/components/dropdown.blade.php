{{--
    TEDI Dropdown (root).
    Port of angular/tedi/components/overlay/dropdown/dropdown.component.{ts,html}

    Angular's host has no class bindings — `tedi-dropdown` is styled purely via
    the vendored SCSS's element selector (`tedi-dropdown { display: inline-flex }`),
    so the custom tag is emitted literally (CONVENTIONS.md §4).

    Positioning is CDK Overlay upstream; here it comes from the shared
    `tediOverlay` engine in resources/js/src/overlay.js (CONVENTIONS.md §11), a
    direct port of upstream's overlay-position.util.ts. This component's
    `x-data` is `tediDropdown` (resources/js/src/dropdown.js), which composes
    that engine and adds the keyboard layer. Config mapping:

      position        -> placement
      offset          -> offset - 8, because tediOverlay's `offset` is *extra* px
                         on top of an 8px base gap, whereas upstream's dropdown
                         REPLACES the base gap (Math.sign(offsetY) * offset).
                         The default 4 therefore arrives as -4 (§11, last bullet).
      preventOverflow -> preventOverflow
      hideOnScroll    -> hideOnScroll
      (always)        -> matchTriggerWidth: true, which sets
                         --_tedi-dropdown-trigger-width on the panel, matching
                         Angular's [style.--_tedi-dropdown-trigger-width].

    Angular projects the trigger and the content separately
    (`<ng-content select="[tedi-dropdown-trigger]">` + the overlay template);
    in Blade both are written into the default slot, and
    <tedi:dropdown-content> renders the `.tedi-dropdown__panel` wrapper itself.

    KNOWN DIVERGENCES:

    - `value` does NOT drive item selection. Angular's items read it through
      `inject(DROPDOWN_API)`; Blade's only ambient channel is @aware, which
      resolves the *item's own* `value` attribute first (Laravel merges the
      current component's data into `currentComponentData` before the template
      runs), so every item would compare its value with itself. Selection is
      therefore an explicit `:selected` prop on <tedi:dropdown-item>
      (CONVENTIONS.md §5). This prop is kept for input parity (§9.2) and is
      what a consumer echoes back into `:selected`.
    - `container-id` replaces Angular's generated `containerId`: Blade siblings
      have no shared instance to generate an id into (same situation as
      accordion-item's `item-id`). Given, it wires
      trigger `id`/`aria-controls` to the panel's `id` and the content's
      `aria-labelledby`; omitted, those attributes are left off entirely rather
      than emitted dangling.
    - Focus management IS ported, via `tediDropdown` — `tediOverlay` plus the
      keyboard layer from dropdown.component.ts / dropdown-item.component.ts /
      dropdown-trigger.directive.ts (roving tabindex, Arrow/Home/End, Enter and
      Space activation, `tabOutOfDropdown`, ArrowDown/ArrowUp open-and-focus).
      Upstream has no focus trap in the dropdown either — Tab leaves the panel
      by design — so nothing is missing here. See CONVENTIONS.md §11.
      One divergence: Angular reads items and their disabled/selected state from
      `contentChildren` signals; Blade has no instances to query, so the engine
      reads the DOM the templates already emit (`li[tedi-dropdown-item]`,
      `aria-disabled`, `aria-selected`).
    - The panel stays in the document instead of being re-parented into a CDK
      overlay container, so a dropdown inside a `transform`ed ancestor is
      positioned relative to that ancestor (CONVENTIONS.md §11).
--}}
@props([
    /** Current value of dropdown (used with listbox). Informational — see the note above. */
    'value' => null,
    /** The position of the dropdown relative to the trigger element. */
    'position' => 'bottom-start',
    /** Should position to opposite direction when overflowing screen? */
    'preventOverflow' => true,
    /** Gap in px between the trigger and the dropdown panel. */
    'offset' => 4,
    /** Does the dropdown hide when the page scrolls? */
    'hideOnScroll' => false,
    /** Stable id used to pair trigger, panel and content for ARIA. */
    'containerId' => null,
])

<tedi-dropdown
    {{ $attributes }}
    x-data="tediDropdown({
        placement: '{{ $position }}',
        offset: {{ (int) $offset - 8 }},
        preventOverflow: {{ $preventOverflow ? 'true' : 'false' }},
        hideOnScroll: {{ $hideOnScroll ? 'true' : 'false' }},
        matchTriggerWidth: true,
    })"
>
    {{ $slot }}
</tedi-dropdown>
