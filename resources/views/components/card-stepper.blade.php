{{--
    TEDI Card Stepper — DOCUMENTED SUBSET.
    Port of react/src/tedi/components/navigation/card-stepper/{card-stepper.tsx,card-stepper-step.tsx}
    (CONVENTIONS.md §13).

    A compact, card-shaped stepper for narrow layouts: the active step's
    number, title and description, an `N / M` counter, a segmented progress
    bar, and a button that opens the full step list in a bottom sheet.

    STEPS ARE DATA. Upstream accepts either `CardStepper.Step` children, which
    it reads with `Children.toArray`, or a `steps` array. Blade cannot read its
    own slot as structured data (CONVENTIONS.md §13.5), so only the `steps`
    array is ported. Each entry is an array with `title` and, optionally,
    `description`, `state` (default|completed|error|disabled), `disabled`,
    `id`, `default_expanded`, and `sub_steps` — each sub-step `title`, `state`
    (default|completed|error|disabled|informative), `current`, `disabled`,
    `href`. Upstream's per-step `bottomSlot` is the `bottom` slot here, and is
    shown for whichever step is active (it also drops the progress bar's
    flush-bottom styling, as a step with a bottom slot does upstream).

    THE ACTIVE STEP IS SERVER STATE. Upstream keeps an uncontrolled index and
    moves it itself; here `active-step` is authoritative and the controls
    report a request instead of acting on it: the prev / next arrows and every
    navigable step in the list dispatch a bubbling `tedi:step-change` DOM event
    with `{ index }` in its detail — the same convention as `tedi:hash-match`.
    Listen with `x-on:tedi:step-change` or Livewire, and render back with the
    new `active-step`. That is upstream's controlled mode (`activeStep` +
    `onStepChange`); the uncontrolled one has no server-side equivalent. The
    step list closes itself when a step is picked, as upstream.

    `goTo`'s rules are upstream's and are applied on the server: the arrows
    skip disabled steps and disable themselves at either end, and `allow-jump`
    (true|false|'completed'|'completed-or-next') decides which list entries
    navigate. A per-step `disabled` always wins. Picking the step that is
    already active only closes the list — upstream's `goTo` returns early on it.

    THE STEP LIST. Upstream renders it in `SheetModal`, an internal, unexported
    bottom-sheet variant of its React `Modal` that it describes as temporary
    until its users "migrate to the public compound `Sheet`". This package's
    modal is Angular's, which has no bottom-sheet layout, so the list is
    rendered in `tedi:sheet` — the public compound sheet — with a default
    header carrying the title and close button. `sheet-modal.module.scss` is
    therefore not vendored. The list itself is `tedi:vertical-stepper`.
    Within it a step with sub-steps keeps its title as the expand toggle (the
    Angular item has one control there, not two), so on such a step picking
    the title expands it rather than navigating.

    ACCESSIBILITY. The `N / M` counter is hidden from assistive tech and
    paired with an `sr-only` "Step N of M" — upstream uses its own
    `visually-hidden` utility, which core does not ship. Icon-only buttons
    carry their label as `aria-label`.
--}}
@props([
    /** The steps: [['title' => …, 'description' => …, 'state' => …, 'disabled' => …, 'sub_steps' => […]], …]. */
    'steps' => [],
    /** Index (0-based) of the active step. */
    'activeStep' => 0,
    /** Show the active step's number on the left. Ignored with show-navigation. */
    'showStepNumber' => true,
    /** Show a success / danger icon next to a completed / errored title. */
    'showStatusIcon' => false,
    /** top|bottom — where the active step's description sits. */
    'descriptionPosition' => 'bottom',
    /** inline|top — where the N / M counter sits. */
    'counterPosition' => 'inline',
    /** Show the segmented progress bar. */
    'showProgress' => true,
    /** Show previous / next arrows on the edges. */
    'showNavigation' => false,
    /** Show the list button and the step-list sheet. */
    'showStepList' => true,
    /** true|false|'completed'|'completed-or-next' — which steps the list can jump to. */
    'allowJump' => true,
    /** h2–h6 — the semantic level of the title; it is always styled as h4. */
    'headingElement' => 'h4',
    /** Overrides: ['previous' => …, 'next' => …, 'openSteps' => …, 'modalHeading' => …, 'status' => '… :current … :total']. */
    'labels' => [],
    /** Accessible name for the group. */
    'ariaLabel' => null,
])

