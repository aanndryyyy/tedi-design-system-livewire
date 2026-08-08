@storybook([
    'name' => 'Colors',
    'order' => 3,
    'status' => 'stable',
])

@php
    $copy = 'Rebane on väikese koera suurune ja pika koheva sabaga. Joostes hoiab ta saba horisontaalselt. Tema selja karvad on oranžid. Eestis eelistab ta elupaigana metsatukkasid.';
@endphp

<tedi:row :cols="1" :gap-y="3">
    <tedi:link href="#">{{ $copy }}</tedi:link>
    <tedi:link href="#" :underline="false">{{ $copy }}</tedi:link>
    <tedi:row :cols="1" :gap-y="3" style="background: var(--general-icon-background-brand-primary); border-radius: 4px; padding: 1rem;">
        <tedi:link href="#" variant="inverted">{{ $copy }}</tedi:link>
        <tedi:link href="#" variant="inverted" :underline="false">{{ $copy }}</tedi:link>
    </tedi:row>
</tedi:row>
