<?php

namespace App\Http\Livewire\Edit;

use App\Models\Course;
use App\Models\CourseVersion;
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
    public CourseVersion $courseVersion;
    public $details = [];
    public $courseTitle;
    public $course_type;
    public $subtitle;
    public $description;
    public $private_mode;
    public $is_subscription_enable;

    public array $original_key_points;
    public array $key_points = [];
    public array $deleted_key_points = [];
    public array $updated_key_points = [];
    public array $new_key_points = [];
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

        $this->courseVersion = $course->activeCourseVersion();
        $this->details = $this->courseVersion->details;
        // dd($this->course);
        $this->user = auth()->user();


        $this->updated_key_points = data_get($this->details, 'updated_key_points', []);
        $this->new_key_points = data_get($this->details, 'new_key_points', []);
        $this->deleted_key_points = data_get($this->details, 'deleted_key_points', []);

        $this->original_key_points = $this->course->keyPoints->pluck('name', 'id')->toArray();

        $key_points = LearnKeyPoint::whereIn('id', $this->new_key_points)->get()->concat($this->course->keyPoints);
        $this->key_points = $key_points->mapWithKeys(function ($keyPoint) {
            return [
                $keyPoint->id => [
                    'id' => $keyPoint->id,
                    'name' => $keyPoint->name,
                ],
            ];
        })->toArray();
        // dd($this->key_points);

        foreach ($this->key_points as $key => $value) {
            $id = $value['id'];
            $name = $value['name'];
            if (isset($id) && isset($this->updated_key_points[$id])) {
                $this->key_points[$key]['name'] = $this->updated_key_points[$id];
            }
        }




        $this->forOrganization = $this->user->role == USER_ROLE_ORGANIZATION;
        $this->fill([
            'courseTitle' => data_get($this->details, 'title', $this->course->title),
            'course_type' => data_get($this->details, 'course_type', $this->course->course_type),
            'subtitle' => data_get($this->details, 'subtitle', $this->course->subtitle),
            'description' => data_get($this->details, 'description', $this->course->description),
            'private_mode' => data_get($this->details, 'private_mode', $this->course->private_mode),
            'is_subscription_enable' => data_get($this->details, 'is_subscription_enable', $this->course->is_subscription_enable),
        ]);
    }
    public function mountAttribute($attribute)
    {
        // if (property_exists($this, $attribute)) {
        $this->$attribute = data_get($this->details, $attribute, $this->course->$attribute);
        // }
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

        $new_key_point = LearnKeyPoint::create(['name' => $this->new_key_point]);

        $this->key_points[$new_key_point->id] =  $new_key_point->only('id', 'name');
        $this->new_key_points[$new_key_point->id] = $new_key_point->id;

        $this->new_key_point = '';
    }
    public function removeKeyPoint($index)
    {
        ['id' => $point_id, 'name' => $point_name] = $this->key_points[$index];

        if (isset($this->new_key_point[$point_id])) {
            LearnKeyPoint::destroy($point_id);
            unset($this->key_points[$index]);
        } else {
            $this->deleted_key_points[] = $point_id;
        }
        unset(
            $this->new_key_points[$point_id],
            $this->updated_key_points[$point_id]
        );
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
    public function updateCourseOverview()
    {
        $this->validate();
        DB::beginTransaction();
        try {

            if (Course::where('slug', Str::slug($this->title))->whereNot('id', $this->course->id)->count() > 0) {
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
                'description' => $this->description
            ];

            $data['is_subscription_enable'] = 0;

            if (get_option('subscription_mode')) {
                $data['is_subscription_enable'] = $this->is_subscription_enable;
            }




            foreach ($this->key_points as ['id' => $point_id, 'name' => $point_name]) {

                $point_old_name = data_get($this->original_key_points, $point_id);
                $point_was_updated = $point_old_name && $point_old_name != $point_name;

                $point_is_new = isset($this->new_key_points[$point_id]);
                $key_point = LearnKeyPoint::find($point_id);

                if ($point_is_new) {
                    $key_point->update(['name' => $point_name]);
                    continue;
                }

                if ($point_was_updated) {
                    $this->updated_key_points[$point_id] = $point_name;
                }
            }
            $details = [
                ...$this->details,
                ...$data,
                'new_key_points' => $this->new_key_points,
                'updated_key_points' => $this->updated_key_points,
                'deleted_key_points' => $this->deleted_key_points
            ];
            $this->courseVersion->update(['details' => $details]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
        // $this->mount(
        //     $this->title,
        //     $this->navCourseUploadActiveClass,
        //     $this->conditions,
        //     $this->course
        // );
        // dd($this);
        return redirect(route('organization.course.update-category', ['uuid' => $this->course->uuid]));
    }
}
