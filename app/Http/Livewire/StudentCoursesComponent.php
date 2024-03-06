<?php

namespace App\Http\Livewire;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order_item;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;
use Stripe\OrderItem;

class StudentCoursesComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $items_per_page = 25;
    public $selected_course;
    protected $listeners = ['delete' => 'delete'];
    public function updated()
    {
        $this->resetPage();
    }
    public function confirm_remove($user_id)
    {
        $this->dispatchBrowserEvent('confirm_delete', [
            'type' => 'warning',
            'title' => __('are you sure you want to remove this student from the course'),
            'text' => '',
            'id' => $user_id,
        ]);
    }
    public function delete($user_id)
    {
        Enrollment::where('user_id', $user_id)->where('course_id', $this->selected_course)->delete();
        Order_item::where('user_id', $user_id)->where('course_id', $this->selected_course)->delete();
        $this->dispatchBrowserEvent('alert_message', [
            'type' => 'success',
            'title' => __('student was removed from course successfully !'),
            'text' => '',
        ]);
    }
    public function render()
    {

        $data['courses'] = Course::all();
        if ($this->selected_course) {
            $data['students'] = Student::with('user')->has('user')->whereHas('user.enrollments', function ($q) {
                $q->where('course_id', $this->selected_course);
            })->orderBy('id', 'DESC')->paginate($this->items_per_page);
        } else {
            $data['students'] = Student::with('user')->has('user')->orderBy('id', 'DESC')->paginate($this->items_per_page);
        }
        return view('livewire.student-courses-component', $data);
    }
}
