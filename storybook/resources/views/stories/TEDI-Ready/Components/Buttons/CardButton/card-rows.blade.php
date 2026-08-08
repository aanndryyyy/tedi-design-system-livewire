{{--
    Angular renders "Broneerima" text only at the `sm` breakpoint (`*showAt="'sm'"`).
    Breakpoint props aren't ported (CONTRACT.md §5), so the text is shown
    unconditionally here.
--}}
@storybook([
    'name' => 'Card Rows',
    'order' => 2,
    'status' => 'subset',
])

<div class="tedi-vertical-spacing" style="--vertical-spacing-internal: 1em;">
    <tedi:card-button type="button">
        <tedi:card>
            <tedi:card-content class="flex align-items-center gap-3">
                <tedi:text as="p" modifiers="bold">8:30</tedi:text>
                <div class="flex-fill">
                    <tedi:text as="p" modifiers="bold">Kardioloog</tedi:text>
                    <tedi:text as="p" modifiers="small" color="secondary">Valdkond</tedi:text>
                </div>
                <tedi:text as="span" color="brand" class="flex align-items-center gap-2">
                    <span aria-hidden="true">Broneerima</span>
                    <tedi:icon name="arrow_right_alt" color="brand" label="Broneerima" />
                </tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:card-button>
    <tedi:card-button type="button">
        <tedi:card>
            <tedi:card-content class="flex align-items-center gap-3">
                <tedi:text as="p" modifiers="bold">8:30</tedi:text>
                <tedi:text as="p" modifiers="bold" class="flex-fill">Kardioloog</tedi:text>
                <tedi:text as="span" color="brand" class="flex align-items-center gap-2">
                    <span aria-hidden="true">Broneerima</span>
                    <tedi:icon name="arrow_right_alt" color="brand" label="Broneerima" />
                </tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:card-button>
    <tedi:card-button type="button">
        <tedi:card>
            <tedi:card-content class="flex align-items-center gap-3">
                <tedi:icon name="monitor_heart" color="secondary" />
                <div class="flex-fill">
                    <tedi:text as="p" modifiers="bold">Kardioloog</tedi:text>
                    <tedi:text as="p" modifiers="small" color="secondary">Valdkond</tedi:text>
                </div>
                <tedi:text as="span" color="brand" class="flex align-items-center gap-2">
                    <span aria-hidden="true">Broneerima</span>
                    <tedi:icon name="arrow_right_alt" color="brand" label="Broneerima" />
                </tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:card-button>
    <tedi:card-button type="button">
        <tedi:card>
            <tedi:card-content class="flex align-items-center gap-3">
                <tedi:icon name="monitor_heart" color="secondary" />
                <tedi:text as="p" modifiers="bold" class="flex-fill">Kardioloog</tedi:text>
                <tedi:text as="p" modifiers="small" color="secondary">Valdkond</tedi:text>
                <tedi:text as="span" color="brand" class="flex align-items-center gap-2">
                    <span aria-hidden="true">Broneerima</span>
                    <tedi:icon name="arrow_right_alt" color="brand" label="Broneerima" />
                </tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:card-button>
    <tedi:card-button type="button">
        <tedi:card>
            <tedi:card-row>
                <tedi:card-icon>
                    <tedi:icon name="monitor_heart" />
                </tedi:card-icon>
                <tedi:separator axis="vertical" size="auto" />
                <tedi:card-content class="flex align-items-center gap-3">
                    <div class="flex-fill">
                        <tedi:text as="p" modifiers="bold">Kardioloog</tedi:text>
                        <tedi:text as="p" modifiers="small" color="secondary">Valdkond</tedi:text>
                    </div>
                    <tedi:text as="span" color="brand" class="flex align-items-center gap-2">
                        <span aria-hidden="true">Broneerima</span>
                        <tedi:icon name="arrow_right_alt" color="brand" label="Broneerima" />
                    </tedi:text>
                </tedi:card-content>
            </tedi:card-row>
        </tedi:card>
    </tedi:card-button>
    <tedi:card-button type="button">
        <tedi:card>
            <tedi:card-content class="flex align-items-center gap-3">
                <div class="flex-fill">
                    <tedi:text as="p" modifiers="bold">Kardioloog</tedi:text>
                    <tedi:text as="p" modifiers="small" color="secondary">Valdkond</tedi:text>
                </div>
                <tedi:text as="span" color="brand" class="flex align-items-center gap-2">
                    <span aria-hidden="true">Broneerima</span>
                    <tedi:icon name="arrow_right_alt" color="brand" label="Broneerima" />
                </tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:card-button>
    <tedi:card-button type="button">
        <tedi:card>
            <tedi:card-content class="flex align-items-center gap-3">
                <div class="flex-fill">
                    <tedi:text as="p" modifiers="bold">Kardioloog</tedi:text>
                    <tedi:status-badge color="success" text="Kindlustatud | Tervisekassa" />
                </div>
                <tedi:text as="span" color="brand" class="flex align-items-center gap-2">
                    <span aria-hidden="true">Broneerima</span>
                    <tedi:icon name="arrow_right_alt" color="brand" label="Broneerima" />
                </tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:card-button>
    <tedi:card-button type="button">
        <tedi:card>
            <tedi:card-content class="flex align-items-center gap-3">
                <tedi:icon name="monitor_heart" color="secondary" />
                <div class="flex-fill">
                    <tedi:text as="p" modifiers="bold">Perearst</tedi:text>
                    <tedi:text as="p" modifiers="small" color="secondary">Dr. Mari Maasikas</tedi:text>
                </div>
                <tedi:status-badge color="success" text="Aktiivne" />
                <tedi:icon name="arrow_right_alt" color="secondary" />
            </tedi:card-content>
        </tedi:card>
    </tedi:card-button>
    <tedi:card-button type="button">
        <tedi:card>
            <tedi:card-content class="flex align-items-center gap-3">
                <tedi:text as="p" modifiers="bold" class="flex-fill">Üldandmed</tedi:text>
                <tedi:icon name="arrow_right_alt" color="secondary" />
            </tedi:card-content>
        </tedi:card>
    </tedi:card-button>
    <tedi:card-button type="button">
        <tedi:card>
            <tedi:card-content class="flex align-items-center justify-content-between gap-3">
                <div>
                    <tedi:text as="h4" color="brand">Minu andmed</tedi:text>
                    <tedi:text as="p" modifiers="small" color="secondary">Isikuandmed ja sinu perearstiga seotud info.</tedi:text>
                </div>
                <tedi:icon name="arrow_right_alt" color="secondary" />
            </tedi:card-content>
        </tedi:card>
    </tedi:card-button>
</div>
