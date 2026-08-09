<div class="gx-sec">
    <h2>Tooltip</h2>
    <p>Port of <code>overlay/tooltip</code> (+ trigger, content) and
        <code>overlay/info-tooltip</code>. Positioning comes from
        <code>tediOverlay</code> (CONVENTIONS.md §11); the panel stays in the DOM
        with its real class list while closed (<code>x-show</code>, §8), which is
        what makes it harvestable here.</p>

    <div class="gx-case">
        <div class="gx-case__label">maxWidth: none / small / medium / large</div>
        <div class="gx-case__demo gx-case__demo--row">
            @foreach (['none', 'small', 'medium', 'large'] as $maxWidth)
                <tedi:tooltip>
                    <tedi:tooltip-trigger :text="true">{{ ucfirst($maxWidth) }}</tedi:tooltip-trigger>
                    <tedi:tooltip-content :max-width="$maxWidth">
                        This is an example for {{ $maxWidth }} tooltip. The quick brown fox jumps over the lazy dog.
                    </tedi:tooltip-content>
                </tedi:tooltip>
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">position: every OverlayPosition</div>
        <div class="gx-case__demo gx-case__demo--row">
            @foreach ([
                'auto', 'auto-start', 'auto-end',
                'top', 'top-start', 'top-end',
                'bottom', 'bottom-start', 'bottom-end',
                'right', 'right-start', 'right-end',
                'left', 'left-start', 'left-end',
            ] as $position)
                <tedi:tooltip :position="$position">
                    <tedi:tooltip-trigger :text="true">{{ ucfirst($position) }}</tedi:tooltip-trigger>
                    <tedi:tooltip-content>Tooltip content</tedi:tooltip-content>
                </tedi:tooltip>
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">openWith: hover / click / both / none — only <code>click</code> is clickable</div>
        <div class="gx-case__demo gx-case__demo--row">
            @foreach (['hover', 'click', 'both', 'none'] as $openWith)
                <tedi:tooltip :open-with="$openWith">
                    <tedi:tooltip-trigger :text="true">{{ ucfirst($openWith) }}</tedi:tooltip-trigger>
                    <tedi:tooltip-content>Tooltip content</tedi:tooltip-content>
                </tedi:tooltip>
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">element trigger — no synthesized span, no focus ring</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:tooltip>
                <tedi:tooltip-trigger>
                    <tedi:button variant="secondary">Button trigger</tedi:button>
                </tedi:tooltip-trigger>
                <tedi:tooltip-content>Natively focusable triggers keep their own focus ring.</tedi:tooltip-content>
            </tedi:tooltip>

            <tedi:tooltip>
                <tedi:tooltip-trigger>
                    <span class="tedi-tooltip-trigger--focus" tabindex="0">Span trigger</span>
                </tedi:tooltip-trigger>
                <tedi:tooltip-content>A non-focusable element carries the fallback focus ring itself.</tedi:tooltip-content>
            </tedi:tooltip>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">description — sr-only text + aria-describedby (CONVENTIONS.md §5)</div>
        <div class="gx-case__demo">
            <tedi:tooltip description="Sisestage linn, kus te praegu elate." description-id="matrix-tooltip-city">
                <tedi:tooltip-trigger :text="true" described-by="matrix-tooltip-city">Linn</tedi:tooltip-trigger>
                <tedi:tooltip-content>Sisestage linn, kus te praegu elate.</tedi:tooltip-content>
            </tedi:tooltip>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Info tooltip</h2>
    <p>Port of <code>overlay/info-tooltip</code>: composition of tooltip +
        trigger + content around <code>&lt;tedi:info-button&gt;</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">color: primary / inverted</div>
        <div class="gx-case__demo gx-case__demo--row" style="gap:1rem;padding:1rem;background:var(--general-icon-background-brand-primary,#1c3f66)">
            <tedi:info-tooltip>Seda välja kasutatakse teie isikusamasuse tuvastamiseks.</tedi:info-tooltip>
            <tedi:info-tooltip color="inverted">Seda välja kasutatakse teie isikusamasuse tuvastamiseks.</tedi:info-tooltip>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">maxWidth: none / small / medium / large</div>
        <div class="gx-case__demo gx-case__demo--row">
            @foreach (['none', 'small', 'medium', 'large'] as $maxWidth)
                <tedi:info-tooltip :max-width="$maxWidth">
                    Info tooltip with {{ $maxWidth }} max width.
                </tedi:info-tooltip>
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">inside a label row, with an sr-only description</div>
        <div class="gx-case__demo">
            <tedi:label-row>
                <tedi:form.label for="city" :required="true">Linn</tedi:form.label>
                <tedi:info-tooltip description="Sisestage linn, kus te praegu elate.">
                    Sisestage linn, kus te praegu elate.
                </tedi:info-tooltip>
            </tedi:label-row>
        </div>
    </div>
</div>
