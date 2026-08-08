@storybook([
    'name' => 'Sizes',
    'order' => 2,
    'status' => 'stable',
    'args' => [],
])

<style>
    .tedi-search-sizes {
        overflow: hidden;
        border: 1px solid var(--tedi-neutral-350);
        border-radius: var(--tedi-radius-03);
    }
    .tedi-search-sizes__row {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        padding: 14px 16px;
    }
    .tedi-search-sizes__row + .tedi-search-sizes__row {
        border-top: 1px solid var(--tedi-neutral-350);
    }
    /* min-width: 0 lets the field column (and the inputs inside it) shrink
       below their intrinsic width so they never overflow on narrow screens. */
    .tedi-search-sizes__fields {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        min-width: 0;
    }
    @media (min-width: 36rem) {
        .tedi-search-sizes__row {
            flex-direction: row;
            align-items: center;
        }
        .tedi-search-sizes__label {
            flex: 0 0 5rem;
        }
        .tedi-search-sizes__fields {
            flex: 1 1 auto;
        }
    }
</style>

<div class="tedi-search-sizes">
    @foreach (['small', 'default', 'large'] as $size)
        <div class="tedi-search-sizes__row">
            <tedi:text modifiers="bold" class="tedi-search-sizes__label">{{ ucfirst($size) }}</tedi:text>
            <div class="tedi-search-sizes__fields">
                <tedi:search
                    :input-id="'size-'.$size.'-plain'"
                    :size="$size"
                    label="Otsing"
                    :aria-label="'Otsing – '.$size.', ilma nuputa'"
                />
                <tedi:search
                    :input-id="'size-'.$size.'-icon'"
                    :size="$size"
                    label="Otsing"
                    :aria-label="'Otsing – '.$size.', nupp ikooniga'"
                    :button="['ariaLabel' => 'Otsi']"
                />
                <tedi:search
                    :input-id="'size-'.$size.'-button'"
                    :size="$size"
                    label="Otsing"
                    :aria-label="'Otsing – '.$size.', nupp ikooni ja tekstiga'"
                    :button="['text' => 'Otsi']"
                />
            </div>
        </div>
    @endforeach
</div>
