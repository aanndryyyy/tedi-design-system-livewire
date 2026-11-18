@storybook([
    'name' => 'Vertical (icons)',
    'order' => 6,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.76.92?node-id=15548-139124&m=dev',
])

<tedi:rating
    type="icon"
    orientation="vertical"
    :item-labels="['Väga halb kogemus', 'Halb kogemus', 'Keskmine kogemus', 'Hea kogemus', 'Väga hea kogemus']"
    :value="4"
    label="Teenuse hinnang"
/>
