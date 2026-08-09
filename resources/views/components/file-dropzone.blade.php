{{--
    TEDI File Dropzone (community).
    Port of angular/community/components/form/file-dropzone/file-dropzone.component.{ts,html}

    Ported from the `community/` tree — see CONVENTIONS.md §12. Angular's
    selector is the element `tedi-file-dropzone`, so per §4 the root is that
    literal custom element — note that `.tedi-file-dropzone` itself belongs to
    the inner `<button>`, which is the dropzone surface, not to the host.

    SUBSET — the same shape as `tedi:select` and `tedi:table` (CONVENTIONS.md
    §7.4). What you get is the full markup plus the browser's own file input;
    what is not ported is Angular's client-side file *pipeline*: `FileService`
    (duplicate-name renaming, append/replace modes), the `ControlValueAccessor`,
    and the async validator machinery (`validators`, `validateFileSize`,
    `validateFileType`, `runValidators`, `uploadState`). In Livewire the file
    list and its validation live on the server — `wire:model` on the input, a
    validation rule, and the resulting list rendered back through `:files`.

    Consequently:

    | Angular | here |
    |---|---|
    | `defaultFiles` + the internal file list | the `files` prop, rendered server-side |
    | `uploadState` ('valid'/'invalid'), set by the validator | the `state` prop |
    | `validateIndividually` + per-file `helper` | an `error` key on each `files` entry |
    | the aggregated `uploadError` | the `error` prop / `error-text` slot |
    | `mode` ('append'/'replace'), `validators` | not ported — the server decides |
    | `fileChange` / `fileDelete` outputs | bind `wire:change` / your own handler (CONVENTIONS.md §7.2) |

    `accept` and `max-size` still do two real things: they set the native
    `accept` attribute, and they generate the hint line under the dropzone
    (upstream's `getDefaultHelpers`, ported below along with `formatBytes`).
    `max-size` is not enforced client-side — it was not really enforced upstream
    either, since the validator ran on the form control.

    Alpine adds only what the markup cannot do by itself: click-through from the
    dropzone surface to the hidden input, the drag-over class, and dropping
    files into the input so `wire:model` sees them. The label and icon swap by
    `x-text` on the existing nodes rather than by adding elements, so the DOM
    stays the one upstream renders.

    `{{ $attributes }}` sits on the `<input>`, not the wrapper, so `wire:model`
    binds the control (CONVENTIONS.md §6).
--}}
@props([
    /** Comma-separated accepted types, e.g. ".pdf,.docx" or "image/*". */
    'accept' => '',
    /** Maximum size in bytes. Shown in the hint; not enforced client-side. */
    'maxSize' => 0,
    /** SI (1 kB = 1000 B) | IEC (1 KiB = 1024 B) — how sizes are formatted. */
    'sizeDisplayStandard' => 'IEC',
    /** Allow selecting several files. */
    'multiple' => false,
    /** Allow picking a directory (`webkitdirectory`). */
    'uploadFolder' => false,
    /** Id of the input; also what a `tedi:form.label` should point at. */
    'inputId' => null,
    /** Name attribute of the input. */
    'name' => null,
    /** Dropzone label. Defaults to the translated `file-dropzone.label`. */
    'label' => null,
    /** none|valid|invalid — the validated state border. Overridden by `has-error`. */
    'state' => 'none',
    /** Forces the error border regardless of `state`. */
    'hasError' => false,
    /** Aggregated error message shown under the list. Ignored when the `error-text` slot is used. */
    'error' => null,
    'disabled' => false,
    /**
     * Files to list under the dropzone. Each entry:
     * ['name' => string, 'size' => int|null, 'error' => string|null, 'invalid' => bool|null,
     *  'label' => string|null, 'disabled' => bool|null]
     */
    'files' => [],
])

@php
    $inputId = $inputId ?? \Tedi\Livewire\Tedi::id('tedi-file-dropzone');
    $label = $label ?? __('tedi::tedi.file-dropzone.label');

    // utils.ts — roundNumber() + formatBytes(), including the SI/IEC unit names.
    $formatBytes = function ($bytes) use ($sizeDisplayStandard) {
        $kB = $sizeDisplayStandard === 'SI' ? 1000 : 1024;
        $mB = $kB * $kB;

        $round = function ($number) {
            $rounded = number_format($number, 2, '.', '');

            return str_contains($rounded, '.') ? rtrim(rtrim($rounded, '0'), '.') : $rounded;
        };

        if ($bytes >= $mB) {
            return $round($bytes / $mB).' '.($sizeDisplayStandard === 'SI' ? 'MB' : 'MiB');
        }

        if ($bytes >= $kB) {
            return $round($bytes / $kB).' '.($sizeDisplayStandard === 'SI' ? 'kB' : 'KiB');
        }

        return $bytes.' B';
    };

    // utils.ts — getDefaultHelpers()
    $hintParts = array_filter([
        $accept ? __('tedi::tedi.file-upload.accept').' '.str_replace(',', ', ', $accept) : null,
        $maxSize ? __('tedi::tedi.file-upload.max-size').' '.$formatBytes($maxSize) : null,
    ]);
    $hint = implode('. ', $hintParts);
@endphp

<tedi-file-dropzone
    x-data="{ dragActive: false }"
    x-on:dragenter.prevent="dragActive = true"
    x-on:dragover.prevent
    x-on:dragleave.prevent="dragActive = false"
    x-on:drop.prevent="
        dragActive = false;
        if ($event.dataTransfer && $event.dataTransfer.files.length) {
            $refs.input.files = $event.dataTransfer.files;
            $refs.input.dispatchEvent(new Event('change', { bubbles: true }));
        }
    "
>
    <input
        x-ref="input"
        type="file"
        class="tedi-file-dropzone__input"
        id="{{ $inputId }}"
        @if ($name) name="{{ $name }}" @endif
        @if ($accept) accept="{{ $accept }}" @endif
        @if ($multiple) multiple @endif
        @if ($uploadFolder) webkitdirectory @endif
        @disabled($disabled)
        {{ $attributes }}
    />

    <button
        type="button"
        @disabled($disabled)
        x-on:click="$refs.input.click()"
        {{-- Real class list first, bound class after (CONVENTIONS.md §4). --}}
        class="{{ collect([
            'tedi-file-dropzone',
            $disabled ? 'tedi-file-dropzone--disabled' : null,
            $hasError ? 'tedi-file-dropzone--invalid' : ($state !== 'none' ? 'tedi-file-dropzone--'.$state : null),
        ])->filter()->implode(' ') }}"
        x-bind:class="{ 'tedi-file-dropzone--drop-over': dragActive }"
    >
        <div class="tedi-file-dropzone__label-wrapper">
            <tedi:icon
                name="attach_file"
                :color="$disabled ? 'tertiary' : 'secondary'"
                x-text="dragActive ? 'file_upload' : 'attach_file'"
            />
            <tedi:form.label
                :for="$inputId"
                class="tedi-file-dropzone__label"
                x-text="dragActive ? @js(__('tedi::tedi.file-upload.drag-and-drop')) : @js($label)"
            >{{ $label }}</tedi:form.label>
        </div>
    </button>

    @if (isset($helperText) && $helperText->isNotEmpty())
        {{ $helperText }}
    @elseif ($hint)
        <tedi:feedback-text :text="$hint" type="hint" position="left" />
    @endif

    @if (isset($fileList) && $fileList->isNotEmpty())
        {{ $fileList }}
    @else
        @foreach ($files as $file)
            <div class="tedi-file-dropzone__file-list">
                <tedi:attachment
                    :name="$file['label'] ?? $file['name']"
                    :file-size="isset($file['size']) ? $formatBytes($file['size']) : null"
                    :invalid="(bool) ($file['invalid'] ?? false)"
                    :error="$file['error'] ?? null"
                >
                    <x-slot:actions>
                        <tedi:button
                            variant="neutral"
                            icon-only
                            icon-start="delete"
                            :aria-label="__('tedi::tedi.remove').' '.($file['label'] ?? $file['name'])"
                            :disabled="(bool) ($file['disabled'] ?? false)"
                        />
                    </x-slot:actions>
                </tedi:attachment>
            </div>
        @endforeach
    @endif

    @if (isset($errorText) && $errorText->isNotEmpty())
        {{ $errorText }}
    @elseif ($error)
        <tedi:feedback-text :text="$error" type="error" position="left" />
    @endif
</tedi-file-dropzone>
