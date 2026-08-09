<div class="gx-sec">
    <h2>Attachment</h2>
    <p>Port of <code>helpers/attachment</code>. Project a <code>&lt;tedi:progress-bar&gt;</code> into the <code>progress</code> slot and your own icon-only buttons into the <code>actions</code> slot.</p>

    <div class="gx-case">
        <div class="gx-case__label">base | with file size | with icon</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.75rem;align-items:stretch;width:100%">
            <tedi:attachment name="contract.pdf" />
            <tedi:attachment name="contract.pdf" file-size="0.9 MB" />
            <tedi:attachment name="contract.pdf" file-size="0.9 MB" icon="picture_as_pdf" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">with progress | error (feedback text) | invalid (no feedback text)</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.75rem;align-items:stretch;width:100%">
            <tedi:attachment name="uploading.pdf" file-size="2.1 MB">
                <x-slot:progress>
                    <tedi:progress-bar :value="65" />
                </x-slot:progress>
            </tedi:attachment>
            <tedi:attachment name="too-large.pdf" file-size="12 MB" error="File is too large" />
            <tedi:attachment name="rejected.pdf" file-size="12 MB" :invalid="true" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">with actions (icon-only, flush) | padded actions (labeled buttons) | vertical direction</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.75rem;align-items:stretch;width:100%">
            <tedi:attachment name="contract.pdf" file-size="0.9 MB">
                <x-slot:actions>
                    <tedi:button variant="neutral" size="small" icon-only aria-label="Download" icon-start="download" />
                    <tedi:button variant="neutral" size="small" icon-only aria-label="Delete" icon-start="delete" />
                </x-slot:actions>
            </tedi:attachment>
            <tedi:attachment name="contract.pdf" file-size="0.9 MB" :padded="true">
                <x-slot:actions>
                    <tedi:button variant="neutral" size="small">Replace</tedi:button>
                    <tedi:button variant="neutral" size="small">Delete</tedi:button>
                </x-slot:actions>
            </tedi:attachment>
            <tedi:attachment name="contract.pdf" file-size="0.9 MB" direction="vertical">
                <x-slot:actions>
                    <tedi:button variant="neutral" size="small" icon-only aria-label="Delete" icon-start="delete" />
                </x-slot:actions>
            </tedi:attachment>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Empty State</h2>
    <p>Port of <code>helpers/empty-state</code>. Description is the default slot; actions go in the named <code>actions</code> slot.</p>

    <div class="gx-case">
        <div class="gx-case__label">type: separate | attached | inside</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.75rem;align-items:stretch;width:100%">
            <tedi:empty-state type="separate" heading="No results">Try adjusting your filters.</tedi:empty-state>
            <tedi:empty-state type="attached">No rows to show.</tedi:empty-state>
            <tedi:empty-state type="inside">Nothing here yet.</tedi:empty-state>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">size: default | small</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.75rem;align-items:stretch;width:100%">
            <tedi:empty-state size="default">Default padding.</tedi:empty-state>
            <tedi:empty-state size="small">Small padding.</tedi:empty-state>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">custom icon | no icon | with actions</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.75rem;align-items:stretch;width:100%">
            <tedi:empty-state icon="search_off" heading="No matches">Nothing matched your search.</tedi:empty-state>
            <tedi:empty-state icon="">No icon here.</tedi:empty-state>
            <tedi:empty-state heading="No projects yet">Create your first project to get started.
                <x-slot:actions>
                    <tedi:button variant="primary" icon-start="add">New project</tedi:button>
                </x-slot:actions>
            </tedi:empty-state>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Grid (Row / Col)</h2>
    <p>Port of <code>helpers/grid</code> — Angular has no single "grid" component, only Row + Col. Breakpoint props (xs–xxl) are not ported (CONVENTIONS.md §7).</p>

    <div class="gx-case">
        <div class="gx-case__label">cols: 3 | 4 | 6</div>
        <div class="gx-case__demo" style="width:100%">
            <tedi:row :cols="3" :gap="2">
                <tedi:col width="1" style="background:#e6e8ea;padding:.5rem">1</tedi:col>
                <tedi:col width="1" style="background:#e6e8ea;padding:.5rem">2</tedi:col>
                <tedi:col width="1" style="background:#e6e8ea;padding:.5rem">3</tedi:col>
            </tedi:row>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">cols: auto (min-col-width)</div>
        <div class="gx-case__demo" style="width:100%">
            <tedi:row cols="auto" :min-col-width="150" :gap="2">
                <tedi:col style="background:#e6e8ea;padding:.5rem">A</tedi:col>
                <tedi:col style="background:#e6e8ea;padding:.5rem">B</tedi:col>
                <tedi:col style="background:#e6e8ea;padding:.5rem">C</tedi:col>
            </tedi:row>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">col width: 4 + 8 | justify-items / align-items</div>
        <div class="gx-case__demo" style="width:100%">
            <tedi:row :cols="12" :gap="2">
                <tedi:col :width="4" style="background:#e6e8ea;padding:.5rem">width 4</tedi:col>
                <tedi:col :width="8" style="background:#e6e8ea;padding:.5rem">width 8</tedi:col>
            </tedi:row>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Scroll Fade</h2>
    <p>Port of <code>helpers/scroll-fade</code>. Fade classes toggle via minimal inline Alpine tracking the inner container's scroll position (CONVENTIONS.md §8).</p>

    <div class="gx-case">
        <div class="gx-case__label">both | top only | bottom only</div>
        <div class="gx-case__demo" style="display:flex;gap:1rem;align-items:flex-start">
            <tedi:scroll-fade fade-position="both" style="max-height:120px;width:200px">
                <p>Line one</p><p>Line two</p><p>Line three</p><p>Line four</p><p>Line five</p><p>Line six</p>
            </tedi:scroll-fade>
            <tedi:scroll-fade fade-position="top" style="max-height:120px;width:200px">
                <p>Line one</p><p>Line two</p><p>Line three</p><p>Line four</p><p>Line five</p><p>Line six</p>
            </tedi:scroll-fade>
            <tedi:scroll-fade fade-position="bottom" style="max-height:120px;width:200px">
                <p>Line one</p><p>Line two</p><p>Line three</p><p>Line four</p><p>Line five</p><p>Line six</p>
            </tedi:scroll-fade>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">scrollbar: custom | default</div>
        <div class="gx-case__demo" style="display:flex;gap:1rem;align-items:flex-start">
            <tedi:scroll-fade scroll-bar="custom" style="max-height:120px;width:200px">
                <p>Line one</p><p>Line two</p><p>Line three</p><p>Line four</p><p>Line five</p><p>Line six</p>
            </tedi:scroll-fade>
            <tedi:scroll-fade scroll-bar="default" style="max-height:120px;width:200px">
                <p>Line one</p><p>Line two</p><p>Line three</p><p>Line four</p><p>Line five</p><p>Line six</p>
            </tedi:scroll-fade>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Separator</h2>
    <p>Port of <code>helpers/separator</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">axis: horizontal | vertical</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.75rem;width:100%">
            <tedi:separator axis="horizontal" />
            <div style="display:flex;align-items:center;height:2rem;gap:.5rem">
                <span>Left</span>
                <tedi:separator axis="vertical" />
                <span>Right</span>
            </div>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">color: primary | secondary | accent</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.75rem;width:100%">
            <tedi:separator color="primary" />
            <tedi:separator color="secondary" />
            <tedi:separator color="accent" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">variant: dotted | dotted-small | dot-only (small/medium/large, filled/outlined)</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:1.5rem;width:100%">
            <tedi:separator variant="dotted" />
            <tedi:separator variant="dotted-small" />
            <div style="display:flex;gap:1rem;align-items:center">
                <tedi:separator variant="dot-only" dot-size="small" :dot-filled="true" />
                <tedi:separator variant="dot-only" dot-size="medium" :dot-filled="true" />
                <tedi:separator variant="dot-only" dot-size="large" :dot-filled="true" />
                <tedi:separator variant="dot-only" dot-size="large" :dot-filled="false" />
            </div>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">thickness: 1 | 2 | spacing</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:0;width:100%">
            <tedi:separator :thickness="1" />
            <tedi:separator :thickness="2" />
            <tedi:separator :spacing="2" />
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Vertical Spacing</h2>
    <p>Port of <code>directives/vertical-spacing</code>. Angular's attribute directives port as wrapper elements (CONVENTIONS.md §12); the size union is written as an inline <code>--vertical-spacing-internal</code>.</p>

    @php $verticalSpacingSizes = [0, 0.25, 0.5, 0.75, 1, 1.25, 1.5, 1.75, 2, 2.5, 3, 4, 5]; @endphp

    <div class="gx-case">
        <div class="gx-case__label">size: every value of the union</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:1rem;width:100%">
            @foreach ($verticalSpacingSizes as $size)
                <tedi:vertical-spacing :size="$size">
                    <p>size {{ $size }} — first</p>
                    <p>second</p>
                    <p>last (no margin)</p>
                </tedi:vertical-spacing>
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">item: every value of the union</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;width:100%">
            @foreach ($verticalSpacingSizes as $size)
                <tedi:vertical-spacing-item :size="$size">
                    <p>item at size {{ $size }}</p>
                </tedi:vertical-spacing-item>
            @endforeach
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Hide At / Show At</h2>
    <p>Port of <code>directives/hide-at</code> and <code>directives/show-at</code>. No classes are emitted — visibility is an Alpine <code>matchMedia</code> binding (CONVENTIONS.md §8), so these are here for render coverage only.</p>

    <div class="gx-case">
        <div class="gx-case__label">breakpoint: xs | sm | md | lg | xl | xxl</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.25rem;width:100%">
            @foreach (['xs', 'sm', 'md', 'lg', 'xl', 'xxl'] as $bp)
                <tedi:hide-at :breakpoint="$bp">hidden at and above {{ $bp }}</tedi:hide-at>
                <tedi:show-at :breakpoint="$bp">shown at and above {{ $bp }}</tedi:show-at>
            @endforeach
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Timeline</h2>
    <p>Port of <code>helpers/timeline</code> + <code>timeline-item</code>. <code>index</code> / <code>last</code> replace Angular's contentChildren auto-registration (CONVENTIONS.md §5); <code>activeIndex</code> is inherited by items via <code>@@aware</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">default variant, with timings and active item</div>
        <div class="gx-case__demo" style="width:100%">
            <tedi:timeline :active-index="1">
                <tedi:timeline-item :index="0" :timings="['1990', '14. detsember']">
                    <x-slot:title>Application submitted</x-slot:title>
                </tedi:timeline-item>
                <tedi:timeline-item :index="1" :timings="['2002', '04. oktoober']">
                    <x-slot:title>Under review</x-slot:title>
                    <x-slot:description>Estimated 30 days</x-slot:description>
                </tedi:timeline-item>
                <tedi:timeline-item :index="2" last>
                    <x-slot:title>Decision</x-slot:title>
                </tedi:timeline-item>
            </tedi:timeline>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">card variant</div>
        <div class="gx-case__demo" style="width:100%">
            <tedi:timeline variant="card" :active-index="0">
                <tedi:timeline-item :index="0" :timings="['11.01.2024 12:23']">
                    <x-slot:title>Contact with person</x-slot:title>
                    <p>Additional info: lorem ipsum dolor sit amet.</p>
                </tedi:timeline-item>
                <tedi:timeline-item :index="1" :timings="['08.02.2024 12:23']" last>
                    <x-slot:title>Contact with person</x-slot:title>
                </tedi:timeline-item>
            </tedi:timeline>
        </div>
    </div>
</div>
