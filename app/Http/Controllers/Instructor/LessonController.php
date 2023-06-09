<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\LessionRequest;
use App\Models\Course;
use App\Models\Course_lecture;
use App\Models\Course_lecture_views;
use App\Models\Course_lesson;
use App\Models\CourseVersion;
use App\Models\Enrollment;
use App\Models\Order_item;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Illuminate\Http\Request;
use File;
use Vimeo\Vimeo;


class LessonController extends Controller
{

    use General, ImageSaveTrait, SendNotification;

    protected $model;
    protected $courseModel;
    protected $lectureModel;

    public function __construct(Course_lesson $course_lesson, Course $course, Course_lecture $course_lecture)
    {
        $this->model = new Crud($course_lesson);
        $this->courseModel = new Crud($course);
        $this->lectureModel = new Crud($course_lecture);
    }

    public function store(LessionRequest $request, $course_uuid)
    {
        $course_version = CourseVersion::find($request->course_version_id);
        $course = $this->courseModel->getRecordByUuid($course_uuid);
        $data = [
            'name' => $request->name,
            'short_description' => $request->short_description ?: null,
        ];
        if (!$course_version) {
            $data['course_id'] = $course->id;
        }
        $outcome = $this->model->create($data);
        $edited_lessons = [];
        if ($course_version) {

            $details = $course_version->details;
            $details['lessons'][] = $outcome->id;
            $course_version->update(['details' => $details]);
            $edited_lessons = Course_lesson::whereIn('id', $details['lessons'])->get();
        }

        $this->showToastrMessage('success', __('Created successful.'));
        // dd($returned_data);
        // edited lessons var misson os only to separate them in the view and allow the instructor
        // to see his new added lessons and his old ones
        return redirect()->back()->with('edited_lessons', $edited_lessons);
    }

    public function updateLesson(LessionRequest $request, $course_uuid, $lesson_uuid)
    {
        $lesson = $this->model->getRecordByUuid($lesson_uuid);
        $course = $this->courseModel->getRecordByUuid($course_uuid);
        $course_version = CourseVersion::where('course_id', $course->id)->where('status', INCOMPLETED_COURSE_VERSION)->firstOrFail();
        $details = $course_version->details;
        $data = [
            'name' => $request->name,
            'short_description' => $request->short_description ?: null,
        ];
        if ($lesson->course_id){
            $data['course_id'] = $course->id;
        }
        $details['updated_course_lessons'][$lesson->id] = $data;
        $course_version->details = $details;
        $course_version->update();
        // $this->model->update($data, $lesson->id);
        $this->showToastrMessage('success', __('Updated successful.'));
        return redirect()->back();
    }

    public function deleteLesson(Request $request, $lesson_uuid)
    {
        $deleted_lessons = [];
        $course_version_id = $request->course_version_id;
        if(!$course_version_id){
            $this->model->deleteByUuid($lesson_uuid);
        } else{
            $lesson = $this->model->getRecordByUuid($lesson_uuid);
            $course_version = CourseVersion::find($course_version_id);
            $details = $course_version->details;
            $details['deleted_lessons'][] = $lesson->id;
            $course_version->details = $details;
            $course_version->save();
            $deleted_lessons = Course_lesson::whereIn('id', $details['deleted_lessons'])->pluck('uuid')->toArray();
        }
        $this->showToastrMessage('success', __('Deleted successful'));
        return redirect()->back()->with('deleted_lessons', $deleted_lessons);
    }


    public function uploadLecture($course_uuid, $lesson_uuid)
    {
        $data['course_version_id'] = \request('course_version_id');
        $data['title'] = 'Upload Lecture';
        $data['navCourseUploadActiveClass'] = 'active';
        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        $data['lesson'] = $this->model->getRecordByUuid($lesson_uuid);
        $data['lessons'] = Course_lesson::where('course_id', $data['course']->id)->with('lectures')->get();
        return view('instructor.course.upload-lecture', $data);
    }

