@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.102.102?node-id=65376-125297&m=dev',
    'args' => [
        'contentShowHandle' => true,
        'contentTrapFocus' => true,
        'contentReturnFocus' => true,
        'contentShowOverlay' => true,
        'contentLockScroll' => true,
        'contentVisuallyHiddenDismiss' => true,
        'contentMinHeight' => '',
        'contentMaxHeight' => '',
        'contentPadding' => 'default',
        'contentRadius' => 'default',
        'headerTitle' => 'Filtreerimine',
        'headerHeadingLevel' => 'h3',
        'headerVariant' => 'default',
        'headerTitlePosition' => 'start',
        'headerCollapsible' => false,
        'headerCloseButton' => true,
        'footerAlign' => 'left',
        'footerFullWidth' => true,
    ],
    'argTypes' => [
        'contentShowHandle' => ['control' => 'boolean', 'description' => 'Show the drag handle at the top. While it is shown, the sheet can be dragged from the handle or from anywhere on the header except its interactive content.', 'table' => ['category' => 'Sheet.Content', 'defaultValue' => ['summary' => 'true']]],
        'contentTrapFocus' => ['control' => 'boolean', 'description' => 'Trap focus inside the sheet while open.', 'table' => ['category' => 'Sheet.Content', 'defaultValue' => ['summary' => 'true']]],
        'contentReturnFocus' => ['control' => 'boolean', 'description' => 'Restore focus to the trigger after closing.', 'table' => ['category' => 'Sheet.Content', 'defaultValue' => ['summary' => 'true']]],
        'contentShowOverlay' => ['control' => 'boolean', 'description' => 'Render the dimmed backdrop.', 'table' => ['category' => 'Sheet.Content', 'defaultValue' => ['summary' => 'true']]],
        'contentLockScroll' => ['control' => 'boolean', 'description' => 'Lock background scroll while open.', 'table' => ['category' => 'Sheet.Content', 'defaultValue' => ['summary' => 'true']]],
        'contentVisuallyHiddenDismiss' => ['control' => 'boolean', 'description' => 'Render visually-hidden dismiss buttons at the start and end of the dialog so touch screen-readers can escape without an Escape key. Only applies while trapFocus is true.', 'table' => ['category' => 'Sheet.Content', 'defaultValue' => ['summary' => 'true']]],
        'contentMinHeight' => ['control' => 'text', 'description' => 'Minimum height of the sheet panel (any CSS length).', 'table' => ['category' => 'Sheet.Content']],
        'contentMaxHeight' => ['control' => 'text', 'description' => 'Maximum height of the sheet panel (any CSS length). Defaults to 90dvh.', 'table' => ['category' => 'Sheet.Content']],
        'contentPadding' => ['control' => 'radio', 'options' => ['default', 'small'], 'description' => 'Padding scale for the whole sheet - header, body and footer tighten together.', 'table' => ['category' => 'Sheet.Content', 'defaultValue' => ['summary' => 'default']]],
        'contentRadius' => ['control' => 'radio', 'options' => ['default', 'card', 'none'], 'description' => 'Top-corner radius of the sheet panel (the header follows it).', 'table' => ['category' => 'Sheet.Content', 'defaultValue' => ['summary' => 'default']]],
        'headerTitle' => ['control' => 'text', 'description' => 'Header title. Rendered as a heading and wired to the dialog aria-labelledby.', 'table' => ['category' => 'Sheet.Header']],
        'headerHeadingLevel' => ['control' => 'select', 'options' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'], 'description' => 'Semantic heading level of the title; the visual style stays H6 regardless.', 'table' => ['category' => 'Sheet.Header', 'defaultValue' => ['summary' => 'h3']]],
        'headerVariant' => ['control' => 'radio', 'options' => ['brand', 'default'], 'description' => 'Colour scheme.', 'table' => ['category' => 'Sheet.Header', 'defaultValue' => ['summary' => 'brand']]],
        'headerTitlePosition' => ['control' => 'radio', 'options' => ['start', 'center'], 'description' => 'Where the title sits in the header.', 'table' => ['category' => 'Sheet.Header', 'defaultValue' => ['summary' => 'start']]],
        'headerCollapsible' => ['control' => 'boolean', 'description' => 'Show a collapse toggle that peeks the sheet down to just its header.', 'table' => ['category' => 'Sheet.Header', 'defaultValue' => ['summary' => 'false']]],
        'headerCloseButton' => ['control' => 'boolean', 'description' => 'Show the close button.', 'table' => ['category' => 'Sheet.Header', 'defaultValue' => ['summary' => 'true']]],
        'footerAlign' => ['control' => 'radio', 'options' => ['left', 'center', 'right'], 'description' => 'Horizontal placement of the main actions. Ignored when the end slot is set.', 'table' => ['category' => 'Sheet.Footer', 'defaultValue' => ['summary' => 'left']]],
        'footerFullWidth' => ['control' => 'boolean', 'description' => 'Stretch the actions to fill the footer width.', 'table' => ['category' => 'Sheet.Footer', 'defaultValue' => ['summary' => 'false']]],
    ],
])

