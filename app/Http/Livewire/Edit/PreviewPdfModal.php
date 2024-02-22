<?php

namespace App\Http\Livewire\Edit;

use Livewire\Component;

class PreviewPdfModal extends Component
{
    public $file_path;
    protected $listeners = ['filePathChanged' => 'remountCurrentFilePath'];
    public function remountCurrentFilePath($filePath)
    {
        $this->file_path = $filePath;
        $this->emit('loadPdf', $filePath);
    }
    public function mount($file_path = null)
    {
        $this->file_path = $file_path;
    }
    public function render()
    {
        return view('livewire.edit.preview-pdf-modal');
    }
}
