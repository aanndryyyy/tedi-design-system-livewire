{{--
    TEDI Table Card.
    Port of react/src/tedi/components/content/table-card/table-card.tsx
    (CONVENTIONS.md §13).

    One table row as a stacked card of label / value pairs — the readable
    mobile counterpart of a table. Rows are data: `rows` is an array of
    ['label' => …, 'value' => …, 'bold' => bool, 'colSpan' => int]. A string
    label is wrapped in a label (`label-size` picks small / default); a string
    value is escaped; any Htmlable (`new HtmlString(…)`, a rendered view) is
    emitted as-is — the stand-in for React's ReactNode.

    COMPOSITION. Upstream builds it from React's `Card` / `Card.Content` and
    `TextGroupList`. Here:
      * the card is this package's (Angular's) `<tedi:card>` / `<tedi:card-content>`
        — React's card.module.scss is NOT loaded, because it styles `.tedi-card`
        itself, which Angular's card stylesheet already owns;
      * the rows are `<tedi:text-group-list>`, the port of React's
        `TextGroupList`, whose stylesheet (React's text-group.module.scss) is
        loaded scoped under `.tedi-table-card` (CONVENTIONS.md §13.7) so that it
        does not restyle the Angular `<tedi:text-group>`.

    THE SEPARATOR — A DOCUMENTED DIVERGENCE. React's `Card.Content hasSeparator`
    emits `tedi-card__content--separator`, a 1px `--card-border-primary` bottom
    border defined in React's card.module.scss. Angular's card-content has no
    separator. The class is emitted under React's name, and its one
    declaration is restated in table-card.composition.scss, scoped to
    `.tedi-table-card` — the only rule behind this component that is not
    vendored verbatim. A consumer's child block (see `children` below) uses
    the same class: `<tedi:card-content class="tedi-card__content--separator">`.

    COMPOSED PRIMITIVES, AND WHAT THEY CHANGE.
      * React's `Text` is a bare `cn()` over className, modifiers and color, so
        the title, subtitle and bold values are written as plain elements with
        React's exact class strings rather than through `<tedi:text>`, whose
        Angular default `color="primary"` would add a class React does not.
        The collapsible title keeps React `Text`'s `tabindex="-1"` (it has an id).
      * React's `Label` becomes `<tedi:form.label>`, i.e. Angular's label:
        `tedi-label tedi-label--secondary [tedi-label--small]`. Angular's
        `--small` sets only the font size; React's also sets the small weight and
        line height, so small labels here keep the body line height.
      * React's `Checkbox` becomes `<tedi:checkbox>` (Angular's bare
        `input[tedi-checkbox]`) inside a `<tedi:form.label>` carrying React's
        `data-name="check"`, which keeps table-card.module's
        `__header--select-title [data-name='check']` rule live. React's
        `tedi-checkbox` wrapper / `tedi-checkbox__label` vocabulary is not
        Angular's and is not emitted.
      * React's `CollapseButton` becomes `<tedi:collapse-button>`.

    STATE (CONVENTIONS.md §8). Open / closed lives in Alpine on the root
    (`tediTableCardOpen`), shared with the collapse button through its `state`
    prop. Everything state-dependent — the body's `hidden`, the header
    separator when there are no actions — is rendered statically for the
    initial state and also bound. A click anywhere on the header row toggles,
    except on an `a, button, input, select, textarea, label` (as upstream).
    `open` / `default-open` and `selected` / `default-selected` both just set
    the initial state; `onOpenChange` / `onSelectedChange` are not re-emitted —
    bind `x-on:click` / `wire:model` through the markup instead. The selection
    checkbox is a real form control named `{id}-select` with value `selected`.

    CHILDREN. The default slot is React's `children`: extra full-width body
    content after the rows that collapses with them (React's "child
    row-groups"). Content in it is rendered while `<tedi:table-card>` is the
    enclosing component, so a child `<tedi:card-content>` written without
    `padding` inherits table-card's `padding` (if one was passed) via @aware,
    not React's card-level 0. Pass `padding` on child blocks, as upstream's
    stories do.

    NOT PORTED: breakpoint props (`sm`, `md`, … — CONVENTIONS.md §7), and the
    dev-only console warning for a collapsible card with no accessible name.

    The selection checkbox's default accessible name is the React-only label
    key `table-card.select-row` (CONVENTIONS.md §13.6).
--}}
@props([
    /** Label / value pairs: [['label' => …, 'value' => …, 'bold' => bool, 'colSpan' => int], …]. */
    'rows' => [],
    /** Header title; string, Htmlable, or the `title` slot. Required for `collapsible`. */
    'title' => null,
    /** Secondary line under the title; string, Htmlable, or the `subtitle` slot. */
    'subtitle' => null,
    /** h2|h3|h4|h5|h6 — heading tag for the title. */
    'titleElement' => 'h3',
    /** Visual size / weight of the title (Text modifiers), e.g. 'h4' or ['h6']. */
    'titleModifiers' => null,
    /** Make the header a toggle that shows / hides the body. */
    'collapsible' => false,
    /** Initial open state. Ignored when `open` is set. */
    'defaultOpen' => true,
    /** Open state; in Blade also only the initial value. */
    'open' => null,
    /** default|secondary — collapse indicator style. */
    'arrowType' => 'default',
    /** Emphasised summary / total row: ['label' => …, 'value' => …]. */
    'summary' => null,
    /** Show a leading checkbox to select the row. */
    'selectable' => false,
    /** Selected state; in Blade also only the initial value. */
    'selected' => null,
    /** Initial selected state. Ignored when `selected` is set. */
    'defaultSelected' => false,
    /** small|default — label text size. */
    'labelSize' => 'default',
    /** Accessible name for the selection checkbox. Falls back to the translated "Select row". */
    'selectionLabel' => null,
    /** Accessible name for the card region. */
    'ariaLabel' => null,
    /** Root element id (also wires the collapsible ARIA). Generated when omitted. */
    'id' => null,
    /** horizontal|vertical — label beside value, or above it. */
    'layout' => 'horizontal',
    /** Number of equal-width columns. Best with layout="vertical". */
    'columns' => 1,
    /** Label column width: CSS length, or a number read as a percent (upstream's TextGroupList). */
    'labelWidth' => null,
    /** left|right. Defaults to right in horizontal layout, left otherwise. */
    'labelAlign' => null,
    /** left|right. Defaults to right in horizontal layout, left otherwise. */
    'valueAlign' => null,
    /** start|center — label against value (horizontal only). */
    'rowAlign' => 'start',
    /** Body padding, as card-content's `padding`: rem number, ['vertical'=>,'horizontal'=>] or ['top'=>,…]. */
    'padding' => 1,
    /** Vertical gap between rows: CSS length, or a number read as rem. */
    'rowGap' => null,
])

