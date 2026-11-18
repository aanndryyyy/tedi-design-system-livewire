<?php

namespace Tedi\Livewire\Tests\Components;

use Illuminate\Support\Facades\Blade;
use Tedi\Livewire\Tests\TestCase;

/**
 * Parity for `tedi:file-upload`, ported from
 * angular/src/tedi/components/form/file-upload (Angular 10). The expected
 * classes come from the component's `host` bindings and its template.
 */
class FileUploadComponentsTest extends TestCase
{
    private const TWO = "[['name' => 'a.pdf'], ['name' => 'b.pdf']]";

    private function render(string $attributes = '', ?string $wrapper = null): string
    {
        $tag = '<tedi:file-upload input-id="f" '.$attributes.' />';

        return Blade::render($wrapper ? sprintf($wrapper, $tag) : $tag);
    }

    // -- host ----------------------------------------------------------------

    public function test_root_is_the_angular_selector_element(): void
    {
        $html = $this->render();

        $this->assertMatchesRegularExpression('/^\s*<tedi-file-upload class="/', $html);
        $this->assertSame(['tedi-file-upload', 'tedi-file-upload--empty'], $this->classesOf($html, 'tedi-file-upload'));
        $this->assertStringContainsString('x-data="tediFileUpload(', $html);
    }

    public function test_host_modifiers(): void
    {
        $cases = [
            ['size="small"', 'tedi-file-upload--small'],
            ['disabled', 'tedi-file-upload--disabled'],
            ['read-only', 'tedi-file-upload--read-only'],
        ];

        foreach ($cases as [$attributes, $class]) {
            $html = $this->render($attributes.' :files="'.self::TWO.'"');
            $this->assertSame(['tedi-file-upload', $class], $this->classesOf($html, 'tedi-file-upload'), $attributes);
        }

        $this->assertSame(['tedi-file-upload'], $this->classesOf($this->render(':files="'.self::TWO.'"'), 'tedi-file-upload'));
    }

    /** resolvedSize(): own size, else the form field's when it is `small`, else `default`. */
    public function test_size_falls_back_to_the_form_field(): void
    {
        $small = $this->render('', '<tedi:form-field size="small">%s</tedi:form-field>');
        $this->assertHasClass('tedi-file-upload--small', $small, on: 'tedi-file-upload');

        $large = $this->render('', '<tedi:form-field size="large">%s</tedi:form-field>');
        $this->assertMissingClass('tedi-file-upload--small', $large, on: 'tedi-file-upload');

        $own = $this->render('size="default"', '<tedi:form-field size="small">%s</tedi:form-field>');
        $this->assertMissingClass('tedi-file-upload--small', $own, on: 'tedi-file-upload');
    }

    // -- the field surface ---------------------------------------------------

    public function test_field_paints_the_surface_with_its_state_modifiers(): void
    {
        $this->assertSame(
            ['tedi-file-upload__field', 'tedi-field-surface'],
            $this->classesOf($this->render(), 'tedi-file-upload__field'),
        );

        $cases = [
            'invalid' => 'tedi-field-surface--invalid',
            'error="Viga"' => 'tedi-field-surface--invalid',
            'success="Korras"' => 'tedi-field-surface--valid',
            'disabled' => 'tedi-field-surface--disabled',
        ];

        foreach ($cases as $attributes => $class) {
            $this->assertSame(
                ['tedi-file-upload__field', 'tedi-field-surface', $class],
                $this->classesOf($this->render($attributes), 'tedi-file-upload__field'),
                $attributes,
            );
        }

        // valid(): never while invalid.
        $both = $this->render('error="Viga" success="Korras"');
        $this->assertMissingClass('tedi-field-surface--valid', $both, on: 'tedi-file-upload__field');
    }

    public function test_field_takes_state_from_the_form_field(): void
    {
        $invalid = $this->render('', '<tedi:form-field :invalid="true">%s</tedi:form-field>');
        $this->assertHasClass('tedi-field-surface--invalid', $invalid, on: 'tedi-file-upload__field');

        $disabled = $this->render('', '<tedi:form-field :disabled="true">%s</tedi:form-field>');
        $this->assertHasClass('tedi-file-upload--disabled', $disabled, on: 'tedi-file-upload');
        $this->assertHasClass('tedi-field-surface--disabled', $disabled, on: 'tedi-file-upload__field');
    }

