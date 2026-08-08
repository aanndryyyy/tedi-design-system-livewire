<div class="gx-sec">
    <h2>Link</h2>
    <p>Port of <code>navigation/link</code>. Angular's <code>[tedi-link]</code> attribute
        directive maps to a <code>href</code>-driven <code>&lt;a&gt;</code>/<code>&lt;button&gt;</code>
        polymorphic root, like <code>&lt;tedi:button&gt;</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">variant: default / inverted</div>
        <div class="gx-case__demo gx-case__demo--row" style="gap:1rem;padding:1rem;background:var(--general-icon-background-brand-primary,#1c3f66)">
            <tedi:link href="#">Default link</tedi:link>
            <tedi:link href="#" variant="inverted">Inverted link</tedi:link>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">size: default / small</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:link href="#">Default size</tedi:link>
            <tedi:link href="#" size="small">Small size</tedi:link>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">underline: true / false</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:link href="#">Underlined (default)</tedi:link>
            <tedi:link href="#" :underline="false">No underline</tedi:link>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">icon-start / icon-end / as button / target _blank</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:link href="#" icon-end="arrow_forward">Continue</tedi:link>
            <tedi:link href="#" icon-start="arrow_back">Back</tedi:link>
            <tedi:link>As a button</tedi:link>
            <tedi:link href="https://tedi.ee" target="_blank">Opens in a new tab</tedi:link>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Tabs</h2>
    <p>Port of <code>navigation/tabs</code> (<code>tedi-tabs</code>,
        <code>tedi-tabs-list</code>, <code>tedi-tabs-trigger</code>, <code>tedi-tabs-content</code>).
        Tab switching runs on minimal inline Alpine (<code>x-data</code>/<code>x-on</code>)
        layered on top of markup/classes that match Angular exactly — the overflow
        "More" dropdown and horizontal-scroll fade (both runtime DOM measurement) are
        not ported, see the Blade comment in <code>tabs/list.blade.php</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">uncontrolled, default-value="overview"</div>
        <div class="gx-case__demo">
            <tedi:tabs default-value="overview">
                <tedi:tabs.list aria-label="Demo tabs">
                    <tedi:tabs.trigger id="overview">Overview</tedi:tabs.trigger>
                    <tedi:tabs.trigger id="details" icon="info">Details</tedi:tabs.trigger>
                    <tedi:tabs.trigger id="disabled" :disabled="true">Disabled</tedi:tabs.trigger>
                </tedi:tabs.list>
                <tedi:tabs.content id="overview">Overview panel content.</tedi:tabs.content>
                <tedi:tabs.content id="details">Details panel content.</tedi:tabs.content>
                <tedi:tabs.content id="disabled">You should never see this.</tedi:tabs.content>
            </tedi:tabs>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">anchor tab (href, navigates instead of switching in-page)</div>
        <div class="gx-case__demo">
            <tedi:tabs default-value="page-1">
                <tedi:tabs.list aria-label="Anchor tabs">
                    <tedi:tabs.trigger id="page-1" href="#page-1">Page 1</tedi:tabs.trigger>
                    <tedi:tabs.trigger id="page-2" href="#page-2">Page 2</tedi:tabs.trigger>
                </tedi:tabs.list>
            </tedi:tabs>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">overflow-mode: scroll (dropdown mode's "More" menu needs unported overlay positioning, see comment)</div>
        <div class="gx-case__demo">
            <tedi:tabs default-value="one">
                <tedi:tabs.list aria-label="Scrollable tabs" overflow-mode="scroll" dropdown-label="Rohkem">
                    <tedi:tabs.trigger id="one">One</tedi:tabs.trigger>
                    <tedi:tabs.trigger id="two">Two</tedi:tabs.trigger>
                    <tedi:tabs.trigger id="three">Three</tedi:tabs.trigger>
                    <tedi:tabs.trigger id="four">Four</tedi:tabs.trigger>
                    <tedi:tabs.trigger id="five">Five</tedi:tabs.trigger>
                </tedi:tabs.list>
            </tedi:tabs>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Pagination</h2>
    <p>Port of <code>navigation/pagination</code>. The mobile compact picker and its
        page-jump/page-size modals rely on <code>BreakpointService</code> + overlay
        positioning (both out of scope, see the Blade comment) — this port always
        renders the desktop pager and a native <code>&lt;select&gt;</code>. Page
        links are real <code>&lt;a href&gt;</code>s built from the <code>page-url</code>
        closure prop, so paging works without JS.</p>

    <div class="gx-case">
        <div class="gx-case__label">basic, with results label and page-size select</div>
        <div class="gx-case__demo">
            <tedi:pagination
                :page-count="12"
                :page="4"
                :total-items="238"
                :page-size="20"
                :page-size-options="[10, 20, 50]"
                :page-url="fn (int $p) => '?page='.$p"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">near boundary (ellipsis collapses to the far side)</div>
        <div class="gx-case__demo">
            <tedi:pagination :page-count="20" :page="1" :page-url="fn (int $p) => '?page='.$p" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">background: transparent, divider: none, arrow labels shown</div>
        <div class="gx-case__demo">
            <tedi:pagination
                :page-count="6"
                :page="3"
                background="transparent"
                divider-position="none"
                :show-arrow-labels="true"
                :page-url="fn (int $p) => '?page='.$p"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">single page — pager hidden (tedi-pagination--no-pager)</div>
        <div class="gx-case__demo">
            <tedi:pagination :page-count="1" :total-items="7" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">custom results slot (tediPaginationResults)</div>
        <div class="gx-case__demo">
            <tedi:pagination :page-count="4" :page="2" :page-url="fn (int $p) => '?page='.$p">
                <x-slot:results>1000+ results</x-slot:results>
            </tedi:pagination>
        </div>
    </div>
</div>
