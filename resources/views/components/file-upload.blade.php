{{--
    TEDI File Upload — DOCUMENTED SUBSET.
    Port of angular/src/tedi/components/form/file-upload/
    {file-upload.component.ts,html}, file-upload.types.ts and
    utils/file.util.ts (Angular 10).

    Angular's selector is the element `tedi-file-upload`, so per CONVENTIONS.md
    §4 the root is that literal element, carrying the host classes:
    `tedi-file-upload`, `--small`, `--disabled`, `--read-only` and `--empty`.

    `{{ $attributes }}` sits on the hidden native `<input type="file">`, not the
    root, so `wire:model` binds the control (CONVENTIONS.md §6). That includes
    a consumer `class`, which therefore lands on the hidden input: wrap the
    field to style it. A consumer `aria-describedby` is the exception, as in
    text-field.blade.php: it is merged onto the add button, which is what
    upstream's `controlDescribedBy()` does with a host `aria-describedby`.

    THE FILE LIST IS SERVER STATE. Upstream's `files` is a two-way `model()`
    and a ControlValueAccessor fed by the component's own selection pipeline.
    Here `files` is rendered by Blade and nothing on the client adds to it or
    takes from it: with `wire:model` on the input, Livewire uploads on change
    and your component renders the result back through `:files`. Each entry is
    an array with upstream's FileUploadFile fields:

        ['name' => 'a.pdf', 'id' => …, 'isValid' => false, 'isLoading' => true,
         'removeAttributes' => ['wire:click' => "removeFile('a.pdf')"]]

    `isValid => false` renders the danger tag and the screen-reader
    "(Invalid file)" note; `isLoading` the tag spinner, with no remove button.
    `removeAttributes` is this port's: it is forwarded to that file's tag
    remove button, and `clear-attributes` to the clear button (the
    `$attributes->only([])->merge()` idiom, CONVENTIONS.md §6), because
    removing a file is the server's job here (§7 item 2 — upstream's
    `fileRemove` output has no equivalent).

    So the following are NOT PORTED, and are the server's job:
    - adding the selection to the list, and `multiple`'s append semantics
      (each Livewire upload replaces the model unless you merge it yourself);
    - the `accept` / `maxSize` / `validator` checks, duplicate skipping,
      `keepRejectedFiles`, and the rejection summary built from them (the
      `file-upload.*-rejected` messages). Validate on the server, mark kept
      files `isValid => false`, and pass the message as `error`. `validator`
      and `keepRejectedFiles` are therefore not declared;
    - the ControlValueAccessor / Validator (`rejectedFiles` error), touched /
      dirty tracking, and the `fileRemove` / `filesChange` outputs.
    `accept` still lands on the native input (the browser filters the dialog
    with it), and `accept` / `max-size` still write upstream's restrictions
    hint.

    WHAT ALPINE ADDS (`tediFileUpload`, resources/js/src/file-upload.js):
    opening the picker from the add button; naming the add button (and the
    read-only list) by the `label` whose `for` is `input-id` — the label gets
    the id `{input-id}-label` when it has none, as upstream; and upstream's
    announcements — `file-upload.added` on a selection, `file-upload.cleared`
    and `file-upload.removed` on the clear and remove buttons, which also move
    focus to the add button. Upstream clears `input.value` after each
    selection; here the input is the value (`wire:model` and a native submit
    both read it), so only the clear button resets it.

    THE BREAKPOINT SWAP IS AN EXPLICIT PROP. Upstream's `isStacked()` is
    `BreakpointService.isBelowBreakpoint('md')`, resolved at runtime
    (CONVENTIONS.md §7 item 1). It becomes `stacked` (default false, the
    desktop branch), as `tedi:header.search`'s `mobile` does: true renders the
    clear button as a neutral button with the "Clear" text and the add button
    at the default size. The stylesheet's own below-`md` layout (the stacked
    column, full-width buttons) applies regardless. Both buttons are composed
    with `icon-start`, which gives the padding-modifier parity, so at the
    default (stacked) size their icons are 24px instead of upstream's
    hard-coded 18px — the divergence `tedi:button-group` documents.

    THE FIELD CONTEXT (`Tedi::fieldContext()`, CONVENTIONS.md §5):
    - `size`: own → the form field's when `small` → `default`.
    - `clearable`: own → the form field's → true.
    - `disabled` / `invalid`: own, or the form field's; `invalid` also when
      `error` is set. `valid`: not invalid, and `success` or the form field's.
    - `.tedi-file-upload__field` paints `tedi-field-surface` (and its
      `--invalid|--valid|--disabled` modifiers) unless the form field's box
      owns the surface, which only its `icon` makes it do here: upstream's
      `ownsClearButton` means the form field never draws a clear button for
      this control, so it registers without `reset`.
    - The form field's describedby ids (its character counter) join the add
      button's `aria-describedby` after the consumer's own and before the
      error / success / hint ids, as upstream's effect orders them.

    `input-id`'s fallback is `Tedi::id('tedi-file-upload')` rather than
    upstream's module counter; the label, add-label and feedback ids are
    derived from it exactly as upstream.
