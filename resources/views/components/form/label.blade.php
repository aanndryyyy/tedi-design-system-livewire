{{--
    TEDI Label.
    Port of angular/tedi/components/form/label/label.component.{ts,html}

    Angular's selector is the attribute form `[tedi-label]`, applied to a
    consumer-supplied element (almost always `<label tedi-label for="...">`).
    The `as` prop lets Blade pick the element the same way; it defaults to
    `label` since that is the selector's overwhelming real-world use.

    Kept under form/ (addressed as <tedi:form.label>) rather than flattened to
    the top level: `label` is the single most generic name in the library and
    the lead pre-authorized this path to avoid a future collision. No other
    component named exactly `label` exists today (only
    content/text-group/text-group-label, which is unrelated) — see the port
    report for this finding.
--}}
@props([
    /** Element to render as. */
    'as' => 'label',
    /** small|default */
    'size' => 'default',
    /** Whether the field is required; appends a "*" and a screen-reader hint. */
    'required' => false,
    /** primary|secondary */
    'color' => 'secondary',
    /** false|true|reserve-space — hide visually; reserve-space keeps the line. */
    'visuallyHidden' => false,
])

<{{ $as }} {{ $attributes->class([
    'tedi-label',
    'tedi-label--'.$color,
    'tedi-label--small' => $size === 'small',
    'tedi-label--reserve-space' => $visuallyHidden === 'reserve-space',
    'sr-only' => $visuallyHidden === true,
]) }}>
    {{ $slot }}
    @if ($required)
        <span class="tedi-label--required" aria-hidden="true">*</span>
        <span class="sr-only">, {{ __('tedi::tedi.required') }}</span>
    @endif
</{{ $as }}>
