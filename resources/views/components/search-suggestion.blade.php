{{--
    TEDI Search Suggestion — one row of the search autocomplete panel.
    Port of angular/src/tedi/components/form/search/search-suggestion.component.{ts,html}
    and search-highlight.util.ts.

    Angular's selector is `li[tedi-search-suggestion]`, so per CONVENTIONS.md §4
    the root is an `<li>` carrying the literal `tedi-search-suggestion`
    attribute as well as the host class.

    `tedi:search` renders these itself from its `suggestions` prop. Write them
    by hand only inside its `rows` slot, the stand-in for Angular's per-item
    `ng-template tediSearchSuggestion` (Blade has no scoped slots; see
    search.blade.php).

    Divergences:

    * `--active` and `aria-selected="true"` are runtime state. Angular's
      ActiveDescendantKeyManager sets them; here `tediSearch`
      (resources/js/src/search.js) toggles them on the DOM, so the server
      render is always the inactive row, exactly as Angular's first render is.
    * `id` is an explicit prop. Angular's parent binds `optionId(i)`
      (`<inputId>-opt-<i>`); `tedi:search` passes the same id for the rows it
      renders. In a hand-written `rows` slot, pass it yourself to keep that
      scheme, or leave it out to get a generated one. The JS reads whichever
      id is in the DOM, so either works for `aria-activedescendant`.
    * `data-tedi-search-label` carries the resolved label. Selecting a row
      fills the field with it; Angular reads it off the component instance,
      which Blade does not have. `item`, when given, is JSON-encoded into
      `data-tedi-search-item` for the same reason: it is what
      `tedi-search:select` hands back as `detail.item`, Angular's
      `suggestionSelect` payload.
    * The `tedi-text` spans are written out rather than rendered through
      `tedi:text`, with the class list that component emits
      (`tedi-text--<color>` + `text-<modifier>`). `tedi:text` ends its output
      with a newline, and a whitespace text node between two label segments is
      a visible gap inside a word ("Mar ia"). Angular strips those nodes.
--}}
@props([
    /** Resolved display label. Also what the field is filled with on select. */
    'label' => '',
    /** Secondary line under the label. */
    'description' => '',
    /** Current field value, used to bold the matching segment. */
    'query' => '',
    /** Whether the default slot supplies the row content (Angular's custom suggestion template). */
    'custom' => false,
    /** Unselectable: greyed out, skipped by the arrow keys, ignores clicks. */
    'disabled' => false,
    /** Element id, the `aria-activedescendant` target. Generated when omitted. */
    'id' => null,
    /** The suggestion itself, JSON-encoded onto the row and handed back as `tedi-search:select`'s `detail.item`. */
    'item' => null,
])

@php
    $id = $id ?? \Tedi\Livewire\Tedi::id('tedi-search-suggestion');

    // Port of highlightParts(): split `label` around the first case-insensitive
    // occurrence of the trimmed query, so that segment can be bolded.
    $parts = [['text' => (string) $label, 'match' => false]];
    $trimmed = trim((string) $query);

    if (! $custom && $trimmed !== '' && $label !== '') {
        $index = mb_stripos((string) $label, $trimmed);

        if ($index !== false) {
            $length = mb_strlen($trimmed);
            $parts = [];

            if ($index > 0) {
                $parts[] = ['text' => mb_substr($label, 0, $index), 'match' => false];
            }

            $parts[] = ['text' => mb_substr($label, $index, $length), 'match' => true];

            if ($index + $length < mb_strlen($label)) {
                $parts[] = ['text' => mb_substr($label, $index + $length), 'match' => false];
            }
        }
    }

    $labelHtml = implode('', array_map(
        fn ($part) => '<span class="tedi-text--primary'.($part['match'] ? ' text-bold' : '').'">'.e($part['text']).'</span>',
        $parts,
    ));
@endphp

<li
    tedi-search-suggestion
    role="option"
    tabindex="-1"
    {{ $attributes->class([
        'tedi-search-suggestion',
        'tedi-search-suggestion--custom' => $custom,
        'tedi-search-suggestion--disabled' => $disabled,
    ])->merge(array_filter([
        'id' => $id,
        'aria-selected' => 'false',
        'aria-disabled' => $disabled ? 'true' : null,
        'data-tedi-search-label' => (string) $label,
        'data-tedi-search-item' => $item === null ? null : json_encode($item, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR),
    ], fn ($v) => $v !== null)) }}
>
@if ($custom)
    {{ $slot }}
@else
    {{-- One line on purpose: no whitespace may sit between the segments. --}}
    <span class="tedi-search-suggestion__label">{!! $labelHtml !!}</span>
    @if (filled($description))
        <span class="tedi-search-suggestion__description tedi-text--tertiary text-small">{{ $description }}</span>
    @endif
@endif
</li>
