@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.23.38?node-id=4626-89579&m=dev',
    'args' => [
        'open' => true,
        'size' => 'default',
        'width' => 'md',
        'position' => 'center',
        'closeOnBackdropClick' => true,
        'showClose' => true,
    ],
    'argTypes' => [
        'open' => [
            'control' => 'boolean',
            'description' => 'Is the modal open? Angular\'s deprecated `[(open)]` two-way binding; here the initial Alpine state.',
            'table' => [
                'category' => 'ModalConfig',
                'defaultValue' => ['summary' => 'false'],
                'type' => ['summary' => 'boolean'],
            ],
        ],
        'size' => [
            'control' => 'select',
            'options' => ['default', 'small'],
            'description' => 'Modal size variant. Controls padding and heading size. Close button defaults to match this variant unless `closeButtonSize` is set explicitly on `tedi:modal-header`.',
            'table' => [
                'category' => 'ModalConfig',
                'defaultValue' => ['summary' => "'default'"],
                'type' => ['summary' => "'default' | 'small'"],
            ],
        ],
        'width' => [
            'control' => 'text',
            'description' => 'Modal width — preset token or custom CSS value (e.g. `\'800px\'`, `\'60%\'`).',
            'table' => [
                'category' => 'ModalConfig',
                'defaultValue' => ['summary' => "'sm'"],
                'type' => ['summary' => "'xs' | 'sm' | 'md' | 'lg' | 'xl' | string"],
            ],
        ],
        'position' => [
            'control' => 'select',
            'options' => ['center', 'top', 'bottom', 'left', 'right'],
            'description' => 'Position of the modal on screen. `\'left\'` / `\'right\'` create side/drawer modals. `\'top\'` and `\'bottom\'` anchor the modal to the corresponding edge with a fixed margin — useful for mobile bottom-sheet patterns. TEDI ships no CSS for `\'bottom\'`, so it emits no class.',
            'table' => [
                'category' => 'ModalConfig',
                'defaultValue' => ['summary' => "'center'"],
                'type' => ['summary' => "'center' | 'top' | 'bottom' | 'left' | 'right'"],
            ],
        ],
        'closeOnBackdropClick' => [
            'control' => 'boolean',
            'description' => 'Whether clicking the backdrop closes the modal.',
            'table' => [
                'category' => 'ModalConfig',
                'defaultValue' => ['summary' => 'true'],
                'type' => ['summary' => 'boolean'],
            ],
        ],
        'showClose' => [
            'control' => 'boolean',
            'description' => 'Whether to show a close button in the header. Set via `show-close` on `tedi:modal-header`.',
            'table' => [
                'category' => 'ModalConfig',
                'defaultValue' => ['summary' => 'true'],
                'type' => ['summary' => 'boolean'],
            ],
        ],
    ],
])

<tedi:modal
    :open="(bool) $open"
    :size="$size"
    :width="$width"
    :position="$position"
    :close-on-backdrop-click="(bool) $closeOnBackdropClick"
>
    <tedi:modal-header :show-close="(bool) $showClose">
        <h1>Modal title</h1>
    </tedi:modal-header>
    <tedi:modal-content>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="field-1">Label</tedi:form.label>
            </x-slot:label>

            <tedi:text-field id="field-1" />
        </tedi:form-field>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="field-2">Label</tedi:form.label>
            </x-slot:label>

            <tedi:text-field id="field-2" />
        </tedi:form-field>
    </tedi:modal-content>
    <tedi:modal-footer>
        <tedi:button variant="secondary" x-on:click="hide()">Cancel</tedi:button>
        <tedi:button x-on:click="hide()">Continue</tedi:button>
    </tedi:modal-footer>
</tedi:modal>
