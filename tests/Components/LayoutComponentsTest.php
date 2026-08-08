<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

class LayoutComponentsTest extends TestCase
{
    // -- progress-bar ---------------------------------------------------------

    public function test_progress_bar_base_class_and_value(): void
    {
        $html = Blade::render('<tedi:progress-bar :value="40" aria-label="Progress" />');

        $this->assertHasClass('tedi-progress-bar', $html);
        $this->assertHasClass('tedi-progress-bar__track', $html, on: 'tedi-progress-bar__track');
        $this->assertStringContainsString('value="40"', $html);
        $this->assertStringContainsString('40%', $html);
    }

    public function test_progress_bar_size_small(): void
    {
        $html = Blade::render('<tedi:progress-bar :value="20" size="small" />');

        $this->assertHasClass('tedi-progress-bar--small', $html);
    }

    public function test_progress_bar_label_horizontal_modifier_requires_label(): void
    {
        $withLabel = Blade::render('<tedi:progress-bar :value="20" label="Upload" label-position="horizontal" />');
        $this->assertHasClass('tedi-progress-bar--label-horizontal', $withLabel);

        $withoutLabel = Blade::render('<tedi:progress-bar :value="20" label-position="horizontal" />');
        $this->assertMissingClass('tedi-progress-bar--label-horizontal', $withoutLabel, on: 'tedi-progress-bar');
    }

    public function test_progress_bar_value_position_bottom_uses_value_modifier_not_host_class(): void
    {
        // Angular's host binding also has `[class.tedi-progress-bar--value-bottom]`,
        // but the vendored SCSS never styles it (verified against dist/tedi.css) —
        // see the doc comment in progress-bar.blade.php. This port doesn't emit
        // the dead host class; the bottom placement comes entirely from moving
        // the value into __hint-row with the (real) __value--bottom modifier.
        $html = Blade::render('<tedi:progress-bar :value="20" value-position="bottom" />');

        $this->assertHasClass('tedi-progress-bar__value--bottom', $html, on: 'tedi-progress-bar__value--bottom');
        $this->assertMissingClass('tedi-progress-bar--value-bottom', $html, on: 'tedi-progress-bar');
    }

    public function test_progress_bar_value_label_overrides_percentage(): void
    {
        $html = Blade::render('<tedi:progress-bar :value="20" value-label="1 / 5" />');

        $this->assertStringContainsString('1 / 5', $html);
        $this->assertStringContainsString('aria-valuetext="1 / 5"', $html);
    }

    public function test_progress_bar_show_value_false_hides_value(): void
    {
        $html = Blade::render('<tedi:progress-bar :value="60" :show-value="false" />');

        $this->assertMissingClass('tedi-progress-bar__value', $html, on: 'tedi-progress-bar');
    }

    // -- footer -----------------------------------------------------------------

    public function test_footer_renders_container_and_center(): void
    {
        $html = Blade::render('<tedi:footer>Body</tedi:footer>');

        $this->assertHasClass('tedi-footer', $html);
        $this->assertHasClass('tedi-footer__container', $html, on: 'tedi-footer__container');
        $this->assertHasClass('tedi-footer__center', $html, on: 'tedi-footer__center');
    }

    public function test_footer_body_class(): void
    {
        $html = Blade::render('<tedi:footer.body>Links</tedi:footer.body>');

        $this->assertHasClass('tedi-footer-body', $html);
    }

    public function test_footer_bottom_class(): void
    {
        $html = Blade::render('<tedi:footer.bottom>Links</tedi:footer.bottom>');

        $this->assertHasClass('tedi-footer-bottom', $html);
    }

    public function test_footer_side_placement_classes(): void
    {
        foreach (['start', 'end'] as $placement) {
            $html = Blade::render("<tedi:footer.side placement=\"{$placement}\">Logo</tedi:footer.side>");
            $this->assertHasClass('tedi-footer-side--'.$placement, $html);
        }
    }

    public function test_footer_side_position_classes(): void
    {
        // "center" is the base rule's default justify-content; Angular's own
        // host binding pushes `--vertical-center` too, but the vendored SCSS
        // never styles it (verified against dist/tedi.css), so this port skips
        // it — only start/end get the vertical modifier. See side.blade.php.
        foreach (['start', 'end'] as $position) {
            $html = Blade::render("<tedi:footer.side position=\"{$position}\">Logo</tedi:footer.side>");
            $this->assertHasClass('tedi-footer-side--vertical-'.$position, $html);
        }

        $center = Blade::render('<tedi:footer.side position="center">Logo</tedi:footer.side>');
        $this->assertMissingClass('tedi-footer-side--vertical-center', $center, on: 'tedi-footer-side');
    }

