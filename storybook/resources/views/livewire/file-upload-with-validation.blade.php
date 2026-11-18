<?php

/*
 * Backs the TEDI-Ready/Components/Form/FileUpload "With Reactive Forms"
 * story: upstream binds the field to an Angular FormControl with
 * `Validators.required`; the Livewire equivalent is `wire:model` plus server
 * validation. The field's own checks (`accept`, `max-size`) run before the
 * upload, with rejected files kept in the list; the server's rules decide.
 */

use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public $uploads = [];

    public bool $dirty = false;

    protected function rules(): array
    {
        return [
            'uploads' => 'required|array|min:1',
            'uploads.*' => 'file|extensions:pdf,txt|max:1024',
        ];
    }

    protected function validationAttributes(): array
    {
        return ['uploads' => 'files', 'uploads.*' => 'file'];
    }

    public function updatedUploads(): void
    {
        $this->dirty = true;
        $this->validate();
    }

    public function remove(int $index): void
    {
        $uploads = $this->uploads;
        array_splice($uploads, $index, 1);
        $this->uploads = $uploads;
        $this->dirty = true;
        $this->validate();
    }

    public function resetForm(): void
    {
        $this->reset('uploads', 'dirty');
        $this->resetValidation();
    }
};
?>

<div>
    <tedi:row :cols="1" :gap-y="3">
        <tedi:col>
            <tedi:form-field>
                <x-slot:label>
                    <tedi:form.label for="reactive" :required="true">Label</tedi:form.label>
                </x-slot:label>
                <tedi:file-upload
                    input-id="reactive"
                    name="file"
                    multiple
                    accept=".pdf,.txt"
                    :max-size="1024 ** 2"
                    keep-rejected-files
                    wire:model="uploads"
                    :error="$errors->first('uploads') ?: ($errors->first('uploads.*') ?: null)"
                    :files="collect($uploads)->values()->map(fn ($upload, $index) => [
                        'id' => $upload->getFilename(),
                        'name' => $upload->getClientOriginalName(),
                        'isValid' => ! $errors->has('uploads.'.$index),
                        'removeAttributes' => ['wire:click' => 'remove('.$index.')'],
                    ])->all()"
                    :clear-attributes="['wire:click' => 'resetForm']"
                />
            </tedi:form-field>
        </tedi:col>
        <tedi:col>
            <tedi:button type="button" variant="secondary" wire:click="resetForm">Reset form</tedi:button>
        </tedi:col>
        <tedi:col>
            <tedi:alert type="info">
                <tedi:text as="pre" modifiers="small">{{ json_encode([
                    'files' => count($uploads),
                    'invalidFiles' => collect($errors->getMessages())->keys()->filter(fn ($key) => str_starts_with($key, 'uploads.'))->count(),
                    'dirty' => $dirty,
                    'invalid' => $errors->isNotEmpty(),
                ], JSON_PRETTY_PRINT) }}</tedi:text>
            </tedi:alert>
        </tedi:col>
    </tedi:row>
</div>
