<div class="gx-sec">
    <h2>Collapse</h2>
    <p>Port of <code>buttons/collapse</code> and <code>buttons/collapse-button</code>.
        The root is the literal <code>&lt;tedi-collapse&gt;</code> element and the
        toggle is <code>&lt;button tedi-collapse-button&gt;</code>, matching the
        Angular element/attribute selectors.</p>

    <div class="gx-case">
        <div class="gx-case__label">default (closed) / defaultOpen (tedi-collapse--open)</div>
        <div class="gx-case__demo">
            <tedi:collapse open-text="Ava" close-text="Sulge">
                Lorem ipsum dolor sit amet consectetur adipisicing elit.
            </tedi:collapse>
            <tedi:collapse :default-open="true" open-text="Ava" close-text="Sulge">
                Lorem ipsum dolor sit amet consectetur adipisicing elit.
            </tedi:collapse>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">size: default / small</div>
        <div class="gx-case__demo">
            <tedi:collapse size="default" open-text="Ava" close-text="Sulge">Sisu</tedi:collapse>
            <tedi:collapse size="small" open-text="Ava" close-text="Sulge">Sisu</tedi:collapse>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">hideCollapseText + arrowType: default (neutral) / secondary</div>
        <div class="gx-case__demo">
            <tedi:collapse :hide-collapse-text="true" arrow-type="default">Sisu</tedi:collapse>
            <tedi:collapse :hide-collapse-text="true" arrow-type="secondary">Sisu</tedi:collapse>
            <tedi:collapse :hide-collapse-text="true" arrow-type="default" size="small">Sisu</tedi:collapse>
            <tedi:collapse :hide-collapse-text="true" arrow-type="secondary" size="small">Sisu</tedi:collapse>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">inverted (tedi-collapse--inverted) — ignored for arrowType "secondary"</div>
        <div class="gx-case__demo" style="padding:1rem;background:var(--general-icon-background-brand-primary,#1c3f66)">
            <tedi:collapse :inverted="true" open-text="Ava" close-text="Sulge">Sisu</tedi:collapse>
            <tedi:collapse :inverted="true" :hide-collapse-text="true" arrow-type="default">Sisu</tedi:collapse>
            <tedi:collapse :inverted="true" :hide-collapse-text="true" arrow-type="secondary">Sisu</tedi:collapse>
        </div>
    </div>

    <h2>Collapse Button (standalone)</h2>

    <div class="gx-case">
        <div class="gx-case__label">open: false / true (tedi-collapse-button--open)</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:collapse-button open-text="Ava" close-text="Sulge" />
            <tedi:collapse-button :open="true" open-text="Ava" close-text="Sulge" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">size: default / small (tedi-collapse-button--small)</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:collapse-button size="default" open-text="Ava" />
            <tedi:collapse-button size="small" open-text="Ava" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">hideText + arrowType (icon-only / neutral / secondary)</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:collapse-button :hide-text="true" arrow-type="default" aria-label="Ava" />
            <tedi:collapse-button :hide-text="true" arrow-type="secondary" aria-label="Ava" />
            <tedi:collapse-button :hide-text="true" arrow-type="default" size="small" aria-label="Ava" />
            <tedi:collapse-button :hide-text="true" arrow-type="secondary" size="small" aria-label="Ava" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">underline: false (tedi-collapse-button--no-underline)</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:collapse-button :underline="false" open-text="Ava" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">inverted (tedi-collapse-button--inverted)</div>
        <div class="gx-case__demo gx-case__demo--row" style="padding:1rem;background:var(--general-icon-background-brand-primary,#1c3f66)">
            <tedi:collapse-button :inverted="true" open-text="Ava" />
            <tedi:collapse-button :inverted="true" :hide-text="true" arrow-type="default" aria-label="Ava" />
            <tedi:collapse-button :inverted="true" :hide-text="true" arrow-type="secondary" aria-label="Ava" />
        </div>
    </div>
</div>
