{{--
    TEDI Attachment.
    Port of angular/tedi/components/helpers/attachment/attachment.component.{ts,html}

    Angular projects content by selector: `<ng-content select="tedi-progress-bar" />`
    and `<ng-content select="tedi-attachment-actions" />`. Per CONVENTIONS.md §3
    those become named slots `progress` and `actions` — project a
    <tedi:progress-bar> into `progress` to show upload progress, and your own
    icon-only action buttons into `actions`.

    Angular's `verticalBelow` input picks the breakpoint below which
    `direction` auto-switches to `vertical` via BreakpointService. The
    auto-switch itself is not portable (CONVENTIONS.md §7 — it's resolved at
    runtime against the viewport) — `direction` defaults to `horizontal` and
    must be set explicitly for the vertical layout. `verticalBelow` is still
    declared as a @props entry (with Angular's `sm` default) purely so it's
    accepted/stripped from $attributes instead of leaking onto the root <div>
    as a stray HTML attribute (CONVENTIONS.md §9.2); it has no rendering
    effect.

    `padded` belongs to the separate AttachmentActionsComponent Angular
    projects into the actions slot (`<tedi-attachment-actions padded>`), not
    to AttachmentComponent itself. Rather than add a second, only-ever-nested
    Blade component for it, its `tedi-attachment-actions` / `--padded`
    classes are folded directly into this component's `actions` slot
    wrapper — same classes, same default, one file.

    FeedbackTextComponent isn't ported yet, so the error message below the
    card is rendered inline with its exact class list instead of via
    <tedi:feedback-text>.
--}}
@props([
    /** File name to display. */
    'name',
    /** Pre-formatted file size string (e.g. "0.9 MB"). */
    'fileSize' => null,
    /** Leading file-type icon (Material Symbols name). */
    'icon' => null,
    /**
     * Error feedback message. Switches to the error visual (red card, error
     * icon, feedback text below) and implies `invalid`.
     */
    'error' => null,
    /** Error visual without rendering feedback text below the card. */
    'invalid' => false,
    /** horizontal|vertical */
    'direction' => 'horizontal',
    /** Accepted for input parity only — see comment above; no effect. */
    'verticalBelow' => 'sm',
    /** Adds a gap + inline padding around the actions slot's contents. */
    'padded' => false,
])

@php
    $hasErrorVisual = (bool) $error || (bool) $invalid;
    $hasProgress = isset($progress) && trim((string) $progress) !== '';
    $hasActions = isset($actions) && trim((string) $actions) !== '';
@endphp

<div
    {{ $attributes->class([
        'tedi-attachment',
        'tedi-attachment--error' => $hasErrorVisual,
        'tedi-attachment--vertical' => $direction === 'vertical',
        'tedi-attachment--has-progress' => $hasProgress,
    ]) }}
>
    <div class="tedi-attachment__card">
        <div class="tedi-attachment__title-row">
            <div class="tedi-attachment__title-group">
                @if ($icon)
                    <tedi:icon :name="$icon" :size="18" class="tedi-attachment__icon" />
                @endif

                <span class="tedi-attachment__title">{{ $name }}</span>

                @if ($hasErrorVisual)
                    <tedi:icon name="error" color="danger" :size="18" :label="$error" class="tedi-attachment__error-icon" />
                @endif
            </div>

            @if ($fileSize)
                <span class="tedi-attachment__size">{{ $fileSize }}</span>
            @endif
        </div>

        <div @class(['tedi-attachment__progress', 'tedi-attachment__progress--empty' => ! $hasProgress])>
            {{ $progress ?? '' }}
        </div>

        <div class="tedi-attachment__actions">
            @if ($hasActions)
                <div @class(['tedi-attachment-actions', 'tedi-attachment-actions--padded' => (bool) $padded])>
                    {{ $actions }}
                </div>
            @endif
        </div>
    </div>

    @if ($error)
        <span class="tedi-attachment__feedback tedi-feedback-text tedi-feedback-text--error tedi-feedback-text--left" role="alert" aria-live="assertive">
            {{ $error }}
        </span>
    @endif
</div>