    public function storeLecture(Request $request, $course_uuid, $lesson_uuid)
    {
        $edited_lessons = [];
        if ($request->type == 'video') {
            $request->validate([
                'video_file' => ['required'],
            ]);
        } elseif ($request->type == 'youtube') {
            $request->validate([
                'youtube_url_path' => ['required'],
            ]);

            if ($request->youtube_file_duration) {
                if (preg_match('/^([0-9][0-9]):[0-5][0-9]$/', $request->youtube_file_duration)) {
                } else {
                    $request->validate([
                        'youtube_file_duration' => 'date_format:H:i'
                    ]);
                }
            }
        } elseif ($request->type == 'vimeo') {
            if (env('VIMEO_STATUS') == 'active') {
                $request->validate([
                    'vimeo_url_path' => 'exclude_unless:vimeo_upload_type,1|required',
                    'vimeo_url_uploaded_path' => 'exclude_unless:vimeo_upload_type,2|required',
                ]);

                if ($request->vimeo_file_duration && ($request->vimeo_upload_type == 2)) {
                    if (preg_match('/^([0-9][0-9]):[0-5][0-9]$/', $request->vimeo_file_duration)) {
                    } else {
                        $request->validate([
                            'vimeo_file_duration' => 'date_format:H:i'
                        ]);
                    }
                }
            } else {
                $this->showToastrMessage('success', __('At present, upload new video in vimeo service is off. Please try other way.'));
            }
        } elseif ($request->type == 'text') {
            $request->validate([
                'text_description' => 'required',
            ]);
        } elseif ($request->type == 'image') {
            $request->validate([
                'image' => 'required',
            ]);
        } elseif ($request->type == 'pdf') {
            $request->validate([
                'pdf' => 'required',
            ]);
        } elseif ($request->type == 'slide_document') {
            $request->validate([
                'slide_document' => 'required',
            ]);
        } elseif ($request->type == 'audio') {
            $request->validate([
                'audio' => 'required',
            ]);
        }

        $course = $this->courseModel->getRecordByUuid($course_uuid);
        $lesson = $this->model->getRecordByUuid($lesson_uuid);

        $lecture = new Course_lecture();
        $lecture->fill($request->all());
        $lecture->pre_ids = ($lecture->pre_ids) ? json_encode($lecture->pre_ids) : NULL;
        $course_version_id = $request->course_version_id;
        $lecture->lesson_id = $lesson->id;
        if (!$course_version_id) {
            $lecture->course_id = $course->id;
        }

        if ($request->video_file && $request->type == 'video') {
            $this->saveLectureVideo($request, $lecture); // Save Video File, Path, Size, Duration
        }

        if ($request->type == 'youtube') {
            $lecture->url_path = $request->youtube_url_path;

            $lecture->file_duration = $request->youtube_file_duration;
            $lecture->file_duration_second = $this->timeToSeconds($request->youtube_file_duration);
            $lecture->file_path = null;
        }

        if ($request->type == 'vimeo') {
            if ($request->file('vimeo_url_path') && ($request->vimeo_upload_type == 1)) {
                $path = $this->uploadVimeoVideoFile($request->title, $request->file('vimeo_url_path'));
                $lecture->url_path = $path;
                $lecture->file_duration = gmdate("i:s", $request->file_duration);
                $file_duration_second = $request->file_duration;
                $lecture->file_duration_second = (int)$file_duration_second;
                $lecture->vimeo_upload_type = $request->vimeo_upload_type;
            } elseif ($request->vimeo_url_uploaded_path && ($request->vimeo_upload_type == 2)) {
                $lecture->vimeo_upload_type = $request->vimeo_upload_type;
                $lecture->url_path = $request->vimeo_url_uploaded_path;
                $lecture->file_duration = $request->vimeo_file_duration;
                $lecture->file_duration_second = $this->timeToSeconds($request->vimeo_file_duration);
            }
            $lecture->file_path = null;
        }

        if ($request->type == 'text') {
            $lecture->text = $request->text_description;
        }

        if ($request->type == 'image') {
            $lecture->image = $request->image ? $this->saveImage('lecture', $request->image, null, null) :   null;
        }

        if ($request->type == 'pdf') {
            //            $lecture->pdf = $request->pdf ? $this->uploadFile('lecture', $request->pdf, null, null) :   null;
            $file_details = $this->uploadFileWithDetails('lecture', $request->pdf);
            if ($file_details['is_uploaded']) {
                $lecture->pdf = $file_details['path'];
            }
        }

        if ($request->type == 'slide_document') {
            $lecture->slide_document = $request->slide_document;
        }

        if ($request->type == 'audio') {
            $file_details = $this->uploadFileWithDetails('lecture', $request->audio);
            if ($file_details['is_uploaded']) {
                $lecture->audio = $file_details['path'];
            }
            //            $lecture->audio = $request->audio ? $this->uploadFile('lecture', $request->audio) :   null;
            try {
                $duration = gmdate("i:s", $request->file_duration);
                $lecture->file_duration = $duration;

                $file_duration_second = $request->file_duration;
                $lecture->file_duration_second = (int)$file_duration_second;
            } catch (\Exception $exception) {
                //
            }
        }

        $lecture->save();
        $data = [
            $course->uuid,
            'step=lesson',
        ];
        if($course_version_id){
            $data["course_version_id"] = $course_version_id;
        }
        if ($course_version_id) {
            $course_version = CourseVersion::find($course_version_id);
            $details = $course_version->details;
            $details['lectures'][$lecture->id] = $lesson->id;
            $course_version->details = $details;
            $course_version->update();
            if(isset($details['lessons'])){
                $edited_lessons = Course_lesson::whereIn('id', $details['lessons'])->get();
            }
            $data['edited_lessons'] = $edited_lessons;

        }

        if ($course->status == 1) {
            /** ====== send notification to student ===== */
            $students = Enrollment::where('course_id', $course->id)->select('user_id')->get();
            foreach ($students as $student) {
                $text = __("New lesson has been added");
                $target_url = route('student.my-course.show', $course->slug);
                $this->send($text, 3, $target_url, $student->user_id);
            }
            /** ====== send notification to student ===== */
        }

        if ($course->status != 0) {
            $text = __("New lesson has been added");
            $target_url = route('admin.course.index');
            $this->send($text, 1, $target_url, null);
        }


        return redirect(route('instructor.course.edit', $data));
    }

