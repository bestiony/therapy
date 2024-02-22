<?php

namespace App\Http\Livewire\Edit;

use App\Models\Course_lesson;
use Livewire\Component;

class EditSectionNameComponent extends Component
{
    public  $section_id = null;
    public  $section_name = null;
    protected $listeners = ['sectionChanged' => 'mount'];

    public function mount($section_id, $section_name)
    {
        $this->section_id = $section_id;
        $this->section_name = $section_name;
    }

    public function updateSection()
    {
        Course_lesson::where('id',$this->section_id)->update([
            'name' => $this->section_name,
        ]);
        $this->emit('sectionUpdated');
        $this->dispatchBrowserEvent('closeModal');

    }
    public function render()
    {
        return view('livewire.edit.edit-section-name-component');
    }
}