@php
    use Illuminate\Contracts\Support\Htmlable;
    use Illuminate\Support\Facades\Blade;
    use Illuminate\Support\HtmlString;
    use Illuminate\View\ComponentSlot;

    $filledValue = fn ($value) => $value instanceof ComponentSlot ? $value->isNotEmpty() : filled($value);
    $render = fn ($value) => $value instanceof Htmlable ? $value->toHtml() : e((string) ($value ?? ''));

    $actions = $actions ?? null;
    $endSlot = $endSlot ?? null;

    // LAYOUT_DEFAULTS
    $isHorizontal = $layout === 'horizontal';
    $layoutDefaults = $isHorizontal
        ? ['labelAlign' => 'right', 'valueAlign' => 'right', 'labelWidth' => 'var(--text-group-label-width-sm)', 'rowGap' => '0']
        : ['labelAlign' => 'left', 'valueAlign' => 'left', 'labelWidth' => 'auto', 'rowGap' => 'var(--layout-grid-gutters-16)'];
    $resolvedLabelAlign = $labelAlign ?? $layoutDefaults['labelAlign'];
    $resolvedValueAlign = $valueAlign ?? $layoutDefaults['valueAlign'];
    $resolvedLabelWidth = $labelWidth ?? $layoutDefaults['labelWidth'];
    // A numeric rowGap is rems (consistent with `padding`); strings pass through.
    $resolvedRowGap = is_int($rowGap) || is_float($rowGap) ? $rowGap.'rem' : ($rowGap ?? $layoutDefaults['rowGap']);

    $id = $id ?? \Tedi\Livewire\Tedi::id('tedi-table-card');
    $bodyId = $id.'-body';
    $titleId = $id.'-title';

    $isOpen = $collapsible ? (bool) ($open ?? $defaultOpen) : true;
    $isSelected = (bool) ($selected ?? $defaultSelected);
    $selectionLabel = $selectionLabel ?? __('tedi::tedi.table-card.select-row');

    $hasTitle = $filledValue($title);
    $hasSubtitle = $filledValue($subtitle);
    $hasEndSlot = $filledValue($endSlot);
    $hasActions = $filledValue($actions);
    $hasSummary = is_array($summary);
    $hasChildren = $slot->isNotEmpty();

    $hasHeader = $selectable || $hasTitle || $hasSubtitle || $hasEndSlot || $collapsible;
    $headerHasSeparator = $collapsible ? ($isOpen || $hasActions) : $selectable;
    $tightenHeaderBottom = $isOpen && ! $headerHasSeparator;
    $bodyHasSeparator = $hasSummary || $hasActions || $hasChildren;
    $summaryHasSeparator = $hasActions;
    $titleAsSelectionLabel = $selectable && ! $collapsible && $hasTitle && ! $hasSubtitle;
    // Only a collapsible card without actions changes its header separator with state.
    $separatorFollowsState = $collapsible && ! $hasActions;

    // Text: cn(className, modifiers → tedi-text--hN | text-X, color → tedi-text--X)
    $modifierClasses = array_map(
        fn ($m) => preg_match('/^h[1-6]$/', $m) ? 'tedi-text--'.$m : 'text-'.$m,
        is_array($titleModifiers) ? $titleModifiers : (filled($titleModifiers) ? [$titleModifiers] : [])
    );
    $titleClass = implode(' ', array_merge(
        ['tedi-table-card__title'],
        $modifierClasses ? [] : ['tedi-table-card__title--body'],
        $modifierClasses,
    ));

    // renderRowLabel(): a string gets a (small or default) Label, anything else is as-is.
    $rowLabel = fn ($label) => is_string($label)
        ? new HtmlString(Blade::render(
            '<tedi:form.label :size="$size">{{ $text }}</tedi:form.label>',
            ['size' => $labelSize === 'small' ? 'small' : 'default', 'text' => $label]
        ))
        : $label;
    $bold = fn ($value) => new HtmlString('<span class="text-bold">'.$render($value).'</span>');

    $items = array_map(fn ($row) => array_filter([
        'label' => $rowLabel($row['label'] ?? ''),
        'value' => ($row['bold'] ?? false) ? $bold($row['value'] ?? '') : ($row['value'] ?? ''),
        'colSpan' => $row['colSpan'] ?? null,
    ], fn ($v) => $v !== null), $rows);

    $headerPadding = $tightenHeaderBottom ? ['top' => 1, 'right' => 1, 'bottom' => 0, 'left' => 1] : 1;

    $root = $attributes->class(['tedi-table-card'])->merge(array_filter([
        'id' => $id,
        'aria-label' => $ariaLabel,
        'x-data' => $collapsible ? '{ tediTableCardOpen: '.($isOpen ? 'true' : 'false').' }' : null,
    ], fn ($v) => $v !== null));

    $headerAttributes = new \Illuminate\View\ComponentAttributeBag(array_filter([
        'class' => implode(' ', array_filter([
            'tedi-table-card__header',
            $titleAsSelectionLabel ? 'tedi-table-card__header--select-title' : null,
            $headerHasSeparator ? 'tedi-card__content--separator' : null,
        ])),
        'x-bind:class' => $separatorFollowsState ? "{ 'tedi-card__content--separator': tediTableCardOpen }" : null,
    ]));