    public function test_footer_section_base_and_collapse(): void
    {
        $html = Blade::render('<tedi:footer.section heading="Heading" icon="account_circle">Links</tedi:footer.section>');
        $this->assertHasClass('tedi-footer-section', $html);
        $this->assertHasClass('tedi-footer-section__icon', $html, on: 'tedi-footer-section__icon');
        $this->assertMissingClass('tedi-footer-section--collapse', $html, on: 'tedi-footer-section');

        $collapsible = Blade::render('<tedi:footer.section heading="Heading" :collapse="true">Links</tedi:footer.section>');
        $this->assertHasClass('tedi-footer-section--collapse', $collapsible);
        $this->assertHasClass('tedi-footer-section__button', $collapsible, on: 'tedi-footer-section__button');
        $this->assertHasClass('tedi-footer-section__content-wrapper--collapsed', $collapsible, on: 'tedi-footer-section__content-wrapper--collapsed');
    }

    // -- header -------------------------------------------------------------

    public function test_header_renders_main_and_slots(): void
    {
        $html = Blade::render(<<<'BLADE'
            <tedi:header>
                <x-slot:top>Top bar</x-slot:top>
                Main content
                <x-slot:bottom>Bottom bar</x-slot:bottom>
            </tedi:header>
        BLADE);

        $this->assertHasClass('tedi-header', $html);
        $this->assertHasClass('tedi-header__main', $html, on: 'tedi-header__main');
        $this->assertHasClass('tedi-header__main--content', $html, on: 'tedi-header__main--content');
        $this->assertStringContainsString('Top bar', $html);
        $this->assertStringContainsString('Main content', $html);
        $this->assertStringContainsString('Bottom bar', $html);
    }

    public function test_header_top_alignment_utility_classes(): void
    {
        $map = [
            'flex-start' => 'justify-content-start',
            'center' => 'justify-content-center',
            'flex-end' => 'justify-content-end',
            'space-between' => 'justify-content-between',
            'space-around' => 'justify-content-around',
            'space-evenly' => 'justify-content-evenly',
        ];

        foreach ($map as $alignment => $utilityClass) {
            $html = Blade::render("<tedi:header.top alignment=\"{$alignment}\">x</tedi:header.top>");
            $this->assertHasClass('tedi-header-top', $html);
            $this->assertHasClass($utilityClass, $html);
        }
    }

    public function test_header_content_alignment_utility_classes(): void
    {
        $html = Blade::render('<tedi:header.content alignment="space-between">x</tedi:header.content>');

        $this->assertHasClass('tedi-header-content', $html);
        $this->assertHasClass('justify-content-between', $html);
    }

    public function test_header_bottom_and_actions_classes(): void
    {
        $this->assertHasClass('tedi-header-bottom', Blade::render('<tedi:header.bottom>x</tedi:header.bottom>'));
        $this->assertHasClass('tedi-header-actions', Blade::render('<tedi:header.actions>x</tedi:header.actions>'));
    }

    public function test_header_toggle_renders_sidenav_toggle_classes(): void
    {
        $html = Blade::render('<tedi:header.toggle />');

        $this->assertHasClass('tedi-sidenav-toggle', $html);
        $this->assertHasClass('tedi-sidenav-toggle__icon', $html, on: 'tedi-sidenav-toggle__icon');
    }

    public function test_header_logo_dark_and_hidden_modifiers(): void
    {
        $default = Blade::render('<tedi:header.logo>Logo</tedi:header.logo>');
        $this->assertHasClass('tedi-header-logo', $default);
        $this->assertHasClass('tedi-header-logo__default', $default, on: 'tedi-header-logo__default');
        $this->assertHasClass('tedi-header-logo__dark', $default, on: 'tedi-header-logo__dark');
        $this->assertMissingClass('tedi-header-logo--dark', $default, on: 'tedi-header-logo');
        $this->assertMissingClass('tedi-header-logo--hidden', $default, on: 'tedi-header-logo');

        $dark = Blade::render('<tedi:header.logo :dark="true">Logo</tedi:header.logo>');
        $this->assertHasClass('tedi-header-logo--dark', $dark);

        $hidden = Blade::render('<tedi:header.logo :show-logo="false">Logo</tedi:header.logo>');
        $this->assertHasClass('tedi-header-logo--hidden', $hidden);

        $link = Blade::render('<tedi:header.logo href="/">Logo</tedi:header.logo>');
        $this->assertHasClass('tedi-header-logo__link', $link, on: 'tedi-header-logo__link');
        $this->assertStringContainsString('href="/"', $link);
    }

