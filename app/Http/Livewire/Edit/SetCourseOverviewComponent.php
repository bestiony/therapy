<?php

namespace App\Http\Livewire\Edit;

use App\Models\Course;
use App\Models\LearnKeyPoint;
use App\Traits\General;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Str;


class SetCourseOverviewComponent extends Component
{
    use General;
    // menu properties
    public $navCourseUploadActiveClass;
    public $conditions;
    public $title;
    // course properties
    public Course $course;
    public $courseTitle;
    public $course_type;
    public $subtitle;
    public $description;
    public $private_mode;
    public $is_subscription_enable;
    public array $key_points = [];
    public $new_key_point;
    public $user;
    public bool  $forOrganization;
    public function mount(
        $title,
        $navCourseUploadActiveClass,
        $conditions,
        $course,
    ) {
        $this->title = $title;
        $this->navCourseUploadActiveClass = $navCourseUploadActiveClass;
        $this->conditions = $conditions;
        $this->course = $course;
        $this->key_points = $this->course->keyPoints()->select('id', 'name')->get()->toArray();
        $this->user = auth()->user();

        $this->forOrganization = $this->user->role == USER_ROLE_ORGANIZATION;
        $this->fill([
            'courseTitle' => $this->course->title,
            'course_type' => $this->course->course_type,
            'subtitle' => $this->course->subtitle,
            'description' => $this->course->description,
            'private_mode' => $this->course->private_mode,
            'is_subscription_enable' => $this->course->is_subscription_enable,
        ]);
    }
    public function render()
    {

        return view('livewire.edit.set-course-overview-component');
    }
    public function addKeyPoint()
    {
        $this->validate([
            'new_key_point' => ['required', 'string'],
        ]);
        $this->key_points[] =  ['name' => $this->new_key_point];

        $this->new_key_point = '';
    }
    public function removeKeyPoint($index)
    {
        $point = $this->key_points[$index];
        if (isset($point['id'])) {
            LearnKeyPoint::find($point['id'])->delete();
        }
        unset($this->key_points[$index]);
    }
    public function rules()
    {
        $rules = [
            'courseTitle' => ['required', 'string', 'max:255'],
            'course_type' => ['required'],
            'subtitle' => ['required', 'string', 'max:1000'],
            'description' => ['required'],
        ];
        if ($this->forOrganization) {
            $rules['private_mode'] = ['required', 'boolean'];
        }
        return $rules;
    }
    public function setCourseOverview()
    {
        $this->validate();
        if (Course::where('slug', Str::slug($this->title))->count() > 0) {
            $slug = Str::slug($this->title) . '-' . rand(100000, 999999);
        } else {
            $slug = Str::slug($this->title);
        }

        $data = [
            'title' => $this->courseTitle,
            'private_mode' => $this->private_mode,
            'course_type' => $this->course_type,
            'subtitle' => $this->subtitle,
            'slug' => $slug,
            'status' => DRAFT_COURSE,
            'description' => $this->description
        ];

        $data['is_subscription_enable'] = 0;

        if (get_option('subscription_mode')) {
            $data['is_subscription_enable'] = $this->is_subscription_enable;
        }

        if ($data['is_subscription_enable']) {
            $count = Course::where('user_id', auth()->id())->count();
            if (!hasLimitSaaS(PACKAGE_RULE_SUBSCRIPTION_COURSE, PACKAGE_TYPE_SAAS_ORGANIZATION, $count)) {
                $this->showToastrMessage('error', __('Your Subscription Enable Course Create limit has been finish.'));
                return redirect()->back();
            }
        }


        $this->course->update($data);
        foreach ($this->key_points as $item) {
            if (isset($item['id'])) {
                LearnKeyPoint::find($item['id'])->update(['name' => $item['name']]);
            } else {
                $this->course->keyPoints()->create(['name' => $item['name']]);
            }
        }


        return redirect(route('organization.course.set-category', ['uuid'=>$this->course->uuid]));
    }
}