--}}
@props([
    /** Selected files: arrays with `name`, and optional `id`, `isValid`, `isLoading`, `removeAttributes`. Rendered server-side. */
    'files' => [],
    /** Id of the file input, which the field label's `for` points to. Generated when not set. */
    'inputId' => null,
    /** `name` attribute of the file input. */
    'name' => null,
    /** Allowed file types as comma-separated extensions and MIME types, e.g. ".pdf,image/*". Shown in the hint and set on the input; validate on the server. */
    'accept' => null,
    /** Largest accepted file size, in bytes. Shown in the hint; validate on the server. */
    'maxSize' => null,
    /** Whether more than one file can be selected. Lists even one file as a tag. */
    'multiple' => false,
    /** Shows the allowed types and maximum size in the hint. */
    'showRestrictions' => true,
    /** Hint text, shown after the allowed types and maximum size. */
    'hint' => null,
    /** Error text. Marks the field invalid. */
    'error' => null,
    /** Success text, shown while the field has no error. */
    'success' => null,
    /** Whether the clear button shows while files are listed. Null inherits the form field's `clearable` (else true). */
    'clearable' => null,
    /** Shows only the file list, without buttons. */
    'readOnly' => false,
    /** Disables the field. Combines with the form field's `disabled`. */
    'disabled' => false,
    /** Marks the field as invalid. Combines with `error` and the form field's `invalid`. */
    'invalid' => false,
    /** default|small. Null inherits the form field's size (`small`, else `default`). */
    'size' => null,
    /** Explicit replacement for upstream's below-`md` `isStacked()`: the spelled-out clear button and a default-size add button. */
    'stacked' => false,
    /** Extra attributes forwarded to the clear button (e.g. wire:click). */
    'clearAttributes' => [],
])