    public function test_header_mobile_button_renders_link_or_button(): void
    {
        $button = Blade::render('<tedi:header.mobile-button icon="menu" label="Menu" />');
        $this->assertHasClass('tedi-header-mobile-button', $button);
        $this->assertStringContainsString('<button', $button);

        $link = Blade::render('<tedi:header.mobile-button icon="search" label="Search" href="/search" />');
        $this->assertStringContainsString('<a', $link);
        $this->assertStringContainsString('href="/search"', $link);

        $selected = Blade::render('<tedi:header.mobile-button icon="notifications" label="Alerts" :selected="true" />');
        $this->assertHasClass('tedi-header-mobile-button--selected', $selected);

        $disabled = Blade::render('<tedi:header.mobile-button icon="search" label="Search" href="/x" :disabled="true" />');
        $this->assertHasClass('tedi-header-mobile-button--disabled', $disabled);
        $this->assertStringContainsString('<button', $disabled);
    }

    public function test_header_login_default_and_small(): void
    {
        $default = Blade::render('<tedi:header.login />');
        $this->assertHasClass('tedi-header-login__button', $default);
        $this->assertHasClass('tedi-button', $default);
        $this->assertStringContainsString('Log in', $default);

        $small = Blade::render('<tedi:header.login size="small" />');
        $this->assertHasClass('tedi-header-mobile-button', $small);
        $this->assertStringContainsString('Log in', $small);
    }

    public function test_header_logout_default_and_small(): void
    {
        $default = Blade::render('<tedi:header.logout />');
        $this->assertHasClass('tedi-header-logout', $default);
        $this->assertHasClass('tedi-header-logout__button', $default, on: 'tedi-header-logout__button');
        $this->assertStringContainsString('Log out', $default);

        $link = Blade::render('<tedi:header.logout href="/logout" />');
        $this->assertHasClass('tedi-link', $link, on: 'tedi-link');
        $this->assertHasClass('tedi-link--no-underline', $link, on: 'tedi-link--no-underline');

        $small = Blade::render('<tedi:header.logout size="small" />');
        $this->assertHasClass('tedi-header-mobile-button', $small, on: 'tedi-header-mobile-button');
    }

    public function test_header_language_renders_label_trigger_and_options(): void
    {
        $html = Blade::render('<tedi:header.language :languages="[\'et\' => \'EST\', \'en\' => \'ENG\']" />');

        $this->assertHasClass('tedi-header-language', $html);
        $this->assertHasClass('tedi-header-language__label', $html, on: 'tedi-header-language__label');
        $this->assertHasClass('tedi-header-language__chevron', $html, on: 'tedi-header-language__chevron');
        $this->assertHasClass('tedi-link', $html, on: 'tedi-link');
        $this->assertMissingClass('tedi-header-language__options', $html, on: 'tedi-header-language');
        $this->assertStringContainsString('EST', $html);
        $this->assertStringContainsString('ENG', $html);

        $left = Blade::render('<tedi:header.language label-position="left" :languages="[\'et\' => \'EST\']" />');
        $this->assertHasClass('tedi-header-language--label-left', $left);
    }

    public function test_header_language_current_language_drives_trigger_label(): void
    {
        $html = Blade::render('<tedi:header.language :languages="[\'et\' => \'EST\', \'en\' => \'ENG\']" current-language="en" />');

        // The trigger's <span> shows the active language; ENG should appear
        // before EST's own option button in the rendered order.
        $this->assertLessThan(
            strpos($html, '>EST<'),
            strpos($html, '>ENG<'),
            'Expected the current-language trigger label (ENG) to render before the EST option.'
        );
    }

