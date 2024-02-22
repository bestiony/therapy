<?php

namespace App\Http\Livewire\Create;

use App\Models\Category;
use App\Models\Course;
use App\Models\Course_language;
use App\Models\Difficulty_level;
use App\Models\Subcategory;
use App\Models\Tag;
use App\Models\User;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SetCourseCategoryComponent extends Component
{
    use WithFileUploads;
    use General;
    use ImageSaveTrait;
    // menu properties
    public $categories = [];
    public $subcategories = [];
    public $tags;
    public $course_languages;
    public $difficulty_levels;

    // form properties
    public User $user;
    public Course $course;
    public $category_id;
    public $subcategory_id;
    public $selectedTags = [];
    public $drip_content;
    public $access_period;
    public $learner_accessibility;
    public $price;
    public $old_price;
    public $course_language_id;
    public $difficulty_level_id;
    public $image;
    public $intro_video_check;
    public $youtube_video_id;
    public $video;
    public function mount()
    {
        $this->categories = Category::active()->orderBy('name', 'asc')->select('id', 'name')->get();
        if($this->course->category_id){
            $this->subcategories = Subcategory::where('category_id', $this->course->category_id)->select('id', 'name')->orderBy('name', 'asc')->get();
        }
        $this->fill([
            'category_id' => $this->course->category_id,
            'subcategory_id' => $this->course->subcategory_id,
            'selectedTags' => $this->course->tags->pluck('id')->toArray(),
            'drip_content' => $this->course->drip_content,
            'access_period' => $this->course->access_period,
            'learner_accessibility' => $this->course->learner_accessibility,
            'price' => $this->course->price,
            'old_price' => $this->course->old_price,
            'course_language_id' => $this->course->course_language_id,
            'difficulty_level_id' => $this->course->difficulty_level_id,
            'intro_video_check' => $this->course->intro_video_check,
            'youtube_video_id' => $this->course->youtube_video_id,
        ]);
        /** @var User $user */
        $user = auth()->user();
        $this->user = $user;

        $this->tags = Tag::orderBy('name', 'asc')->select('id', 'name')->get();
        $this->course_languages = Course_language::orderBy('name', 'asc')->select('id', 'name')->get();
        $this->difficulty_levels = Difficulty_level::orderBy('name', 'asc')->select('id', 'name')->get();
        // ? DO NO SET VIDEO AND IMAGE VALUES AT FIRST
        $data['subcategories'] = Subcategory::where('category_id', old('category_id'))->select('id', 'name')->orderBy('name', 'asc')->get();
    }
    public function render()
    {
        $this->reintilizeSelect2();
        return view('livewire.create.set-course-category-component');
    }
    public function updatedCategoryId($new_category_id)
    {
        $this->subcategories = Subcategory::where('category_id', $new_category_id)->select('id', 'name')->orderBy('name', 'asc')->get();
    }
    // public function updated($field)
    // {
    //     $this->validateOnly($field, [
    //         'category_id' => 'required',
    //         'subcategory_id' => 'required',
    //         'selectedTags' => 'required',
    //         'drip_content' => 'required',
    //         'access_period' => 'required',
    //         'learner_accessibility' => 'required',
    //         'price' => 'required',
    //         'old_price' => 'required',
    //         'course_language_id' => 'required',
    //         'difficulty_level_id' => 'required',
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         'intro_video_check' => 'required',
    //         'youtube_video_id' => 'required',
    //         'video' => 'required',
    //     ]);
    // }
    public function rules()
    {
        return [
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'selectedTags' => 'required',
            'drip_content' => 'required',
            'access_period' => ['required', 'numeric', 'min:0'],
            'learner_accessibility' => 'required',
            'price' => 'required',
            'old_price' => 'required',
            'course_language_id' => 'required',
            'difficulty_level_id' => 'required',
            // 'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'intro_video_check' => 'required',
            'youtube_video_id' => [Rule::requiredIf($this->intro_video_check == VIDEO_TYPE_YOUTUBE)],
            'video' => [Rule::requiredIf($this->intro_video_check == VIDEO_TYPE_LOCAL && !$this->course->video)],
        ];
    }
    public function updatedImage()
    {
        try {
            $this->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|dimensions:min_width=575,min_height=450,max_width=575,max_height=450',
            ]);
        } catch (Exception $e) {
            $this->reset('image');
            throw $e;
        }
    }
    public function updatedSelectedTags()
    {
        $this->reintilizeSelect2();
    }
    public function reintilizeSelect2()
    {
        $this->emit('reinitializeSelect2');
    }
    public function setCourseCategory()
    {
        $this->validate();
        if (Gate::denies('update', $this->course)) {
            $this->showToastrMessage('error', __('You don\'t have permission to edit this'));
            return redirect()->to('/');
        }
        if ($this->image) {

            $this->deleteFile($this->course->image); // delete file from server

            $image = $this->saveImage('course', $this->image, null, null); // new file upload into server
        } else {
            $image = $this->course->image;
        }
        if ($this->video) {
            $this->deleteVideoFile($this->course->video); // delete file from server
            $file_name = time() . Str::random(10) . '.' . $this->video->getClientOriginalExtension();
            $video = $this->video->storeAs('uploads/course', $file_name);
        } else {
            $video = $this->course->video;
        }

        $data = [
            'category_id' => $this->category_id,
            'subcategory_id' => $this->subcategory_id,
            'price' => $this->price,
            'old_price' => $this->old_price,
            'drip_content' => $this->drip_content,
            'access_period' => $this->access_period == 0  ? NULL : $this->access_period,
            'course_language_id' => $this->course_language_id,
            'difficulty_level_id' => $this->difficulty_level_id,
            'learner_accessibility' => $this->learner_accessibility,
            'image' => $image ?? null,
            'video' => $video ?? null,
            'intro_video_check' => $this->intro_video_check,
            'youtube_video_id' => $this->youtube_video_id ?? null,
        ];


        $this->course->update($data);
        $this->course->tags()->sync($this->selectedTags);



        // if ($course->status != 0) {
        //     $text = __("Course category has been updated");
        //     $target_url = route('admin.course.index');
        //     $this->send($text, 1, $target_url, null);
        // }

        return redirect(route('organization.course.add-lessons', [$this->course->uuid,]));
    }
}
