{{--
    TEDI File Upload — DOCUMENTED SUBSET.
    Port of react/src/tedi/components/form/file-upload/file-upload.tsx (CONVENTIONS.md §13).

    The button-triggered sibling of `tedi:file-dropzone`: a row that lists the
    chosen files on the left and carries "Add attachment" — plus a clear
    control — on the right. Distinct component with its own stylesheet; the
    dropzone is a drop *surface*, this is a picker.

    SUBSET — the same shape, and for the same reason, as `tedi:file-dropzone`.
    What you get is the full markup and a real `<input type="file">` that
    `wire:model` binds. What is not ported is `useFileUpload`, upstream's
    client-side pipeline: the internal file list, `maxSize` / `accept`
    *enforcement*, `keepRejectedFiles` (20.x's rename of `validateIndividually`,
    #889), duplicate skipping, the rejected-file messages, and the
    `announcement` string derived from them. In Livewire that work belongs on
    the server — a validation rule, and the resulting list rendered back in
    through `:files`.

    | React | here |
    |---|---|
    | `defaultFiles` + internal state | the `files` prop, rendered server-side |
    | `onChange` / `onDelete` | bind `wire:model` / your own handler (CONVENTIONS.md §7 item 2) |
    | `keepRejectedFiles`, the rejection `errorHelper` | the `helper` prop plus `is_valid => false` on a file; validate server-side |
    | `announcement` | the live region is still rendered — put your own message in `announcement` |

    THE RESTRICTIONS HINT IS PORTED. `accept` and `max-size` generate upstream's
    `getDefaultHelpers` line ("Allowed file extensions: .pdf, .txt. Maximum
    size: 1 KB") under the field, exactly as `useFileUpload` does when nothing
    has been rejected. `max-size` is in **bytes** since 20.x (#889 — it used to
    be megabytes), formatted with upstream's 1024-based `formatFileSize`
    (`512 B`, `1 KB`, `2.5 MB`). `show-restrictions="false"` suppresses the
    hint (upstream's `showRestrictions`, #786). A `helper` you pass wins over
    the hint, as upstream's `errorHelper ?? restrictionsHint` does.
    `max-size` is not enforced client-side; `accept` still lands on the native
    input.

    THE BREAKPOINT SWAP IS NOT PORTED. Upstream renders the clear control as a
    full `<Button icon="close">` below `md` and as a `ClosingButton` above it
    (CONVENTIONS.md §7 item 1). This always renders the `ClosingButton`, which
    is the desktop branch and the one the stylesheet is written around.

    CLASSES DROPPED. Upstream emits `tedi-file-upload__label-wrapper` and
    `tedi-file-upload__label`; neither has a rule in `file-upload.module.scss`,
    so per CONVENTIONS.md §4 they are not emitted. Restore them if TEDI ships
    the rules. `tedi-file-upload__container--{size}` gained rules upstream and
    is emitted.

    THE CLEAR BUTTON CARRIES `data-name="closing-button"`. That is not
    decoration: the shared `_field-icon-button` partial keys on it
    (`button:not([data-name='closing-button']):last-child`) to keep the clear
    control out of the picker-icon hover treatment.

    Each `files` entry is an array with `name`, plus optional `is_loading` and
    `is_valid`. `is_valid => false` renders the danger tag and the
    screen-reader failure note, as upstream. One file renders as truncating
    text and several as a tag list — that switch is upstream's `showFiles()`,
    and it is written out in both branches rather than shared, because
    duplicating markup is what CONVENTIONS.md §2 asks for over a tag that
    straddles a conditional.

    `{{ $attributes }}` sits on the `<input>`, not the wrapper, so `wire:model`
    binds the control (CONVENTIONS.md §6) — as in `tedi:file-dropzone`.
--}}
@props([
    /** Id for the input; also the label's `for`. Auto-generated when omitted. */
    'id' => null,
    /** Name attribute of the file input. */
    'name' => null,
    /** Visible label above the field. */
    'label' => null,
    /** Comma-separated accepted types, e.g. ".pdf,.docx" or "image/*". */
    'accept' => null,
    /** Maximum file size in bytes (e.g. 5 * 1024 ** 2 for 5 MB). Shown in the hint; not enforced client-side. */
    'maxSize' => null,
    /** Show the auto-generated accept / max-size hint below the field. */
    'showRestrictions' => true,
    /** Allow picking several files. */
    'multiple' => false,
    /** Chosen files: [['name' => …, 'is_loading' => false, 'is_valid' => true], …]. */
    'files' => [],
    /** Show the clear control when there are files. */
    'hasClearButton' => true,
    /** Renders the file list only — no input, no buttons. */
    'readOnly' => false,
    /** Disables the input and both buttons. */
    'disabled' => false,
    /** default|small — forwarded to the label and the add button. */
    'size' => 'default',
    /** ['text' => …, 'type' => 'hint'|'error'|'valid'] rendered under the field. */
    'helper' => null,
    /** Text for the polite live region. */
    'announcement' => null,
    /** Marks the label required. */
    'required' => false,
])

@php
    $id = $id ?? \Tedi\Livewire\Tedi::id('tedi-file-upload');
    $files = array_values($files);

    // use-file-upload.ts — formatFileSize(): 1024-based, one decimal, sub-unit
    // values rolled down (0.5 MB reads as 512 KB).
    $formatFileSize = function ($bytes) {
        if ($bytes < 1024) {
            return $bytes.' B';
        }

        $units = ['KB', 'MB', 'GB', 'TB'];
        $value = $bytes / 1024;
        $unit = 0;

        while ($value >= 1024 && $unit < count($units) - 1) {
            $value /= 1024;
            $unit++;
        }

        // Math.round(value * 10) / 10 — JS prints no trailing ".0".
        $rounded = round($value * 10) / 10;

        return (floor($rounded) == $rounded ? (string) (int) $rounded : (string) $rounded).' '.$units[$unit];
    };

    // getDefaultHelpers(), gated by showRestrictions — the restrictionsHint.
    $restrictionsText = implode('. ', array_filter([
        $accept ? __('tedi::tedi.file-upload.accept').' '.str_replace(',', ', ', $accept) : null,
        $maxSize ? __('tedi::tedi.file-upload.max-size').' '.$formatFileSize($maxSize) : null,
    ]));
    $restrictionsHint = $showRestrictions && $restrictionsText !== '' ? ['type' => 'hint', 'text' => $restrictionsText] : null;

    // uploadErrorHelper = errorHelper ?? restrictionsHint
    $helper = $helper ?: $restrictionsHint;
    $helperType = $helper['type'] ?? null;
    $helperId = $helper ? $id.'-helper' : null;
    $failedLabel = __('tedi::tedi.file-upload.failed');
@endphp

{{-- Upstream's root is a Fragment. Blade needs one root element (CONVENTIONS.md
     §6), so this is a classless div — inventing a wrapper class would break §4.
     It carries the `x-data` scope the two buttons' `$refs` resolve against. --}}
<div x-data>
    @if (filled($label))
        <tedi:form.label for="{{ $id }}" :size="$size" :required="(bool) $required">{{ $label }}</tedi:form.label>
    @endif

    {{-- Upstream's announcement region. Always rendered, so a Livewire update
         into it is announced rather than creating a new live region. --}}
    <div role="status" aria-live="polite" aria-atomic="true" class="sr-only">{{ $announcement }}</div>

    @if ($readOnly)
        @if (count($files) === 1)
            <span class="tedi-file-upload__items tedi-file-upload__items--truncate">{{ $files[0]['name'] ?? '' }}@if (($files[0]['is_valid'] ?? true) === false)<span class="sr-only"> ({{ $failedLabel }})</span>@endif</span>
        @elseif (count($files) > 1)
            <ul class="tedi-file-upload__items">
                @foreach ($files as $file)
                    <li>
                        <tedi:tag
                            role="presentation"
                            :type="($file['is_valid'] ?? true) === false ? 'danger' : 'primary'"
                            :loading="(bool) ($file['is_loading'] ?? false)"
                        >{{ $file['name'] ?? '' }}@if (($file['is_valid'] ?? true) === false)<span class="sr-only"> ({{ $failedLabel }})</span>@endif</tedi:tag>
                    </li>
                @endforeach
            </ul>
        @endif
    @else
        <div @class([
            'tedi-file-upload__container',
            'tedi-file-upload__container--'.$size => (bool) $size,
            'tedi-file-upload--disabled' => (bool) $disabled,
            'tedi-file-upload--error' => $helperType === 'error',
            'tedi-file-upload--valid' => $helperType === 'valid',
        ])>
            <div class="tedi-file-upload__content">
                <tedi:row>
                    <tedi:col class="flex">
                        @if (count($files) === 1)
                            <span class="tedi-file-upload__items tedi-file-upload__items--truncate">{{ $files[0]['name'] ?? '' }}@if (($files[0]['is_valid'] ?? true) === false)<span class="sr-only"> ({{ $failedLabel }})</span>@endif</span>
                        @elseif (count($files) > 1)
                            <ul class="tedi-file-upload__items">
                                @foreach ($files as $file)
                                    <li>
                                        <tedi:tag
                                            role="presentation"
                                            :type="($file['is_valid'] ?? true) === false ? 'danger' : 'primary'"
                                            :loading="(bool) ($file['is_loading'] ?? false)"
                                            :closable="! ($file['is_loading'] ?? false) && ! $disabled"
                                        >{{ $file['name'] ?? '' }}@if (($file['is_valid'] ?? true) === false)<span class="sr-only"> ({{ $failedLabel }})</span>@endif</tedi:tag>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </tedi:col>
                    <tedi:col>
                        <div @class([
                            'tedi-file-upload',
                            'tedi-file-upload--disabled' => (bool) $disabled,
                        ])>
                            <input
                                id="{{ $id }}"
                                type="file"
                                @if ($name) name="{{ $name }}" @endif
                                @if ($accept) accept="{{ $accept }}" @endif
                                @if ($multiple) multiple @endif
                                @disabled($disabled)
                                aria-invalid="{{ $helperType === 'error' ? 'true' : 'false' }}"
                                @if ($helperId) aria-describedby="{{ $helperId }}" @endif
                                x-ref="input"
                                {{ $attributes }}
                            />

                            @if ($hasClearButton && count($files) > 0 && ! $disabled)
                                <tedi:closing-button
                                    :icon-size="18"
                                    :aria-label="__('tedi::tedi.clear')"
                                    data-name="closing-button"
                                    x-on:click="$refs.input.value = ''; $refs.add && $refs.add.focus()"
                                />
                                <tedi:separator axis="vertical" size="1.5rem" :spacing="0.5" color="primary" />
                            @endif

                            <tedi:button
                                variant="neutral"
                                icon-start="file_upload"
                                :size="$size"
                                :disabled="(bool) $disabled"
                                class="tedi-file-upload__button"
                                x-ref="add"
                                x-on:click="$refs.input.click()"
                            >{{ __('tedi::tedi.file-upload.add') }}</tedi:button>
                        </div>
                    </tedi:col>
                </tedi:row>
            </div>
        </div>
    @endif

    @if ($helper)
        <tedi:feedback-text
            id="{{ $helperId }}"
            :text="$helper['text'] ?? ''"
            :type="$helper['type'] ?? 'hint'"
        />
    @endif

    {{ $slot }}
</div>
