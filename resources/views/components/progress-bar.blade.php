{{--
    TEDI Progress Bar.
    Port of angular/src/tedi/components/loader/progress-bar/progress-bar.component.{ts,html}

    Breakpoint inputs (xs–xxl) are not ported (CONVENTIONS.md §7) — only the base
    props are supported. Project a tedi:feedback-text into the default slot to
    render a hint/error row beneath the bar, mirroring Angular's
    `<ng-content select="tedi-feedback-text" />`.

    Angular's `[tedi-label]` selector maps to tedi:form.label (ported under
    form/ — see form/label.blade.php); its `as` prop picks the element the same
    way `[tedi-label]` picks its host, so the title label renders as `<label>`
    (the default) and the value readouts render `as="span"` to match
    `<span tedi-label>` in the Angular template.

    Angular's host binding also has `[class.tedi-progress-bar--value-bottom]`
    (progress-bar.component.ts) whenever `valuePosition === 'bottom'`. The
    vendored SCSS defines no rule for it at all — dist/tedi.css has no
    `.tedi-progress-bar--value-bottom` — so it visibly styles nothing in
    Angular either; the actual bottom placement comes from moving the value
    into `__hint-row` and giving it `__value--bottom` (which IS styled). This
    port therefore does not emit the dead host class.

    Angular 8+: when `progressId` is omitted a unique id is generated
    (`tedi-progress-bar-…`), so the internal label's `for` stays bound to the
    bar. `announce` (off|polite|assertive) opts in to a visually hidden live
    region that publishes changes to the formatted value, throttled by
    `announceInterval`. Upstream drives it from signal effects; here the
    `tediProgressBar` Alpine behaviour (resources/js/src/progress-bar.js)
    watches the rendered `<progress>` for attribute changes — a Livewire morph
    or an Alpine `:value` binding — so it needs the bundled JS. Without it the
    region renders but stays empty, which is upstream's initial state anyway
    (the current value is never announced, only changes).
--}}
@props([
    /** Optional id for the underlying <progress> element. */
    'progressId' => null,
    /** 0-100, clamped. */
    'value' => 0,
    /** default|small */
    'size' => 'default',
    /** Optional title rendered above/left of the bar. */
    'label' => null,
    /** top|horizontal — ignored when label is not set. */
    'labelPosition' => 'top',
    /** Renders a red * after the label. Ignored when label is not set. */
    'required' => false,
    'showValue' => true,
    /** horizontal|bottom */
    'valuePosition' => 'horizontal',
    /** Overrides the rendered value text. Defaults to "{value}%". */
    'valueLabel' => null,
    /** Accessible label; falls back to label when omitted. */
    'ariaLabel' => null,
    /** off|polite|assertive — publish value changes in a visually hidden live region. */
    'announce' => 'off',
    /** Minimum ms between live-region updates; 0 disables throttling. Ignored when announce is off. */
    'announceInterval' => 1000,
])

@php
    $value = min(100, max(0, (float) $value));
    $formattedValue = $valueLabel ?? $value.'%';
    $accessibleLabel = $ariaLabel ?? $label;
    $resolvedId = $progressId ?: \Tedi\Livewire\Tedi::id('tedi-progress-bar');
    $announcing = $announce !== 'off';
    $interval = is_numeric($announceInterval) && is_finite((float) $announceInterval)
        ? max(0, (float) $announceInterval)
        : 1000;
@endphp

<div {{ $attributes->class([
    'tedi-progress-bar',
    'tedi-progress-bar--small' => $size === 'small',
    'tedi-progress-bar--label-horizontal' => $label && $labelPosition === 'horizontal',
]) }}
    @if ($announcing) x-data="tediProgressBar({ interval: {{ $interval }} })" @endif
>
    @if ($label && $labelPosition === 'top')
        <tedi:form.label color="primary" :required="$required" :for="$resolvedId" class="tedi-progress-bar__label">
            {{ $label }}
        </tedi:form.label>
    @endif

    <div class="tedi-progress-bar__row">
        @if ($label && $labelPosition === 'horizontal')
            <tedi:form.label color="primary" :required="$required" :for="$resolvedId" class="tedi-progress-bar__label">
                {{ $label }}
            </tedi:form.label>
        @endif

        <div class="tedi-progress-bar__main">
            <div class="tedi-progress-bar__track-row">
                <progress
                    class="tedi-progress-bar__track"
                    id="{{ $resolvedId }}"
                    @if ($announcing) x-ref="bar" @endif
                    max="100"
                    value="{{ $value }}"
                    @if ($accessibleLabel) aria-label="{{ $accessibleLabel }}" @endif
                    @if ($valueLabel) aria-valuetext="{{ $valueLabel }}" @endif
                ></progress>

                @if ($showValue && $valuePosition === 'horizontal')
                    <tedi:form.label as="span" size="small" class="tedi-progress-bar__value">
                        {{ $formattedValue }}
                    </tedi:form.label>
                @endif
            </div>

            <div class="tedi-progress-bar__hint-row">
                {{ $slot }}

                @if ($showValue && $valuePosition === 'bottom')
                    <tedi:form.label as="span" size="small" class="tedi-progress-bar__value tedi-progress-bar__value--bottom">
                        {{ $formattedValue }}
                    </tedi:form.label>
                @endif
            </div>
        </div>
    </div>

    @if ($announcing)
        <span
            class="sr-only"
            role="{{ $announce === 'assertive' ? 'alert' : 'status' }}"
            aria-live="{{ $announce }}"
            aria-atomic="true"
            wire:ignore
            x-text="announcedValue"
        ></span>
    @endif
</div>
