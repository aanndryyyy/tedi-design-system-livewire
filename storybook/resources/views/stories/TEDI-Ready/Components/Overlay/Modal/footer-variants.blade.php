@storybook([
    'name' => 'Footer Variants',
    'order' => 11,
    'status' => 'subset',
    'args' => [
        'footer' => 'three-buttons',
    ],
    'argTypes' => [
        'footer' => [
            'control' => 'select',
            'options' => ['default', 'space-between', 'three-buttons', 'none'],
            'description' => 'Which footer composition to render. Angular opens four separate modals from four trigger buttons; two `position: fixed` modals cannot share a screen, so the four compositions are one control here.',
            'table' => [
                'category' => 'Story',
                'defaultValue' => ['summary' => "'default'"],
            ],
        ],
    ],
])

<tedi:modal :open="true" width="md">
    <tedi:modal-header>
        <h1>Title</h1>
    </tedi:modal-header>
    <tedi:modal-content>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="footer-field">Label</tedi:form.label>
            </x-slot:label>

            <tedi:text-field id="footer-field" />
        </tedi:form-field>
    </tedi:modal-content>

    @if ($footer === 'default')
        <tedi:modal-footer>
            <tedi:button variant="secondary" x-on:click="hide()">Cancel</tedi:button>
            <tedi:button x-on:click="hide()">Continue</tedi:button>
        </tedi:modal-footer>
    @elseif ($footer === 'space-between')
        <tedi:modal-footer style="justify-content: space-between;">
            <tedi:button variant="secondary" x-on:click="hide()">Cancel</tedi:button>
            <tedi:button x-on:click="hide()">Continue</tedi:button>
        </tedi:modal-footer>
    @elseif ($footer === 'three-buttons')
        <tedi:modal-footer style="justify-content: space-between;">
            <tedi:button variant="neutral" icon-start="arrow_back" x-on:click="hide()">Back</tedi:button>
            <div style="display: flex; gap: 12px;">
                <tedi:button variant="secondary" x-on:click="hide()">Cancel</tedi:button>
                <tedi:button x-on:click="hide()">Continue</tedi:button>
            </div>
        </tedi:modal-footer>
    @endif
</tedi:modal>
