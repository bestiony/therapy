<?php

namespace App\Http\Livewire\Edit;

use App\Models\Course;
use App\Models\Course_lecture;
use App\Models\Course_lesson;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class EditLectureComponent extends Component
{
    use WithFileUploads;
    use ImageSaveTrait;
    use SendNotification;
    public Course $course;
    public ?Course_lesson $lesson;
    public ?Course_lecture $lecture;
    protected $listeners = ['lectureChanged' => 'remountLecture'];


    // form properties
    public $type = 'video';
    public $video_file;
    public $file_duration;
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
    public function remountLecture($lecture_id)
    {
        $this->lecture = $this->course->lectures()->where('course_lectures.id', $lecture_id)->firstOrFail();
        $this->fill([
            'type' => $this->lecture->type,
            'lecture_type' => $this->lecture->lecture_type,
            'title' => $this->lecture->title,
            'youtube_url_path' => $this->lecture->url_path,
            'youtube_file_duration' => $this->lecture->file_duration,
            'vimeo_url_path' => $this->lecture->url_path,
            'vimeo_url_uploaded_path' => $this->lecture->url_path,
            'vimeo_file_duration' => $this->lecture->file_duration,
            'text_description' => $this->lecture->text,
        ]);
    }
    public function mount(Course $course,  $lecture = null)
    {
        $this->course = $course;
        $this->lecture = $lecture;
    }
    public function rules()
    {
        return [
            'type' => 'required|in:video,youtube,vimeo,text,image,pdf,slide_document,audio',
            'lecture_type' => ['required', Rule::in([1, 2])],
            'title' => 'required',
            'video_file' => Rule::requiredIf($this->type == 'video' && !$this->lecture?->video),
            'youtube_url_path' => Rule::requiredIf($this->type == 'youtube'),
            'youtube_file_duration' => [Rule::requiredIf($this->type == 'youtube'), function ($attribute, $value, $fail) {
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
            'image' => Rule::requiredIf($this->type == 'image' && !$this->lecture?->image),
            'pdf' => Rule::requiredIf($this->type == 'pdf' && !$this->lecture?->pdf),
            'slide_document' => Rule::requiredIf($this->type == 'slide_document'&& !$this->lecture?->slide_document),
            'audio' => Rule::requiredIf($this->type == 'audio' && !$this->lecture?->audio),
        ];
    }
    public function updateLecture()
    {

        $this->resetErrorBag();
        $this->validate();
        DB::beginTransaction();
        try {

            /**
             * @var Course_lecture $lecture
             */
            $lecture = $this->lecture;
            $lecture->update([
                'course_id' => $this->course->id,
                'type' => $this->type,
                'title' => $this->title,
                'lecture_type' => $this->lecture_type,
                'pre_ids' => json_encode($this->pre_ids),
            ]);
            if ($this->video_file && $this->type == 'video') {
                $lecture->file_path = $this->uploadFile($this->video_file, 'uploads/video');
            }

            if ($this->type == 'youtube' && $this->youtube_url_path) {
                $lecture->url_path = $this->youtube_url_path;
                $lecture->file_duration = $this->youtube_file_duration;
                $lecture->file_duration_second = $this->timeToSeconds($this->youtube_file_duration);
                $lecture->file_path = null;
            }

            if ($this->type == 'vimeo' &&  $this->vimeo_url_path) {
                if ($this->vimeo_url_path && ($this->vimeo_upload_type == 1)) {
                    $path = $this->uploadVimeoVideoFile($this->title, $this->vimeo_url_path);
                    $lecture->url_path = $path;
                    $lecture->file_duration = gmdate("i:s", $this->file_duration);
                    $file_duration_second = $this->file_duration;
                    $lecture->file_duration_second = (int)$file_duration_second;
                    $lecture->vimeo_upload_type = $this->vimeo_upload_type;
                } elseif ($this->vimeo_url_uploaded_path && ($this->vimeo_upload_type == 2)) {
                    $lecture->vimeo_upload_type = $this->vimeo_upload_type;
                    $lecture->url_path = $this->vimeo_url_uploaded_path;
                    $lecture->file_duration = $this->vimeo_file_duration;
                    $lecture->file_duration_second = $this->timeToSeconds($this->vimeo_file_duration);
                }
                $lecture->file_path = null;
            }
            if ($this->type == 'text' && $this->text_description) {
                $lecture->text = $this->text_description;
            }

            if ($this->type == 'image' && $this->image) {
                $lecture->image = $this->image ? $this->uploadFile($this->image, 'lecture') :   null;
            }

            if ($this->type == 'pdf' && $this->pdf) {

                $lecture->pdf = $this->uploadFile($this->pdf, 'lecture');
            }


            if ($this->type == 'slide_document' && $this->slide_document) {
                $lecture->slide_document = $this->slide_document;
            }

            if ($this->type == 'audio' && $this->audio) {
                $lecture->audio = $this->uploadFile($this->audio, 'lecture');
                $duration = gmdate("i:s", $this->file_duration);
                $lecture->file_duration = $duration;

                $file_duration_second = $this->file_duration;
                $lecture->file_duration_second = (int)$file_duration_second;
            }

            $lecture->save();

            if (
                $this->course->status != 0
            ) {
                $text = __("New lesson has been added");
                $target_url = route('admin.course.index');
                $this->send($text, 1, $target_url, null);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
        $this->emit('lectureAdded');
        $this->dispatchBrowserEvent('closeModal');
        $this->dispatchBrowserEvent('showSuccess', 'Lecture update was successful!');
        $this->dispatchBrowserEvent('pageReload');
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
    public function uploadFile(TemporaryUploadedFile $file, string $path, string $disk = 'public-storage'): string
    {
        $file_name = time() . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $file_path = $file->storeAs('uploads/' . $path, $file_name, $disk);
        return $file_path;
    }

    public function render()
    {
        return view('livewire.edit.edit-lecture-component');
    }
    function timeToSeconds(string $time): int
    {
        $arr = explode(':', $time);
        if (count($arr) === 3) {
            return $arr[0] * 3600 + $arr[1] * 60 + $arr[2];
        }
        return $arr[0] * 60 + $arr[1];
    }
}