@php
    $inputId = $inputId ?? \Tedi\Livewire\Tedi::id('tedi-file-upload');
    $files = array_values($files ?? []);
    $fileCount = count($files);

    $errorText = filled($error) ? $error : null;

    // TEDI_FIELD_CONTEXT. Registered without `reset`: upstream's
    // `ownsClearButton` keeps the form field from drawing a clear button.
    $context = \Tedi\Livewire\Tedi::fieldContext($__env, [
        'value' => $files,
        'disabled' => (bool) $disabled,
        'readOnly' => (bool) $readOnly,
        'invalid' => (bool) $invalid || $errorText !== null,
    ]);

    $disabled = (bool) $disabled || (bool) ($context['disabled'] ?? false);
    $invalid = (bool) $invalid || $errorText !== null || (bool) ($context['invalid'] ?? false);
    $valid = ! $invalid && (filled($success) || (bool) ($context['valid'] ?? false));
    $resolvedSize = $size ?? (($context['size'] ?? null) === 'small' ? 'small' : 'default');
    $paintsSurface = ! ($context['ownsSurface'] ?? false);
    $clearableResolved = $clearable !== null ? (bool) $clearable : (bool) ($context['clearable'] ?? true);

    $showClear = $clearableResolved && $fileCount > 0 && ! $disabled;
    $showTags = (bool) $multiple || $fileCount > 1;
    $isEditable = ! $disabled && ! $readOnly;

    // utils/file.util.ts — formatFileSize(): 1024-based, toFixed(1) with a
    // trailing ".0" dropped, up to GB; '' for a negative or non-finite size.
    $formatFileSize = function ($bytes): string {
        if (! is_numeric($bytes) || ! is_finite((float) $bytes) || $bytes < 0) {
            return '';
        }

        $round = fn ($value) => preg_replace('/\.0$/', '', number_format($value, 1, '.', ''));

        return match (true) {
            $bytes < 1024 => $bytes.' B',
            $bytes < 1024 ** 2 => $round($bytes / 1024).' KB',
            $bytes < 1024 ** 3 => $round($bytes / 1024 ** 2).' MB',
            default => $round($bytes / 1024 ** 3).' GB',
        };
    };

    // hintText(): the restrictions, then `hint`. None in read-only mode.
    $hintText = null;
    if (! $readOnly) {
        $hintText = implode('. ', array_filter([
            $showRestrictions && filled($accept) ? __('tedi::tedi.file-upload.accept').' '.str_replace(',', ', ', $accept) : null,
            $showRestrictions && $maxSize ? __('tedi::tedi.file-upload.max-size').' '.$formatFileSize($maxSize) : null,
            filled($hint) ? $hint : null,
        ])) ?: null;
    }

    $successText = $errorText === null && filled($success) ? $success : null;

    $feedbackIds = [
        'error' => $inputId.'-error',
        'success' => $inputId.'-success',
        'hint' => $inputId.'-hint',
    ];

    // controlDescribedBy(): the consumer's own ids, then the pushed ones (the
    // form field's, then error / success / hint), de-duplicated.
    $describedBy = array_values(array_unique(array_merge(
        preg_split('/\s+/', trim((string) $attributes->get('aria-describedby', '')), -1, PREG_SPLIT_NO_EMPTY),
        $context['describedBy'] ?? [],
        array_filter([
            $errorText !== null ? $feedbackIds['error'] : null,
            $successText !== null ? $feedbackIds['success'] : null,
            $hintText !== null ? $feedbackIds['hint'] : null,
        ]),
    )));

    $failedLabel = __('tedi::tedi.file-upload.failed');
    $clearLabel = __('tedi::tedi.clear');

    // Our handler and a consumer's `x-on:click` / `@click` both run: merging
    // them as-is would let the consumer's replace ours (an attribute bag keeps
    // one value per key) — the same join form-field.blade.php makes.
    $withClick = function ($attributes, string $own): array {
        $attributes = $attributes instanceof \Illuminate\View\ComponentAttributeBag ? $attributes->getAttributes() : (array) $attributes;
        $clicks = array_values(array_filter([$attributes['x-on:click'] ?? null, $attributes['@click'] ?? null]));
        unset($attributes['x-on:click'], $attributes['@click']);

        return ['x-on:click' => implode('; ', array_merge([$own], $clicks))] + $attributes;
    };
    $clearButtonAttributes = $withClick($clearAttributes, 'clearFiles()');

    $alpine = \Illuminate\Support\Js::from([
        'inputId' => $inputId,
        'multiple' => (bool) $multiple,
        'labels' => [
            'added' => __('tedi::tedi.file-upload.added'),
            'removed' => __('tedi::tedi.file-upload.removed'),
            'cleared' => __('tedi::tedi.file-upload.cleared'),
        ],
    ]);
@endphp