    /** paintsSurface(): not when the form field's box owns it (an icon). */
    public function test_field_leaves_the_surface_to_a_form_field_box(): void
    {
        $html = $this->render('invalid', '<tedi:form-field icon="attach_file">%s</tedi:form-field>');

        $this->assertSame(['tedi-file-upload__field'], $this->classesOf($html, 'tedi-file-upload__field'));
        $this->assertHasClass('tedi-field-surface', $html, on: 'tedi-form-field__box');
    }

    /** ownsClearButton: the form field never draws its own clear button for it. */
    public function test_form_field_draws_no_clear_button_or_box(): void
    {
        $html = $this->render(':files="'.self::TWO.'"', '<tedi:form-field>%s</tedi:form-field>');

        $this->assertStringNotContainsString('tedi-form-field__clear', $html);
        $this->assertStringNotContainsString('tedi-form-field__box', $html);
        $this->assertHasClass('tedi-field-surface', $html, on: 'tedi-file-upload__field');
    }

    // -- the file list -------------------------------------------------------

    public function test_a_single_file_is_a_name_and_several_are_tags(): void
    {
        $one = $this->render(":files=\"[['name' => 'a.pdf']]\"");
        $this->assertSame(['tedi-file-upload__name'], $this->classesOf($one, 'tedi-file-upload__name'));
        $this->assertStringNotContainsString('tedi-file-upload__tags', $one);
        $this->assertStringNotContainsString('tedi-tag', $one);

        $two = $this->render(':files="'.self::TWO.'"');
        $this->assertMatchesRegularExpression('/<ul class="tedi-file-upload__tags" role="list">/', $two);
        $this->assertSame(2, substr_count($two, '<li class="tedi-file-upload__tag">'));
        $this->assertStringNotContainsString('tedi-file-upload__name', $two);

        // showTags(): a multiple field shows even one file as a tag.
        $multiple = $this->render("multiple :files=\"[['name' => 'a.pdf']]\"");
        $this->assertSame(1, substr_count($multiple, '<li class="tedi-file-upload__tag">'));
        $this->assertHasClass('tedi-tag--closable', $multiple, on: 'tedi-tag');

        $none = $this->render();
        $this->assertStringNotContainsString('tedi-file-upload__tags', $none);
        $this->assertStringNotContainsString('tedi-file-upload__name', $none);
    }

    public function test_an_invalid_file_is_a_danger_tag_with_a_screen_reader_note(): void
    {
        $note = '<span class="sr-only"> ('.e(__('tedi::tedi.file-upload.failed')).')</span>';

        $tags = $this->render(":files=\"[['name' => 'a.pdf'], ['name' => 'b.pdf', 'isValid' => false]]\"");
        $this->assertSame(1, substr_count($tags, $note));
        $this->assertStringContainsString('b.pdf'.$note, $tags);
        $this->assertSame(1, substr_count($tags, 'tedi-tag--danger'));
        $this->assertMatchesRegularExpression('/class="tedi-tag tedi-tag--danger tedi-tag--closable"/', $tags);

        $single = $this->render(":files=\"[['name' => 'b.pdf', 'isValid' => false]]\"");
        $this->assertStringContainsString('<span class="tedi-file-upload__name">b.pdf'.$note.'</span>', $single);

        // Only an explicit false marks a file invalid.
        $valid = $this->render(":files=\"[['name' => 'a.pdf', 'isValid' => true], ['name' => 'b.pdf']]\"");
        $this->assertStringNotContainsString('tedi-tag--danger', $valid);
        $this->assertStringNotContainsString('sr-only"> (', $valid);
    }

