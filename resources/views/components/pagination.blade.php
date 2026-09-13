{{--
    TEDI Pagination.
    Port of angular/tedi/components/navigation/pagination/{pagination.component.ts,html,pagination.utils.ts}

    Divergences (documented per CONVENTIONS.md §7):
    - `useCompactPicker` (`isBelowBreakpoint('md')`) swaps the pager for a
      mobile "current / total" trigger that opens a `tedi-modal` page-jump
      picker, and the page-size select gets the same mobile trigger. Both are
      resolved by `BreakpointService` at runtime and the modal needs overlay
      positioning (out of scope per §7.3) — so this port always renders the
      desktop pager (`<ul>` page list) and page-size `<select>`.
      `pagination-option-picker-modal` is therefore not ported at all.
      `showModalTitle` is accepted for API parity but is inert — it only
      controls a heading inside that unported modal.
    - `hideResults` / `hidePageSize` / `hidePager` / `hideArrows` accept only
      `boolean` here, not the Angular `PaginationVisibility` breakpoint-name
      union — the vendored SCSS has no per-breakpoint hide classes for them
      (only the boolean `--no-results` / `--no-pager` / `--no-page-size`
      modifiers), so there is nothing to port a breakpoint value onto.
    - `tedi-select` (the custom dropdown form control) is a form component
      outside this port's scope; the page-size control is a plain native
      `<select>` sharing the same wrapper classes.
    - `pageChange` / `pageSizeChange` are not re-emitted (CONVENTIONS.md §3).
      Per this repo's hard rule for pagination, page links render as real
      `<a href>`s via the `page-url` closure prop so paging works without JS;
      `page-size-attributes` forwards e.g. `wire:model` to the `<select>`.
    - Angular's `hostClasses()` also emits `tedi-pagination--no-arrows` when
      the arrows are hidden, but the vendored SCSS has no corresponding rule
      for it (unlike `--no-results` / `--no-pager` / `--no-page-size`, which
      all collapse their grid slot) — it's dead in Angular too. Per the
      whole-library `IntegrityTest` guardrail (only emit classes the compiled
      stylesheet actually defines), this port omits it.
    - Same reasoning for `dividerPosition="none"`: Angular's `hostClasses()`
      unconditionally emits `tedi-pagination--divider-${dividerPosition()}`
      (pagination.component.ts:204), so `--divider-none` is a real upstream
      class — but the vendored SCSS only has `&--divider-top` and
      `&--divider-bottom` rules, nothing for `none`. Since neither existing
      rule matches when the class is simply absent, omitting it for the
      `none` case produces the identical "no divider" rendering without an
      unstyled class. `--divider-top` / `--divider-bottom` are still emitted
      as-is (both are real, styled classes).
--}}
@props([
    /** Total number of pages. Required. */
    'pageCount',
    /** Current page (1-based). */
    'page' => 1,
    /** Total number of items across all pages. Shows the "{count} results" label when set. */
    'totalItems' => null,
    /** Current page size, for the page-size select. */
    'pageSize' => null,
    /** Options for the page-size select: plain numbers or ['value' => int, 'label' => string]. */
    'pageSizeOptions' => [],
    /** Pages always shown at the start and end. */
    'boundaryCount' => 1,
    /** Pages shown on either side of the current page. */
    'siblingCount' => 1,
    /** Override any of the default text/aria labels (see $defaultLabels below). */
    'labels' => [],
    /** white|transparent */
    'background' => 'white',
    /** top|bottom|none */
    'dividerPosition' => 'top',
    /** between|left|right — left/right group the slots; between is the default spread. */
    'align' => 'between',
    /** Hide the "X results" label even when totalItems/results slot is set. */
    'hideResults' => false,
    /** Hide the page-size select even when pageSizeOptions is non-empty. */
    'hidePageSize' => false,
    /** Hide the pager (prev/next + page list). */
    'hidePager' => false,
    /** Hide the prev/next arrow buttons inside the pager. */
    'hideArrows' => false,
    /** Keep prev/next arrows rendered (but disabled) at the first/last page. */
    'disableArrowsAtBoundary' => false,
    /** tedi-button variant for the prev/next arrows. */
    'arrowVariant' => 'neutral',
    /** Render previous/next labels as visible button text next to the arrow icon. */
    'showArrowLabels' => false,
    /** Material Symbols icon name for the previous-page arrow. */
    'previousIcon' => 'arrow_back',
    /** Material Symbols icon name for the next-page arrow. */
    'nextIcon' => 'arrow_forward',
    /** Show a heading in the mobile page-jump/page-size picker modals. Kept for API parity — inert since that modal isn't ported (see §7 note above). */
    'showModalTitle' => true,
    /** Closure(int $page): ?string building the href for a page link. Required for pages to be real links. */
    'pageUrl' => null,
    /** Extra attributes forwarded to the page-size <select> (e.g. wire:model). */
    'pageSizeAttributes' => [],
])

