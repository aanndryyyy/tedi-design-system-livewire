@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.46.70?node-id=31693-133265&m=dev',
    'args' => [
        'ariaLabel' => 'Primary navigation',
        'maxWidth' => 'xxl',
    ],
    'argTypes' => [
        'ariaLabel' => [
            'control' => 'text',
            'description' => 'Accessible name for the wrapping nav landmark.',
        ],
        'maxWidth' => [
            'control' => 'select',
            'options' => ['sm', 'md', 'lg', 'xl', 'xxl', 'none'],
            'description' => "Constrains the inner content to a maximum width and centers it inside the blue nav background. A breakpoint name resolves to that breakpoint's min-width; a number is px; none removes the constraint. The nav itself still spans 100%.",
            'table' => ['defaultValue' => ['summary' => 'xxl']],
        ],
    ],
])

{{-- The mobile branch is not ported (see the component's header comment), so
     these stories show the desktop bar only. --}}
<tedi:top-nav :aria-label="$ariaLabel" :max-width="$maxWidth">
    <tedi:top-nav-item href="#">Avaleht</tedi:top-nav-item>
    <tedi:top-nav-item href="#" is-active>Perekond</tedi:top-nav-item>
    <tedi:top-nav-item href="#">Hüvitised ja toetused</tedi:top-nav-item>
    <tedi:top-nav-item href="#">Töö ja töösuhted</tedi:top-nav-item>
    <tedi:top-nav-item href="#">Liiklus ja sõidukid</tedi:top-nav-item>
    <tedi:top-nav-item href="#">Minu andmed</tedi:top-nav-item>
</tedi:top-nav>
