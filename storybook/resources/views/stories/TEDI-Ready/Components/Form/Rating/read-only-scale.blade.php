@storybook([
    'name' => 'Read Only Scale',
    'order' => 8,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.76.92?node-id=15548-139124&m=dev',
])

<div class="flex flex-column gap-3">
    @foreach ([4.8, 3.5, 2.5, 1.2] as $value)
        <tedi:rating :read-only="true" read-only-variant="scale" :value="$value" :ratings-count="271" label="Teenuse hinnang" />
    @endforeach
</div>
