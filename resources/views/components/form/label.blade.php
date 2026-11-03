{{--
    TEDI Label.
    Port of angular/src/tedi/components/content/label/label.component.{ts,html}
    (upstream moved it from form/label to content/label in 9.0, #738).

    Angular's selector is the attribute form `[tedi-label]`, applied to a
    consumer-supplied element (almost always `<label tedi-label for="...">`).
    The `as` prop lets Blade pick the element the same way; it defaults to
    `label` since that is the selector's overwhelming real-world use.

    Kept under form/ (addressed as `tedi:form.label`) rather than flattened to
    the top level: `label` is the single most generic name in the library, and
    the tag is used across the library under this name. Upstream's move to
    content/ is a source-path change only; the tag is unchanged.

    `size` falls back to the wrapping form-field's (Angular's
    `inject(TEDI_FIELD_CONTEXT)`, via `Tedi::fieldContext()`): a small field
    gets a small label, any other size the default one. Only a label rendered
    inside the form-field — its `label` slot — sees the field.

    `visuallyHidden` (Angular 8.0): `true` adds core's `sr-only`;
    `'reserve-space'` adds `tedi-label--reserve-space`, which also keeps the
    label's line of layout.
--}}
@props([
    /** Element to render as. */
    'as' => 'label',
    /** small|default. Falls back to the wrapping form-field's size. */
    'size' => null,
    /** Whether the field is required; appends a "*" and a screen-reader hint. */
    'required' => false,
    /** primary|secondary */
    'color' => 'secondary',
    /** false|true|'reserve-space' — hides the label visually, keeping it for screen readers. */
    'visuallyHidden' => false,
])

@php
    // resolvedSize()
    $resolvedSize = $size ?: ((\Tedi\Livewire\Tedi::fieldContext($__env)['size'] ?? null) === 'small' ? 'small' : 'default');
@endphp

<{{ $as }} {{ $attributes->class([
    'tedi-label',
    'tedi-label--'.$color,
    'tedi-label--small' => $resolvedSize === 'small',
    'tedi-label--reserve-space' => $visuallyHidden === 'reserve-space',
    'sr-only' => $visuallyHidden && $visuallyHidden !== 'reserve-space',
]) }}>
    {{ $slot }}
    @if ($required)
        <span class="tedi-label--required" aria-hidden="true">*</span>
        <span class="sr-only">, {{ __('tedi::tedi.required') }}</span>
    @endif
</{{ $as }}>
