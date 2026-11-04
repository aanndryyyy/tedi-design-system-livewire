{{--
    TEDI Search.
    Port of angular/src/tedi/components/form/search/{search.component.ts,search.component.html}
    (Angular 9.0.1: the 8.1 autocomplete #613 with its 9.0.1 design-review
    fixes #727 / #777, and the 9.0 clear-button interaction option #569).

    Angular's selector is the element `tedi-search`, but the vendored SCSS has
    no `tedi-search` ELEMENT rule — every rule keys on the `.tedi-search` class
    and its descendants — so per the batch ruling the root is a `<div>` carrying
    the host class, the way radio-card-group.blade.php does.

    Composition mirrors the Angular template: tedi:form-field wrapping a
    tedi:text-field, plus an optional trailing tedi:button. Because the
    component wraps a single native control, `{{ $attributes }}` goes on the
    `<input>` so `wire:model` binds directly (CONVENTIONS.md §6, the
    select.blade.php precedent); the root `<div>` therefore carries its
    computed `role` / `aria-label` / `style` raw, which is safe precisely
    because no attribute bag is merged onto it.

    TWO MODES, exactly as upstream (`autocomplete = suggestions !== undefined`):

    * PLAIN (no `suggestions`). A `role="searchbox"` input. No JavaScript: the
      markup carries no `x-data`, so it needs neither Alpine nor the bundle.
    * AUTOCOMPLETE (`:suggestions="[…]"`, an empty array included). The input
      becomes a `role="combobox"` with `aria-autocomplete="list"` and
      `aria-expanded`, and a suggestion panel is rendered after it. Behaviour
      is `tediSearch` (resources/js/src/search.js), which composes
      `tediOverlay` (CONVENTIONS.md §11) and adds upstream's
      `aria-activedescendant` combobox layer: focus never leaves the input,
      ArrowUp/ArrowDown move the highlight (wrapping, skipping disabled rows),
      Enter accepts it, Escape closes, Tab reaches the footer. Search never
      filters — upstream's contract — so pass the already-filtered list on
      every render, typically from a Livewire property bound with
      `wire:model.live`. The JS reads every row fact off the DOM, so a
      Livewire morph of the panel is picked up as it lands.

    Divergences:

    * EXTRA WRAPPER: `tedi-search__field` sits on a wrapper `<div>` around
      tedi:form-field, not on the form-field itself as in Angular. Blade's
      form-field.blade.php emits its `label` slot as a SIBLING before the
      `.tedi-form-field` div, so without the wrapper the label would become its
      own flex item under `.tedi-search { display: flex }`. Only one rule uses
      the class (`&__field { flex: 1 1 auto; min-width: 0 }`) and it is
      element-agnostic; every other search rule is a descendant chain, so the
      extra level changes nothing.
    * OUTPUTS. `clear` is not re-emitted (CONVENTIONS.md §7 item 2): pass
      `clearAttributes` for the form-field's clear button. In PLAIN mode
      `searchEvent` is not re-emitted either: bind `wire:keydown.enter` on the
      input (it receives `$attributes`) and `buttonAttributes` on the button.
      In AUTOCOMPLETE mode Enter is overloaded — it accepts the highlighted row
      OR submits, and upstream suppresses `searchEvent` in the first case —
      which a consumer's `wire:keydown.enter` cannot tell apart. So there
      `searchEvent` and `suggestionSelect` are dispatched as DOM events from
      the input, the `tedi:hash-match` precedent:
      `tedi-search:search` (detail `{ value }`, on Enter or the button) and
      `tedi-search:select` (detail `{ index, label, id, item }`, `item` being
      the suggestion JSON-decoded — Angular's `suggestionSelect` payload, as
      far as JSON carries it; prefer it over `index`, which is only valid
      against the list as it was rendered when the row was picked, because
      the `input` event below re-queries a `wire:model.live` list). Listen with
      `x-on:tedi-search:select="…"` on the component. Selecting also writes the
      label into the input and fires `input` + `change`, which is upstream's
      `onChange(label)`, so `wire:model` sees it.
    * CUSTOM ROWS. Angular's per-item `ng-template tediSearchSuggestion`
      has no Blade equivalent (no scoped slots). Write the rows yourself in
      the `rows` slot instead — one `tedi:search-suggestion` per item, with
      `:custom="true"`, its `label` (what the field is filled with) and,
      to keep upstream's id scheme, `:id="$inputId.'-opt-'.$i"` — the
      `vertical-stepper-item` `sub-items` precedent. Pass `:item` too if you
      want it back in `tedi-search:select`. `suggestions` must still
      be passed: it is what marks the combobox and decides between the
      results, loading and no-results rows. Angular's `tediSearchFooter`
      template is the `footer` slot.
    * `panelOpen` is the initial state only. Angular's two-way `model()` has
      no server-side return path; the panel's open state lives in Alpine.
    * `resultsCountText` is a function in Angular. Here it is a Closure
      (`fn ($count) => …`) or a string with a `:count` placeholder.
    * The panel stays in the document, inside the root, instead of being
      portalled into a CDK overlay container (CONVENTIONS.md §11), so a search
      inside a `transform`ed ancestor positions its panel relative to that
      ancestor. The CDK container's stacking context is replaced by an inline
      `z-index: var(--z-index-dropdown)` on the panel, the token the vendored
      dropdown panel uses.
    * `tedi-search__announcement` is DROPPED from the live region: the
      vendored SCSS has no rule for it (CONVENTIONS.md §4). `sr-only`,
      `role="status"`, `aria-live` and `aria-atomic` are kept. Restore the
      class here if TEDI ever ships a rule.
    * `invalid` / `valid` on the form-field are derived from
      `feedbackText['type']` ('error' → invalid, 'valid' → valid). Angular
      derives them at runtime from the projected `tedi-feedback-text`; there is
      no runtime here, so the derivation happens up front (CONVENTIONS.md §5).
    * `disabled` is the local prop only — Angular also folds in the
      reactive-forms disabled state, which has no server-side equivalent.
    * When `button.text` is set, the button's icon is rendered by
      tedi:button's own `icon-start` and therefore uses that component's size
      mapping (18 small / 24 default and large) rather than Angular's
      `buttonIconSize()` (18 small and default / 24 large) — they differ only at
      `size="default"`. `icon-start` is used because it is the only way to get
      tedi:button's exact class list (`--pr`, no `--pl`) for an
      icon-then-text button, and class parity is the tested invariant
      (CONVENTIONS.md §4). The icon-only branch passes `:icon-only="true"` and
      renders the icon in the slot, so there both the classes and
      `buttonIconSize()` are exact.
    * `value` is passed down to tedi:form-field so its clear button knows
      whether the field is non-empty; Angular reads that off the projected
      control at runtime instead.
    * `aria-describedby` is set on the input here. Angular 9 moved it into the
      form-field's control context (`describedByIds`); the result is the same
      attribute pointing at the same feedback id.
--}}
@props([
    /** Id for the <input>, also the label's `for`. Auto-generated (Tedi::id()) when omitted. */
    'inputId' => null,
    /** Visible label text. When omitted, provide `ariaLabel`. */
    'label' => null,
    /** Value of the search input. Emitted only when non-empty (see text-field.blade.php). */
    'value' => '',
    /** Placeholder text for the search input. */
    'placeholder' => '',
    /** default|small|large */
    'size' => 'default',
    /** Shows a clear button once the field has a value. */
    'clearable' => true,
    /** Show the clear button only while the filled field is hovered or focused. Requires `clearable`. */
    'showClearOnInteraction' => false,
    /** Icon shown inside the input; ignored when `button` is set. Name, or a form-field icon array. */
    'searchIcon' => 'search',
    'disabled' => false,
    /** Trailing search button: ['text' => ..., 'icon' => 'search', 'variant' => 'primary', 'ariaLabel' => ...]. */
    'button' => null,
    /** Feedback text below the field: ['text' => ..., 'type' => 'hint', 'position' => 'left']. */
    'feedbackText' => null,
    /** Accessible name for the search region. Falls back to label, placeholder, then the translated "search". */
    'ariaLabel' => null,
    /**
     * Already-filtered suggestions: strings, arrays or objects. Pass `[]` to
     * keep the combobox while nothing matches; leave it null for a plain field.
     */
    'suggestions' => null,
    /** Key holding the display label when suggestions are arrays/objects. */
    'bindLabel' => 'label',
    /** Key holding a secondary line under the label. Unset by default: single-line rows. */
    'bindDescription' => null,
    /** Key marking a suggestion as unselectable. */
    'bindDisabled' => 'disabled',
    /** Characters required before the panel opens. Below it nothing shows, not even the no-results row. */
    'minQueryLength' => 0,
    /** Shows a loading row instead of results. */
    'loading' => false,
    /** Closes the panel when the page (or a scrollable ancestor) scrolls. Scrolling the list itself does not. */
    'hideOnScroll' => false,
    /** Whether the suggestion panel starts open. */
    'panelOpen' => false,
    /** Overrides the translated "no results" row. */
    'noResultsText' => null,
    /** Overrides the translated loading row. */
    'loadingText' => null,
    /** Overrides the screen-reader result count: a Closure taking the count, or a string with `:count`. */
    'resultsCountText' => null,
    /** Extra attributes forwarded to the trailing search button (e.g. wire:click). */
    'buttonAttributes' => [],
    /** Extra attributes forwarded to the form-field's clear button (e.g. wire:click). */
    'clearAttributes' => [],
])

@php
    // Unconditional: the <input> id and the label's `for` both read it on every
    // render path, so it must never depend on a branch being taken.
    $inputId = $inputId ?? \Tedi\Livewire\Tedi::id('tedi-search');

    // An empty array is a legitimate `button` config (icon-only, all defaults),
    // and PHP treats it as falsy — so presence is tested against null, not truthiness.
    $hasButton = $button !== null;

    // Angular: fieldIcon() — the inline icon is suppressed once a button is shown.
    $fieldIcon = $hasButton ? null : $searchIcon;

    // Angular: fieldHeight().
    $fieldHeight = match ($size) {
        'small' => 'var(--form-field-height-sm)',
        'large' => 'var(--form-field-height-lg)',
        default => 'var(--form-field-height)',
    };

    // Angular: buttonSize() / buttonIconSize().
    $buttonSize = $size === 'small' ? 'small' : 'default';
    $buttonIconSize = $size === 'large' ? 24 : 18;

    $buttonText = $button['text'] ?? null;
    $buttonIcon = $button['icon'] ?? 'search';
    $buttonVariant = $button['variant'] ?? 'primary';

    // Angular: buttonAriaLabel() — a labelled button needs no aria-label.
    $buttonAriaLabel = $buttonText ? null : ($button['ariaLabel'] ?? __('tedi::tedi.search'));

    // Angular: searchAriaLabel() / inputAriaLabel(). A visible label already
    // names the input via for/id, so aria-label would silently override it.
    $searchAriaLabel = $ariaLabel ?: ($label ?: ($placeholder ?: __('tedi::tedi.search')));
    $inputAriaLabel = $label ? null : $searchAriaLabel;

    // Angular: feedbackId(). It is always computed upstream, but only reaches
    // aria-describedby when a feedback text is projected.
    $feedbackId = $feedbackText ? $inputId.'-feedback' : null;

    $feedbackType = $feedbackText['type'] ?? 'hint';

    // -- autocomplete (Angular 8.1) -------------------------------------

    // Angular: autocomplete() — derived from capability, never from content.
    $autocomplete = $suggestions !== null;

    // Angular: resolvedSuggestions().
    $resolvedSuggestions = [];

    foreach ($autocomplete ? $suggestions : [] as $item) {
        $isRecord = is_array($item) || is_object($item);

        $resolvedSuggestions[] = [
            'item' => $item,
            'label' => $isRecord ? (string) (data_get($item, $bindLabel) ?? '') : (string) $item,
            'description' => $isRecord && $bindDescription ? (string) (data_get($item, $bindDescription) ?? '') : '',
            'disabled' => $isRecord && (bool) data_get($item, $bindDisabled),
        ];
    }

    $suggestionCount = count($resolvedSuggestions);

    // Angular: query(), hasSuggestions(), listboxId(), meetsMinQueryLength(), panelVisible().
    $query = trim((string) $value);
    $hasSuggestions = ! $loading && $suggestionCount > 0;
    $listboxId = $inputId.'-listbox';
    $meetsMinQueryLength = mb_strlen($query) >= (int) $minQueryLength;
    $panelVisible = $autocomplete && $panelOpen && $meetsMinQueryLength
        && ($loading || $hasSuggestions || $query !== '');

    // Angular: resolvedNoResultsText(), resolvedLoadingText(), announcement().
    $resolvedNoResultsText = $noResultsText ?? __('tedi::tedi.search.no-results');
    $resolvedLoadingText = $loadingText ?? __('tedi::tedi.search.loading');

    if ($loading) {
        $panelAnnouncement = $resolvedLoadingText;
    } elseif ($suggestionCount === 0) {
        $panelAnnouncement = $resolvedNoResultsText;
    } elseif ($resultsCountText instanceof \Closure) {
        $panelAnnouncement = (string) $resultsCountText($suggestionCount);
    } elseif (is_string($resultsCountText) && $resultsCountText !== '') {
        $panelAnnouncement = str_replace(':count', (string) $suggestionCount, $resultsCountText);
    } else {
        $panelAnnouncement = trans_choice('tedi::tedi.search.results-count', $suggestionCount, ['count' => $suggestionCount]);
    }

    $announcement = $panelVisible ? $panelAnnouncement : '';

    $panelState = $loading ? 'loading' : ($hasSuggestions ? 'results' : 'empty');

    $hasRowsSlot = isset($rows) && $rows->isNotEmpty();
    $hasFooter = isset($footer) && $footer->isNotEmpty();

    // Angular: inputRole(), ariaAutocomplete(), ariaExpanded(), ariaControls().
    // ARIA states are strings: the attribute bag drops `false` and renders
    // `true` as `aria-expanded="aria-expanded"`.
    $inputAttributes = [
        'role' => $autocomplete ? 'combobox' : 'searchbox',
        'aria-autocomplete' => $autocomplete ? 'list' : null,
        'aria-expanded' => $autocomplete ? ($panelVisible ? 'true' : 'false') : null,
        'aria-controls' => $panelVisible && $hasSuggestions ? $listboxId : null,
        'aria-label' => $inputAriaLabel,
        'aria-describedby' => $feedbackId,
    ];

    if ($autocomplete) {
        $inputAttributes += [
            'x-ref' => 'trigger',
            'x-bind:aria-expanded' => "panelVisible ? 'true' : 'false'",
            'x-bind:aria-controls' => 'ariaControls',
            'x-bind:aria-activedescendant' => 'activeOptionId',
        ];
    }

    $searchConfig = [
        'minQueryLength' => (int) $minQueryLength,
        'hideOnScroll' => (bool) $hideOnScroll,
        'open' => (bool) $panelOpen,
        'listboxId' => $listboxId,
    ];
@endphp

<div
    role="search"
    aria-label="{{ $searchAriaLabel }}"
    style="--tedi-search-field-height: {{ $fieldHeight }}"
    @class([
        'tedi-search',
        'tedi-search--button-icon-only' => $hasButton && ! $buttonText,
        'tedi-search--has-button' => $hasButton,
    ])
    @if ($autocomplete)
        x-data="tediSearch({{ \Illuminate\Support\Js::from($searchConfig) }})"
        x-on:input="onInput($event)"
        x-on:focusin="onFocusIn($event)"
        x-on:focusout="onFocusOut($event)"
        x-on:keydown="onKeydown($event)"
    @endif
>
    <div class="tedi-search__field">
        <tedi:form-field
            :size="$size"
            :icon="$fieldIcon"
            :clearable="$clearable && ! $disabled"
            :show-clear-on-interaction="$showClearOnInteraction"
            :value="$value"
            :disabled="$disabled"
            :invalid="$feedbackType === 'error'"
            :valid="$feedbackType === 'valid'"
            :clear-attributes="$clearAttributes"
        >
            @if ($label)
                <x-slot:label>
                    <tedi:form.label :for="$inputId" :size="$size === 'small' ? 'small' : 'default'">
                        {{ $label }}
                    </tedi:form.label>
                </x-slot:label>
            @endif

            <tedi:text-field
                type="text"
                :id="$inputId"
                :value="$value"
                :placeholder="$placeholder"
                :disabled="$disabled"
                {{ $attributes->merge(array_filter($inputAttributes, fn ($v) => $v !== null)) }}
            />

            @if ($feedbackText)
                <x-slot:feedback>
                    <tedi:feedback-text
                        :id="$feedbackId"
                        :text="$feedbackText['text']"
                        :type="$feedbackType"
                        :position="$feedbackText['position'] ?? 'left'"
                    />
                </x-slot:feedback>
            @endif
        </tedi:form-field>
    </div>

    @if ($hasButton)
        <tedi:button
            class="tedi-search__button"
            type="button"
            :variant="$buttonVariant"
            :size="$buttonSize"
            :disabled="$disabled"
            :aria-label="$buttonAriaLabel"
            :icon-start="$buttonText ? $buttonIcon : null"
            :icon-only="! $buttonText"
            {{ $attributes->only([])->merge($buttonAttributes)->merge($autocomplete ? ['x-on:click' => 'emitSearch()'] : []) }}
        >
            @if ($buttonText)
                {{ $buttonText }}
            @else
                <tedi:icon :name="$buttonIcon" :size="$buttonIconSize" color="inherit" />
            @endif
        </tedi:button>
    @endif

    <div
        class="sr-only"
        role="status"
        aria-live="polite"
        aria-atomic="true"
        @if ($autocomplete) x-text="announcement" @endif
    >{{ $announcement }}</div>

    @if ($autocomplete)
        <div
            class="tedi-search__panel"
            style="z-index: var(--z-index-dropdown)"
            x-ref="panel"
            x-show="panelVisible"
            x-cloak
            x-on:mousedown="onPanelMousedown($event)"
            x-on:click="onPanelClick($event)"
        >
            {{-- Panel state for tediSearch, on a child: Livewire's morph does
                 not patch the attributes of an element x-show is showing. --}}
            <span hidden data-tedi-search-state="{{ $panelState }}" data-tedi-search-announcement="{{ $panelAnnouncement }}"></span>

            @if ($loading)
                <div class="tedi-search__panel-status">
                    <tedi:spinner :size="16" />
                    <tedi:text color="tertiary">{{ $resolvedLoadingText }}</tedi:text>
                </div>
            @elseif ($hasSuggestions)
                <ul class="tedi-search__panel-list" role="listbox" id="{{ $listboxId }}">
                    @if ($hasRowsSlot)
                        {{ $rows }}
                    @else
                        @foreach ($resolvedSuggestions as $index => $suggestion)
                            <tedi:search-suggestion
                                :id="$inputId.'-opt-'.$index"
                                :item="$suggestion['item']"
                                :label="$suggestion['label']"
                                :description="$suggestion['description']"
                                :disabled="$suggestion['disabled']"
                                :query="$query"
                            />
                        @endforeach
                    @endif
                </ul>
            @else
                <div class="tedi-search__panel-status">
                    <tedi:text color="tertiary">{{ $resolvedNoResultsText }}</tedi:text>
                </div>
            @endif

            @if ($hasFooter)
                <div class="tedi-search__footer" x-ref="footer">
                    {{ $footer }}
                </div>
            @endif
        </div>
    @endif
</div>
