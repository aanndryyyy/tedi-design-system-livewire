<div class="gx-sec">
    <h2>Alert</h2>
    <p>Port of <code>notifications/alert</code>. <code>closeDelay</code> and the
        <code>[tedi-alert-action]</code>/close-button precedence are CSS-only
        (<code>:has()</code>) and need no Blade logic.</p>

    <div class="gx-case">
        <div class="gx-case__label">type: info / success / warning / danger</div>
        <div class="gx-case__demo">
            <tedi:alert type="info" title="Info" icon="info">Info-tüüpi teavitus.</tedi:alert>
            <br>
            <tedi:alert type="success" title="Õnnestus" icon="check_circle">Andmed salvestati.</tedi:alert>
            <br>
            <tedi:alert type="warning" title="Hoiatus" icon="warning">Kontrolli sisestatud andmeid.</tedi:alert>
            <br>
            <tedi:alert type="danger" title="Viga" icon="error">Andmete salvestamine ebaõnnestus.</tedi:alert>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">without title (content-only)</div>
        <div class="gx-case__demo">
            <tedi:alert type="info" icon="info">Ilma pealkirjata teavitus.</tedi:alert>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">size: default / small</div>
        <div class="gx-case__demo">
            <tedi:alert size="default" type="info" title="Tavaline">Vaikimisi suurus.</tedi:alert>
            <br>
            <tedi:alert size="small" type="info" title="Väike">Väiksem paddingu ja tekstiga variant.</tedi:alert>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">variant: default / global / noSideBorders</div>
        <div class="gx-case__demo">
            <tedi:alert variant="default" type="info" title="Default">Tavaline raamiga variant.</tedi:alert>
            <br>
            <tedi:alert variant="global" type="warning" title="Global">Täislaiuses, raamideta.</tedi:alert>
            <br>
            <tedi:alert variant="noSideBorders" type="danger" title="No side borders">Ilma külgraamideta.</tedi:alert>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">closable — closeAttributes binds wire:click</div>
        <div class="gx-case__demo">
            <tedi:alert
                type="success"
                title="Suletav teavitus"
                icon="check_circle"
                :show-close="true"
                :close-attributes="['wire:click' => 'dismissAlert']"
            >
                Sulgemisnupp on seotud tarbija enda wire:click käsitlejaga.
            </tedi:alert>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">titleElement: h3 / div</div>
        <div class="gx-case__demo">
            <tedi:alert title="H3 pealkiri" title-element="h3" type="info">Pealkiri renderdub h3 elemendina.</tedi:alert>
            <br>
            <tedi:alert title="Div pealkiri" title-element="div" type="info">Pealkiri renderdub div elemendina.</tedi:alert>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Toast</h2>
    <p>Port of <code>notifications/toast</code>. Renders the toast's visual
        markup/classes only, statically positioned — CDK Overlay positioning
        (top-left/top-right/bottom-left/bottom-right, slide animations) is out
        of scope per CONVENTIONS.md §7. Wrap it in your own fixed-position
        container to place it.</p>

    <div class="gx-case">
        <div class="gx-case__label">type: info / success / warning / danger</div>
        <div class="gx-case__demo">
            <tedi:toast type="info" title="Teave" icon="info">See on infoteade.</tedi:toast>
            <br>
            <tedi:toast type="success" title="Salvestatud" icon="check_circle">Muudatused salvestati edukalt.</tedi:toast>
            <br>
            <tedi:toast type="warning" title="Hoiatus" icon="warning">Osa andmeid vajab ülevaatamist.</tedi:toast>
            <br>
            <tedi:toast type="danger" title="Viga" icon="error">Toimingut ei õnnestunud lõpetada.</tedi:toast>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">showProgressBar + duration</div>
        <div class="gx-case__demo">
            <tedi:toast type="success" title="Kaob 4 sekundi pärast" :show-progress-bar="true" :duration="4000">
                Progress-riba animeerub duration'i jooksul.
            </tedi:toast>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">paused progress bar</div>
        <div class="gx-case__demo">
            <tedi:toast type="warning" title="Peatatud" :show-progress-bar="true" :paused="true">
                Progress-riba animatsioon on peatatud (hover-simulatsioon).
            </tedi:toast>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Status badge</h2>
    <p>Port of <code>tags/status-badge</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">color: neutral / brand / accent / success / danger / warning / transparent</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:status-badge color="neutral" text="Neutral" />
            <tedi:status-badge color="brand" text="Brand" />
            <tedi:status-badge color="accent" text="Accent" />
            <tedi:status-badge color="success" text="Success" />
            <tedi:status-badge color="danger" text="Danger" />
            <tedi:status-badge color="warning" text="Warning" />
            <tedi:status-badge color="transparent" text="Transparent" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">variant: filled / filled-bordered / bordered</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:status-badge variant="filled" color="brand" text="Filled" />
            <tedi:status-badge variant="filled-bordered" color="brand" text="Filled bordered" />
            <tedi:status-badge variant="bordered" color="brand" text="Bordered" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">size: default / large</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:status-badge size="default" color="accent" text="Default" />
            <tedi:status-badge size="large" color="accent" text="Large" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">status indicator dot: danger / success / warning / inactive</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:status-badge color="neutral" text="Danger" status="danger" />
            <tedi:status-badge color="neutral" text="Success" status="success" />
            <tedi:status-badge color="neutral" text="Warning" status="warning" />
            <tedi:status-badge color="neutral" text="Inactive" status="inactive" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">icon, icon-only, and title (renders as &lt;abbr&gt;)</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:status-badge color="brand" icon="star" text="Icon + text" />
            <tedi:status-badge color="brand" icon="star" />
            <tedi:status-badge color="warning" text="KKK" title="Korduma kippuvad küsimused" />
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Status indicator</h2>
    <p>Port of <code>tags/status-indicator</code>. A decorative dot; Angular's
        template is empty (host-classes only).</p>

    <div class="gx-case">
        <div class="gx-case__label">type: success / danger / warning / inactive</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:status-indicator type="success" />
            <tedi:status-indicator type="danger" />
            <tedi:status-indicator type="warning" />
            <tedi:status-indicator type="inactive" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">size: sm / lg, hasBorder</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:status-indicator type="success" size="sm" />
            <tedi:status-indicator type="success" size="lg" />
            <tedi:status-indicator type="success" size="lg" :has-border="true" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">position: top-right (relative to a parent)</div>
        <div class="gx-case__demo gx-case__demo--row">
            <span style="position: relative; display: inline-block; width: 32px; height: 32px; background: var(--tedi-neutral-200, #ccc); border-radius: 50%;">
                <tedi:status-indicator type="danger" position="top-right" :has-border="true" />
            </span>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">label — accessible (role="img") vs decorative (aria-hidden)</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:status-indicator type="success" label="Aktiivne" />
            <tedi:status-indicator type="inactive" />
        </div>
    </div>
</div>
