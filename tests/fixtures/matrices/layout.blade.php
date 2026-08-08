@php
    $languages = ['et' => 'EST', 'en' => 'ENG', 'ru' => 'RUS'];
    $representatives = [
        ['id' => '1', 'name' => 'Jaan Tamm', 'icon' => 'person', 'description' => 'Private person'],
        ['id' => '2', 'name' => 'Acme OÜ', 'icon' => 'business', 'description' => 'Organization'],
        ['id' => '3', 'name' => 'Kati Saar', 'icon' => 'person'],
    ];
    $singleRepresentative = [['id' => '1', 'name' => 'Jaan Tamm']];
@endphp

<div class="gx-sec">
    <h2>Progress Bar</h2>
    <p>Port of <code>loader/progress-bar</code>. Breakpoint overrides (<code>xs</code>-<code>xxl</code>) are not ported. Project a <code>&lt;tedi:feedback-text&gt;</code> into the default slot for a hint/error row.</p>

    <div class="gx-case">
        <div class="gx-case__label">size: default | small</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:1rem;align-items:stretch;width:100%">
            <tedi:progress-bar :value="60" aria-label="Progress" />
            <tedi:progress-bar :value="60" size="small" aria-label="Progress" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">label position: top | horizontal</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:1rem;align-items:stretch;width:100%">
            <tedi:progress-bar :value="40" label="Upload" label-position="top" :required="true" />
            <tedi:progress-bar :value="40" label="Upload" label-position="horizontal" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">value position: horizontal | bottom | hidden | custom label</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:1rem;align-items:stretch;width:100%">
            <tedi:progress-bar :value="40" value-position="horizontal" />
            <tedi:progress-bar :value="40" value-position="bottom" />
            <tedi:progress-bar :value="40" :show-value="false" />
            <tedi:progress-bar :value="20" value-label="1 / 5" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">with hint / error (project tedi:feedback-text)</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:1rem;align-items:stretch;width:100%">
            <tedi:progress-bar :value="30" label="Upload" value-position="bottom">
                <tedi:feedback-text text="Uploading..." type="hint" />
            </tedi:progress-bar>
            <tedi:progress-bar :value="30" label="Upload" value-position="bottom">
                <tedi:feedback-text text="Upload failed, file is too large" type="error" />
            </tedi:progress-bar>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Footer</h2>
    <p>Port of <code>layout/footer</code>. Named slots <code>start</code> / <code>end</code> / <code>bottom</code> map to Angular's <code>[tedi-footer-start]</code> / <code>[tedi-footer-end]</code> / <code>tedi-footer-bottom</code> projections. <code>mobileLayout</code> (BreakpointService) is not ported.</p>

    <div class="gx-case">
        <div class="gx-case__label">body with sections, side logo, and bottom row</div>
        <div class="gx-case__demo" style="width:100%;background:#1c1c28">
            <tedi:footer>
                <tedi:footer.body>
                    <tedi:footer.section heading="Product" icon="account_circle">
                        <a href="#" class="tedi-link tedi-link--inverted">Features</a>
                        <a href="#" class="tedi-link tedi-link--inverted">Pricing</a>
                    </tedi:footer.section>
                    <tedi:footer.section heading="Company" icon="business">
                        <a href="#" class="tedi-link tedi-link--inverted">About</a>
                        <a href="#" class="tedi-link tedi-link--inverted">Careers</a>
                    </tedi:footer.section>
                    <tedi:footer.section heading="Collapsible" :collapse="true">
                        <a href="#" class="tedi-link tedi-link--inverted">Docs</a>
                        <a href="#" class="tedi-link tedi-link--inverted">Support</a>
                    </tedi:footer.section>
                </tedi:footer.body>

                <x-slot:end>
                    <tedi:footer.side placement="end" position="center">Logo</tedi:footer.side>
                </x-slot:end>

                <x-slot:bottom>
                    <tedi:footer.bottom>
                        <a href="#" class="tedi-link tedi-link--inverted">Privacy</a>
                        <a href="#" class="tedi-link tedi-link--inverted">Terms</a>
                    </tedi:footer.bottom>
                </x-slot:bottom>
            </tedi:footer>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Header</h2>
    <p>Port of <code>layout/header</code> and its sub-components. Breakpoint-gated behaviour (mobile/desktop split, popover positioning) is not ported per CONVENTIONS.md §7/§7.3 — see each sub-component's doc comment for its specific divergence.</p>

    <div class="gx-case">
        <div class="gx-case__label">full header: toggle, logo, content, actions</div>
        <div class="gx-case__demo" style="width:100%;padding:0">
            <tedi:header>
                <x-slot:toggle>
                    <tedi:header.toggle />
                </x-slot:toggle>

                <tedi:header.logo href="/">
                    <strong>TEDI</strong>
                </tedi:header.logo>

                <tedi:header.content alignment="center">
                    <a href="#" class="tedi-link">Home</a>
                    <a href="#" class="tedi-link">Services</a>
                    <a href="#" class="tedi-link">Contact</a>
                </tedi:header.content>

                <tedi:header.actions>
                    <tedi:header.language :languages="$languages" current-language="et" />
                    <tedi:separator axis="vertical" />
                    <tedi:header.role
                        label="I represent:"
                        :representatives="$representatives"
                        :current-representative="$representatives[0]"
                    />
                    <tedi:separator axis="vertical" />
                    <tedi:header.profile :show-label="true" />
                    <tedi:header.logout href="/logout" />
                </tedi:header.actions>
            </tedi:header>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">header top + bottom bars</div>
        <div class="gx-case__demo" style="width:100%;padding:0">
            <tedi:header>
                <x-slot:top>
                    <tedi:header.top alignment="space-between">
                        <span>Announcement bar</span>
                        <a href="#" class="tedi-link">Details</a>
                    </tedi:header.top>
                </x-slot:top>

                <tedi:header.logo href="/">
                    <strong>TEDI</strong>
                </tedi:header.logo>

                <tedi:header.actions>
                    <tedi:header.login href="/login" />
                </tedi:header.actions>

                <x-slot:bottom>
                    <tedi:header.bottom>
                        <tedi:header.search>
                            <input type="search" placeholder="Search..." />
                        </tedi:header.search>
                    </tedi:header.bottom>
                </x-slot:bottom>
            </tedi:header>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">login / logout: default | small (mobile-button variant)</div>
        <div class="gx-case__demo gx-case__demo--row" style="gap:1rem">
            <tedi:header.login href="/login" />
            <tedi:header.login size="small" href="/login" />
            <tedi:header.logout href="/logout" />
            <tedi:header.logout size="small" href="/logout" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">header.role: with switch | single representative (no switch)</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:1rem;align-items:flex-start;width:100%">
            <tedi:header.role
                label="I represent:"
                :representatives="$representatives"
                :current-representative="$representatives[0]"
            />
            <tedi:header.role
                :representatives="$singleRepresentative"
                :current-representative="$singleRepresentative[0]"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">header.search: inline | mobile modal (:mobile="true")</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:1rem;align-items:flex-start;width:100%">
            <tedi:header.search>
                <input type="search" placeholder="Search..." />
            </tedi:header.search>
            <tedi:header.search :mobile="true">
                <input type="search" placeholder="Search..." />
            </tedi:header.search>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">header.mobile-button: default | selected | disabled</div>
        <div class="gx-case__demo gx-case__demo--row" style="gap:1rem">
            <tedi:header.mobile-button icon="menu" label="Menu" />
            <tedi:header.mobile-button icon="notifications" label="Alerts" :selected="true" />
            <tedi:header.mobile-button icon="search" label="Search" href="/search" :disabled="true" />
        </div>
    </div>
</div>