    /** A loading file has a spinner and no remove button; the clear button stays. */
    public function test_a_loading_file_is_not_closable(): void
    {
        $html = $this->render(":files=\"[['name' => 'a.pdf', 'isLoading' => true], ['name' => 'b.pdf']]\"");

        $this->assertMatchesRegularExpression('/class="tedi-tag tedi-tag--loading"/', $html);
        $this->assertMatchesRegularExpression('/class="tedi-tag tedi-tag--closable"/', $html);
        $this->assertStringContainsString('tedi-file-upload__clear', $html);
    }

    /** isEditable(): no remove buttons while disabled or read-only. */
    public function test_tags_are_not_closable_when_not_editable(): void
    {
        foreach (['disabled', 'read-only'] as $attributes) {
            $html = $this->render($attributes.' :files="'.self::TWO.'"');

            $this->assertStringNotContainsString('tedi-tag--closable', $html, $attributes);
            $this->assertStringNotContainsString('tedi-closing-button', $html, $attributes);
        }
    }

    public function test_remove_attributes_reach_the_tag_remove_button_alongside_the_announcement(): void
    {
        $html = $this->render(":files=\"[['name' => 'a.pdf', 'removeAttributes' => ['wire:click' => 'remove(0)', 'x-on:click' => 'mine()']], ['name' => 'b.pdf']]\"");

        $this->assertStringContainsString('x-on:click="fileRemoved(&#039;a.pdf&#039;); mine()"', $html);
        $this->assertStringContainsString('wire:click="remove(0)"', $html);
        $this->assertStringContainsString('x-on:click="fileRemoved(&#039;b.pdf&#039;)"', $html);
    }

    // -- read-only -----------------------------------------------------------

    public function test_read_only_renders_only_the_list_and_the_disabled_input(): void
    {
        $several = $this->render('read-only :files="'.self::TWO.'"');

        foreach (['tedi-file-upload__field', 'tedi-file-upload__actions', 'tedi-file-upload__add', 'tedi-feedback-text'] as $absent) {
            $this->assertStringNotContainsString($absent, $several);
        }
        $this->assertMatchesRegularExpression('/<ul class="tedi-file-upload__tags" role="list" x-ref="list">/', $several);
        $this->assertMatchesRegularExpression('/<input[^>]*\sdisabled[\s>]/', $several);

        // A single read-only file is still a list, so the label can name it.
        $single = $this->render("read-only :files=\"[['name' => 'a.pdf']]\"");
        $this->assertMatchesRegularExpression(
            '/<ul class="tedi-file-upload__tags" role="list" x-ref="list">\s*<li class="tedi-file-upload__tag">\s*<span class="tedi-file-upload__name">a\.pdf<\/span>/',
            $single,
        );

        // hintText(): none in read-only mode.
        $this->assertStringNotContainsString('tedi-feedback-text', $this->render('read-only accept=".pdf" hint="Vihje"'));
    }

    // -- the clear button ----------------------------------------------------

    public function test_clear_button_shows_only_with_files_while_enabled_and_clearable(): void
    {
        $html = $this->render(':files="'.self::TWO.'"');
        $this->assertSame(['tedi-file-upload__clear-group'], $this->classesOf($html, 'tedi-file-upload__clear-group'));
        $this->assertSame(
            ['no-print', 'tedi-closing-button', 'tedi-closing-button--small', 'tedi-file-upload__clear'],
            $this->classesOf($html, 'tedi-file-upload__clear'),
        );
        $this->assertStringContainsString('title="'.e(__('tedi::tedi.clear')).'"', $html);
        $this->assertStringContainsString('x-on:click="clearFiles()"', $html);
        $this->assertSame(
            ['tedi-separator', 'tedi-separator--primary', 'tedi-separator--vertical', 'tedi-separator--thickness-1'],
            $this->classesOf($html, 'tedi-separator'),
        );
        $this->assertStringContainsString('height: 1.5rem', $html);

        foreach (['', 'disabled :files="'.self::TWO.'"', ':clearable="false" :files="'.self::TWO.'"'] as $attributes) {
            $this->assertStringNotContainsString('tedi-file-upload__clear', $this->render($attributes), $attributes);
        }
    }

