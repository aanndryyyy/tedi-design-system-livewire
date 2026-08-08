@storybook([
    'name' => 'With Title Long Text And Closing Button',
    'order' => 11,
    'status' => 'stable',
])

<tedi:alert type="danger" icon="error" :show-close="true" title="Pealkiri">
    Teie kontol on mitu lahendamata teadet, mis vajavad tähelepanu. Palun vaadake üle oma profiili andmed ja kinnitage need enne jätkamist.
    Süsteem salvestab muudatused automaatselt, kuid soovitame need siiski üle kontrollida.
    Mõned väljad võivad olla puudulikud või vananenud ning vajavad täiendamist.
    Kui teil on küsimusi, võtke palun ühendust klienditoega.
</tedi:alert>