@endphp

<tedi:card :padding="0" :attributes="$root">
    @if ($hasHeader)
        <tedi:card-content :padding="$headerPadding" :attributes="$headerAttributes">
            @if ($titleAsSelectionLabel)
                <{{ $titleElement }} class="tedi-table-card__heading">
                    <tedi:form.label color="primary" data-name="check" class="flex align-items-center" style="gap: var(--form-checkbox-radio-inner-spacing)">
                        <tedi:checkbox :id="$id.'-select'" :name="$id.'-select'" value="selected" :checked="$isSelected" />
                        <span class="{{ $titleClass }} tedi-text--primary">{!! $render($title) !!}</span>
                    </tedi:form.label>
                </{{ $titleElement }}>
            @elseif ($selectable)
                <tedi:form.label data-name="check" class="flex align-items-center">
                    <tedi:checkbox :id="$id.'-select'" :name="$id.'-select'" value="selected" :checked="$isSelected" />
                    <span class="sr-only">{{ $selectionLabel }}</span>
                </tedi:form.label>
            @endif

            @if ($collapsible)
                <div
                    class="tedi-table-card__collapse-row"
                    x-on:click="if (! $event.target.closest('a, button, input, select, textarea, label')) tediTableCardOpen = ! tediTableCardOpen"
                >
                    <span class="tedi-table-card__title-group">
                        @if ($hasTitle)
                            <{{ $titleElement }} id="{{ $titleId }}" tabindex="-1" class="{{ $titleClass }}">{!! $render($title) !!}</{{ $titleElement }}>
                        @endif
                        @if ($hasSubtitle)
                            <span class="text-small tedi-text--secondary">{!! $render($subtitle) !!}</span>
                        @endif
                    </span>
                    @if ($hasEndSlot)
                        <span class="tedi-table-card__end-slot">{!! $render($endSlot) !!}</span>
                    @endif
                    <tedi:collapse-button
                        :hide-text="true"
                        :arrow-type="$arrowType"
                        :open="$isOpen"
                        state="tediTableCardOpen"
                        :aria-controls="$bodyId"
                        :aria-label="$hasTitle ? null : $ariaLabel"
                        :aria-labelledby="$hasTitle ? $titleId : null"
                    />
                </div>
            @else
                @if (($hasTitle || $hasSubtitle) && ! $titleAsSelectionLabel)
                    <span class="tedi-table-card__title-group">
                        @if ($hasTitle)
                            <{{ $titleElement }} class="{{ $titleClass }}">{!! $render($title) !!}</{{ $titleElement }}>
                        @endif
                        @if ($hasSubtitle)
                            <span class="text-small tedi-text--secondary">{!! $render($subtitle) !!}</span>
                        @endif
                    </span>
                @endif
                @if ($hasEndSlot)
                    <div class="tedi-table-card__end-slot">{!! $render($endSlot) !!}</div>
                @endif
            @endif
        </tedi:card-content>
    @endif

    <div
        id="{{ $bodyId }}"
        @if ($hasActions) class="tedi-table-card__body--has-footer" @endif
        @if (! $isOpen) hidden @endif
        @if ($collapsible) x-bind:hidden="! tediTableCardOpen" @endif
    >
        <tedi:card-content :padding="$padding" @class(['tedi-card__content--separator' => $bodyHasSeparator])>
            @if ($isHorizontal)
                <tedi:text-group-list
                    type="horizontal"
                    :items="$items"
                    :columns="$columns"
                    :row-gap="$resolvedRowGap"
                    :label-align="$resolvedLabelAlign"
                    :value-align="$resolvedValueAlign"
                    :label-width="$resolvedLabelWidth"
                    :row-align="$rowAlign"
                />
            @else
                <tedi:text-group-list
                    type="vertical"
                    :items="$items"
                    :columns="$columns"
                    :row-gap="$resolvedRowGap"
                    label-align="left"
                    :label-width="$resolvedLabelWidth"
                />
            @endif
        </tedi:card-content>

        {{ $slot }}

        @if ($hasSummary)
            <tedi:card-content :padding="1" background="tertiary" @class(['tedi-card__content--separator' => $summaryHasSeparator])>
                <tedi:text-group-list
                    type="horizontal"
                    :label-align="$resolvedLabelAlign"
                    value-align="right"
                    :label-width="$resolvedLabelWidth"
                    :items="[['label' => $rowLabel($summary['label'] ?? ''), 'value' => $bold($summary['value'] ?? '')]]"
                />
            </tedi:card-content>
        @endif
    </div>

    @if ($hasActions)
        <tedi:card-content :padding="['vertical' => 0.5, 'horizontal' => 1]" class="tedi-table-card__actions">
            {{ $actions }}
        </tedi:card-content>
    @endif
</tedi:card>
