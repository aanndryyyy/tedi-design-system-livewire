{{--
    TEDI Date field — DOCUMENTED SUBSET.
    Port of angular/src/tedi/components/form/date-field/{date-field.component.ts,html}

    Angular's `tedi-date-field` is a thin composer: a <tedi-date-input> plus a
    CDK-overlay-mounted <tedi-calendar>. The selector is an element selector, but
    the vendored SCSS has no `tedi-date-field { … }` rule — it styles
    `.tedi-date-field` only — so per the batch ruling the root is a <div> carrying
    the host class.

    `{{ $attributes }}` goes on that root <div>. This component is NOT one of the
    native-control carve-outs (CONVENTIONS.md §6): it renders no control of its
    own, it composes <tedi:date-input>, which owns the carve-out and puts its own
    bag on the real <input>. So `wire:model` written on <tedi:date-field> lands on
    the wrapper — put it on a nested <tedi:date-input> if you need the binding, or
    bind the calendar's day buttons directly.

    SUBSET — the overlay:
    * **Popover positioning is dropped** (CONVENTIONS.md §7 item 3). Angular mounts
      the calendar through `CdkConnectedOverlay`. Here `.tedi-date-field__overlay`
      renders under an explicit `open` prop, anchored with inline styles to its
      first position: below the field, start-aligned for `calendar-trigger="input"`
      and end-aligned otherwise, 4px gap (`FIELD_OVERLAY_GAP`).
      It cannot stay in the flow: since 8.0 the root is the flex surface row, so an
      in-flow panel would sit beside the input. CDK's fallback positions (flip
      above, start-aligned), `hideOnScroll` and re-parenting are not ported.
    THE MODAL BRANCH (Angular's `date-field-modal/`):
    * `modal` takes `true`, `false` or a breakpoint name (`sm`|`md`|`lg`|`xl`,
      Angular's `DateFieldModalInput`): the modal below that breakpoint, the
      popover from it up. `true` renders the modal alone and `false` the popover
      alone, server-side. A breakpoint name is resolved in the browser by the
      `tediDateField` behaviour (resources/js/src/date-field.js, core's
      `$grid-breakpoints`) — a deliberate exception to CONVENTIONS.md §7 item 1,
      because the choice is between two whole branches, not a class. Both
      branches are rendered; the popover is `x-show`n off `modalActive` and the
      modal's own `open` follows it with an `x-effect`. Without JS the popover
      shows and the modal stays closed, which is upstream's state before its
      breakpoint observer first emits. Any other string is treated as `false`.
    * The modal composes `tedi:modal` (size `small`, position `center`, as
      `openModal()` passes to `ModalService.open()`), `tedi:modal-header` with
      the `date-field.modal-title` `<h2>`, `tedi:modal-content` holding a
      borderless calendar, and `tedi:modal-footer` with Cancel / Confirm.
      Upstream opens it through `ModalService` (the CDK service branch); the
      port has only the standalone `tedi:modal`, so the host carries the
      standalone classes (`tedi-modal--small tedi-modal--center …`) instead of
      `tedi-modal--service` inside a `tedi-modal-dialog*` pane. The heading gets
      a server-side id and the dialog `aria-labelledby` points at it — what
      upstream's `resolveModalHeadingId` does at runtime, but morph-safe.
    * Width. Upstream caps the pane at
      `calc(N * var(--tedi-containers-03) + 2 * var(--tedi-borders-01))` (N =
      `numberOfMonths`) unless `calendarFullWidth`, and leaves the `sm` preset in
      place when it is. Here the cap is `tedi:modal`'s custom `width` (inline,
      still bounded by the dialog's 95vw max-width), and `calendarFullWidth`
      keeps the `sm` preset (`tedi-modal--sm`).
    * `calendarFullWidth` adds `tedi-date-field-modal--full-width` to the modal
      host and `full-width` to the modal's calendar, never to the popover's —
      Angular applies it in modal mode only.
    * `fullscreen` is declared but INERT. Upstream's fullscreen rules are
      `.tedi-modal-dialog--fullscreen[-<bp>]`, which style the service-branch
      pane; the standalone `.tedi-modal__dialog` has no fullscreen rule, and no
      class this component can emit makes it fullscreen. It is declared so it
      does not leak onto the root as a stray attribute (§7 item 1's exception);
      it starts working once `tedi:modal` supports fullscreen.
    * Draft / Confirm / Cancel. Upstream's modal keeps a `draft` signal seeded
      from the value and commits it on Confirm. Nothing here selects a date on
      the client (the calendar is server markup, its day buttons carry
      `data-date-key`, and the consumer binds them), so the draft is an explicit
      `draft` prop, like `open` (CONVENTIONS.md §5): the modal's calendar shows
      `draft`, falling back to `value`. Confirm dispatches a bubbling
      `tedi-date-field-confirm` event and closes the modal; every other close —
      Cancel (`requestClose('programmatic')`, upstream's `ref.close(undefined)`),
      the header close button, the backdrop, Escape — goes through `tediModal`'s
      cancelable `tedi-modal-close`. Both bubble to this root, where
      `$attributes` live, so a Livewire consumer binds
      `wire:tedi-date-field-confirm` to commit `draft` and close, and
      `wire:tedi-modal-close` to discard it and close. Confirm uses the
      unguarded `close()` so `tedi-modal-close` always means "discard"; upstream
      runs Confirm through `canClose` too.
    * `closeOnSelect` stays runtime-only: upstream's modal confirms on a day
      click in single mode; here the consumer's day handler decides whether to
      write `draft` or commit straight away.
    * The calendar footer slot is not forwarded into the modal — upstream's own
      template says the modal "does NOT receive projected footers".
    * No re-parenting: the modal renders inside this root (`position: fixed`
      takes it out of the surface row), so the surface's `:focus-within` state
      shows while focus is in the modal. A strategy flip across the breakpoint
      while open swaps the visible branch; upstream closes the picker instead.

    Further divergences:
    * `useNativePicker` is dropped (breakpoint prop, §7 item 1), so `showCalendar()`
      reduces to `enableCalendar` and the `<input type="date">` branch is
      unreachable. `numberOfMonths`, `enableCalendar` and `calendarTrigger` are
      Angular `BreakpointInput`s whose names do not signal breakpoints; per §7
      item 1's exception they are declared as plain scalars carrying the Angular
      `xs` value as the default.
    * `localeCode` is dropped — the calendar sources month/weekday names from the
      existing `date-picker.*` translation keys, not `Intl`, because `ext-intl` is
      not a declared dependency of this package. As a consequence Angular's
      `effectivePlaceholder()` fallback (`formatLocaleDateHint(localeCode)`, which
      renders "pp.kk.aaaa" in single mode when no placeholder is set) is dropped
      too: the placeholder here is exactly what you pass, and nothing when you
      pass nothing.
    * `formatDate` / `parseDate` are JS callables. Instead of accepting PHP
      callables, the formatted strings Angular derives from them are explicit
      props: `display` (Angular's `displayValue()`) and `tags` (its
      `tagsForMultipleMode()`), per CONVENTIONS.md §5. Format the value however
      your application wants and pass the result.
    * `disabledMatchers`, `minDate`, `maxDate`, `disablePast`, `disableFuture`,
      `shouldDisableMonth`, `shouldDisableYear`, `availableDays`,
      `unavailableDays`, `minYear` and `maxYear` are matcher machinery with no
      server-side analogue — they collapse into the flat `disabledDays` array of
      'Y-m-d' strings that <tedi:calendar> already takes.
    * The FIELD CONTEXT (Angular 8.0's `TEDI_FIELD_CONTEXT`). Since 8.0 the
      date field paints its own field surface — `tedi-field-surface` and its
      `--invalid` / `--valid` / `--disabled` modifiers, plus
      `tedi-date-field--small|large` — unless a wrapping form-field renders a
      box that owns the surface. Angular injects the wrapper's computed state;
      Blade cannot, so the wrapper's state is read from what the consumer WROTE
      on the nearest enclosing `tedi:form-field` (`Tedi::fieldContext()`, the
      form-field-scoped analogue of `@aware`), with form-field's `@props`
      defaults applied:
        - `size`: own `size` → the form-field's `size` → `default`.
        - `clearable`: own `clearable` → the form-field's `clearable` → `true`
          (Angular 9.0, #572: set it once on the form field).
        - `invalid`: own `invalid`, or the form-field's `invalid`. Angular also
          derives it from reactive-forms state and an error feedback text;
          here the form-field's explicit `invalid` is that stand-in.
        - `valid`: the form-field's `valid` (only a wrapper can know it).
        - `disabled`: own `inputDisabled`, or the form-field's `disabled` (the
          stand-in for an enclosing input group disabling the field).
        - Surface ownership: the form-field only renders a box when it has an
          `icon`, because a date field owns its clear button
          (`ownsClearButton`) and so never asks the wrapper for one. With an
          `icon` on the form-field, the box paints the surface and this root
          carries none of the `tedi-field-surface*` classes.
      Standalone (no form-field), every fallback is the default and the field
      paints itself.
    * `selectionLevel` is forwarded to <tedi:calendar>, which seeds its opening
      grid from it when `view` is omitted (Angular's constructor effect:
      `this.view.set(this.selectionLevel())`). This component has no `view` prop
      of its own — Angular's `tedi-date-field` doesn't either — so a stray
      `view="…"` attribute on the field lands on the root <div> and never reaches
      the calendar.
    * `readOnly` (Angular 9.0, #726) only locks the text input. While the
      calendar is available (`enableCalendar`) the value stays editable, so the
      clear button and the tag close buttons still show (`valueEditable()`).
    * `name` (8.1, #686) is forwarded to the <input>, omitted when empty. An
      `aria-describedby` written on this component is forwarded to the <input>
      too (#675's `controlDescribedBy()`), not left on the wrapper <div>. The
      form-field's own feedback-text id is not added automatically — Blade
      cannot query it; pass it in `aria-describedby`.
    * The 8.1 "clear uncommitted typed text on reset" fix (#677, the
      `typedText` linked signal) is runtime-only: the text shown here is
      always the explicit `display` prop, so there is no uncommitted text.
    * `canClear` reads an array `value` as "has a value" only when it is
      non-empty. Angular's `!!value` is true for `[]` as well, so a
      `mode="multiple"` field holding an explicitly-empty array shows a clear
      button in Angular and none here. That is the PHP-natural reading of a
      degenerate case, but it is a real difference.
    * `currentMonth`, `open` and `draft` are explicit props standing in for Angular's
      runtime signals (CONVENTIONS.md §5); `closeOnSelect` and the `openChange`
      output are runtime-only and not ported (§7 item 2).
--}}
@props([
    /** Id for the <input>, also used for the label's `for`. Auto-generated (Tedi::id()) when omitted. */
    'inputId' => null,
    /**
     * Selected value, forwarded to the calendar; shape follows `mode`:
     *   single   → 'Y-m-d'
     *   multiple → ['Y-m-d', …]
     *   range    → ['from' => 'Y-m-d', 'to' => 'Y-m-d'|null]
     */
    'value' => null,
    /** Formatted text shown in the input. Stands in for Angular's displayValue(). */
    'display' => '',
    /** Multiple mode tags: [['id' => …, 'label' => …], …]. Stands in for tagsForMultipleMode(). */
    'tags' => [],
    /** single|multiple|range */
    'mode' => 'single',
    /** Multiple mode: true wraps tags across rows, false keeps one row with a +N counter. */
    'multiRow' => true,
    /** false|'start'|'end' — which end a multiple-mode tag label truncates from. */
    'tagEllipsis' => false,
    /** Whether multiple-mode tags show a remove button. */
    'isTagRemovable' => true,
    /** How many tags fit on one row; null = unmeasured (renders them all). */
    'visibleTagCount' => null,
    /** Placeholder shown when the input is empty. */
    'placeholder' => '',
    /** Disables the field entirely — input, icon button and calendar. */
    'inputDisabled' => false,
    /** Blocks typing but leaves the calendar interactive. */
    'readOnly' => false,
    /** Sets the native `required` attribute on the input. */
    'required' => false,
    /** days|months|years — lowest level the user can commit to. */
    'selectionLevel' => 'days',
    /** dropdown|grid — how the calendar header exposes month/year picking. */
    'monthYearSelectType' => 'dropdown',
    /** Render the leading/trailing days of the adjacent months. */
    'showOutsideDays' => true,
    /** Render the ISO week-number column. */
    'showWeekNumbers' => false,
    /** How many month grids render side by side (Angular's `xs` base value). */
    'numberOfMonths' => 1,
    /** Enables the calendar picker UI; false hides the icon button (Angular's `xs` base value). */
    'enableCalendar' => true,
    /** button|input — what opens the calendar (Angular's `xs` base value). */
    'calendarTrigger' => 'button',
    /** Month the calendar opens on. Any strtotime()-able value; null → the value's month, else the current month. */
    'currentMonth' => null,
    /** Whether the calendar panel is shown. Stands in for Angular's overlay open state. */
    'open' => false,
    /** First column of the calendar's day grid: 0 = Sunday … 6 = Saturday. */
    'firstDayOfWeek' => 1,
    /** Days that cannot be selected, as 'Y-m-d' strings. */
    'disabledDays' => [],
    /** default|small — overrides the wrapping form-field's size. Null inherits it (else `default`). */
    'size' => null,
    /** Whether the clear button shows once the field has a value. Null inherits the form-field's `clearable` (else true). */
    'clearable' => null,
    /** Forces the error state on (surface + `aria-invalid` on the input). Combines with the form-field's `invalid`. */
    'invalid' => false,
    /** `name` attribute of the <input>, for native form submission. Omitted when empty. */
    'name' => null,
    /** Open the calendar in a modal: true always, false never, sm|md|lg|xl → modal below that breakpoint (resolved in the browser). */
    'modal' => false,
    /** Upstream: make the modal fullscreen (true|false|breakpoint). INERT — the standalone modal has no fullscreen rule; see the header comment. */
    'fullscreen' => false,
    /** Stretch the modal's calendar across the modal body (Calendar `fullWidth`). Modal mode only. */
    'calendarFullWidth' => false,
    /** The modal's uncommitted selection, same shape as `value`. Null → the modal's calendar shows `value`. */
    'draft' => null,
])

@php
    // Unconditional: the id is referenced on every render path (CONVENTIONS.md §5,
    // precedent select.blade.php).
    $inputId = $inputId ?? \Tedi\Livewire\Tedi::id('tedi-date-field');

    // showCalendar(): useNativePicker is dropped, so it reduces to enableCalendar.
    $showCalendar = (bool) $enableCalendar;
    // inputIsTrigger()
    $inputIsTrigger = $showCalendar && $calendarTrigger === 'input';

    $hasValue = is_array($value) ? count($value) > 0 : ($value !== null && $value !== '');

    // TEDI_FIELD_CONTEXT (Tedi::fieldContext): the nearest form-field's state.
    // The control half registers this field with it — no `reset`, because a
    // date field owns its clear button (`ownsClearButton`), so the form-field
    // renders neither a clear button nor, without an icon, a box for it.
    // The registered value is the TEXT the field shows (the form-field only
    // uses it for truthiness and a character count), never the raw value,
    // which is an array in multiple / range mode.
    $registeredValue = (string) $display !== ''
        ? (string) $display
        : implode(', ', array_filter(array_map(fn ($tag) => is_array($tag) ? (string) ($tag['label'] ?? '') : '', is_array($tags) ? $tags : [])));
    $fieldContext = \Tedi\Livewire\Tedi::fieldContext($__env, [
        'value' => $hasValue ? ($registeredValue !== '' ? $registeredValue : null) : null,
        'disabled' => (bool) $inputDisabled,
        'readOnly' => (bool) $readOnly,
        'invalid' => (bool) $invalid,
    ]);

    // fieldDisabled() / invalid() / valid() / resolvedSize() / paintsSurface()
    $fieldDisabled = (bool) $inputDisabled || (bool) ($fieldContext['disabled'] ?? false);
    $isInvalid = (bool) $invalid || (bool) ($fieldContext['invalid'] ?? false);
    $isValid = (bool) ($fieldContext['valid'] ?? false);
    $resolvedSize = $size ?? ($fieldContext['size'] ?? 'default');
    $paintsSurface = ! ($fieldContext['ownsSurface'] ?? false);
    // clearableResolved(): own `clearable` wins, then the form-field's.
    $clearableResolved = $clearable !== null ? (bool) $clearable : ($fieldContext['clearable'] ?? true);
    // valueEditable(): readOnly only locks typing while the calendar is available.
    $valueEditable = ! $fieldDisabled && (! $readOnly || $showCalendar);
    // canClear()
    $canClear = $clearableResolved && $hasValue && $valueEditable;

    // describedBy: the form-field's ids (its character counter), plus any
    // aria-describedby written on the field, go to the <input>.
    // Angular's `childContext`, handed to the date input's text-field: the
    // date field paints the surface, never the input inside it.
    $childContext = \Tedi\Livewire\View\FieldContext::child(
        size: $resolvedSize,
        invalid: $isInvalid,
        valid: $isValid,
        disabled: $fieldDisabled,
        clearable: $clearableResolved,
    );

    // modalEnabled(): true | false | a breakpoint name resolved in the browser.
    $modalMode = match (true) {
        $modal === true, $modal === 'true' => true,
        is_string($modal) && in_array($modal, ['sm', 'md', 'lg', 'xl'], true) => $modal,
        default => false,
    };
    $modalBreakpoint = is_string($modalMode) ? $modalMode : null;
    // The modal's calendar shows the draft (upstream's `draft` signal), seeded from the value.
    $modalValue = $draft ?? $value;
    $modalTitleId = $modalMode !== false ? \Tedi\Livewire\Tedi::id('tedi-date-field-modal-title') : null;
    // openModal()'s maxWidth: one month grid per month plus the modal's two
    // outer borders, unless the calendar stretches (then the `sm` preset).
    $modalWidth = $calendarFullWidth
        ? 'sm'
        : 'calc('.max(1, (int) $numberOfMonths).' * var(--tedi-containers-03) + 2 * var(--tedi-borders-01))';

    $describedBy = trim(implode(' ', array_merge(
        $fieldContext['describedBy'] ?? [],
        [(string) $attributes->get('aria-describedby', '')],
    )));
@endphp

<div {{ $attributes->except(['aria-describedby'])->merge($modalBreakpoint !== null ? [
    'x-data' => "tediDateField({ modal: '".$modalBreakpoint."' })",
] : [])->class([
    'tedi-date-field',
    'tedi-field-surface' => $paintsSurface,
    'tedi-field-surface--invalid' => $paintsSurface && $isInvalid,
    'tedi-field-surface--valid' => $paintsSurface && $isValid,
    'tedi-field-surface--disabled' => $paintsSurface && $fieldDisabled,
    'tedi-date-field--small' => $resolvedSize === 'small',
    'tedi-date-field--large' => $resolvedSize === 'large',
])->style([
    // The open panel anchors to the field (see the overlay below).
    'position: relative' => $open && $showCalendar && $modalMode !== true,
]) }}>
    <tedi:date-input
        :input-id="$inputId"
        :name="$name"
        :aria-describedby="$describedBy"
        :value="$display"
        :tags="$tags"
        :mode="$mode"
        :multi-row="(bool) $multiRow"
        :ellipsis="$tagEllipsis"
        :removable="(bool) $isTagRemovable && $valueEditable"
        :visible-tag-count="$visibleTagCount"
        :placeholder="$placeholder"
        :disabled="$fieldDisabled"
        :invalid="$isInvalid"
        :size="$resolvedSize"
        :read-only="$readOnly || $inputIsTrigger"
        :required="(bool) $required"
        :icon-active="(bool) $open"
        :icon-disabled="! $showCalendar"
        :clearable="$canClear"
        :field-context="$childContext"
    />

    @if ($open && $showCalendar && $modalMode !== true)
        {{-- Angular portals this panel out of the field with CDK, anchored below
             it: start-aligned for the input trigger, end-aligned for the button
             (overlayPositions()), FIELD_OVERLAY_GAP = 4px.
             Rendered inline, it would be a flex item of the surface row, beside
             the input; so it is taken out of the flow and anchored the same way. --}}
        <div class="tedi-date-field__overlay" @if ($modalBreakpoint !== null) x-show="! modalActive" @endif style="position: absolute; top: calc(100% + 4px); {{ $calendarTrigger === 'input' ? 'left' : 'right' }}: 0; z-index: var(--z-index-dropdown)" role="dialog" aria-label="{{ __('tedi::tedi.date-field.calendar-dialog') }}">
            <tedi:calendar
                :bordered="false"
                :value="$value"
                :current-month="$currentMonth"
                :mode="$mode"
                :selection-level="$selectionLevel"
                :show-outside-days="(bool) $showOutsideDays"
                :show-week-numbers="(bool) $showWeekNumbers"
                :number-of-months="$numberOfMonths"
                :month-year-select-type="$monthYearSelectType"
                :required="(bool) $required"
                :input-disabled="$fieldDisabled"
                :disabled-days="$disabledDays"
                :first-day-of-week="$firstDayOfWeek"
            >{{-- one line: .tedi-calendar__footer relies on :empty --}}<x-slot:footer>{{ $footer ?? '' }}</x-slot:footer>
            </tedi:calendar>
        </div>
    @endif

    @if ($open && $showCalendar && $modalMode !== false)
        {{-- date-field-modal.component.html. `modal=true`: open now. A
             breakpoint name: rendered closed, and opened by tediDateField's
             `modalActive` once Alpine runs (popover fallback without JS). --}}
        <tedi:modal
            :class="\Illuminate\Support\Arr::toCssClasses([
                'tedi-date-field-modal',
                'tedi-date-field-modal--full-width' => (bool) $calendarFullWidth,
            ])"
            size="small"
            :width="$modalWidth"
            position="center"
            :open="$modalMode === true"
            :aria-labelledby="$modalTitleId"
            :x-effect="$modalBreakpoint !== null ? 'modalActive ? show() : close()' : null"
        >
            <tedi:modal-header>
                <h2 id="{{ $modalTitleId }}">{{ __('tedi::tedi.date-field.modal-title') }}</h2>
            </tedi:modal-header>
            <tedi:modal-content class="tedi-date-field-modal__content">
                <tedi:calendar
                    :bordered="false"
                    :value="$modalValue"
                    :current-month="$currentMonth"
                    :mode="$mode"
                    :selection-level="$selectionLevel"
                    :show-outside-days="(bool) $showOutsideDays"
                    :show-week-numbers="(bool) $showWeekNumbers"
                    :number-of-months="$numberOfMonths"
                    :full-width="(bool) $calendarFullWidth"
                    :month-year-select-type="$monthYearSelectType"
                    :required="(bool) $required"
                    :input-disabled="$fieldDisabled"
                    :disabled-days="$disabledDays"
                    :first-day-of-week="$firstDayOfWeek"
                />
            </tedi:modal-content>
            <tedi:modal-footer>
                <tedi:button type="button" variant="secondary" x-on:click="requestClose('programmatic')">
                    {{ __('tedi::tedi.date-field.cancel') }}
                </tedi:button>
                {{-- Backticks, not quotes: the event name is not a class, and
                     IntegrityTest harvests quoted 'tedi-…' literals as classes. --}}
                <tedi:button type="button" x-on:click="$dispatch(`tedi-date-field-confirm`); close()">
                    {{ __('tedi::tedi.date-field.confirm') }}
                </tedi:button>
            </tedi:modal-footer>
        </tedi:modal>
    @endif
</div>