@php
    $range = fn (int $start, int $end) => $end < $start ? [] : range($start, $end);

    $usePagination = function (int $page, int $pageCount, int $boundaryCount, int $siblingCount) use ($range): array {
        if ($pageCount <= 0) {
            return [];
        }

        $safeBoundary = max(0, $boundaryCount);
        $safeSibling = max(0, $siblingCount);
        $currentPage = max(1, min($pageCount, $page));
        $windowSize = $safeBoundary * 2 + $safeSibling * 2 + 3;

        if ($pageCount <= $windowSize) {
            $pageList = $range(1, $pageCount);
        } else {
            $edgeRun = $safeBoundary + $safeSibling * 2 + 2;
            $startThreshold = $safeBoundary + $safeSibling + 2;
            $endThreshold = $pageCount - $safeBoundary - $safeSibling - 1;

            if ($currentPage <= $startThreshold) {
                $pageList = array_merge($range(1, $edgeRun), ['ellipsis'], $range($pageCount - $safeBoundary + 1, $pageCount));
            } elseif ($currentPage >= $endThreshold) {
                $pageList = array_merge($range(1, $safeBoundary), ['ellipsis'], $range($pageCount - $edgeRun + 1, $pageCount));
            } else {
                $pageList = array_merge(
                    $range(1, $safeBoundary),
                    ['ellipsis'],
                    $range($currentPage - $safeSibling, $currentPage + $safeSibling),
                    ['ellipsis'],
                    $range($pageCount - $safeBoundary + 1, $pageCount)
                );
            }
        }

        $items = [[
            'type' => 'previous',
            'page' => $currentPage > 1 ? $currentPage - 1 : null,
            'selected' => false,
            'disabled' => $currentPage <= 1,
        ]];

        foreach ($pageList as $entry) {
            $items[] = $entry === 'ellipsis'
                ? ['type' => 'ellipsis', 'page' => null, 'selected' => false, 'disabled' => true]
                : ['type' => 'page', 'page' => $entry, 'selected' => $entry === $currentPage, 'disabled' => false];
        }

        $items[] = [
            'type' => 'next',
            'page' => $currentPage < $pageCount ? $currentPage + 1 : null,
            'selected' => false,
            'disabled' => $currentPage >= $pageCount,
        ];

        return $items;
    };

    $emptyNavItem = ['type' => 'page', 'page' => null, 'selected' => false, 'disabled' => true];

    $currentPage = $pageCount > 0 ? max(1, min($pageCount, $page)) : 1;
    $items = $usePagination($currentPage, $pageCount, $boundaryCount, $siblingCount);
    $previousItem = $items[0] ?? $emptyNavItem;
    $nextItem = $items[count($items) - 1] ?? $emptyNavItem;
    $pageItems = count($items) > 2 ? array_slice($items, 1, -1) : [];

    // mergedLabels() — see the file header for why .true/.false keys are
    // treated as interchangeable templates with a literal placeholder word.
    $defaultLabels = [
        'ariaLabel' => __('tedi::tedi.pagination.title'),
        'previous' => __('tedi::tedi.pagination.prev-page'),
        'next' => __('tedi::tedi.pagination.next-page'),
        'pageAriaLabel' => fn (int $p) => str_replace('false', (string) $p, __('tedi::tedi.pagination.page.false')),
        'currentPageAriaLabel' => fn (int $p) => str_replace('true', (string) $p, __('tedi::tedi.pagination.page.true')),
        'results' => fn (int $count) => str_replace('false', (string) $count, __('tedi::tedi.pagination.results.false')),
        'pageSize' => __('tedi::tedi.pagination.page-size'),
        'pageStatus' => fn (int $p, int $total) => strtr(__('tedi::tedi.pagination.page-status.true'), ['true' => (string) $p, '0' => (string) $total]),
    ];
    $mergedLabels = array_replace($defaultLabels, $labels);

    $showResults = ! $hideResults && ($totalItems !== null || isset($results));
    $showPageSizeSelect = ! $hidePageSize && count($pageSizeOptions) > 0;
    $showPager = ! $hidePager && $pageCount > 1;
    $showArrows = ! $hideArrows;
    $showPrevious = $showArrows && ($disableArrowsAtBoundary || ! $previousItem['disabled']);
    $showNext = $showArrows && ($disableArrowsAtBoundary || ! $nextItem['disabled']);

    $pageSizeSelectOptions = array_map(
        fn ($option) => is_array($option) ? $option : ['value' => $option, 'label' => (string) $option],
        $pageSizeOptions
    );

    $statusText = $pageCount > 1 ? $mergedLabels['pageStatus']($currentPage, $pageCount) : '';

    $linkFor = fn (?int $targetPage) => $targetPage !== null && $pageUrl ? $pageUrl($targetPage) : null;
