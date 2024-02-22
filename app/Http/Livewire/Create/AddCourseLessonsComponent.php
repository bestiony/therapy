<?php

namespace App\Http\Livewire\Create;

use App\Models\Course;
use App\Models\Course_lesson;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class AddCourseLessonsComponent extends Component
{
    public Course $course;
    public $lessons;
    public $newLessonName;
    public $showAddNewLesson = false;
    public $currentSection;
    public $currentAccordion = 0;
    protected $listeners = [
        'sectionUpdated' => 'remountSections',
        'deleteSection' => 'deleteSection',
        'deleteLecture' => 'deleteLecture',
    ];
    public function remountSections()
    {
        $this->course->load('lessons.lectures');
        $this->lessons = $this->course->lessons;
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

    public function confirmDelete($lessonId, $deleteEvent)
    {
        $this->dispatchBrowserEvent('triggerConfirmDelete', ['itemId' => $lessonId, 'deleteEvent' => $deleteEvent]);
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
        $lecture = $this->course->lectures()->where('course_lectures.id', $lectureId)->firstOrFail();
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
        return view('livewire.create.add-course-lessons-component');
    }
}