    /** clearableResolved(): own `clearable` wins, then the form field's. */
    public function test_clearable_falls_back_to_the_form_field(): void
    {
        $inherited = $this->render(':files="'.self::TWO.'"', '<tedi:form-field :clearable="false">%s</tedi:form-field>');
        $this->assertStringNotContainsString('tedi-file-upload__clear', $inherited);

        $own = $this->render(':clearable="true" :files="'.self::TWO.'"', '<tedi:form-field :clearable="false">%s</tedi:form-field>');
        $this->assertStringContainsString('tedi-file-upload__clear', $own);
    }

    /** isStacked(): the spelled-out neutral clear button and a default-size add button. */
    public function test_stacked_swaps_the_clear_and_add_buttons(): void
    {
        $html = $this->render('stacked :files="'.self::TWO.'"');

        $this->assertSame(
            ['tedi-button', 'no-print', 'tedi-button--neutral', 'tedi-button--default', 'tedi-button--pr', 'tedi-file-upload__clear'],
            $this->classesOf($html, 'tedi-file-upload__clear'),
        );
        $this->assertMatchesRegularExpression('/>close<\/tedi-icon>\s*'.preg_quote(e(__('tedi::tedi.clear')), '/').'\s*<\/button>/', $html);
        $this->assertStringNotContainsString('tedi-closing-button', $this->between($html, 'tedi-file-upload__actions', '</tedi-file-upload>'));
        $this->assertHasClass('tedi-button--default', $html, on: 'tedi-file-upload__add');
    }

    public function test_clear_attributes_are_forwarded_and_keep_our_handler(): void
    {
        foreach (['', 'stacked'] as $stacked) {
            $html = $this->render($stacked.' :files="'.self::TWO.'" :clear-attributes="[\'wire:click\' => \'clearAll\', \'@click\' => \'mine()\']"');

            $this->assertStringContainsString('x-on:click="clearFiles(); mine()"', $html, $stacked);
            $this->assertStringContainsString('wire:click="clearAll"', $html, $stacked);
        }
    }

    // -- the add button ------------------------------------------------------

    public function test_add_button(): void
    {
        $html = $this->render();

        $this->assertSame(
            ['tedi-button', 'no-print', 'tedi-button--neutral', 'tedi-button--small', 'tedi-button--pr', 'tedi-file-upload__add'],
            $this->classesOf($html, 'tedi-file-upload__add'),
        );
        $this->assertStringContainsString('>file_upload</tedi-icon>', $html);
        $this->assertStringContainsString('<span id="f-add">'.e(__('tedi::tedi.file-upload.add')).'</span>', $html);
        $this->assertStringContainsString('x-ref="add" x-on:click="openPicker()"', $html);
        $this->assertStringNotContainsString('aria-describedby', $html);

        $this->assertMatchesRegularExpression('/<button\s+type="button"\s+disabled/', $this->render('disabled'));
    }

    /** describedBy: own ids, the form field's, then error / success / hint. */
    public function test_add_button_is_described_by_the_feedback_texts(): void
    {
        $html = $this->render('aria-describedby="own" error="Viga" success="Korras" accept=".pdf"');
        $this->assertStringContainsString('aria-describedby="own f-error f-hint"', $html);
        $this->assertDoesNotMatchRegularExpression('/<input[^>]*aria-describedby/', $html);

        $this->assertStringContainsString('aria-describedby="f-success"', $this->render('success="Korras"'));

        $counted = $this->render('hint="Vihje"', '<tedi:form-field :character-limit="5">%s</tedi:form-field>');
        $this->assertMatchesRegularExpression('/aria-describedby="tedi-form-field-[^" ]+-character-count f-hint"/', $counted);
    }

    // -- feedback ------------------------------------------------------------