    public function editLecture($course_uuid, $lesson_uuid, $lecture_uuid, Request $request)
    {
        $course_version_id = $request->validate(['course_version_id' => 'required'])['course_version_id'];
        // dd($course_version_id);
        $data['course_version_id'] = $course_version_id;
        $data['title'] = 'Edit Lecture';
        $data['navCourseActiveClass'] = 'active';

        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        $data['lesson'] = $this->model->getRecordByUuid($lesson_uuid);
        $data['lecture'] = $this->lectureModel->getRecordByUuid($lecture_uuid);
        $data['lessons'] = Course_lesson::where('course_id', $data['course']->id)->with(['lectures' => function ($q) use ($lecture_uuid) {
            $q->where('uuid', '!=', $lecture_uuid);
        }])->get();
        return view('instructor.course.edit-lecture', $data);
    }

    public function updateLecture(Request $request,  $lecture_uuid)
    {
        $course_version_id = $request->validate(['course_version_id' => 'required'])['course_version_id'];
        $course_version = CourseVersion::findOrFail($course_version_id);
        $deleted_files = [];
        $delete_vimeo = '';
        if ($request->type == 'youtube') {
            $request->validate([
                'youtube_url_path' => ['required'],
            ]);

            if ($request->youtube_file_duration) {
                if (preg_match('/^([0-9][0-9]):[0-5][0-9]$/', $request->youtube_file_duration)) {
                } else {
                    $request->validate([
                        'youtube_file_duration' => 'date_format:H:i'
                    ]);
                }
            }
        } elseif ($request->type == 'text') {
            $request->validate([
                'text_description' => 'required',
            ]);
        } elseif ($request->type == 'slide_document') {
            $request->validate([
                'slide_document' => 'required',
            ]);
        } elseif ($request->type == 'vimeo') {
            $request->validate([
                'vimeo_url_uploaded_path' => 'exclude_unless:vimeo_upload_type,2|required',
            ]);

            if ($request->vimeo_file_duration && ($request->vimeo_upload_type == 2)) {
                if (preg_match('/^([0-9][0-9]):[0-5][0-9]$/', $request->vimeo_file_duration)) {
                } else {
                    $request->validate([
                        'vimeo_file_duration' => 'date_format:H:i'
                    ]);
                }
            }
        }

        $lecture = Course_lecture::whereUuid($lecture_uuid)->firstOrFail();
        $details = $course_version->details;
        // $model = $request->except('_token');
        $lecture->fill($request->all());
        $lecture->pre_ids = ($lecture->pre_ids) ? json_encode($lecture->pre_ids) : NULL;

        if ($request->video_file && $request->type == 'video') {
            $deleted_files[] = $lecture->file_path;
            // $this->deleteFile($lecture->file_path); // delete file from server
            // $details['updated_lessons'][$lecture->id]['delete_video'] = $lecture->file_path;
            $this->saveLectureVideo($request, $lecture, $course_version); // Save Video File, Path, Size, Duration
        }

        if ($request->type == 'youtube') {
            $lecture->url_path = $request->youtube_url_path;
            $lecture->file_duration = $request->youtube_file_duration;
            $lecture->file_duration_second = $this->timeToSeconds($request->youtube_file_duration);
            $lecture->file_path = null;
        }

        if ($request->type == 'vimeo') {
            if ($request->file('vimeo_url_path') && ($request->vimeo_upload_type == 1)) {
                if (env('VIMEO_STATUS') == 'active') {
                    if ($lecture->url_path) {
                        $delete_vimeo = 'https://vimeo.com/' . $lecture->url_path;
                        // $this->deleteVimeoVideoFile('https://vimeo.com/' . $lecture->url_path);
                    }

                    $path = $this->uploadVimeoVideoFile($request->title, $request->file('vimeo_url_path'));
                    $lecture->url_path = $path;
                    $lecture->file_duration = gmdate("i:s", $request->file_duration);
                    $file_duration_second = $request->file_duration;
                    $lecture->file_duration_second = (int)$file_duration_second;
                    $lecture->vimeo_upload_type = $request->vimeo_upload_type;
                } else {
                    $this->showToastrMessage('success', __('At present, upload new video in vimeo service is off. Please try other way.'));
                }
            } elseif ($request->vimeo_url_uploaded_path && ($request->vimeo_upload_type == 2)) {
                $lecture->vimeo_upload_type = $request->vimeo_upload_type;
                $lecture->url_path = $request->vimeo_url_uploaded_path;
                $lecture->file_duration = $request->vimeo_file_duration;
                $lecture->file_duration_second = $this->timeToSeconds($request->vimeo_file_duration);
            }

            $lecture->file_path = null;
        }

        if ($request->type == 'text') {
            $lecture->text = $request->text_description;
        }

        if ($request->type == 'image' && $request->image) {
            $deleted_files[] = $lecture->image;
            // $this->deleteFile($lecture->image); // delete file from server
            $lecture->image = $request->image ? $this->saveImage('lecture', $request->image, null, null) :   null;
        }

        if ($request->type == 'pdf' && $request->pdf) {
            $deleted_files[] = $lecture->pdf;

            // $this->deleteFile($lecture->pdf); // delete file from server
            //            $lecture->pdf = $request->pdf ? $this->uploadFile('lecture', $request->pdf) :   null;
            $file_details = $this->uploadFileWithDetails('lecture', $request->pdf);
            if ($file_details['is_uploaded']) {
                $lecture->pdf = $file_details['path'];
            }
        }

        if ($request->type == 'slide_document') {
            $lecture->slide_document = $request->slide_document;
        }

        if ($request->audio && $request->type == 'audio') {
            $deleted_files[] = $lecture->audio;

            // $this->deleteFile($lecture->audio); // delete file from server
            $file_details = $this->uploadFileWithDetails('lecture', $request->audio);
            if ($file_details['is_uploaded']) {
                $lecture->audio = $file_details['path'];
            }
            //            $lecture->audio = $request->audio ? $this->uploadFile('lecture', $request->audio) :   null;
            try {
                $duration = gmdate("i:s", $request->file_duration);
                $lecture->file_duration = $duration;
                $file_duration_second = $request->file_duration;
                $lecture->file_duration_second = (int)$file_duration_second;
            } catch (\Exception $exception) {
                //
            }
        }

        $details['updated_lessons'][$lecture->id] = [
            'model' => $lecture->toArray(),
            'deleted_files' => $deleted_files,
            'delete_vimeo' => $delete_vimeo,
        ];
        $course_version->details = $details;
        $course_version->save();
        // $lecture->save();

        /** ====== send notification to student ===== */
        // $students = Order_item::where('course_id', $lecture->course->id)->select('user_id')->get();
        // foreach ($students as $student) {
        //     $text = __("Lesson has been updated");
        //     $target_url = route('student.my-course.show', $lecture->course->slug);
        //     $this->send($text, 3, $target_url, $student->user_id);
        // }
        /** ====== send notification to student ===== */

        // if ($lecture->course->status != 0) {
        //     $text = __("New lesson has been added");
        //     $target_url = route('admin.course.index');
        //     $this->send($text, 1, $target_url, null);
        // }

        return redirect(route('instructor.course.edit', [($lecture->course ? $lecture->course->uuid :($course_version ? $course_version->course->uuid : '')), 'step=lesson' , 'course_version_id'=> $course_version_id]));
    }

