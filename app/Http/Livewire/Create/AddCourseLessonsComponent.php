<?php

namespace App\Http\Livewire\Create;

use App\Models\Course;
use App\Models\Course_lesson;
use App\Traits\ImageSaveTrait;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class AddCourseLessonsComponent extends Component
{
    use ImageSaveTrait;
    public Course $course;
    public $lessons;
    public $newLessonName;
    public $showAddNewLesson = false;
    public $currentSection;
    public $currentLecture;
    public $currentFilePath;
    public $currentAccordion = 0;
    protected $listeners = [
        'sectionUpdated' => 'remountSections',
        'deleteSection' => 'deleteSection',
        'deleteLecture' => 'deleteLecture',
        'lectureAdded' => 'remountSections'
    ];
    public function remountSections()
    {
        $this->course->load('lessons.lectures');
        $this->lessons = $this->course->lessons;
    }
    public function setCurrentLecture($lecture)
    {
        $this->currentLecture = $lecture;
        $this->emit('lectureChanged', $lecture['id']);
    }
    public function setCurrentFilePath($filePath)
    {
        $this->currentFilePath = $filePath;
        $this->emit('filePathChanged', $filePath);
    }
    public function setCurrentAccordion($accordion)
    {
        if ($this->currentAccordion != $accordion) {
            $this->currentAccordion = $accordion;
        } else {
            $this->currentAccordion = null;
        }
    }
    public function setCurrentSection($section)
    {
        $this->currentSection = $section;
        $this->emit('sectionChanged', $section['id'], $section['name']);
    }
    public function toggleAddNewLesson()
    {
        $this->showAddNewLesson = !$this->showAddNewLesson;
    }
    public function addNewLesson()
    {
        $this->course->lessons()->create([
            'name' => $this->newLessonName,
        ]);
        $this->newLessonName = '';
        $this->course->load('lessons.lectures');
        $this->lessons = $this->course->lessons;
        $this->showAddNewLesson = false;
    }

    public function confirmDelete($itemId, $deleteEvent)
    {
        $this->dispatchBrowserEvent('triggerConfirmDelete', ['itemId' => $itemId, 'deleteEvent' => $deleteEvent]);
    }
    public function deleteSection(int $lessonId)
    {
        if (Gate::denies('update', $this->course)) {
            $this->showToastrMessage('error', __('You don\'t have permission to edit this'));
            return redirect()->to('/');
        }
        $this->course->lessons()->where('course_lessons.id', $lessonId)->delete();
        $this->remountSections();
    }
    public function deleteLecture(int $lectureId)
    {
        if (Gate::denies('update', $this->course)) {
            $this->showToastrMessage('error', __('You don\'t have permission to edit this'));
            return redirect()->to('/');
        }
        $lecture = $this->course->lectures()->where('id', $lectureId)->first();


        $this->deleteFile($lecture->file_path); // delete file from server

        if ($lecture->type == 'vimeo') {
            if ($lecture->url_path) {
                $this->deleteVimeoVideoFile($lecture->url_path);
            }
        }
        $lecture->delete();
        $this->remountSections();
    }
    public function mount()
    {
        $this->course->load('lessons.lectures');
        $this->lessons = $this->course->lessons;
    }
    public function render()
    {
        $this->dispatchBrowserEvent('contentUpdated');

        return view('livewire.create.add-course-lessons-component');
    }
}
