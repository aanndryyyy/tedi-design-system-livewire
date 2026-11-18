{{--
    TEDI Rating (Angular 10).
    Port of angular/src/tedi/components/form/rating/{rating.component.ts,rating.component.html}

    Root element: Angular's selector is the ELEMENT `tedi-rating`, so the root
    is a literal <tedi-rating> (CONVENTIONS.md §4). `.tedi-rating` sets
    `display: flex`, so the unknown element needs no display fallback. The
    host bindings (`classes()`, `role`, `aria-label`, `--tedi-rating-columns`)
    land on it through the attribute bag.

    VALUE BINDING. The Angular template's native controls are one
    <input type="radio"> per item, and Angular's ControlValueAccessor writes
    the picked position. Here the radios ARE the binding: every `wire:model*`
    and `x-model*` attribute the consumer writes is copied onto each radio
    (same name, value = position), so Livewire / Alpine bind the group
    natively. Every other attribute — `class`, `id`, `data-*`, `x-on:*` —
    lands on the root, unlike toggle / slider, where the whole bag goes on
    the single input. Pass `:value` together with `wire:model` so the first
    server render (checked radio, selected classes, star caption) matches the
    bound state. A plain non-Livewire form POST submits the radio group under
    `name`.

    INTERACTIVITY (CONVENTIONS.md §8). The hover preview (`hoverValue`) and the
    live re-colouring after a pick run on `tediRating` from
    resources/js/src/rating.js. It only reads the checked radio, so it never
    fights `wire:model`. The server render already carries the right classes
    for the value, so without the bundle the scale is still correct and
    selectable; only the hover preview, the hovered star caption and the
    re-colouring after a pick are missing (CSS still paints `:hover`).
    Keyboard behaviour is the native radio group's, as upstream's is.

    DIVERGENCES:

    * `orientation` takes `horizontal` / `vertical` only. Angular's
      `BreakpointInput` object (`{ xs: 'vertical', md: 'horizontal' }`) is
      resolved by BreakpointService at runtime and is not ported
      (CONVENTIONS.md §7.1).
    * `count` also accepts an integral numeric string (`count="7"`), since a
      plain Blade attribute is a string; anything else that is not a positive
      integer falls back to 5 / 10, as upstream.
    * `value` / `disabled` are props, not a ControlValueAccessor:
      `writeValue`, `setDisabledState`, `registerOnTouched` and the
      `valueChange` output are not re-emitted (CONVENTIONS.md §7.2). Listen to
      `change` on the root (it bubbles from the radios) if you need an event.
    * The dev-mode `console.warn` for `type="icon"` without `icons` at a
      count other than 5 is not ported; the items render the `circle` glyph,
      as upstream's do.
    * The `readOnly` summary formats numbers with PHP's intl NumberFormatter
      for `app()->getLocale()` (Angular uses `Intl.NumberFormat` for the
      translation service's language), rounding half up like `Intl`. Without
      ext-intl it falls back to `,` decimals and a no-break-space grouping
      for every locale except `en`.
    * `label` is required (`input.required`). It is guarded with an exception
      rather than left to an undefined-variable error.

    Every class emitted here exists in dist/tedi.css; nothing is dropped.
--}}
@props([
    /** Accessible name for the group; also prefixes the readOnly summary. Required. */
    'label' => null,
    /** star|number|icon — star / number fill cumulatively, icon highlights a single item. */
    'type' => 'star',
    /** Number of items (the max rating). Defaults to 5 (star / icon) or 10 (number). */
    'count' => null,
    /** Selected value (1-based; 0 = none). In readOnly, the (possibly fractional) average. */
    'value' => 0,
    /** Per-item labels (length = count): each item's accessible name, and its caption. */
    'itemLabels' => null,
    /** Material Symbol glyph(s): an array (one per item) for type="icon", or one string to swap the star glyph. */
    'icons' => null,
    /** Disable interaction and mute the colours. */
    'disabled' => false,
    /** Show a compact read-only summary (visual + value/count text) instead of the interactive scale. */
    'readOnly' => false,
    /** Number of ratings shown in the readOnly summary (e.g. "271 hindajat"); omitted when unset. */
    'ratingsCount' => null,
    /** Show the descriptive raters label next to ratingsCount; false shows the number only. */
    'showRatingsCountLabel' => true,
    /** summary|scale — readOnly layout: a single visual, or the full star scale with fractional fill. */
    'readOnlyVariant' => 'summary',
    /** `name` for the radio inputs. Auto-generated (Tedi::id()) when omitted. */
    'name' => null,
    /** horizontal|vertical — vertical stacks icon + caption per row. Ignored for type="star". Breakpoint objects are not ported. */
    'orientation' => 'horizontal',
])

@php
    if ($label === null) {
        throw new \InvalidArgumentException('The rating component requires a `label`.');
    }

    $starIconSize = 24;   // STAR_ICON_SIZE (matches --feedback-star-size)
    $circleIconSize = 18; // CIRCLE_ICON_SIZE
    $defaultIcons = [
        'sentiment_sad',
        'sentiment_dissatisfied',
        'sentiment_neutral',
        'sentiment_satisfied',
        'mood',
    ];

    $readOnly = (bool) $readOnly;
    $disabled = (bool) $disabled;
    $showRatingsCountLabel = (bool) $showRatingsCountLabel;
    $numericValue = is_numeric($value) ? 0 + $value : 0;

    // groupName()
    $groupName = $name ?? \Tedi\Livewire\Tedi::id('tedi-rating');

    // total()
    $countInt = is_int($count) ? $count : (is_string($count) ? filter_var($count, FILTER_VALIDATE_INT) : false);
    $total = is_int($countInt) && $countInt > 0 ? $countInt : ($type === 'number' ? 10 : 5);

    // itemLabelList() / iconList() — non-arrays are ignored, as upstream.
    $labels = is_array($itemLabels) ? array_values($itemLabels) : null;
    $iconList = is_array($icons) ? array_values($icons) : (is_string($icons) ? [$icons] : null);

    // starGlyph() / resolvedIcons()
    $starGlyph = $iconList[0] ?? 'kid_star';
    $resolvedIcons = $iconList ?? ($total === count($defaultIcons) ? $defaultIcons : null);

    // JS truthiness for a label: '0' is a real label, '' and null are not.
    $isLabel = fn ($label) => $label !== null && $label !== '' && $label !== false;

    // isInRange()
    $inRange = fn (int $position, $target) => $type === 'icon' ? $position == $target : $position <= $target;

    // items() — the server has no hover, so `previewed` is always false and
    // `selected` is just the range test.
    $items = [];
    for ($position = 1; $position <= $total; $position++) {
        $itemLabel = $labels[$position - 1] ?? null;
        $isEndpoint = $position === 1 || $position === $total;

        $items[] = [
            'position' => $position,
            'selected' => $inRange($position, $numericValue),
            'icon' => $resolvedIcons[$position - 1] ?? 'circle',
            // accessibleName()
            'accessibleName' => ! $isLabel($itemLabel)
                ? $position.'/'.$total
                : ($type === 'number' ? $position.', '.$itemLabel : $itemLabel),
            'caption' => $isLabel($itemLabel) && ($type === 'icon' || ($type === 'number' && $isEndpoint))
                ? $itemLabel
                : null,
        ];
    }

    // mobileColumns()
    $mobileColumns = $type === 'number' ? ($total > 6 ? (int) ceil($total / 2) : $total) : null;

    // hasCaptions()
    $someLabel = $labels !== null && count(array_filter($labels, $isLabel)) > 0;
    $hasCaptions = $type !== 'star' && $someLabel;

    // starCaption() — null stands for Angular's `undefined` (no element).
    $starCaption = null;
    if ($type === 'star' && $someLabel) {
        $display = $numericValue;
        $starCaption = $display > 0 && $display == (int) $display ? (string) ($labels[(int) $display - 1] ?? '') : '';
    }

    // Intl.NumberFormat(locale, { maximumFractionDigits }) for the summary.
    $locale = app()->getLocale();
    $formatNumber = function ($number, int $maxFraction) use ($locale) {
        if (class_exists(\NumberFormatter::class)) {
            $formatter = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
            $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $maxFraction);
            // Intl.NumberFormat's default is halfExpand; ICU's is half-even.
            $formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);

            return $formatter->format($number);
        }

        $english = str_starts_with($locale, 'en');
        $formatted = number_format((float) $number, $maxFraction, $english ? '.' : ',', $english ? ',' : "\u{00A0}");

        return $maxFraction > 0 ? rtrim(rtrim($formatted, '0'), '.,') : $formatted;
    };

    // summary()
    $countText = '';
    if ($ratingsCount !== null && $ratingsCount !== '') {
        $raters = (int) $ratingsCount;
        $countText = ' - '.($showRatingsCountLabel
            ? trans_choice('tedi::tedi.rating.raters', $raters, ['count' => $raters])
            : $formatNumber($raters, 0));
    }
    $summary = $formatNumber($numericValue, 1).'/'.$total.$countText;

    // readOnlyIcon()
    $readOnlyPosition = min($total, max(1, (int) round($numericValue)));
    $readOnlyIcon = $resolvedIcons[$readOnlyPosition - 1] ?? 'circle';

    // partialFills()
    $partialFills = [];
    for ($index = 0; $index < $total; $index++) {
        $partialFills[] = max(0, min(1, $numericValue - $index)) * 100;
    }

    // The bound-model attributes go on every radio; everything else on the root.
    $modelPrefixes = ['wire:model', 'x-model'];
    $modelAttributes = $attributes->whereStartsWith($modelPrefixes)->getAttributes();
    $rootAttributes = $attributes->whereDoesntStartWith($modelPrefixes);

    $isVertical = $orientation === 'vertical';

    // host: classes(), role, aria-label and --tedi-rating-columns. The style
    // is only merged when set: ->style([]) would still print style="".
    $rootAttributes = $rootAttributes->class([
        'tedi-rating',
        'tedi-rating--'.$type,
        'tedi-rating--readonly' => $readOnly,
        'tedi-rating--vertical' => ! $readOnly && $type !== 'star' && $isVertical,
        'tedi-rating--disabled' => ! $readOnly && $disabled,
    ])->merge(array_filter([
        'role' => $readOnly ? 'img' : 'radiogroup',
        'aria-label' => $readOnly ? $label.': '.$summary : $label,
    ], fn ($attribute) => $attribute !== null && $attribute !== ''));

    if ($mobileColumns !== null) {
        $rootAttributes = $rootAttributes->style(['--tedi-rating-columns: '.$mobileColumns]);
    }
