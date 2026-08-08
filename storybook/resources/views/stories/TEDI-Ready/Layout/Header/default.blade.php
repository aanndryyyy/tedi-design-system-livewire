@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.47.70?m=dev&node-id=6380-53060',
    'layout' => 'fullscreen',
    'args' => [
        'logoHref' => '/',
        'showLogo' => true,
        'alignment' => 'center',
        'selectLabel' => '',
        'labelPosition' => 'top',
        'loginHref' => '',
        'loginLabel' => '',
    ],
    'argTypes' => [
        'logoHref' => [
            'name' => 'href',
            'control' => 'text',
            'description' => 'URL to wrap the logo with an anchor. When omitted, the logo renders without a link.',
            'table' => ['category' => 'header-logo', 'type' => ['summary' => 'string']],
        ],
        'showLogo' => [
            'control' => 'boolean',
            'description' => 'Controls visibility of the logo.',
            'table' => ['category' => 'header-logo', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'true']],
        ],
        'alignment' => [
            'control' => 'select',
            'options' => ['flex-start', 'center', 'flex-end', 'space-between', 'space-around', 'space-evenly'],
            'description' => 'Horizontal alignment of content area.',
            'table' => ['category' => 'header-content', 'type' => ['summary' => "'flex-start' | 'center' | 'flex-end' | 'space-between' | 'space-around' | 'space-evenly'"], 'defaultValue' => ['summary' => "'center'"]],
        ],
        'selectLabel' => [
            'control' => 'text',
            'description' => "Label for the language selector. Falls back to the 'header.select-lang' translation.",
            'table' => ['category' => 'header-language', 'type' => ['summary' => 'string']],
        ],
        'labelPosition' => [
            'control' => 'inline-radio',
            'options' => ['top', 'left'],
            'description' => 'Position of the select label relative to the trigger.',
            'table' => ['category' => 'header-language', 'type' => ['summary' => "'top' | 'left'"], 'defaultValue' => ['summary' => "'top'"]],
        ],
        'loginHref' => [
            'name' => 'href',
            'control' => 'text',
            'description' => 'URL — when provided, renders as <a>. Otherwise renders as <button>.',
            'table' => ['category' => 'header-login', 'type' => ['summary' => 'string']],
        ],
        'loginLabel' => [
            'name' => 'label',
            'control' => 'text',
            'description' => "Custom label text. Falls back to the 'header.login' translation key.",
            'table' => ['category' => 'header-login', 'type' => ['summary' => 'string']],
        ],
    ],
])

{{--
    Angular's Default story wraps the header in a mobile sidenav (a
    `tedi-sidenav-toggle` button in the header plus a `<nav tedi-sidenav>`
    below it). `layout/sidenav` is not in this port's phase (README "Not in
    this phase"), so the actual `<nav tedi-sidenav>` menu is dropped here.
    The mobile toggle button itself is kept via `tedi:header.toggle`, which
    is a self-contained replacement documented in header/toggle.blade.php's
    header comment — it renders the same classes but has no sidenav to open.
--}}
<tedi:header>
    <x-slot:toggle>
        <tedi:header.toggle />
    </x-slot:toggle>

    <tedi:header.logo :href="$logoHref ?: null" :show-logo="(bool) $showLogo">
        <img src="header-logo.svg" alt="Logo">
        <x-slot:darkLogo>
            <img src="header-logo-white.svg" alt="Logo (Dark Mode)">
        </x-slot:darkLogo>
    </tedi:header.logo>

    <tedi:header.content :alignment="$alignment">
        <tedi:link href="#" :underline="false">Link text</tedi:link>
        <tedi:link href="#" :underline="false">Link text</tedi:link>
        <tedi:link href="#" :underline="false">Link text</tedi:link>
    </tedi:header.content>

    <tedi:header.actions>
        <tedi:header.language
            :languages="['et' => 'EST', 'en' => 'ENG', 'ru' => 'RUS']"
            :select-label="$selectLabel ?: null"
            :label-position="$labelPosition"
        />
        <tedi:separator axis="vertical" />
        <tedi:header.login
            :href="$loginHref ?: null"
            :label="$loginLabel"
        />
    </tedi:header.actions>
</tedi:header>