    public function test_header_search_inline_and_modal(): void
    {
        $inline = Blade::render('<tedi:header.search>Search input</tedi:header.search>');
        $this->assertHasClass('tedi-header-search', $inline);
        $this->assertStringContainsString('Search input', $inline);
        $this->assertMissingClass('tedi-header-search__modal', $inline, on: 'tedi-header-search');

        $modal = Blade::render('<tedi:header.search :mobile="true">Search input</tedi:header.search>');
        $this->assertHasClass('tedi-header-search__modal', $modal, on: 'tedi-header-search__modal');
        $this->assertHasClass('tedi-header-mobile-button', $modal, on: 'tedi-header-mobile-button');
    }

    public function test_header_profile_default_and_small(): void
    {
        $default = Blade::render('<tedi:header.profile />');
        $this->assertHasClass('tedi-header-profile__icon', $default, on: 'tedi-header-profile__icon');
        $this->assertHasClass('tedi-header-profile__overlay', $default, on: 'tedi-header-profile__overlay');
        $this->assertHasClass('tedi-header-profile__modal', $default, on: 'tedi-header-profile__modal');

        $withLabel = Blade::render('<tedi:header.profile :show-label="true" />');
        $this->assertHasClass('tedi-header-profile__label', $withLabel, on: 'tedi-header-profile__label');
        $this->assertHasClass('tedi-header-profile__icon--small', $withLabel, on: 'tedi-header-profile__icon--small');

        $small = Blade::render('<tedi:header.profile size="small" />');
        $this->assertHasClass('tedi-header-mobile-button', $small, on: 'tedi-header-mobile-button');

        $noStyle = Blade::render('<tedi:header.profile :no-style="true" />');
        $this->assertHasClass('tedi-header-profile__modal--no-style', $noStyle, on: 'tedi-header-profile__modal--no-style');
    }

    public function test_header_profile_show_popover_is_accepted_but_inert(): void
    {
        // API parity only (CONVENTIONS.md §9 DoD item 2) — always renders the
        // modal branch regardless of the value, see profile.blade.php.
        $default = Blade::render('<tedi:header.profile />');
        $explicit = Blade::render('<tedi:header.profile show-popover="md" />');

        $this->assertHasClass('tedi-header-profile__modal', $default, on: 'tedi-header-profile__modal');
        $this->assertHasClass('tedi-header-profile__modal', $explicit, on: 'tedi-header-profile__modal');
    }

    public function test_header_role_with_and_without_selection(): void
    {
        $reps = "[['id' => '1', 'name' => 'Rep One'], ['id' => '2', 'name' => 'Rep Two']]";

        $withSelection = Blade::render(
            '<tedi:header.role label="I represent:" :representatives="'.$reps.'" :current-representative="[\'id\' => \'1\', \'name\' => \'Rep One\']" />'
        );
        $this->assertHasClass('tedi-header-role', $withSelection);
        $this->assertHasClass('tedi-header-role__head', $withSelection, on: 'tedi-header-role__head');
        $this->assertHasClass('tedi-header-role__chevron', $withSelection, on: 'tedi-header-role__chevron');
        $this->assertHasClass('tedi-header-role__dropdown', $withSelection, on: 'tedi-header-role__dropdown');
        $this->assertHasClass('tedi-header-role__representative', $withSelection, on: 'tedi-header-role__representative');
        $this->assertStringContainsString('data-selected="true"', $withSelection);

        $single = "[['id' => '1', 'name' => 'Rep One']]";
        $withoutSelection = Blade::render(
            '<tedi:header.role :representatives="'.$single.'" :current-representative="[\'id\' => \'1\', \'name\' => \'Rep One\']" />'
        );
        $this->assertHasClass('tedi-header-role__value', $withoutSelection, on: 'tedi-header-role__value');
        $this->assertMissingClass('tedi-header-role__dropdown', $withoutSelection, on: 'tedi-header-role');
    }

    public function test_header_role_clear_search_on_select_is_accepted_but_inert(): void
    {
        // API parity only (CONVENTIONS.md §9 DoD item 2) — selection isn't
        // wired up at all in this port, see role.blade.php's doc comment.
        $reps = "[['id' => '1', 'name' => 'Rep One'], ['id' => '2', 'name' => 'Rep Two']]";

        $html = Blade::render(
            '<tedi:header.role :representatives="'.$reps.'" :current-representative="[\'id\' => \'1\', \'name\' => \'Rep One\']" :clear-search-on-select="false" />'
        );

        $this->assertHasClass('tedi-header-role', $html);
        $this->assertHasClass('tedi-header-role__dropdown', $html, on: 'tedi-header-role__dropdown');
    }
}