    public function test_feedback_texts(): void
    {
        $error = $this->render('error="Viga" success="Korras" hint="Vihje"');
        $this->assertMatchesRegularExpression('/<tedi-feedback-text\s+id="f-error"[^>]*class="tedi-feedback-text tedi-feedback-text--error tedi-feedback-text--left"\s*>Viga</', $error);
        $this->assertStringNotContainsString('Korras', $error);
        $this->assertMatchesRegularExpression('/<tedi-feedback-text\s+id="f-hint"[^>]*class="tedi-feedback-text tedi-feedback-text--hint tedi-feedback-text--left"\s*>Vihje</', $error);

        $success = $this->render('success="Korras"');
        $this->assertMatchesRegularExpression('/<tedi-feedback-text\s+id="f-success"[^>]*class="tedi-feedback-text tedi-feedback-text--valid tedi-feedback-text--left"\s*>Korras</', $success);

        $this->assertStringNotContainsString('tedi-feedback-text', $this->render());
    }

    public function test_hint_lists_the_restrictions_before_the_hint(): void
    {
        $html = $this->render('accept=".pdf,.txt" :max-size="1024" hint="Vihje"');
        $expected = __('tedi::tedi.file-upload.accept').' .pdf, .txt. '.__('tedi::tedi.file-upload.max-size').' 1 KB. Vihje';
        $this->assertStringContainsString('>'.e($expected).'</tedi-feedback-text>', $html);

        $off = $this->render('accept=".pdf" :max-size="1024" :show-restrictions="false" hint="Vihje"');
        $this->assertStringContainsString('>Vihje</tedi-feedback-text>', $off);
        $this->assertStringNotContainsString(__('tedi::tedi.file-upload.accept'), $off);

        $this->assertStringNotContainsString('tedi-feedback-text', $this->render('accept=".pdf" :show-restrictions="false"'));
    }

    /** utils/file.util.ts formatFileSize(). */
    public function test_format_file_size(): void
    {
        $cases = [
            0 => null, // falsy maxSize writes no restriction
            512 => '512 B',
            1023 => '1023 B',
            1024 => '1 KB',
            1536 => '1.5 KB',
            1024 ** 2 - 1 => '1024 KB',
            1024 ** 2 => '1 MB',
            (int) (2.5 * 1024 ** 2) => '2.5 MB',
            1024 ** 3 => '1 GB',
            1024 ** 4 => '1024 GB',
        ];

        foreach ($cases as $bytes => $expected) {
            $html = $this->render(':max-size="'.$bytes.'"');

            if ($expected === null) {
                $this->assertStringNotContainsString('tedi-feedback-text', $html, (string) $bytes);
            } else {
                $this->assertStringContainsString('>'.__('tedi::tedi.file-upload.max-size').' '.$expected.'</tedi-feedback-text>', $html, (string) $bytes);
            }
        }
    }

    // -- the native input ----------------------------------------------------

    public function test_native_input_carries_the_attributes(): void
    {
        $html = $this->render('name="manused" accept=".pdf" multiple wire:model="files"');

        $this->assertMatchesRegularExpression('/<input\s+type="file"\s+tabindex="-1"\s+aria-hidden="true"\s+multiple\s+x-ref="input"\s+x-on:change="handleSelection\(\$event\)"\s+id="f" name="manused" accept=".pdf" class="tedi-file-upload__input" wire:model="files"\s*\/>/', $html);

        $bare = $this->render();
        $this->assertDoesNotMatchRegularExpression('/<input[^>]*\s(multiple|disabled|name=|accept=)/', $bare);
        $this->assertMatchesRegularExpression('/<input[^>]*\sdisabled[\s>]/', $this->render('disabled'));
    }

    public function test_input_id_is_generated_when_omitted(): void
    {
        $html = Blade::render('<tedi:file-upload hint="Vihje" />');

        $this->assertMatchesRegularExpression('/id="(tedi-file-upload-[^"]+)"/', $html);
        preg_match('/<input[^>]*\sid="([^"]+)"/', $html, $match);
        $this->assertStringContainsString('<span id="'.$match[1].'-add">', $html);
        $this->assertStringContainsString('id="'.$match[1].'-hint"', $html);
    }

    private function between(string $html, string $from, string $to): string
    {
        $start = strpos($html, $from);

        return substr($html, $start, strpos($html, $to, $start) - $start);
    }
}
