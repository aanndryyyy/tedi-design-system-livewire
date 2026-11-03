{{--
    TEDI Feedback text.
    Port of angular/src/tedi/components/form/feedback-text/feedback-text.component.{ts,html}

    Since Angular 8.0 the host always carries an `id` — the `id` prop, or a
    generated `tedi-feedback-text-…` — so a container can name it in a
    control's `aria-describedby`.
--}}
@props([
    /** Id of the element. Generated when not set. */
    'id' => null,
    /** Helper text. Required. */
    'text',
    /** hint|valid|error */
    'type' => 'hint',
    /** left|right */
    'position' => 'left',
])

@php
    $role = in_array($type, ['valid', 'error'], true) ? 'alert' : null;
    $ariaLive = in_array($type, ['valid', 'error'], true) ? 'assertive' : 'polite';
    // elementId()
    $elementId = ($id !== null && $id !== '') ? $id : \Tedi\Livewire\Tedi::id('tedi-feedback-text');
@endphp

{{--
    Root is <tedi-feedback-text>, the Angular selector's element, not a <span>:
    checkbox.component.scss:110 and radio.component.scss:102 indent the hint via
    `label:has(input[tedi-checkbox]) + tedi-feedback-text`, a sibling rule that a
    class alone cannot satisfy. `.tedi-feedback-text { display: block }`
    (feedback-text.component.scss:2) supplies the display, so the
    unknown-element `inline` default never applies.
--}}
<tedi-feedback-text
    id="{{ $elementId }}"
    @if ($role) role="{{ $role }}" @endif
    aria-live="{{ $ariaLive }}"
    {{ $attributes->class([
        'tedi-feedback-text',
        'tedi-feedback-text--'.$type,
        'tedi-feedback-text--'.$position,
    ]) }}
>{{ $text }}</tedi-feedback-text>
