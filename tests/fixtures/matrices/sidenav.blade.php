<div class="gx-sec">
    <h2>SideNav</h2>
    <p>Port of <code>layout/sidenav</code> (<code>tedi-sidenav</code>,
        <code>tedi-sidenav-item</code>, <code>tedi-sidenav-group-title</code>,
        <code>tedi-sidenav-dropdown</code>, <code>tedi-sidenav-dropdown-item</code>,
        <code>tedi-sidenav-dropdown-group</code>, <code>tedi-sidenav-overlay</code>,
        <code>tedi-sidenav-toggle</code>). Angular drives open/collapsed/mobile state
        through the injectable <code>SideNavService</code>; a service is not portable, so
        each signal is an explicit prop (CONVENTIONS.md §5) with an inline Alpine layer
        on top (§8). <code>desktopBreakpoint</code> is not declared at all (§7 #1).</p>

    <div class="gx-case">
        <div class="gx-case__label">size: large / medium / small</div>
        <div class="gx-case__demo">
            @foreach (['large', 'medium', 'small'] as $size)
                <tedi:sidenav :size="$size">
                    <tedi:sidenav.item icon="home" href="#" label="Avaleht">Avaleht</tedi:sidenav.item>
                    <tedi:sidenav.item icon="account_box" href="#" label="Kliendid">Kliendid</tedi:sidenav.item>
                </tedi:sidenav>
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">dividers: true (default) / false</div>
        <div class="gx-case__demo">
            <tedi:sidenav>
                <tedi:sidenav.item icon="home" href="#" label="Avaleht">Avaleht</tedi:sidenav.item>
            </tedi:sidenav>
            <tedi:sidenav :dividers="false">
                <tedi:sidenav.item icon="home" href="#" label="Avaleht">Avaleht</tedi:sidenav.item>
            </tedi:sidenav>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">collapsible: renders the collapse button</div>
        <div class="gx-case__demo">
            <tedi:sidenav :collapsible="true">
                <tedi:sidenav.group-title>Menüü</tedi:sidenav.group-title>
                <tedi:sidenav.item icon="home" href="#" label="Avaleht">Avaleht</tedi:sidenav.item>
                <tedi:sidenav.item icon="payments" href="#" :selected="true" label="Maksed">Maksed</tedi:sidenav.item>
            </tedi:sidenav>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">collapsed: fly-out dropdown, generated parent link, tooltip trigger</div>
        <div class="gx-case__demo">
            <tedi:sidenav :collapsible="true" :collapsed="true">
                <tedi:sidenav.item icon="medical_services" href="#" label="Ravi" :has-dropdown="true" :open="true">
                    Ravi
                    <x-slot:dropdown>
                        <tedi:sidenav.dropdown parent-label="Ravi">
                            <tedi:sidenav.dropdown-item href="#">Elulised näitajad</tedi:sidenav.dropdown-item>
                            <tedi:sidenav.dropdown-item href="#" :selected="true">Hinnangud</tedi:sidenav.dropdown-item>
                        </tedi:sidenav.dropdown>
                    </x-slot:dropdown>
                </tedi:sidenav.item>
            </tedi:sidenav>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">mobile: hidden drawer / open drawer / drilled into a sub-menu</div>
        <div class="gx-case__demo">
            <tedi:sidenav :mobile="true">
                <tedi:sidenav.item icon="home" href="#" label="Avaleht">Avaleht</tedi:sidenav.item>
            </tedi:sidenav>

            <tedi:sidenav :mobile="true" :mobile-open="true">
                <tedi:sidenav.item icon="home" href="#" label="Avaleht">Avaleht</tedi:sidenav.item>
            </tedi:sidenav>

            <tedi:sidenav :mobile="true" :mobile-open="true" :mobile-item-open="true">
                <tedi:sidenav.item icon="medical_services" label="Ravi" :has-dropdown="true" :open="true">
                    Ravi
                    <x-slot:dropdown>
                        <tedi:sidenav.dropdown>
                            <tedi:sidenav.dropdown-item href="#">Elulised näitajad</tedi:sidenav.dropdown-item>
                        </tedi:sidenav.dropdown>
                    </x-slot:dropdown>
                </tedi:sidenav.item>
                <tedi:sidenav.item icon="home" href="#" label="Avaleht">Avaleht</tedi:sidenav.item>
            </tedi:sidenav>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">item: plain link + caret button, buttonless (no link) trigger, third-level group</div>
        <div class="gx-case__demo">
            <tedi:sidenav>
                <tedi:sidenav.item icon="medical_services" href="#" label="Ravi" :has-dropdown="true" :open="true">
                    Ravi
                    <x-slot:dropdown>
                        <tedi:sidenav.dropdown>
                            <tedi:sidenav.dropdown-item href="#">Elulised näitajad</tedi:sidenav.dropdown-item>
                            <tedi:sidenav.dropdown-item route="#">Hinnangud</tedi:sidenav.dropdown-item>
                            <tedi:sidenav.dropdown-item>Ilma lingita</tedi:sidenav.dropdown-item>
                            <tedi:sidenav.dropdown-group :items="[
                                ['label' => 'Raviplaanid', 'href' => '#'],
                                ['label' => 'Aktiivsed', 'href' => '#', 'selected' => true],
                                ['label' => 'Ajalugu', 'route' => '#'],
                                ['label' => 'Ilma lingita'],
                            ]" />
                        </tedi:sidenav.dropdown>
                    </x-slot:dropdown>
                </tedi:sidenav.item>

                <tedi:sidenav.item icon="admin_panel_settings" label="Haldus" :has-dropdown="true">
                    Haldus
                    <x-slot:dropdown>
                        <tedi:sidenav.dropdown>
                            <tedi:sidenav.dropdown-item route="#">Töötajad</tedi:sidenav.dropdown-item>
                        </tedi:sidenav.dropdown>
                    </x-slot:dropdown>
                </tedi:sidenav.item>
            </tedi:sidenav>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">toggle + overlay: desktop (hidden) and mobile (visible)</div>
        <div class="gx-case__demo">
            <tedi:sidenav.toggle />
            <tedi:sidenav.overlay />
            <tedi:sidenav.toggle :mobile="true" :mobile-open="true" />
            <tedi:sidenav.overlay :mobile="true" :mobile-open="true" />
        </div>
    </div>
</div>
