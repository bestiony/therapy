<?php

namespace App\Http\Livewire\Create;

use App\Models\Course;
use App\Models\Course_lesson;
use App\Traits\ImageSaveTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddLectureComponent extends Component
{
    use WithFileUploads;
    use ImageSaveTrait;
    public Course $course;
    public ?Course_lesson $lesson;

    protected $listeners = ['sectionChanged' => 'remountLesson'];


    // form properties
    public $type = 'video';
    public $video_file;
    public $youtube_url_path;
    public $youtube_file_duration;
    public $vimeo_upload_type;
    public $vimeo_url_path;
    public $vimeo_url_uploaded_path;
    public $vimeo_file_duration;
    public $text_description;
    public $image;
    public $pdf;
    public $slide_document;
    public $audio;
    //
    public $title;
    public $lecture_type;

    // drip types
    public $after_day;
    public $unlock_date;
    public $pre_ids = [];
    public function remountLesson($section_id, $section_name)
    {
        $this->lesson = $this->course->lessons()->where('course_lessons.id', $section_id)->firstOrFail();
    }
    public function mount(Course $course,  $lesson = null)
    {
        $this->course = $course;
        $this->lesson = $lesson;
    }
    public function rules()
    {
        return [
            'type' => 'required|in:video,youtube,vimeo,text,image,pdf,slide_document,audio',
            'lecture_type'=> ['required',Rule::in([1,2])],
            'title' => 'required',
            'video_file' => Rule::requiredIf($this->type == 'video'),
            'youtube_url_path' => Rule::requiredIf($this->type == 'youtube'),
            'youtube_file_duration' => [Rule::requiredIf($this->type == 'youtube' ), function ($attribute, $value, $fail) {
                if (!empty($value) && !preg_match('/^([0-9][0-9]):[0-5][0-9]$/', $value)) {
                    $fail($attribute . ' is not a valid time.');
                }
            },],
            'vimeo_url_path' => Rule::requiredIf($this->type == 'vimeo' && env('VIMEO_STATUS') == 'active' && $this->vimeo_upload_type == 1),
            'vimeo_url_uploaded_path' => Rule::requiredIf($this->type == 'vimeo' && env('VIMEO_STATUS') == 'active' && $this->vimeo_upload_type == 2),
            'vimeo_file_duration' => [Rule::requiredIf($this->type == 'vimeo' && $this->vimeo_upload_type == 2), function ($attribute, $value, $fail) {
                if (!empty($value) && !preg_match('/^([0-9][0-9]):[0-5][0-9]$/', $value)) {
                    $fail($attribute . ' is not a valid time.');
                }
            },],
            'text_description' => Rule::requiredIf($this->type == 'text'),
            'image' => Rule::requiredIf($this->type == 'image'),
            'pdf' => Rule::requiredIf($this->type == 'pdf'),
            'slide_document' => Rule::requiredIf($this->type == 'slide_document'),
            'audio' => Rule::requiredIf($this->type == 'audio'),
        ];
    }
    public function storeLecture()
    {
        DB::beginTransaction();
        $lecture = $this->lesson->lectures()->create([
            'type' => $this->type,
            'title' => $this->title,
            'lecture_type' => $this->lecture_type,
            'pre_ids' => json_encode($this->pre_ids),
        ]);
        if ($this->video_file && $this->type == 'video') {
            $deleted_files[] = $lecture->file_path;
            // $this->deleteFile($lecture->file_path); // delete file from server
            // $details['updated_lessons'][$lecture->id]['delete_video'] = $lecture->file_path;
            $this->saveLectureVideo($this, $lecture); // Save Video File, Path, Size, Duration
        }
        $this->resetErrorBag();
        $this->validate();
        dd($this);
    }

    public function render()
    {
        return view('livewire.create.add-lecture-component');
    }


    private function saveLectureVideo($request, $lecture, $course_version = null)
    {

        //        $lecture->file_path = $this->uploadFile('video', $request->video_file); // new file upload into server;
        $file_details = $this->uploadFileWithDetails('video', $request->video_file);
        if ($file_details['is_uploaded']) {
            $lecture->file_path = $file_details['path'];
        }
        //        $lecture->file_size = number_format(File::size($lecture->file_path) / 1048576, 2);
        try {
            $duration = gmdate("i:s", $request->file_duration);
            $lecture->file_duration = $duration;

            $file_duration_second = $request->file_duration;
            $lecture->file_duration_second = (int)$file_duration_second;
        } catch (\Exception $exception) {
            //
        }
    }
}