<tedi-file-upload @class([
    'tedi-file-upload',
    'tedi-file-upload--small' => $resolvedSize === 'small',
    'tedi-file-upload--disabled' => $disabled,
    'tedi-file-upload--read-only' => (bool) $readOnly,
    'tedi-file-upload--empty' => $fileCount === 0,
]) x-data="tediFileUpload({{ $alpine }})">
    @unless ($readOnly)
    <div @class([
        'tedi-file-upload__field',
        'tedi-field-surface' => $paintsSurface,
        'tedi-field-surface--invalid' => $paintsSurface && $invalid,
        'tedi-field-surface--valid' => $paintsSurface && $valid,
        'tedi-field-surface--disabled' => $paintsSurface && $disabled,
    ])>
        <div class="tedi-file-upload__value">
    @endunless

    {{-- #fileList. In read-only mode a single file is still wrapped in the
         list, so the label can name it. --}}
    @if ($showTags)
        <ul class="tedi-file-upload__tags" role="list"@if ($readOnly) x-ref="list"@endif>
            @foreach ($files as $file)
                @php
                    $fileName = (string) ($file['name'] ?? '');
                    $fileLoading = (bool) ($file['isLoading'] ?? false);
                @endphp
                <li class="tedi-file-upload__tag">
                    <tedi:tag
                        :type="($file['isValid'] ?? null) === false ? 'danger' : 'primary'"
                        :loading="$fileLoading"
                        :closable="$isEditable && ! $fileLoading"
                        :close-attributes="$withClick($file['removeAttributes'] ?? [], 'fileRemoved('.\Illuminate\Support\Js::from($fileName).')')"
                    >{{ $fileName }}@if (($file['isValid'] ?? null) === false)<span class="sr-only"> ({{ $failedLabel }})</span>@endif</tedi:tag>
                </li>
            @endforeach
        </ul>
    @elseif ($fileCount > 0)
        @if ($readOnly)
            <ul class="tedi-file-upload__tags" role="list" x-ref="list">
                <li class="tedi-file-upload__tag">
                    <span class="tedi-file-upload__name">{{ $files[0]['name'] ?? '' }}@if (($files[0]['isValid'] ?? null) === false)<span class="sr-only"> ({{ $failedLabel }})</span>@endif</span>
                </li>
            </ul>
        @else
            <span class="tedi-file-upload__name">{{ $files[0]['name'] ?? '' }}@if (($files[0]['isValid'] ?? null) === false)<span class="sr-only"> ({{ $failedLabel }})</span>@endif</span>
        @endif
    @endif

    @unless ($readOnly)
        </div>

        <div class="tedi-file-upload__actions">
            @if ($showClear)
                <div class="tedi-file-upload__clear-group">
                    @if ($stacked)
                        <tedi:button
                            type="button"
                            variant="neutral"
                            icon-start="close"
                            class="tedi-file-upload__clear"
                            {{ $attributes->only([])->merge($clearButtonAttributes) }}
                        >{{ $clearLabel }}</tedi:button>
                    @else
                        <tedi:closing-button
                            size="small"
                            class="tedi-file-upload__clear"
                            :icon-size="18"
                            :aria-label="$clearLabel"
                            {{ $attributes->only([])->merge($clearButtonAttributes) }}
                        />
                    @endif
                    <tedi:separator axis="vertical" size="1.5rem" />
                </div>
            @endif

            <tedi:button
                type="button"
                variant="neutral"
                class="tedi-file-upload__add"
                :size="$stacked ? 'default' : 'small'"
                icon-start="file_upload"
                :disabled="$disabled"
                :aria-describedby="$describedBy !== [] ? implode(' ', $describedBy) : null"
                x-ref="add"
                x-on:click="openPicker()"
            ><span id="{{ $inputId }}-add">{{ __('tedi::tedi.file-upload.add') }}</span></tedi:button>
        </div>
    </div>

    @if ($errorText !== null)
        <tedi:feedback-text :id="$feedbackIds['error']" :text="$errorText" type="error" />
    @elseif ($successText !== null)
        <tedi:feedback-text :id="$feedbackIds['success']" :text="$successText" type="valid" />
    @endif

    @if ($hintText !== null)
        <tedi:feedback-text :id="$feedbackIds['hint']" :text="$hintText" />
    @endif
    @endunless

    <input
        type="file"
        tabindex="-1"
        aria-hidden="true"
        @if ($multiple) multiple @endif
        @disabled($disabled || $readOnly)
        x-ref="input"
        x-on:change="handleSelection($event)"
        {{ $attributes->except('aria-describedby')->class(['tedi-file-upload__input'])->merge(array_filter([
            'id' => $inputId,
            'name' => filled($name) ? $name : null,
            'accept' => filled($accept) ? $accept : null,
        ])) }}
    />
</tedi-file-upload>
