{{--
    TEDI Truncate.
    Port of angular/src/tedi/components/content/truncate/truncate.component.{ts,html}

    Previously ported from React (CONVENTIONS.md §13); Angular now ships it in
    `tedi/`, which is the source from here on (§1).

    Cuts a long string at a character count and offers a show-more / show-less
    toggle. Distinct from `tedi:ellipsis`, which clamps by *line* in CSS and
    never reveals — this one counts characters and expands in place.

    Markup is upstream's: the literal `tedi-truncate` element (§4) with
    `tedi-truncate--expanded` while expanded, a secondary `tedi:text` span
    (id'd for `aria-controls`) and, when the text is
    longer than `max-length` and `expandable` is on, a `tedi:link` button
    (`tedi-truncate__toggle`) carrying `aria-expanded`.

    Counting is by grapheme cluster, as upstream's `Intl.Segmenter` does —
    PCRE's `\X` here, so `ext-intl` is not needed. A string is truncated only
    when it is strictly longer than `max-length`; the cut is
    `slice(0, maxLength).trimEnd() + ellipsis`. `max-length` is floored and
    clamped at 0, and a non-numeric value falls back to 200, as upstream's
    `normalizeMaxLength`.

    `text` is a prop rather than the slot because the component has to count
    and slice it, and a slot is opaque to the server (CONVENTIONS.md §5).

    The expand/collapse swap is Alpine (CONVENTIONS.md §8). The server renders
    the state `expanded` asks for, so without JS the markup is correct and the
    toggle is inert. `[(expanded)]` two-way binding is not re-emitted (§7 item
    2): `expanded` sets the initial state only.

    DROPPED CLASS: `tedi-truncate__text`. Angular puts it on the text span,
    but the vendored SCSS has no rule for it, so dropping it is behaviourally
    identical (CONVENTIONS.md §4).

    DIVERGENCE: `maxLength` accepts only a number. Upstream's breakpoint-object
    form (`{ xs: 100, md: 200 }`) is resolved at runtime by `BreakpointService`
    and is not ported (CONVENTIONS.md §7 item 1).
--}}
@props([
    /** Text to display and truncate when it exceeds `max-length`. Required. */
    'text' => '',
    /** Maximum characters shown while collapsed, excluding the ellipsis. */
    'maxLength' => 200,
    /** Text appended when content is truncated. */
    'ellipsis' => '...',
    /** Shows an expand/collapse button when the text exceeds `max-length`. */
    'expandable' => true,
    /** Whether the full text is shown initially. */
    'expanded' => false,
])

@php
    // Invalid UTF-8 would make \X match nothing and Js::from throw; replace
    // the bad bytes rather than 500 the page.
    $text = mb_scrub((string) $text, 'UTF-8');
    $maxLength = is_numeric($maxLength) ? max(0, (int) floor((float) $maxLength)) : 200;

    preg_match_all('/\X/u', $text, $matches);
    $characters = $matches[0];

    $exceedsMaxLength = count($characters) > $maxLength;
    $truncatedText = $exceedsMaxLength
        ? rtrim(implode('', array_slice($characters, 0, $maxLength))).$ellipsis
        : $text;
    $showToggle = $exceedsMaxLength && $expandable;
    $expanded = (bool) $expanded;

    $textId = \Tedi\Livewire\Tedi::id('tedi-truncate-text');

    // Only the expandable case needs the Alpine scope.
    $scope = ! $exceedsMaxLength ? null : \Illuminate\Support\Js::from([
        'expanded' => $expanded,
        'truncated' => $truncatedText,
        'full' => $text,
        'moreLabel' => __('tedi::tedi.truncate.see-more'),
        'lessLabel' => __('tedi::tedi.truncate.see-less'),
    ]);
@endphp

<tedi-truncate
    {{ $attributes->class([
        'tedi-truncate',
        'tedi-truncate--expanded' => $expanded,
    ]) }}
    @if ($exceedsMaxLength)
        x-data="{{ $scope }}"
        x-bind:class="{ 'tedi-truncate--expanded': expanded }"
    @endif
>
    <tedi:text
        color="secondary"
        :id="$textId"
        :x-text="$exceedsMaxLength ? 'expanded ? full : truncated' : null"
    >{{ $expanded ? $text : $truncatedText }}</tedi:text>
    @if ($showToggle)
        <tedi:link
            class="tedi-truncate__toggle"
            :aria-expanded="$expanded ? 'true' : 'false'"
            :aria-controls="$textId"
            x-on:click="expanded = ! expanded"
            x-bind:aria-expanded="expanded.toString()"
            x-text="expanded ? lessLabel : moreLabel"
        >{{ $expanded ? __('tedi::tedi.truncate.see-less') : __('tedi::tedi.truncate.see-more') }}</tedi:link>
    @endif
</tedi-truncate>