@php
    $steps = array_values($steps);
    $total = count($steps);
    $active = min(max((int) $activeStep, 0), max($total - 1, 0));
    $current = $steps[$active] ?? null;

    $labelPrevious = $labels['previous'] ?? __('tedi::tedi.stepper.previous');
    $labelNext = $labels['next'] ?? __('tedi::tedi.stepper.next');
    $labelOpenSteps = $labels['openSteps'] ?? __('tedi::tedi.stepper.open-steps');
    $labelModalHeading = $labels['modalHeading'] ?? __('tedi::tedi.stepper.steps');
    $statusText = isset($labels['status'])
        ? strtr($labels['status'], [':current' => $active + 1, ':total' => $total])
        : __('tedi::tedi.stepper.status', ['current' => $active + 1, 'total' => $total]);

    $stateOf = fn ($step) => $step['state'] ?? 'default';
    $isDisabled = fn ($step) => (bool) ($step['disabled'] ?? false);

    // adjacentEnabled()
    $adjacentEnabled = function ($from, $direction) use ($steps, $total, $isDisabled) {
        for ($i = $from; $i >= 0 && $i < $total; $i += $direction) {
            if (! $isDisabled($steps[$i])) {
                return $i;
            }
        }

        return -1;
    };
    $prevIndex = $adjacentEnabled($active - 1, -1);
    $nextIndex = $adjacentEnabled($active + 1, 1);

    // isStepNavigable()
    $isNavigable = function ($index) use ($steps, $active, $allowJump, $stateOf, $isDisabled) {
        $step = $steps[$index] ?? null;
        if (! $step || $isDisabled($step)) {
            return false;
        }
        if ($allowJump === true) {
            return true;
        }
        if ($allowJump === false) {
            return false;
        }
        if ($allowJump === 'completed') {
            return $stateOf($step) === 'completed';
        }

        return $stateOf($step) === 'completed' || $index === $active + 1;
    };

    $hasBottom = isset($bottom) && trim((string) $bottom) !== '';
    $showIndicator = $showStepNumber && ! $showNavigation;
    $hasLead = $showNavigation || $showIndicator;
    $progressFlush = $showProgress && ! $hasBottom;
    $description = $current['description'] ?? null;
    $hasDescription = $description !== null && $description !== '';
    $showTopDescription = $descriptionPosition === 'top' && $hasDescription;
    $showTopRow = $counterPosition === 'top' || $showTopDescription;
    $reserveBottomDescription = $descriptionPosition === 'bottom'
        && count(array_filter($steps, fn ($step) => filled($step['description'] ?? null))) > 0;
    $currentState = $current ? $stateOf($current) : 'default';
    $headingId = \Tedi\Livewire\Tedi::id('tedi-card-stepper-heading');

    $change = fn ($index) => "\$dispatch('tedi:step-change', { index: ".(int) $index.' })';
@endphp

@if ($current)
<div
    role="group"
    {{ $attributes->class([
        'tedi-card-stepper',
        'tedi-card-stepper--progress-flush' => $progressFlush,
        'tedi-card-stepper--reserve-description' => $reserveBottomDescription,
    ])->merge(array_filter(['aria-label' => $ariaLabel])) }}
    @if ($showStepList) x-data="tediSheet({ closeOnBackdropClick: true, closeOnEscape: true })" @else x-data @endif
