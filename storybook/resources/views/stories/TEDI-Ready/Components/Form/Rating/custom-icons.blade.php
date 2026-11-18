@storybook([
    'name' => 'Custom Icons',
    'order' => 9,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.76.92?node-id=15548-139124&m=dev',
])

@php
    $iconLabels = ['Väga halb', 'Halb', 'Keskmine', 'Hea', 'Väga hea'];
    $starLabels = ['Ei jäänud üldse rahule', 'Ei jäänud rahule', 'Osaliselt rahul', 'Jäin rahule', 'Jäin väga rahule'];
@endphp

<div class="flex flex-column gap-3">
    <tedi:rating type="icon" :icons="['thunderstorm', 'rainy', 'cloud', 'partly_cloudy_day', 'sunny']" :item-labels="$iconLabels" :value="4" label="Ilmahinnang" />
    <tedi:rating icons="star" :value="3" :item-labels="$starLabels" label="Pointier star" />
    <tedi:rating icons="favorite" :value="4" label="Favourite" />
</div>