    public function deleteLecture($course_uuid, $lecture_uuid, Request $request)
    {
        $course_version_id = $request->course_version_id;
        $course_version = CourseVersion::find($course_version_id);
        $lecture = $this->lectureModel->getRecordByUuid($lecture_uuid);
        $data = [
            $course_uuid,
            'step=lesson',
        ];
        $deleted_lectures = [];
        if(!$course_version){

            $this->deleteFile($lecture->file_path); // delete file from server

            if ($lecture->type == 'vimeo') {
                if ($lecture->url_path) {
                    $this->deleteVimeoVideoFile($lecture->url_path);
                }
            }

            Course_lecture_views::where('course_lecture_id', $lecture->id)->get()->map(function ($q) {
                $q->delete();
            });

            $this->lectureModel->deleteByUuid($lecture_uuid); // delete record
        }else{
            $data['course_version_id'] = $course_version_id;
            //get details
            $details = $course_version->details;
            //update details
            $details['deleted_lectures'][] = $lecture->id;
            //save details
            $course_version->details = $details;
            $course_version->save();
            //return deleted for view
            $data['deleted_lectures'][] = $lecture_uuid;
            $deleted_lectures = Course_lecture::whereIn('id', $details['deleted_lectures'])->pluck('uuid')->toArray();
            $data['deleted_lectures'] = $deleted_lectures;
        }

        $this->showToastrMessage('success', __('Lecture has been deleted'));
        return redirect(route('instructor.course.edit', $data));
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

    function timeToSeconds(string $time): int
    {
        $arr = explode(':', $time);
        if (count($arr) === 3) {
            return $arr[0] * 3600 + $arr[1] * 60 + $arr[2];
        }
        return $arr[0] * 60 + $arr[1];
    }
}
