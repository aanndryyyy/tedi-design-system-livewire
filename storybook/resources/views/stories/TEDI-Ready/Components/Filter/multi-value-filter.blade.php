@storybook([
    'name' => 'Multi Value Filter',
    'order' => 4,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

@php
    $options = [
        ['label' => 'Fertilitas', 'value' => '1'],
        ['label' => 'Ida-Tallinna Keskhaigla', 'value' => '2'],
        ['label' => 'Lääne-Tallinna Keskhaigla', 'value' => '3'],
        ['label' => 'Põhja-Eesti Regionaalhaigla', 'value' => '4'],
        ['label' => 'Tallinna Lastehaigla', 'value' => '5'],
        ['label' => 'Tartu Ülikooli Kliinikum', 'value' => '6'],
    ];
@endphp

<div style="background: var(--general-surface-primary); padding: 24px;">
    <tedi:row cols="1" :gap-y="3">
        <tedi:col class="flex flex-wrap gap-2">
            <tedi:filter
                text="Raviasutus"
                :allow-multiple="true"
                :options="$options"
                :value="[]"
                :show-search="true"
                :show-select-all="true"
                :show-clear="true"
            />
        </tedi:col>
        <tedi:col class="flex flex-wrap gap-2">
            <tedi:filter
                text="Raviasutus"
                variant="secondary"
                :allow-multiple="true"
                :options="$options"
                :value="[]"
                :show-search="true"
                :show-select-all="true"
                :show-clear="true"
            />
        </tedi:col>
    </tedi:row>
</div>
