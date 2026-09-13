{{--
    TEDI Table.
    Port of angular/tedi/components/content/table/table.component.{ts,html,table.types.ts}

    ══ DOCUMENTED SUBSET (CONVENTIONS.md §7, same spirit as <tedi:select>) ══

    The Angular component is a 2 400-line wrapper around **@tanstack/angular-table**.
    That engine has no Blade equivalent, so this port is the MARKUP LAYER only:
    a pure function of explicit `:columns` / `:rows` arrays (§5), following the
    array-prop precedent of <tedi:pagination>, <tedi:header.language> and
    <tedi:header.role>. Sorting, filtering, selection and expansion STATE are
    the consumer's; they render the result back in through these arrays.

    NOT PORTED — the TanStack engine and everything derived from it:
      • the table instance itself: `data`, `getRowId`, `getSubRows`,
        `getRowCanExpand`, `manualPagination` / `manualSorting` /
        `manualFiltering`, `pageCount`, `rowCount`, sorting comparators
        (`sortingFn`), filter functions, row models.
      • `state` / `stateChange` / `defaultState` / `persist` and
        `table.persistence.ts` (localStorage) — no server-side equivalent.
      • column visibility/order/sizing as *state*. `width` / `min-width` /
        `max-width` are still emitted per column (see `columns` below), but
        interactive column RESIZING (`columnSizing`) is not ported.
      • `reorderableRows` / `reorderableColumns`: CDK drag-and-drop plus the
        `ColumnReorderPhase` keyboard state machine (pick up / move / drop /
        cancel) and its `aria-live` region. With them go the `drag` control
        column, `.tedi-table__drag-handle`, `--picked-up` on rows / header
        cells / handles, and every `cdk-drag-*` / `cdk-drop-list-*` class.
        Those classes DO have rules in the vendored SCSS — they are dropped
        because the FEATURE is not ported, not because the rule is missing.
      • `controlColumnOrder`'s `drag` entry, for the same reason. The prop is
        kept and honoured for `select` / `expand`.
      • row virtualisation.
      • `groupRowsBy` / per-column `groupBy` / `rowSpan` callbacks: the SPANS
        are computed from the live post-filter/sort/pagination row model. The
        RESULT is portable — pass `rowspan` on a cell and `groupStart` on a row
        yourself — but the computation is not.
      • the built-in **filter popover** (`filterable` + `filterTemplate`,
        `.tedi-table__filter-popover` / `__filter` / `__filter-actions`): its
        body is an Angular `TemplateRef` bound to per-column draft state that
        only the engine owns. `enableColumnFilters` — the plain filter ROW —
        IS ported, as inert markup with an attributes hook per column.
      • `aria-rowcount` / `aria-rowindex`: both are derived from the paginated
        row model (`ariaRowIndexingEnabled = paginationEnabled && !interactive`,
        page offsets, total row count) which lives in the engine.
      • breakpoint props — never declared anywhere in this package (§7.1).

    CLASSES DROPPED because the vendored SCSS defines no rule for them (§4):
      • `tedi-table--medium` — only `.tedi-table--small` has a rule.
      • `tedi-table--clickable-rows` — the hover/pointer styling all comes from
        `--row-hover` and `.tedi-table__row--clickable`, which ARE emitted.
      • `tedi-table--grouped-headers` and `tedi-table__header-cell--group` —
        multi-level header groups are not ported (they come from the engine's
        `getHeaderGroups()`), and neither class is styled.
      • `tedi-table--draggable` — its only rule body is commented out upstream,
        and reordering is not ported anyway.

    OTHER DIVERGENCES A CONSUMER WILL NOTICE:
      • Pagination is a SLOT, not a prop. Angular re-renders <tedi-pagination>
        from table-managed state; here you compose <tedi:pagination> yourself in
        the `pagination` / `pagination-top` slots and the wrapper divs plus the
        `--has-pagination*` host classes follow from slot presence.
      • `stickyFirstColumn` offsets: Angular measures each frozen column with
        TanStack's `getSize()`. Here the same arithmetic runs on the declared
        widths — control columns are 60px (upstream's `CONTROL_COLUMN_WIDTH`)
        and a content column falls back to TanStack's 150px default. Set
        `width` on your first content column to keep the offsets exact.
      • `caption` / `placeholder` accept a string or any Htmlable; Angular also
        accepts a `TemplateRef`.
      • `rowAriaLabel` (a callback) becomes each row's `ariaLabel` key.
      • Cell / header / footer values: scalars are escaped, anything
        `Illuminate\Contracts\Support\Htmlable` (e.g. a Blade partial rendered
        with `Blade::render()`, or `new HtmlString(...)`) is emitted raw.
      • Expansion is inline Alpine per §8: an `expanded` map on the <tbody>,
        seeded from each row's `expanded` flag, so the open classes and
        `aria-expanded` are correct in the static HTML too.

    Angular's selector is the element `tedi-table`, so the root is that literal
    element with the `data-name` host attribute (§4).
--}}
@props([
    /** Stable identifier; emitted as the inner <table>'s id and used for synthetic ids. */
    'id' => null,
    /**
     * Column[]. Keys: key (required), header, footer, align (left|center|right),
     * vAlign (top|middle|bottom), sortable, sort (none|asc|desc), sortAttributes,
     * width, minWidth, maxWidth, filterable, filter (Htmlable, replaces the default
     * filter input), filterAttributes, attributes (extra <th> attributes).
     */
    'columns' => [],
    /**
     * Row[]. Keys: id, cells (keyed by column key; a scalar/Htmlable, or an array
     * with value/rowspan/align/vAlign/attributes), selected, subRow, groupStart,
     * expandable, expanded, sub (Htmlable sub-component content), selectAttributes,
     * selectDisabled, selectIndeterminate, expandAttributes, ariaLabel, attributes.
     */
    'rows' => [],
    /** medium|small — visual size. */
    'size' => 'medium',
    /** Table caption; string or Htmlable. */
    'caption' => null,
    /** Alternating row backgrounds. */
    'striped' => false,
    /** Vertical separators between columns. */
    'verticalBorders' => false,
    /** Remove the outer border + radius. */
    'borderless' => false,
    /** Freeze the leading control columns + first content column during horizontal scroll. */
    'stickyFirstColumn' => false,
    'stickyLastColumn' => false,
    /** Pin <thead> during vertical scroll. Requires maxHeight. */
    'stickyHeader' => false,
    /** table-layout: fixed — makes width/minWidth/maxWidth authoritative. */
    'fixedLayout' => false,
    /** Caps the scroll container's height. Number = px, string = any CSS length. */
    'maxHeight' => null,
    /** Row id rendered as the current/active row. */
    'activeRowId' => null,
    /** Highlight selected rows with the active background. */
    'selectedRowHighlight' => true,
    /** Force the row-hover background on/off. Defaults to interactive || expandTrigger==='row'. */
    'rowHover' => null,
    /** Render the selection control column. */
    'enableRowSelection' => false,
    /** multiple (checkbox + select-all) | single (radio, no header control). */
    'selectionMode' => 'multiple',
    /** Render the per-column filter row under the header. */
    'enableColumnFilters' => false,
    /** Render the expand control column. */
    'expandable' => false,
    /** button|row — whether a click anywhere on the row toggles expansion. */
    'expandTrigger' => 'button',
    /** default|secondary — chevron style of the expand toggle. Defaults to secondary. */
    'expandButtonVariant' => null,
    /** Visible expand-toggle label: a string, or ['open' => …, 'close' => …]. Icon-only when unset. */
    'expandButtonLabel' => null,
    /** Order of the auto-injected control columns. `drag` is accepted but never rendered. */
    'controlColumnOrder' => ['drag', 'select', 'expand'],
    /** all|between|none — where row-group dividers are drawn. Needs `grouped`. */
    'rowGroupDividers' => 'all',
    /** Explicit stand-in for Angular's `groupRowsBy !== undefined` — gates rowGroupDividers. */
    'grouped' => false,
    /** Empty-table content; string or Htmlable. Falls back to the translated "no data". */
    'placeholder' => null,
    /** alert|status — role wrapper around the placeholder. */
    'placeholderRole' => null,
    /** Rows act as buttons (role, tabindex, clickable/hover styling). */
    'interactive' => false,
    /** Header select-all checkbox state (selectionMode="multiple"). */
    'selectAllChecked' => false,
    'selectAllIndeterminate' => false,
    /** Attributes forwarded to the select-all checkbox, e.g. ['wire:model' => 'all']. */
    'selectAllAttributes' => [],
    /** Placeholder text of the default filter-row inputs. */
    'filterPlaceholder' => null,
])

@php
    use Illuminate\Contracts\Support\Htmlable;
    use Illuminate\View\ComponentAttributeBag;

    $CONTROL_COLUMN_WIDTH = 60;
    $DEFAULT_COLUMN_WIDTH = 150; // TanStack's default column size.

    $resolvedId = $id ?: \Tedi\Livewire\Tedi::id('tedi-table');

    $render = fn ($value) => $value instanceof Htmlable ? $value->toHtml() : e((string) ($value ?? ''));

    // Consumer-supplied attributes win over the component's computed defaults,
    // mirroring how $attributes->merge() behaves on a root element (§6).
    $bag = fn (array $consumer, array $defaults = []) => new ComponentAttributeBag(
        array_merge(array_filter($defaults, fn ($v) => $v !== null), $consumer)
    );

    $expandIconOnly = $expandButtonLabel === null;
    $expandOpenText = is_array($expandButtonLabel) ? ($expandButtonLabel['open'] ?? null) : $expandButtonLabel;
    $expandCloseText = is_array($expandButtonLabel) ? ($expandButtonLabel['close'] ?? null) : $expandButtonLabel;

    // ── Control columns (augmentedColumns() in table.component.ts). `drag` is
    //    accepted in controlColumnOrder for API parity but never produced.
    $controls = [];

    if ($enableRowSelection) {
        $controls['select'] = [
            'srLabel' => __('tedi::tedi.table.select-column'),
            'align' => 'center',
            'vAlign' => 'top',
            'width' => $CONTROL_COLUMN_WIDTH,
        ];
    }

    if ($expandable) {
        $controls['expand'] = [
            'srLabel' => __('tedi::tedi.table.expand-column'),
            'align' => $expandIconOnly ? 'center' : null,
            'vAlign' => 'top',
            'width' => $expandIconOnly ? $CONTROL_COLUMN_WIDTH : null,
        ];
    }

    $order = array_values(array_unique($controlColumnOrder));
    $contentIndex = array_search('content', $order, true);
    $beforeContent = $contentIndex === false ? $order : array_slice($order, 0, $contentIndex);
    $afterContent = $contentIndex === false ? [] : array_slice($order, $contentIndex + 1);

    $toControls = function (array $keys) use ($controls) {
        $out = [];
        foreach ($keys as $key) {
            if (isset($controls[$key])) {
                $out[$key] = $controls[$key];
            }
        }

        return $out;
    };

    $leadingControlColumns = $toControls($beforeContent);
    $trailingControlColumns = $toControls($afterContent);

    foreach ($controls as $key => $control) {
        if (! isset($leadingControlColumns[$key]) && ! isset($trailingControlColumns[$key])) {
            $leadingControlColumns[$key] = $control;
        }
    }

    $controlColumns = $leadingControlColumns + $trailingControlColumns;

    $leafColumnCount = count($controlColumns) + count($columns);

    // ── stickyLeftColumns(): the leading control columns + the first content
    //    column, pinned as one block with cumulative `left` offsets.
    $sticky = [];

    if ($stickyFirstColumn) {
        $frozen = [];

        foreach ($leadingControlColumns as $key => $control) {
            $frozen[] = ['id' => 'control:'.$key, 'width' => $control['width'] ?? $DEFAULT_COLUMN_WIDTH];
        }

        if (count($columns) > 0) {
            $first = $columns[array_key_first($columns)];
            $frozen[] = ['id' => 'column:'.$first['key'], 'width' => $first['width'] ?? $DEFAULT_COLUMN_WIDTH];
        }

        $left = 0;

        foreach ($frozen as $index => $entry) {
            $sticky[$entry['id']] = [
                'left' => $left,
                'start' => $index === 0,
                'edge' => $index === count($frozen) - 1,
            ];
            $left += $entry['width'];
        }
    }

    $stickyRight = [];

    if ($stickyLastColumn) {
        $frozen = [];

        foreach (array_reverse($trailingControlColumns, true) as $key => $control) {
            $frozen[] = ['id' => 'control:'.$key, 'width' => $control['width'] ?? $DEFAULT_COLUMN_WIDTH];
        }

        if (count($columns) > 0) {
            $last = $columns[array_key_last($columns)];
            $frozen[] = ['id' => 'column:'.$last['key'], 'width' => $last['width'] ?? $DEFAULT_COLUMN_WIDTH];
        }

        $right = 0;

        foreach ($frozen as $index => $entry) {
            $stickyRight[$entry['id']] = [
                'right' => $right,
                'start' => $index === 0,
                'edge' => $index === count($frozen) - 1,
            ];
            $right += $entry['width'];
        }
    }

    $stickyClasses = fn (string $id) => array_filter([
        'tedi-table__cell--sticky-left' => isset($sticky[$id]),
        'tedi-table__cell--sticky-left-start' => $sticky[$id]['start'] ?? false,
        'tedi-table__cell--sticky-left-edge' => $sticky[$id]['edge'] ?? false,
        'tedi-table__cell--sticky-right' => isset($stickyRight[$id]),
        'tedi-table__cell--sticky-right-start' => $stickyRight[$id]['start'] ?? false,
        'tedi-table__cell--sticky-right-edge' => $stickyRight[$id]['edge'] ?? false,
    ]);

    // headerCellWidth(): under fixedLayout only explicitly-sized columns get a
    // width, so the unsized ones absorb the leftover space. `left` comes from
    // stickyLeftColumns(). Returns a ready-to-emit style string, or null.
    $cellStyle = function (string $stickyId, ?int $width = null, ?int $minWidth = null, ?int $maxWidth = null, bool $withSize = true)
        use (&$sticky, &$stickyRight, $fixedLayout, $DEFAULT_COLUMN_WIDTH) {
        $parts = [];

        if ($withSize) {
            $authored = $width !== null || $minWidth !== null || $maxWidth !== null;
            $resolved = $fixedLayout && ! $authored ? null : ($width ?? $DEFAULT_COLUMN_WIDTH);

            if ($resolved !== null) {
                $parts[] = 'width: '.$resolved.'px';
            }
            if ($minWidth !== null) {
                $parts[] = 'min-width: '.$minWidth.'px';
            }
            if ($maxWidth !== null) {
                $parts[] = 'max-width: '.$maxWidth.'px';
            }
        }

        if (isset($sticky[$stickyId])) {
            $parts[] = 'left: '.$sticky[$stickyId]['left'].'px';
        }

        if (isset($stickyRight[$stickyId])) {
            $parts[] = 'right: '.$stickyRight[$stickyId]['right'].'px';
        }

        return $parts ? implode('; ', $parts) : null;
    };

    $sortIcons = ['asc' => 'arrow_upward', 'desc' => 'arrow_downward', 'none' => 'unfold_more'];
    $ariaSorts = ['asc' => 'ascending', 'desc' => 'descending', 'none' => 'none'];

    $hasFooter = false;

    foreach ($columns as $column) {
        if (($column['footer'] ?? null) !== null) {
            $hasFooter = true;
        }
    }

    $hoverEnabled = $rowHover ?? ($interactive || $expandTrigger === 'row');

    $hasTopPagination = isset($paginationTop) && $paginationTop->isNotEmpty();
    $hasBottomPagination = isset($pagination) && $pagination->isNotEmpty();

    $maxHeightStyle = $maxHeight === null
        ? null
        : 'max-height: '.(is_numeric($maxHeight) ? $maxHeight.'px' : $maxHeight).'; overflow-y: auto';

    // Alpine expansion state, seeded from each row's `expanded` flag (§8).
    $expandState = new \stdClass;

    foreach ($rows as $index => $row) {
        $expandState->{(string) ($row['id'] ?? $index)} = (bool) ($row['expanded'] ?? false);
    }

    $expandExpr = fn ($rowId) => "tediTableExpanded[".\Illuminate\Support\Js::from((string) $rowId)."]";
@endphp

<tedi-table
    data-name="tedi-table"
    {{ $attributes->class([
        'tedi-table',
        'tedi-table--small' => $size === 'small',
        'tedi-table--striped' => (bool) $striped,
        'tedi-table--vertical-borders' => (bool) $verticalBorders,
        'tedi-table--borderless' => (bool) $borderless,
        'tedi-table--sticky-first-column' => (bool) $stickyFirstColumn,
        'tedi-table--sticky-last-column' => (bool) $stickyLastColumn,
        'tedi-table--sticky-header' => (bool) $stickyHeader,
        'tedi-table--fixed-layout' => (bool) $fixedLayout,
        'tedi-table--row-hover' => (bool) $hoverEnabled,
        'tedi-table--group-dividers-'.$rowGroupDividers => $grouped && $rowGroupDividers !== 'all',
        'tedi-table--has-pagination' => $hasTopPagination || $hasBottomPagination,
        'tedi-table--has-pagination-top' => $hasTopPagination,
        'tedi-table--has-pagination-bottom' => $hasBottomPagination,
    ]) }}
>
    {{ $slot }}

    @if ($hasTopPagination)
        <div class="tedi-table__pagination tedi-table__pagination--top">{{ $paginationTop }}</div>
    @endif

    <div
        class="tedi-table__scroll"
        tabindex="0"
        role="group"
        aria-label="{{ __('tedi::tedi.table.scroll-region') }}"
        @if ($maxHeightStyle) style="{{ $maxHeightStyle }}" @endif
    >
        <table class="tedi-table__table" id="{{ $resolvedId }}">
            @if ($caption !== null && $caption !== '')
                <caption class="tedi-table__caption">{!! $render($caption) !!}</caption>
            @endif

            <thead class="tedi-table__head">
                <tr class="tedi-table__row">
                    @foreach ($leadingControlColumns as $controlKey => $control)
                        <th
                            scope="col"
                            @class(array_merge([
                                'tedi-table__header-cell',
                                'tedi-table__cell--align-'.$control['align'] => $control['align'] ?? false,
                                'tedi-table__cell--valign-'.$control['vAlign'] => $control['vAlign'] ?? false,
                            ], $stickyClasses('control:'.$controlKey)))
                            @php $style = $cellStyle('control:'.$controlKey, $control['width'] ?? null, $control['width'] ?? null, $control['width'] ?? null); @endphp
                            @if ($style) style="{{ $style }}" @endif
                        >
                            <span class="tedi-table__sr-only">{{ $control['srLabel'] }}</span>
                            @if ($controlKey === 'select' && $selectionMode === 'multiple')
                                <tedi:checkbox
                                    :id="$resolvedId.'-select-all'"
                                    :name="$resolvedId.'-select-all'"
                                    :checked="(bool) $selectAllChecked"
                                    :attributes="$bag($selectAllAttributes, [
                                        'aria-label' => __('tedi::tedi.table.select-all.'.($selectAllChecked ? 'true' : 'false')),
                                        'x-init' => $selectAllIndeterminate ? '$el.indeterminate = true' : null,
                                    ])"
                                />
                            @endif
                        </th>
                    @endforeach

                    @foreach ($columns as $column)
                        @php
                            $sort = $column['sort'] ?? 'none';
                            $isSortable = (bool) ($column['sortable'] ?? false);
                        @endphp
                        <th
                            scope="col"
                            @class(array_merge([
                                'tedi-table__header-cell',
                                'tedi-table__cell--align-'.($column['align'] ?? '') => $column['align'] ?? false,
                                'tedi-table__cell--valign-'.($column['vAlign'] ?? '') => $column['vAlign'] ?? false,
                            ], $stickyClasses('column:'.$column['key'])))
                            @php $style = $cellStyle('column:'.$column['key'], $column['width'] ?? null, $column['minWidth'] ?? null, $column['maxWidth'] ?? null); @endphp
                            @if ($style) style="{{ $style }}" @endif
                            @if ($isSortable) aria-sort="{{ $ariaSorts[$sort] ?? 'none' }}" @endif
                            {{ $bag($column['attributes'] ?? []) }}
                        >
                            <span class="tedi-table__header-content">
                                @if ($isSortable)
                                    <tedi:table-header-button
                                        :icon="$sortIcons[$sort] ?? 'unfold_more'"
                                        :selected="$sort !== 'none'"
                                        :attributes="$bag($column['sortAttributes'] ?? [])"
                                    >{!! $render($column['header'] ?? '') !!}</tedi:table-header-button>
                                @else
                                    {!! $render($column['header'] ?? '') !!}
                                @endif
                            </span>
                        </th>
                    @endforeach

                    @foreach ($trailingControlColumns as $controlKey => $control)
                        <th
                            scope="col"
                            @class(array_merge([
                                'tedi-table__header-cell',
                                'tedi-table__cell--align-'.$control['align'] => $control['align'] ?? false,
                                'tedi-table__cell--valign-'.$control['vAlign'] => $control['vAlign'] ?? false,
                            ], $stickyClasses('control:'.$controlKey)))
                            @php $style = $cellStyle('control:'.$controlKey, $control['width'] ?? null, $control['width'] ?? null, $control['width'] ?? null); @endphp
                            @if ($style) style="{{ $style }}" @endif
                        >
                            <span class="tedi-table__sr-only">{{ $control['srLabel'] }}</span>
                            @if ($controlKey === 'select' && $selectionMode === 'multiple')
                                <tedi:checkbox
                                    :id="$resolvedId.'-select-all'"
                                    :name="$resolvedId.'-select-all'"
                                    :checked="(bool) $selectAllChecked"
                                    :attributes="$bag($selectAllAttributes, [
                                        'aria-label' => __('tedi::tedi.table.select-all.'.($selectAllChecked ? 'true' : 'false')),
                                        'x-init' => $selectAllIndeterminate ? '$el.indeterminate = true' : null,
                                    ])"
                                />
                            @endif
                        </th>
                    @endforeach
                </tr>
                @if ($enableColumnFilters)
                    <tr class="tedi-table__row tedi-table__row--filter">
                        @foreach ($leadingControlColumns as $controlKey => $control)
                            <th class="tedi-table__header-cell" scope="col"></th>
                        @endforeach

                        @foreach ($columns as $column)
                            <th class="tedi-table__header-cell" scope="col">
                                @if (($column['filter'] ?? null) !== null)
                                    {!! $render($column['filter']) !!}
                                @elseif ($column['filterable'] ?? false)
                                    @php $filterId = $resolvedId.'-filter-'.$column['key']; @endphp
                                    <tedi:form-field size="small">
                                        <tedi:text-field
                                            :id="$filterId"
                                            :name="$filterId"
                                            :placeholder="$filterPlaceholder ?? __('tedi::tedi.table.filter-placeholder')"
                                            :attributes="$bag($column['filterAttributes'] ?? [], [
                                                'aria-label' => trim(__('tedi::tedi.table.filter').' '.strip_tags($render($column['header'] ?? ''))),
                                            ])"
                                        />
                                    </tedi:form-field>
                                @endif
                            </th>
                        @endforeach

                        @foreach ($trailingControlColumns as $controlKey => $control)
                            <th class="tedi-table__header-cell" scope="col"></th>
                        @endforeach
                    </tr>
                @endif

            <tbody class="tedi-table__body" x-data="{ tediTableExpanded: {{ \Illuminate\Support\Js::from($expandState) }} }">
                @if (count($rows) === 0)
                    <tr class="tedi-table__row">
                        <td class="tedi-table__cell tedi-table__cell--placeholder" colspan="{{ max($leafColumnCount, 1) }}">
                            @if ($placeholderRole)
                                <div role="{{ $placeholderRole }}">{!! $render($placeholder ?? __('tedi::tedi.table.no-data')) !!}</div>
                            @else
                                {!! $render($placeholder ?? __('tedi::tedi.table.no-data')) !!}
                            @endif
                        </td>
                    </tr>
                @else
                    @foreach ($rows as $index => $row)
                        @php
                            $rowId = (string) ($row['id'] ?? $index);
                            $isActive = $activeRowId !== null && $rowId === (string) $activeRowId;
                            $rowExpandable = (bool) ($row['expandable'] ?? false);
                            $expandsOnClick = $expandable && $expandTrigger === 'row' && $rowExpandable;
                            $isExpanded = (bool) ($row['expanded'] ?? false);
                            $subRowId = $resolvedId.'-sub-'.$rowId;
                            $openExpr = $expandExpr($rowId);
                        @endphp

                        <tr
                            @class([
                                'tedi-table__row',
                                'tedi-table__row--selected' => $selectedRowHighlight && ($row['selected'] ?? false),
                                'tedi-table__row--active' => $isActive,
                                'tedi-table__row--clickable' => $interactive || $expandsOnClick,
                                'tedi-table__row--sub-row' => (bool) ($row['subRow'] ?? false),
                                'tedi-table__row--group-start' => (bool) ($row['groupStart'] ?? false),
                            ])
                            @if ($interactive) role="button" tabindex="0" @endif
                            @if ($isActive) aria-current="true" @endif
                            @if (($row['ariaLabel'] ?? null) && $interactive) aria-label="{{ $row['ariaLabel'] }}" @endif
                            @if ($expandsOnClick)
                                x-on:click="{{ $openExpr }} = ! {{ $openExpr }}"
                                x-on:keydown.enter.prevent="{{ $openExpr }} = ! {{ $openExpr }}"
                                x-on:keydown.space.prevent="{{ $openExpr }} = ! {{ $openExpr }}"
                            @endif
                            {{ $bag($row['attributes'] ?? []) }}
                        >
                            @foreach ($leadingControlColumns as $controlKey => $control)
                                <td
                                    @class(array_merge([
                                        'tedi-table__cell',
                                        'tedi-table__cell--align-'.$control['align'] => $control['align'] ?? false,
                                        'tedi-table__cell--valign-'.$control['vAlign'] => $control['vAlign'] ?? false,
                                    ], $stickyClasses('control:'.$controlKey)))
                                    @php $style = $cellStyle('control:'.$controlKey, withSize: false); @endphp
                                    @if ($style) style="{{ $style }}" @endif
                                >
                                    @if ($controlKey === 'select')
                                        @if ($selectionMode === 'multiple')
                                            <tedi:checkbox
                                                :id="$resolvedId.'-select-'.$rowId"
                                                :name="$resolvedId.'-select-'.$rowId"
                                                :checked="(bool) ($row['selected'] ?? false)"
                                                :disabled="(bool) ($row['selectDisabled'] ?? false)"
                                                :attributes="$bag($row['selectAttributes'] ?? [], [
                                                    'aria-label' => __('tedi::tedi.table.select-row.'.(($row['selected'] ?? false) ? 'true' : 'false')),
                                                    'x-init' => ($row['selectIndeterminate'] ?? false) ? '$el.indeterminate = true' : null,
                                                    'x-on:click' => '$event.stopPropagation()',
                                                ])"
                                            />
                                        @else
                                            <tedi:radio
                                                :id="$resolvedId.'-select-'.$rowId"
                                                :name="$resolvedId.'-select-row'"
                                                :checked="(bool) ($row['selected'] ?? false)"
                                                :disabled="(bool) ($row['selectDisabled'] ?? false)"
                                                :attributes="$bag($row['selectAttributes'] ?? [], [
                                                    'aria-label' => __('tedi::tedi.table.select-row.'.(($row['selected'] ?? false) ? 'true' : 'false')),
                                                    'x-on:click' => '$event.stopPropagation()',
                                                ])"
                                            />
                                        @endif
                                    @elseif ($controlKey === 'expand')
                                        <span @class([
                                            'tedi-table__expand-toggle',
                                            'tedi-table__expand-toggle--icon-only' => $expandIconOnly,
                                        ])>
                                            @if ($rowExpandable)
                                                <tedi:collapse-button
                                                    :state="$openExpr"
                                                    :open="$isExpanded"
                                                    :hide-text="$expandIconOnly"
                                                    :arrow-type="$expandButtonVariant ?? 'secondary'"
                                                    :open-text="$expandOpenText"
                                                    :close-text="$expandCloseText"
                                                    :id="$resolvedId.'-expand-'.$rowId"
                                                    :aria-controls="($row['sub'] ?? null) !== null ? $subRowId : null"
                                                    :aria-label="$expandIconOnly ? __('tedi::tedi.table.'.($isExpanded ? 'collapse-row' : 'expand-row')) : null"
                                                    :attributes="$bag($row['expandAttributes'] ?? [], [
                                                        'x-on:click.stop' => '',
                                                    ])"
                                                />
                                            @endif
                                        </span>
                                    @endif
                                </td>
                            @endforeach

                            @foreach ($columns as $column)
                                @php
                                    $cell = $row['cells'][$column['key']] ?? null;
                                    $cellData = is_array($cell) ? $cell : ['value' => $cell];
                                    $rowspan = $cellData['rowspan'] ?? null;
                                @endphp

                                @if ($rowspan !== 0)
                                    <td
                                        @class(array_merge([
                                            'tedi-table__cell',
                                            'tedi-table__cell--align-'.($cellData['align'] ?? $column['align'] ?? '') => ($cellData['align'] ?? $column['align'] ?? false),
                                            'tedi-table__cell--valign-'.($cellData['vAlign'] ?? $column['vAlign'] ?? '') => ($cellData['vAlign'] ?? $column['vAlign'] ?? false),
                                        ], $stickyClasses('column:'.$column['key'])))
                                        @php $style = $cellStyle('column:'.$column['key'], withSize: false); @endphp
                                        @if ($style) style="{{ $style }}" @endif
                                        @if ($rowspan !== null && $rowspan > 1) rowspan="{{ $rowspan }}" @endif
                                        {{ $bag($cellData['attributes'] ?? []) }}
                                    >{!! $render($cellData['value'] ?? null) !!}</td>
                                @endif
                            @endforeach

                            @foreach ($trailingControlColumns as $controlKey => $control)
                                <td
                                    @class(array_merge([
                                        'tedi-table__cell',
                                        'tedi-table__cell--align-'.$control['align'] => $control['align'] ?? false,
                                        'tedi-table__cell--valign-'.$control['vAlign'] => $control['vAlign'] ?? false,
                                    ], $stickyClasses('control:'.$controlKey)))
                                    @php $style = $cellStyle('control:'.$controlKey, withSize: false); @endphp
                                    @if ($style) style="{{ $style }}" @endif
                                >
                                    @if ($controlKey === 'select')
                                        @if ($selectionMode === 'multiple')
                                            <tedi:checkbox
                                                :id="$resolvedId.'-select-'.$rowId"
                                                :name="$resolvedId.'-select-'.$rowId"
                                                :checked="(bool) ($row['selected'] ?? false)"
                                                :disabled="(bool) ($row['selectDisabled'] ?? false)"
                                                :attributes="$bag($row['selectAttributes'] ?? [], [
                                                    'aria-label' => __('tedi::tedi.table.select-row.'.(($row['selected'] ?? false) ? 'true' : 'false')),
                                                    'x-init' => ($row['selectIndeterminate'] ?? false) ? '$el.indeterminate = true' : null,
                                                    'x-on:click' => '$event.stopPropagation()',
                                                ])"
                                            />
                                        @else
                                            <tedi:radio
                                                :id="$resolvedId.'-select-'.$rowId"
                                                :name="$resolvedId.'-select-row'"
                                                :checked="(bool) ($row['selected'] ?? false)"
                                                :disabled="(bool) ($row['selectDisabled'] ?? false)"
                                                :attributes="$bag($row['selectAttributes'] ?? [], [
                                                    'aria-label' => __('tedi::tedi.table.select-row.'.(($row['selected'] ?? false) ? 'true' : 'false')),
                                                    'x-on:click' => '$event.stopPropagation()',
                                                ])"
                                            />
                                        @endif
                                    @elseif ($controlKey === 'expand')
                                        <span @class([
                                            'tedi-table__expand-toggle',
                                            'tedi-table__expand-toggle--icon-only' => $expandIconOnly,
                                        ])>
                                            @if ($rowExpandable)
                                                <tedi:collapse-button
                                                    :state="$openExpr"
                                                    :open="$isExpanded"
                                                    :hide-text="$expandIconOnly"
                                                    :arrow-type="$expandButtonVariant ?? 'secondary'"
                                                    :open-text="$expandOpenText"
                                                    :close-text="$expandCloseText"
                                                    :id="$resolvedId.'-expand-'.$rowId"
                                                    :aria-controls="($row['sub'] ?? null) !== null ? $subRowId : null"
                                                    :aria-label="$expandIconOnly ? __('tedi::tedi.table.'.($isExpanded ? 'collapse-row' : 'expand-row')) : null"
                                                    :attributes="$bag($row['expandAttributes'] ?? [], [
                                                        'x-on:click.stop' => '',
                                                    ])"
                                                />
                                            @endif
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        @if ($expandable && $rowExpandable && ($row['sub'] ?? null) !== null)
                            <tr
                                @class([
                                    'tedi-table__row',
                                    'tedi-table__row--sub-component',
                                    'tedi-table__row--sub-component-open' => $isExpanded,
                                ])
                                x-bind:class="{ 'tedi-table__row--sub-component-open': {{ $openExpr }} }"
                            >
                                <td
                                    class="tedi-table__cell tedi-table__cell--sub-component"
                                    id="{{ $subRowId }}"
                                    colspan="{{ max($leafColumnCount, 1) }}"
                                    @if ($isExpanded) role="region" aria-label="{{ __('tedi::tedi.table.row-details') }}" @else inert @endif
                                    x-bind:inert="{{ $openExpr }} ? null : ''"
                                >
                                    <div class="tedi-table__sub-component-wrapper">
                                        <div class="tedi-table__sub-component-content">
                                            <div class="tedi-table__sub-component-inner">{!! $render($row['sub']) !!}</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>

            @if ($hasFooter)
                <tfoot class="tedi-table__foot">
                    <tr class="tedi-table__row">
                        @foreach ($leadingControlColumns as $controlKey => $control)
                            <td @class([
                                'tedi-table__cell',
                                'tedi-table__cell--footer',
                                'tedi-table__cell--align-'.$control['align'] => $control['align'] ?? false,
                                'tedi-table__cell--valign-'.$control['vAlign'] => $control['vAlign'] ?? false,
                            ])></td>
                        @endforeach

                        @foreach ($columns as $column)
                            <td @class([
                                'tedi-table__cell',
                                'tedi-table__cell--footer',
                                'tedi-table__cell--align-'.($column['align'] ?? '') => $column['align'] ?? false,
                                'tedi-table__cell--valign-'.($column['vAlign'] ?? '') => $column['vAlign'] ?? false,
                            ])>{!! $render($column['footer'] ?? null) !!}</td>
                        @endforeach

                        @foreach ($trailingControlColumns as $controlKey => $control)
                            <td @class([
                                'tedi-table__cell',
                                'tedi-table__cell--footer',
                                'tedi-table__cell--align-'.$control['align'] => $control['align'] ?? false,
                                'tedi-table__cell--valign-'.$control['vAlign'] => $control['vAlign'] ?? false,
                            ])></td>
                        @endforeach
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>

    @if ($hasBottomPagination)
        <div class="tedi-table__pagination tedi-table__pagination--bottom">{{ $pagination }}</div>
    @endif
</tedi-table>
