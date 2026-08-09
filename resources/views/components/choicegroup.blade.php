{{--
    TEDI Choicegroup (community).
    Port of angular/community/components/form/choicegroup/choicegroup.directive.ts

    Ported from the `community/` tree — see CONVENTIONS.md §12.

    Upstream is an attribute DIRECTIVE with no template: you put it on whatever
    element already groups the choices (`<div tediChoiceGroup>`, a
    `tedi-radio-group`, …) and it contributes four host classes. Blade has no
    directives, so this ports as a **wrapper element** that carries exactly
    those classes and projects the group into it. The stylesheet only ever
    reaches its children as descendants
    (`.tedi-choicegroup .tedi-radio`, `:is(.tedi-radio, .tedi-checkbox):has(input:checked)`),
    so the extra level of DOM changes nothing about which rules match. The one
    thing to keep in mind is that `--stacked` styles the choices by their
    position among their own siblings (`:not(:last-child)`), so the radios or
    checkboxes must be siblings of each other inside this wrapper — as they are
    inside `tedi:radio-group` / `tedi:checkbox-group`.

    Turns `tedi:radio` / `tedi:checkbox` children into bordered, filled cards.
    This is the community predecessor of `tedi/`'s `tedi:radio-card` /
    `tedi:checkbox-card`; prefer those in new code — they are what TEDI-Ready
    ships. The two are independent: nothing here reads or emits
    `tedi-radio-card` / `tedi-checkbox-card` classes.

    NOTE ON `spacing`: upstream declares it as a px value but only ever compares
    it to 0, to decide `--stacked`; it never writes a gap. That is ported as-is,
    so any non-zero value behaves identically — set real spacing on the group
    itself.
--}}
@props([
    /** primary|secondary — the card colour set. */
    'variant' => 'primary',
    /** Whether each choice keeps its radio/checkbox indicator. */
    'hasIndicator' => true,
    /** Spacing between the choices, in px. Only `0` is meaningful — see the note above. */
    'spacing' => 4,
])

<div {{ $attributes->class([
    'tedi-choicegroup',
    'tedi-choicegroup--stacked' => (int) $spacing === 0,
    'tedi-choicegroup--plain' => ! $hasIndicator,
    'tedi-choicegroup--'.$variant,
]) }}>
    {{ $slot }}
</div>
