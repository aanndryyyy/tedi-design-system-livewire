{{--
    TEDI Truncate.
    Port of react/src/tedi/components/content/truncate/truncate.tsx (CONVENTIONS.md §13).

    Cuts a long string at a character count and offers a show-more / show-less
    toggle. Distinct from `tedi:ellipsis`, which clamps by *line* in CSS and
    never reveals — this one counts characters and expands in place.

    It has no stylesheet of its own upstream, and needs none: the whole
    component is a secondary-coloured `<span>` plus a link-styled button.

    Three substitutions, all forced and all documented:

    * Upstream wraps the text in `<Text element="span" color="secondary">`.
      Both of those are fixed — neither the element nor the colour is
      configurable — so this renders the one class that composition would have
      produced (`tedi-text--secondary`) directly on the root, rather than
      nesting a `tedi:text` whose every prop is hardcoded.
    * Upstream's toggle is `<Button visualType="link">`, whose class is
      `tedi-btn--link`. React's button vocabulary is `tedi-btn`; this package's
      — like Angular's — is `tedi-button`, and it has no `link` variant at all
      (CONVENTIONS.md §13.3). The control that *is* a link-looking button here
      is `tedi:link`, so that is what the toggle uses.
    * The text swap is Alpine (CONVENTIONS.md §8). The server renders the
      truncated string, exactly as upstream's first paint does, and `x-text`
      swaps it. Strip the JS and you keep correct, readable, truncated markup
      with an inert toggle — never an empty element.

    `content` is a prop rather than the slot because the component has to
    measure and slice it, and a slot is opaque to the server (CONVENTIONS.md
    §5). The slot is left free for anything that should follow the text.

    Truncation matches upstream's `slice(0, maxLength).trimEnd() + ellipsis + ' '`
    — and its comparison is `length >= maxLength`, so a string of exactly
    `maxLength` characters counts as truncatable even though slicing it changes
    nothing. That is upstream's edge case, kept. Slicing is multibyte-aware
    here (`mb_substr`), which JavaScript's UTF-16 `slice` is not.

    Breakpoint props (React's `BreakpointSupport`) are not ported —
    CONVENTIONS.md §7 item 1, and the `button` prop-override object goes with
    them: pass attributes to `tedi:truncate` and they land on the root, or wrap
    your own toggle in the slot.
--}}
@props([
    /** The full text. This is what gets truncated. */
    'content' => '',
    /** Maximum number of characters shown while collapsed. */
    'maxLength' => 200,
    /** Appended to the cut text. */
    'ellipsis' => '...',
    /** False renders the truncated text with no toggle. */
    'expandable' => true,
    /** Label of the toggle while collapsed. */
    'moreLabel' => null,
    /** Label of the toggle while expanded. */
    'lessLabel' => null,
])

@php
    $content = (string) $content;
    $maxLength = (int) $maxLength;

    $moreLabel = $moreLabel ?? __('tedi::tedi.truncate.see-more');
    $lessLabel = $lessLabel ?? __('tedi::tedi.truncate.see-less');

    // Upstream: `children.length >= maxLength` — not `>`.
    $isTruncatable = mb_strlen($content) >= $maxLength;
    $truncatedText = $isTruncatable
        ? rtrim(mb_substr($content, 0, $maxLength)).$ellipsis.' '
        : $content;

    $showToggle = $isTruncatable && $expandable;

    $scope = \Illuminate\Support\Js::from([
        'isTruncated' => true,
        'truncated' => $truncatedText,
        'full' => $content,
        'moreLabel' => $moreLabel,
        'lessLabel' => $lessLabel,
    ]);
@endphp

<span
    {{ $attributes->class(['tedi-text--secondary']) }}
    @if ($showToggle) x-data="{{ $scope }}" @endif
>@if ($showToggle)<span x-text="isTruncated ? truncated : full">{{ $truncatedText }}</span><tedi:link
        aria-expanded="false"
        x-on:click="isTruncated = ! isTruncated"
        x-bind:aria-expanded="(! isTruncated).toString()"
        x-bind:style="isTruncated ? null : 'display: block'"
    ><span x-text="isTruncated ? moreLabel : lessLabel">{{ $moreLabel }}</span></tedi:link>@else{{ $truncatedText }}@endif{{ $slot }}</span>
