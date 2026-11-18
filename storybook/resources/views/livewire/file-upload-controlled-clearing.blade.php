<?php

/*
 * Backs the TEDI-Ready/Components/Form/FileUpload "Controlled Clearing" story:
 * upstream's two-way `[(files)]` signal, as a Livewire property. The list is
 * this component's `$files`; `wire:model` uploads what the field selects, and
 * `updatedUploads()` merges it in. Removing a file and the outside "Clear
 * files" button both change `$files`, and the field follows.
 */

use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public $uploads = [];

    public array $files = [
        ['id' => '1', 'name' => 'report.pdf'],
        ['id' => '2', 'name' => 'report_1.pdf'],
        ['id' => '3', 'name' => 'report_2.pdf'],
    ];

    public function updatedUploads(): void
    {
        foreach ($this->uploads as $upload) {
            $name = $upload->getClientOriginalName();

            if (! in_array($name, array_column($this->files, 'name'), true)) {
                $this->files[] = ['id' => $upload->getFilename(), 'name' => $name];
            }
        }
    }

    public function remove(int $index): void
    {
        array_splice($this->files, $index, 1);
    }

    public function clear(): void
    {
        $this->files = [];
        $this->uploads = [];
    }
};
?>

<div>
    <tedi:row :cols="1" :gap-y="3">
        <tedi:col>
            <tedi:form-field>
                <x-slot:label>
                    <tedi:form.label for="controlled">Label</tedi:form.label>
                </x-slot:label>
                <tedi:file-upload
                    input-id="controlled"
                    name="file"
                    multiple
                    wire:model="uploads"
                    :files="array_map(fn ($file, $index) => $file + ['removeAttributes' => ['wire:click' => 'remove('.$index.')']], $files, array_keys($files))"
                    :clear-attributes="['wire:click' => 'clear']"
                />
            </tedi:form-field>
        </tedi:col>
        <tedi:col>
            <tedi:button type="button" wire:click="clear">Clear files</tedi:button>
        </tedi:col>
        <tedi:col>
            <tedi:alert type="info">
                <tedi:text as="pre" modifiers="small">{{ json_encode(['files' => array_column($files, 'name')], JSON_PRETTY_PRINT) }}</tedi:text>
            </tedi:alert>
        </tedi:col>
    </tedi:row>
</div>