<tedi:sheet>
    <tedi:sheet-trigger><tedi:button variant="neutral" icon-start="tune">Filtreeri</tedi:button></tedi:sheet-trigger>
    <tedi:sheet-content
        :show-handle="(bool) $contentShowHandle"
        :trap-focus="(bool) $contentTrapFocus"
        :return-focus="(bool) $contentReturnFocus"
        :show-overlay="(bool) $contentShowOverlay"
        :lock-scroll="(bool) $contentLockScroll"
        :visually-hidden-dismiss="(bool) $contentVisuallyHiddenDismiss"
        :min-height="$contentMinHeight ?: null"
        :max-height="$contentMaxHeight ?: null"
        :padding="$contentPadding"
        :radius="$contentRadius"
    >
        <tedi:sheet-header
            :title="$headerTitle"
            :heading-level="$headerHeadingLevel"
            :variant="$headerVariant"
            :title-position="$headerTitlePosition"
            :collapsible="(bool) $headerCollapsible"
            :close-button="(bool) $headerCloseButton"
        />
        <tedi:sheet-body padding="none">
                <div style="padding: var(--tedi-sheet-padding-x)">
                    <tedi:radio-group name="sheet-default-status" direction="vertical" aria-label="Kirjade olek">
                        <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:radio id="sheet-default-status-all" name="sheet-default-status" value="all" checked /> Kõik</tedi:form.label>
                        <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:radio id="sheet-default-status-unread" name="sheet-default-status" value="unread" /> Ainult lugemata</tedi:form.label>
                    </tedi:radio-group>
                </div>
                <tedi:separator :spacing="0" />
                <div style="padding: var(--tedi-sheet-padding-x)">
                    <tedi:radio-group name="sheet-default-sort" direction="vertical" aria-label="Järjestus">
                        <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:radio id="sheet-default-sort-unread" name="sheet-default-sort" value="unread-top" checked /> Lugemata kõige üleval</tedi:form.label>
                        <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:radio id="sheet-default-sort-date" name="sheet-default-sort" value="date" /> Kuupäeva järgi</tedi:form.label>
                    </tedi:radio-group>
                </div>
        </tedi:sheet-body>
        <tedi:sheet-footer :align="$footerAlign" :full-width="(bool) $footerFullWidth">
            <tedi:sheet-closer><tedi:button variant="secondary">Tühista</tedi:button></tedi:sheet-closer>
            <tedi:button>Rakenda</tedi:button>
            <x-slot:end><tedi:button variant="secondary" icon-start="more_vert" icon-only aria-label="Rohkem valikuid" title="Rohkem valikuid" /></x-slot:end>
        </tedi:sheet-footer>
    </tedi:sheet-content>
</tedi:sheet>