@endphp

<div
    data-name="tedi-pagination"
    {{ $attributes->class([
        'tedi-pagination',
        'tedi-pagination--bg-'.$background,
        'tedi-pagination--divider-'.$dividerPosition => $dividerPosition !== 'none',
        'tedi-pagination--align-left' => $align === 'left',
        'tedi-pagination--align-right' => $align === 'right',
        'tedi-pagination--no-pager' => ! $showPager,
        'tedi-pagination--no-results' => ! $showResults,
        'tedi-pagination--no-page-size' => ! $showPageSizeSelect,
    ]) }}
>
    <span class="tedi-pagination__status" role="status" aria-live="polite" aria-atomic="true">
        {{ $statusText }}
    </span>

    <div class="tedi-pagination__slot-start">
        @if ($showResults)
            @if (isset($results))
                {{ $results }}
            @else
                <span class="tedi-pagination__results">{{ $mergedLabels['results']((int) $totalItems) }}</span>
            @endif
        @endif
    </div>

    <div class="tedi-pagination__slot-center">
        @if ($showPager)
            <nav class="tedi-pagination__nav" aria-label="{{ $mergedLabels['ariaLabel'] }}">
                @if ($showPrevious)
                    <tedi:button
                        :href="$linkFor($previousItem['page']) ?? '#'"
                        :disabled="$linkFor($previousItem['page']) === null"
                        :variant="$arrowVariant"
                        size="small"
                        :icon-start="$previousIcon"
                        :icon-only="! $showArrowLabels"
                        :aria-label="$showArrowLabels ? null : $mergedLabels['previous']"
                        class="tedi-pagination__nav-button tedi-pagination__nav-button--previous"
                    >@if ($showArrowLabels){{ $mergedLabels['previous'] }}@endif</tedi:button>
                @endif

                <ul class="tedi-pagination__list">
                    @foreach ($pageItems as $item)
                        @if ($item['type'] === 'ellipsis')
                            <li class="tedi-pagination__item tedi-pagination__item--ellipsis" aria-hidden="true">&hellip;</li>
                        @else
                            <li class="tedi-pagination__item">
                                <a
                                    @if ($linkFor($item['page'])) href="{{ $linkFor($item['page']) }}" @else aria-disabled="true" role="link" @endif
                                    class="tedi-pagination__page{{ $item['selected'] ? ' tedi-pagination__page--selected' : '' }}"
                                    aria-label="{{ $item['selected'] ? $mergedLabels['currentPageAriaLabel']($item['page']) : $mergedLabels['pageAriaLabel']($item['page']) }}"
                                    @if ($item['selected']) aria-current="page" @endif
                                >{{ $item['page'] }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>

                @if ($showNext)
                    <tedi:button
                        :href="$linkFor($nextItem['page']) ?? '#'"
                        :disabled="$linkFor($nextItem['page']) === null"
                        :variant="$arrowVariant"
                        size="small"
                        :icon-end="$nextIcon"
                        :icon-only="! $showArrowLabels"
                        :aria-label="$showArrowLabels ? null : $mergedLabels['next']"
                        class="tedi-pagination__nav-button tedi-pagination__nav-button--next"
                    >@if ($showArrowLabels){{ $mergedLabels['next'] }}@endif</tedi:button>
                @endif
            </nav>
        @endif
    </div>

    <div class="tedi-pagination__slot-end">
        @if ($showPageSizeSelect)
            @php $pageSizeLabelId = \Tedi\Livewire\Tedi::id('tedi-pagination__page-size-label'); @endphp
            <div class="tedi-pagination__page-size">
                <span class="tedi-pagination__page-size-label" id="{{ $pageSizeLabelId }}">{{ $mergedLabels['pageSize'] }}</span>
                <select
                    class="tedi-pagination__page-size-select"
                    aria-labelledby="{{ $pageSizeLabelId }}"
                    {{ (new \Illuminate\View\ComponentAttributeBag)->merge($pageSizeAttributes) }}
                >
                    @foreach ($pageSizeSelectOptions as $option)
                        <option value="{{ $option['value'] }}" @selected($pageSize === $option['value'])>{{ $option['label'] }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>
</div>
