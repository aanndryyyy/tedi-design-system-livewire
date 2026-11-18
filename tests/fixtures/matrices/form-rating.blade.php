<div class="gx-sec">
    <h2>Rating</h2>
    <p>Port of <code>form/rating</code>. A <code>&lt;tedi-rating&gt;</code> radiogroup of native <code>&lt;input type="radio"&gt;</code>s; <code>wire:model</code> / <code>x-model</code> are copied onto every radio. The read-only branch renders a <code>role="img"</code> summary instead.</p>

    @php
        $iconLabels = ['Väga halb', 'Halb', 'Keskmine', 'Hea', 'Väga hea'];
        $numberLabels = ['Väga halb', '', '', '', '', '', '', '', '', 'Suurepärane'];
    @endphp

    <div class="gx-case">
        <div class="gx-case__label">type: star | number | icon (value 0 and 3; with and without itemLabels)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            @foreach (['star', 'number', 'icon'] as $type)
                <tedi:rating :type="$type" :label="$type.' empty'" />
                <tedi:rating :type="$type" :value="3" :label="$type.' 3'" />
                <tedi:rating :type="$type" :value="3" :item-labels="$type === 'number' ? $numberLabels : $iconLabels" :label="$type.' labelled'" />
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">orientation: horizontal | vertical (star ignores vertical)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            @foreach (['star', 'number', 'icon'] as $type)
                @foreach (['horizontal', 'vertical'] as $orientation)
                    <tedi:rating :type="$type" :orientation="$orientation" :value="2" :item-labels="$type === 'number' ? $numberLabels : $iconLabels" :label="$type.' '.$orientation" />
                @endforeach
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">disabled: false | true</div>
        <div class="gx-case__demo gx-case__demo--stack">
            @foreach (['star', 'number', 'icon'] as $type)
                <tedi:rating :type="$type" :value="2" :disabled="true" :label="$type.' disabled'" />
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">readOnly × readOnlyVariant: summary | scale; ratingsCount; showRatingsCountLabel</div>
        <div class="gx-case__demo gx-case__demo--stack">
            @foreach (['star', 'number', 'icon'] as $type)
                @foreach (['summary', 'scale'] as $variant)
                    <tedi:rating :type="$type" :read-only="true" :read-only-variant="$variant" :value="3.5" :ratings-count="271" :label="$type.' '.$variant" />
                @endforeach
                <tedi:rating :type="$type" :read-only="true" :value="3.5" :ratings-count="271" :show-ratings-count-label="false" :label="$type.' count only'" />
                <tedi:rating :type="$type" :read-only="true" :value="0" :label="$type.' zero'" />
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">icons: custom array (icon) | single string (star)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:rating type="icon" :icons="['thunderstorm', 'rainy', 'cloud', 'partly_cloudy_day', 'sunny']" :value="4" label="Ilmahinnang" />
            <tedi:rating icons="favorite" :value="4" label="Favourite" />
        </div>
    </div>
</div>
