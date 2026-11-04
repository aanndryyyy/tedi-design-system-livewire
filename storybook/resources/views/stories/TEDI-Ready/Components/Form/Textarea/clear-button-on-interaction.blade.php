@storybook([
    'name' => 'Clear Button On Interaction',
    'order' => 7,
    'status' => 'stable',
    'args' => [],
])

<tedi:form-field :clearable="true" :show-clear-on-interaction="true">
    <x-slot:label>
        <tedi:form.label for="clear-on-interaction">Label</tedi:form.label>
    </x-slot:label>
    <tedi:textarea
        id="clear-on-interaction"
        value="Rebane on väikese koera suurune ja pika koheva sabaga. Joostes hoiab ta saba horisontaalselt. Tema selja karvad on oranžid. Eestis eelistab ta elupaigana metsatukkasid. Rebane toitub peamiselt närilistest. Ta on ettevaatlik ja oskab hästi põgeneda. Rebase urul on alati mitu väljapääsu. Urud on põhiliselt kutsikate kasvatamiseks. Ta kaevab urud ise või kohendab mägra urge."
    />
</tedi:form-field>
