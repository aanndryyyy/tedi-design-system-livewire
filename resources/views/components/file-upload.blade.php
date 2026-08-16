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
    validation, `validateIndividually`, the rejected-file messages, and the
    `announcement` string derived from them. In Livewire that work belongs on
    the server — a validation rule, and the resulting list rendered back in
    through `:files`.

    | React | here |
    |---|---|
    | `defaultFiles` + internal state | the `files` prop, rendered server-side |
    | `onChange` / `onDelete` | bind `wire:model` / your own handler (CONVENTIONS.md §7 item 2) |
    | `maxSize`, `validateIndividually`, `uploadErrorHelper` | `max-size` + `show-restrictions` build a hint helper when `helper` is omitted; validate server-side |
    | `announcement` | the live region is still rendered — put your own message in `announcement` |

    THE BREAKPOINT SWAP IS NOT PORTED. Upstream renders the clear control as a
    full `<Button icon="close">` below `md` and as a `ClosingButton` above it
    (CONVENTIONS.md §7 item 1). This always renders the `ClosingButton`, which
    is the desktop branch and the one the stylesheet is written around.

    CLASSES DROPPED. Upstream emits `tedi-file-upload__label-wrapper`,
    `tedi-file-upload__label` and `tedi-file-upload__container--{size}`; none of
    the three has a rule in `file-upload.module.scss`, so per CONVENTIONS.md §4
    they are not emitted. Restore them if TEDI ships the rules.

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
    /** Maximum file size in MB — used only for the restrictions hint text. */
    'maxSize' => null,
    /**
     * Show the auto-generated accept/max-size hint when `helper` is omitted.
     * Turn off when the same info is shown elsewhere. Default true (React).
     */
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

    if ($helper === null && $showRestrictions && ($accept || $maxSize !== null && $maxSize !== '')) {
        $parts = [];
        if ($accept) {
            $parts[] = __('tedi::tedi.file-upload.accept').' '.str_replace(',', ', ', (string) $accept);
        }
        if ($maxSize !== null && $maxSize !== '') {
            $parts[] = __('tedi::tedi.file-upload.max-size').' '.$maxSize.'MB';
        }
        $helper = ['text' => implode(' ', $parts), 'type' => 'hint'];
    }

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
            'tedi-file-upload--disabled' => (bool) $disabled,
            'tedi-file-upload--error' => $helperType === 'error',
            'tedi-file-upload--valid' => $helperType === 'valid',
        ])>
            <div class="tedi-file-upload__content">
                <tedi:row>
                    <tedi:col class="display-flex">
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
