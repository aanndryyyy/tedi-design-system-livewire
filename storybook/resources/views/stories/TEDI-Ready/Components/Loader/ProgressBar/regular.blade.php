@storybook([
    'name' => 'Regular',
    'order' => 5,
    'status' => 'stable',
])

<tedi:row :cols="1" :gap-y="4">
    <tedi:progress-bar :value="40" aria-label="Faili üleslaadimine" />
    <tedi:progress-bar :value="40" aria-label="Faili üleslaadimine" value-label="1 / 5" />
    <tedi:progress-bar :value="40" aria-label="Faili üleslaadimine" value-position="bottom" />
</tedi:row>