>
    <div @class([
        'tedi-card-stepper__main',
        'tedi-card-stepper__main--no-lead' => ! $hasLead,
    ])>
        @if ($showNavigation)
            <tedi:button
                variant="neutral"
                icon-start="arrow_back"
                icon-only
                :aria-label="$labelPrevious"
                :title="$labelPrevious"
                class="tedi-card-stepper__lead tedi-card-stepper__nav-prev"
                :disabled="$prevIndex === -1"
                x-on:click="{{ $change($prevIndex) }}"
            />
        @endif

        @if ($showIndicator)
            <span class="tedi-card-stepper__indicator tedi-card-stepper__lead" aria-hidden="true">{{ $active + 1 }}</span>
        @endif

        <div class="tedi-card-stepper__body">
            @if ($showTopRow)
                <div class="tedi-card-stepper__top">
                    @if ($counterPosition === 'top')
                        <span class="tedi-card-stepper__counter"><span aria-hidden="true">{{ $active + 1 }} / {{ $total }}</span><span class="sr-only">{{ $statusText }}</span></span>
                    @endif
                    @if ($showTopDescription)
                        <tedi:text as="p" modifiers="small" color="tertiary" class="tedi-card-stepper__description">{{ $description }}</tedi:text>
                    @endif
                </div>
            @endif

            <tedi:text :as="$headingElement" id="{{ $headingId }}" modifiers="h4" class="tedi-card-stepper__title">
                {{ $current['title'] ?? '' }}
                @if ($showStatusIcon && in_array($currentState, ['completed', 'error'], true))
                    <tedi:icon
                        :name="$currentState === 'completed' ? 'check' : 'error'"
                        :color="$currentState === 'completed' ? 'success' : 'danger'"
                        :size="18"
                        :label="__($currentState === 'completed' ? 'tedi::tedi.stepper.completed' : 'tedi::tedi.stepper.error')"
                        class="tedi-card-stepper__status-icon"
                    />
                @endif
            </tedi:text>

            @if ($descriptionPosition === 'bottom' && $hasDescription)
                <div class="tedi-card-stepper__description-bottom"><tedi:text as="p" modifiers="small" color="tertiary" class="tedi-card-stepper__description">{{ $description }}</tedi:text></div>
            @endif
        </div>

        <div class="tedi-card-stepper__trail">
            @if ($counterPosition === 'inline')
                <span class="tedi-card-stepper__counter"><span aria-hidden="true">{{ $active + 1 }} / {{ $total }}</span><span class="sr-only">{{ $statusText }}</span></span>
            @endif

            @if ($showStepList)
                <tedi:button
                    variant="neutral"
                    icon-start="list"
                    icon-only
                    :aria-label="$labelOpenSteps"
                    :title="$labelOpenSteps"
                    aria-haspopup="dialog"
                    aria-expanded="false"
                    x-ref="trigger"
                    x-on:click="toggle()"
                />
            @endif

            @if ($showNavigation)
                <tedi:button
                    variant="neutral"
                    icon-start="arrow_forward"
                    icon-only
                    :aria-label="$labelNext"
                    :title="$labelNext"
                    class="tedi-card-stepper__nav-next"
                    :disabled="$nextIndex === -1"
                    x-on:click="{{ $change($nextIndex) }}"
                />
            @endif
        </div>
    </div>

    @if ($showProgress)
        <ol class="tedi-card-stepper__progress" aria-hidden="true">
            @foreach ($steps as $index => $step)
                <li @class([
                    'tedi-card-stepper__segment',
                    'tedi-card-stepper__segment--done' => $index <= $active,
                ])></li>
            @endforeach
        </ol>
    @endif

    @if ($hasBottom)
        <div class="tedi-card-stepper__bottom-slot">{{ $bottom }}</div>
    @endif

    @if ($showStepList)
        <tedi:sheet-content :show-handle="false">
            <tedi:sheet-header :title="$labelModalHeading" variant="default" />
            <tedi:sheet-body>
                <tedi:vertical-stepper :aria-label="$labelModalHeading">
                    @foreach ($steps as $index => $step)
                        @php
                            $subSteps = array_values($step['sub_steps'] ?? []);
                            $hasCurrentSubStep = count(array_filter($subSteps, fn ($sub) => (bool) ($sub['current'] ?? false))) > 0;
                            $stepState = $isDisabled($step) ? 'disabled' : $stateOf($step);
                            $navigable = $isNavigable($index);
                        @endphp
                        @if ($subSteps)
                            <tedi:vertical-stepper-item
                                :title="$step['title'] ?? ''"
                                :completed="$stepState === 'completed'"
                                :error="$stepState === 'error'"
                                :disabled="$stepState === 'disabled'"
                                :selected="$index === $active && ! $hasCurrentSubStep"
                                :opened="(bool) ($step['default_expanded'] ?? ($index === $active))"
                            >
                                @if (filled($step['description'] ?? null))
                                    <x-slot:description>{{ $step['description'] }}</x-slot:description>
                                @endif
                                <x-slot:sub-items>
                                    @foreach ($subSteps as $subStep)
                                        @php $subState = ($subStep['disabled'] ?? false) ? 'disabled' : ($subStep['state'] ?? 'default'); @endphp
                                        <tedi:vertical-stepper-item
                                            :sub-item="true"
                                            :title="$subStep['title'] ?? ''"
                                            :href="$subStep['href'] ?? null"
                                            :completed="$subState === 'completed'"
                                            :error="$subState === 'error'"
                                            :disabled="$subState === 'disabled'"
                                            :informative="$subState === 'informative'"
                                            :selected="(bool) ($subStep['current'] ?? false)"
                                        />
                                    @endforeach
                                </x-slot:sub-items>
                            </tedi:vertical-stepper-item>
                        @elseif ($navigable)
                            <tedi:vertical-stepper-item
                                :title="$step['title'] ?? ''"
                                :completed="$stepState === 'completed'"
                                :error="$stepState === 'error'"
                                :selected="$index === $active"
                                x-on:click="{{ $index !== $active ? $change($index).'; ' : '' }}hide()"
                            >
                                @if (filled($step['description'] ?? null))
                                    <x-slot:description>{{ $step['description'] }}</x-slot:description>
                                @endif
                            </tedi:vertical-stepper-item>
                        @else
                            <tedi:vertical-stepper-item
                                :title="$step['title'] ?? ''"
                                :completed="$stepState === 'completed'"
                                :error="$stepState === 'error'"
                                :disabled="$stepState === 'disabled'"
                                :selected="$index === $active"
                            >
                                @if (filled($step['description'] ?? null))
                                    <x-slot:description>{{ $step['description'] }}</x-slot:description>
                                @endif
                            </tedi:vertical-stepper-item>
                        @endif
                    @endforeach
                </tedi:vertical-stepper>
            </tedi:sheet-body>
        </tedi:sheet-content>
    @endif
</div>
@endif
