{{--
    TEDI Empty State.
    Port of angular/tedi/components/helpers/empty-state/empty-state.component.{ts,html}

    Description is the default slot (Angular's <ng-content />). Actions are
    the named `actions` slot (Angular's <ng-content select="[tedi-empty-state-actions]" />).

    Angular's `icon` input accepts `null` to hide the icon. Blade's @props
    defaulting treats an explicit `:icon="null"` the same as "not passed"
    (it falls back to the default via `isset()`), so pass an empty string
    (`icon=""`) to hide the icon here instead.
--}}
@props([
    /** separate|attached|inside */
    'type' => 'separate',
    /** default|small */
    'size' => 'default',
    /** Material Symbols icon name shown above the text. Pass "" to hide. */
    'icon' => 'spa',
    /** Icon color. */
    'iconColor' => 'brand',
    /** Icon size, in pixels. */
    'iconSize' => 36,
    /** Optional heading rendered above the description. */
    'heading' => null,
])

<div
    data-name="tedi-empty-state"
    {{ $attributes->class([
        'tedi-empty-state',
        'tedi-empty-state--'.$type,
        'tedi-empty-state--'.$size,
    ]) }}
>
    <div class="tedi-empty-state__text">
        @if ($icon)
            <div class="tedi-empty-state__icon">
                <tedi:icon :name="$icon" :size="$iconSize" :color="$iconColor" />
            </div>
        @endif

        <div class="tedi-empty-state__content">
            @if ($heading)
                <tedi:text as="h3" color="brand" modifiers="h3" class="tedi-empty-state__heading">
                    {{ $heading }}
                </tedi:text>
            @endif

            <tedi:text color="secondary" modifiers="center" class="tedi-empty-state__description">
                {{ $slot }}
            </tedi:text>
        </div>
    </div>

    <div class="tedi-empty-state__actions">
        {{ $actions ?? '' }}
    </div>
</div>
