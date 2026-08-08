<div class="gx-sec">
    <h2>Card Button</h2>
    <p>Port of <code>buttons/card-button</code>. Angular's selector is
        <code>a[tedi-card-button], button[tedi-card-button]</code> — the <code>href</code>
        prop picks the element, like <code>&lt;tedi:button&gt;</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">button / anchor / disabled</div>
        <div class="gx-case__demo gx-case__demo--row" style="gap:1rem">
            <tedi:card-button style="width:12rem">
                <tedi:card><tedi:card-content>Button card</tedi:card-content></tedi:card>
            </tedi:card-button>
            <tedi:card-button href="#" style="width:12rem">
                <tedi:card><tedi:card-content>Anchor card</tedi:card-content></tedi:card>
            </tedi:card-button>
            <tedi:card-button disabled style="width:12rem">
                <tedi:card><tedi:card-content>Disabled card</tedi:card-content></tedi:card>
            </tedi:card-button>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Info Button</h2>
    <p>Port of <code>buttons/info-button</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">color: primary / inverted</div>
        <div class="gx-case__demo gx-case__demo--row" style="gap:1rem;padding:1rem;background:var(--general-icon-background-brand-primary,#1c3f66)">
            <tedi:info-button />
            <tedi:info-button color="inverted" />
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>List</h2>
    <p>Port of <code>content/list</code>. Angular's selector is
        <code>ul[tedi-list], ol[tedi-list]</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">as: ul / ol, styled: true / false</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:2rem">
            <tedi:list><li>Unordered</li><li>List</li></tedi:list>
            <tedi:list as="ol"><li>Ordered</li><li>List</li></tedi:list>
            <tedi:list :styled="false"><li>Unstyled</li><li>List</li></tedi:list>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">color</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1rem">
            @foreach (['primary', 'secondary', 'tertiary', 'brand', 'brand-dark', 'success', 'warning', 'warning-dark', 'danger', 'white'] as $color)
                <tedi:list :color="$color"><li>{{ $color }}</li></tedi:list>
            @endforeach
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Text Group</h2>
    <p>Port of <code>content/text-group</code> (<code>tedi-text-group</code>,
        <code>tedi-text-group-label</code>, <code>tedi-text-group-value</code>).</p>

    <div class="gx-case">
        <div class="gx-case__label">type: horizontal / vertical</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:2rem">
            <tedi:text-group>
                <x-slot:label>Name</x-slot:label>
                <x-slot:value>John Smith</x-slot:value>
            </tedi:text-group>
            <tedi:text-group type="vertical">
                <x-slot:label>Name</x-slot:label>
                <x-slot:value>John Smith</x-slot:value>
            </tedi:text-group>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">label-width</div>
        <div class="gx-case__demo">
            <tedi:text-group label-width="10rem">
                <x-slot:label>Fixed width label</x-slot:label>
                <x-slot:value>Value</x-slot:value>
            </tedi:text-group>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Card</h2>
    <p>Port of <code>content/card</code> (<code>tedi-card</code>,
        <code>tedi-card-header</code>, <code>tedi-card-content</code>,
        <code>tedi-card-icon</code>, <code>tedi-card-row</code>).</p>

    <div class="gx-case">
        <div class="gx-case__label">background (child inherits from card)</div>
        <div class="gx-case__demo gx-case__demo--row" style="gap:1rem;align-items:flex-start">
            @foreach (['primary', 'secondary', 'brand-primary', 'success-secondary', 'danger-secondary'] as $bg)
                <tedi:card :background="$bg" style="width:10rem">
                    <tedi:card-content>{{ $bg }}</tedi:card-content>
                </tedi:card>
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">border: plain / top- / left-</div>
        <div class="gx-case__demo gx-case__demo--row" style="gap:1rem;align-items:flex-start">
            <tedi:card border="brand-primary" style="width:10rem"><tedi:card-content>plain</tedi:card-content></tedi:card>
            <tedi:card border="top-brand-primary" style="width:10rem"><tedi:card-content>top-</tedi:card-content></tedi:card>
            <tedi:card border="left-brand-primary" style="width:10rem"><tedi:card-content>left-</tedi:card-content></tedi:card>
            <tedi:card borderless style="width:10rem"><tedi:card-content>borderless</tedi:card-content></tedi:card>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">borderRadius: default / false / per-side</div>
        <div class="gx-case__demo gx-case__demo--row" style="gap:1rem;align-items:flex-start">
            <tedi:card style="width:10rem"><tedi:card-content>default</tedi:card-content></tedi:card>
            <tedi:card :border-radius="false" style="width:10rem"><tedi:card-content>false</tedi:card-content></tedi:card>
            <tedi:card :border-radius="['top' => false]" style="width:10rem"><tedi:card-content>top square</tedi:card-content></tedi:card>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">card-header + card-row + card-icon (type/size)</div>
        <div class="gx-case__demo gx-case__demo--row" style="gap:1rem;align-items:flex-start">
            <tedi:card style="width:16rem">
                <tedi:card-header>Header</tedi:card-header>
                <tedi:card-row>
                    <tedi:card-icon><tedi:icon name="info" /></tedi:card-icon>
                    <tedi:card-content>Default icon</tedi:card-content>
                </tedi:card-row>
                <tedi:card-row>
                    <tedi:card-icon type="brand" size="small"><tedi:icon name="star" :size="16" /></tedi:card-icon>
                    <tedi:card-content auto-width>Brand small icon</tedi:card-content>
                </tedi:card-row>
            </tedi:card>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">padding (rem number, and vertical/horizontal object)</div>
        <div class="gx-case__demo gx-case__demo--row" style="gap:1rem;align-items:flex-start">
            <tedi:card :padding="0.5" style="width:10rem"><tedi:card-content>0.5</tedi:card-content></tedi:card>
            <tedi:card :padding="2" style="width:10rem"><tedi:card-content>2</tedi:card-content></tedi:card>
            <tedi:card style="width:10rem"><tedi:card-content :padding="['vertical' => 2, 'horizontal' => 0.5]">v2/h0.5</tedi:card-content></tedi:card>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Accordion</h2>
    <p>Port of <code>content/accordion</code> (<code>tedi-accordion</code>,
        <code>tedi-accordion-item</code>, <code>tedi-accordion-item-header</code>,
        <code>tedi-accordion-item-content</code>). ARIA pairing between header and
        content requires an explicit <code>item-id</code> (see the Blade comment in
        <code>accordion-item.blade.php</code>) — auto-collapsing sibling items when
        <code>allow-multiple</code> is false is not ported (documented divergence).</p>

    <div class="gx-case">
        <div class="gx-case__label">default, defaultExpanded, headingLevel, showIconCard</div>
        <div class="gx-case__demo">
            <tedi:accordion :item-gap="0.5">
                <tedi:accordion-item item-id="acc-1" show-icon-card :default-expanded="true">
                    <x-slot:icon-card>
                        <span style="display:inline-flex;align-items:center;gap:.5rem">
                            <tedi:icon name="business_center" color="secondary" :size="24" />
                            Category
                        </span>
                    </x-slot:icon-card>
                    <tedi:accordion-item-header heading-level="2">
                        <x-slot:title>Expanded by default</x-slot:title>
                    </tedi:accordion-item-header>
                    <tedi:accordion-item-content>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</tedi:accordion-item-content>
                </tedi:accordion-item>
                <tedi:accordion-item item-id="acc-2">
                    <tedi:accordion-item-header>
                        <x-slot:title>Collapsed by default</x-slot:title>
                    </tedi:accordion-item-header>
                    <tedi:accordion-item-content>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</tedi:accordion-item-content>
                </tedi:accordion-item>
            </tedi:accordion>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">titleLayout: hug / fill, expandActionPosition: end / start</div>
        <div class="gx-case__demo">
            <tedi:accordion :item-gap="0.5">
                <tedi:accordion-item item-id="acc-3">
                    <tedi:accordion-item-header title-layout="fill" expand-action-position="start">
                        <x-slot:title>Fill layout, action at start</x-slot:title>
                    </tedi:accordion-item-header>
                    <tedi:accordion-item-content>Content.</tedi:accordion-item-content>
                </tedi:accordion-item>
                <tedi:accordion-item item-id="acc-4" selected>
                    <tedi:accordion-item-header :show-expand-label="false">
                        <x-slot:title>Selected item, icon-only expand action</x-slot:title>
                    </tedi:accordion-item-header>
                    <tedi:accordion-item-content>Content.</tedi:accordion-item-content>
                </tedi:accordion-item>
                <tedi:accordion-item item-id="acc-5" disabled :default-expanded="true">
                    <tedi:accordion-item-header>
                        <x-slot:title>Disabled item</x-slot:title>
                    </tedi:accordion-item-header>
                    <tedi:accordion-item-content>Content.</tedi:accordion-item-content>
                </tedi:accordion-item>
            </tedi:accordion>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">headerClickable=false with custom end-action slot</div>
        <div class="gx-case__demo">
            <tedi:accordion :item-gap="0.5">
                <tedi:accordion-item item-id="acc-6">
                    <tedi:accordion-item-header :header-clickable="false" expand-action-position="start">
                        <x-slot:title>Non-clickable header</x-slot:title>
                        <x-slot:end-action>
                            <tedi:button variant="secondary">Custom action</tedi:button>
                        </x-slot:end-action>
                    </tedi:accordion-item-header>
                    <tedi:accordion-item-content>Content.</tedi:accordion-item-content>
                </tedi:accordion-item>
            </tedi:accordion>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Carousel</h2>
    <p>Port of <code>content/carousel</code> (<code>tedi-carousel</code>,
        <code>tedi-carousel-header</code>, <code>tedi-carousel-content</code>,
        <code>tedi-carousel-slide</code>, <code>tedi-carousel-footer</code>,
        <code>tedi-carousel-navigation</code>, <code>tedi-carousel-indicators</code>).
        Ships correct markup/classes plus minimal Alpine index state — no drag, wheel,
        or wrap-around cloning (documented divergence in <code>carousel.blade.php</code>).</p>

    <div class="gx-case">
        <div class="gx-case__label">1 slide per view, dots indicators</div>
        <div class="gx-case__demo">
            <tedi:carousel>
                <tedi:carousel-header>
                    <h4 class="tedi-text">Carousel title</h4>
                </tedi:carousel-header>
                <tedi:carousel-content>
                    @foreach (['One', 'Two', 'Three'] as $slide)
                        <tedi:carousel-slide>
                            <div style="background:var(--general-surface-secondary,#eee);padding:2rem;text-align:center">Slide {{ $slide }}</div>
                        </tedi:carousel-slide>
                    @endforeach
                </tedi:carousel-content>
                <tedi:carousel-footer>
                    <tedi:carousel-indicators />
                    <tedi:carousel-navigation />
                </tedi:carousel-footer>
            </tedi:carousel>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">slidesPerView=2.5, numbers indicators with arrows</div>
        <div class="gx-case__demo">
            <tedi:carousel>
                <tedi:carousel-content :slides-per-view="2.5" :gap="8" fade>
                    @foreach (['One', 'Two', 'Three', 'Four'] as $slide)
                        <tedi:carousel-slide>
                            <div style="background:var(--general-surface-secondary,#eee);padding:2rem;text-align:center">{{ $slide }}</div>
                        </tedi:carousel-slide>
                    @endforeach
                </tedi:carousel-content>
                <tedi:carousel-footer>
                    <tedi:carousel-indicators variant="numbers" with-arrows />
                </tedi:carousel-footer>
            </tedi:carousel>
        </div>
    </div>
</div>