@endphp

<tedi-rating
    {{ $rootAttributes }}
    @unless ($readOnly)
        x-data="tediRating(@js(['type' => $type, 'value' => $numericValue, 'labels' => $type === 'star' && $someLabel ? $labels : []]))"
    @endunless
>
    @if ($readOnly)
        <div class="tedi-rating__summary" aria-hidden="true">
            @if ($type === 'star' && $readOnlyVariant === 'scale')
                <span class="tedi-rating__stars">
                    @foreach ($partialFills as $fill)
                        <span class="tedi-rating__star-partial">
                            <tedi:icon class="tedi-rating__star" :name="$starGlyph" color="inherit" :size="$starIconSize" />
                            @if ($fill > 0)
                                <span class="tedi-rating__star-partial-fill" style="--tedi-rating-star-fill: {{ $fill }}%">
                                    <tedi:icon class="tedi-rating__star" :name="$starGlyph" variant="filled" color="inherit" :size="$starIconSize" />
                                </span>
                            @endif
                        </span>
                    @endforeach
                </span>
            @elseif ($type === 'star')
                <tedi:icon class="tedi-rating__star" :name="$starGlyph" variant="filled" color="inherit" :size="$starIconSize" />
            @elseif ($type === 'icon')
                <span class="tedi-rating__circle tedi-rating__circle--filled">
                    <tedi:icon :name="$readOnlyIcon" color="inherit" :size="$circleIconSize" />
                </span>
            @endif
            <span class="tedi-rating__summary-text">{{ $summary }}</span>
        </div>
    @else
        <div
            @class([
                'tedi-rating__items',
                'tedi-rating__items--captioned' => $hasCaptions,
            ])
            x-on:mouseleave="ratingSetHover(null)"
        >
            @foreach ($items as $item)
                <label class="tedi-rating__item" x-on:mouseenter="ratingSetHover({{ $item['position'] }})">
                    <input
                        type="radio"
                        class="tedi-rating__input"
                        name="{{ $groupName }}"
                        value="{{ $item['position'] }}"
                        @checked($numericValue == $item['position'])
                        @disabled($disabled)
                        aria-label="{{ $item['accessibleName'] }}"
                        x-on:change="ratingSelect({{ $item['position'] }})"
                        {{ $attributes->only([])->merge($modelAttributes) }}
                    />
                    <span
                        @class([
                            'tedi-rating__visual',
                            'tedi-rating__visual--selected' => $item['selected'],
                        ])
                        x-bind:class="{ 'tedi-rating__visual--selected': ratingSelected({{ $item['position'] }}), 'tedi-rating__visual--hover': ratingPreviewed({{ $item['position'] }}) }"
                        aria-hidden="true"
                    >
                        @if ($type === 'star')
                            {{-- Filled copy behind the glyph; tinted only on hover so the outlined star gets a light fill. --}}
                            <tedi:icon class="tedi-rating__star-backdrop" :name="$starGlyph" variant="filled" color="inherit" :size="$starIconSize" />
                            <tedi:icon
                                class="tedi-rating__star"
                                :name="$starGlyph"
                                :variant="$item['selected'] ? 'filled' : 'outlined'"
                                color="inherit"
                                :size="$starIconSize"
                                x-bind:class="{ 'tedi-icon--filled': ratingSelected({{ $item['position'] }}) }"
                            />
                        @else
                            <span class="tedi-rating__circle">
                                @if ($type === 'number')
                                    {{ $item['position'] }}
                                @else
                                    <tedi:icon :name="$item['icon']" color="inherit" :size="$circleIconSize" />
                                @endif
                            </span>
                        @endif
                    </span>
                    @if ($item['caption'] !== null)
                        <span class="tedi-rating__caption">{{ $item['caption'] }}</span>
                    @endif
                </label>
            @endforeach
        </div>

        @if ($starCaption !== null)
            <div class="tedi-rating__star-caption" x-text="ratingStarCaption()">{{ $starCaption }}</div>
        @endif
    @endif
</tedi-rating>
