@storybook([
    'name' => 'Long Text Values',
    'order' => 6,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<div class="flex flex-column gap-3">
    <tedi:text-group type="vertical" label-width="150px">
        <x-slot:label>Nähtavus</x-slot:label>
        <x-slot:value>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent pulvinar malesuada tellus, nec efficitur orci interdum vitae.
            Proin semper venenatis est, vel malesuada sapien ornare at. Vestibulum egestas in lectus non finibus.
            Donec rhoncus sapien vel justo elementum vestibulum. Vivamus euismod dui vel erat semper luctus.
            Nulla egestas purus elit, non fermentum sapien sagittis nec. Pellentesque ac sapien non justo vehicula porta.
        </x-slot:value>
    </tedi:text-group>
    <tedi:text-group type="horizontal" label-width="150px">
        <x-slot:label>Nähtavus</x-slot:label>
        <x-slot:value>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent pulvinar malesuada tellus, nec efficitur orci interdum vitae.
            Proin semper venenatis est, vel malesuada sapien ornare at. Vestibulum egestas in lectus non finibus.
            Donec rhoncus sapien vel justo elementum vestibulum. Vivamus euismod dui vel erat semper luctus.
            Nulla egestas purus elit, non fermentum sapien sagittis nec. Pellentesque ac sapien non justo vehicula porta.
        </x-slot:value>
    </tedi:text-group>
</div>
