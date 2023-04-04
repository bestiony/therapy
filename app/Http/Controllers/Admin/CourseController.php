<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CartManagement;
use App\Models\Category;
use App\Models\Course;
use App\Models\Course_lecture;
use App\Models\Course_lecture_views;
use App\Models\Course_lesson;
use App\Models\CourseDelete;
use App\Models\CourseInstructor;
use App\Models\CourseUploadRule;
use App\Models\CourseVersion;
use App\Models\Enrollment;
use App\Models\LearnKeyPoint;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subcategory;
use App\Models\User;
use App\Models\Wishlist;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Hamcrest\Core\AllOf;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    use General, ImageSaveTrait, SendNotification;
    protected $model, $lectureModel, $lessonModel;

    public function __construct(Course $course, Course_lesson $course_lesson,  Course_lecture $course_lecture)
    {
        $this->model = new Crud($course);
        $this->lectureModel = new Crud($course_lecture);
        $this->lessonModel = new Crud($course_lesson);
    }

    public function index()
    {
        if (!Auth::user()->can('all_course')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'All Courses';
        $data['courses'] = $this->model->getOrderById('DESC', 25);
        return view('admin.course.index', $data);
    }

    public function view($uuid)
    {
        $data['title'] = "Course Details";
        $data['course'] = $this->model->getRecordByUuid($uuid);

        return view('admin.course.view', $data);
    }
    public function versionView($id)
    {
        $data['course_version'] = CourseVersion::findOrFail($id);
        $data['course'] = Course::with(['keyPoints'])->findOrFail($data['course_version']->course_id);

        return view('admin.course.version-view', $data);
    }

    public function approved()
    {
        if (!Auth::user()->can('approved_course')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Approved Courses';
        $data['courses'] = Course::where('status', 1)->paginate(25);
        return view('admin.course.approved', $data);
    }

    public function reviewPending()
    {
        if (!Auth::user()->can('pending_course')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Review Pending Courses';
        $data['courses'] = Course::where('status', 2)->paginate(25);
        return view('admin.course.review-pending', $data);
    }
    public function editPending()
    {
        if (!Auth::user()->can('pending_course')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Pending Course Edits';
        $data['versions'] = CourseVersion::with('course')->where('status', PENDING_COURSE_VERSION)->paginate(25);
        // $data['categories'] = Category::select('id', 'name')->get()->toArray();
        $data['categories'] = Category::all()->pluck('name', 'id')->toArray();
        $data['subcategories'] = Subcategory::all()->pluck('name', 'id')->toArray();
        $data['course_deletes'] = CourseDelete::with('course')->get();
        return view('admin.course.edit-pending', $data);
    }

    public function hold()
    {
        if (!Auth::user()->can('hold_course')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Hold Courses';
        $data['courses'] = Course::where('status', 3)->paginate(25);
        return view('admin.course.hold', $data);
    }

    public function statusChange($uuid, $status)
    {
        $course = $this->model->getRecordByUuid($uuid);
        $course->status = $status;
        $course->save();

        if ($status == 1) {
            setBadge($course->user_id);
            $text = __("Course has been approved");
            $target_url = route('course-details', $course->slug);
            $this->send($text, 2, $target_url, $course->user_id);

            /** ====== send notification to student ===== */
            $students = Student::where('user_id', '!=', $course->user_id)->select('user_id')->get();
            foreach ($students as $student) {
                $text = __("New course has been published");
                $target_url = route('course-details', $course->slug);
                $this->send($text, 3, $target_url, $student->user_id);
            }
            /** ====== send notification to student ===== */
        }

        if ($status == 3) {
            $text = __("Course has been hold");
            $target_url = route('instructor.course');
            $this->send($text, 2, $target_url, $course->user_id);
        }


        $this->showToastrMessage('success', __('Status has been changed'));
        return redirect()->back();
    }
    public function versionStatusChange($id, $status)
    {
        $course_version = CourseVersion::findOrFail($id);
        $course = Course::find($course_version->course_id);
        /**
         * update order data
         * link learning points to course
         * delete old learning points that are not in details['learning_points']
         * delete video and image if they are different
         * synch tags
         */
        DB::transaction(function () use ($status, $course_version, $course) {
            if ($status == APPROVED_COURSE_VERSION) {
                $details = $course_version->details;
                LearnKeyPoint::
                whereIn('id', $details['new_learn_key_points'])
                ->update(['course_id' => $course->id]);
                LearnKeyPoint::whereNotIn('id', $details['new_learn_key_points'])->where('course_id', $course->id)->delete();

                if ($course->image != $details['image']) {
                    $this->deleteFile($course->image);
                }
                if ($course->video != $details['video']) {
                    $this->deleteVideoFile($course->video);
                }
                if ($details['tags']) {
                    $course->tags()->sync($details['tags']);
                }
                if (isset($details['lessons'])) {
                    Course_lesson::whereIn('id', $details['lessons'])->update(['course_id' => $course->id]);
                }
                if (isset($details['lectures'])){
                    foreach($details['lectures'] as $added_lecture => $related_lesson){

                        $added_lecture_model = Course_lecture::find($added_lecture);
                        if ($added_lecture_model){

                            $added_lecture_model->update([
                                'course_id' => $course->id,
                                'lesson_id' => $related_lesson,
                            ]);
                        }
                    }
                }
                if (isset($details['updated_lessons'])){
                    foreach($details['updated_lessons'] as $id => $content){
                        $updated_lesson = Course_lecture::findOrFail($id);
                        foreach($content['deleted_files'] as $file){
                            $this->deleteFile($file);
                        }
                        if (isset($content['delete_vimeo']) && strlen($content['delete_vimeo']) > 0){
                            $this->deleteVimeoVideoFile($content['delete_vimeo']);
                        }
                        $updated_lesson->update($content['model']);
                    }

                }

                if (isset($details['updated_course_lessons'])){
                    foreach($details['updated_course_lessons'] as $updated_course_lesson_id => $updated_course_lesson_content){
                        $updated_course_lesson = Course_lesson::findOrFail($updated_course_lesson_id);
                        $updated_course_lesson->update($updated_course_lesson_content);
                    }
                }
                if (isset($details['course_instructors'])) {
                    foreach ($details['course_instructors'] as $instructor) {
                        CourseInstructor::updateOrCreate([
                            'instructor_id' => $instructor['instructor_id'],
                            'course_id' => $course->id,
                        ], [
                            'instructor_id' => $instructor['instructor_id'],
                            'course_id' => $course->id,
                            'share' => $instructor['share'],
                        ]);
                    }
                    $instructors_ids = array_keys($details['course_instructors']);
                    CourseInstructor::whereNotIn('id', $instructors_ids)->where('course_id', $course->id)->delete();
                }
                if (isset($details['course_main_instructor'])){
                    CourseInstructor::updateOrCreate([
                        'instructor_id' => $course->user_id,
                        'course_id' => $course->id,
                    ], [
                        'instructor_id' =>$course->user_id,
                        'course_id' =>$course->id,
                        'share' => $details['course_main_instructor']['share'],
                        'status' => STATUS_ACCEPTED ,
                    ]);
                }
                if (isset($details['deleted_lessons'])){
                    Course_lesson::whereIn('id', $details['deleted_lessons'])->delete();
                }
                if (isset($details['deleted_lectures'])) {
                    foreach($details['deleted_lectures'] as $delete_lecture_id){
                        $delete_lecture = Course_lecture::find($delete_lecture_id);
                        $this->deleteFile($delete_lecture->file_path);
                        if ($delete_lecture->type == 'vimeo') {
                            if ($delete_lecture->url_path) {
                                $this->deleteVimeoVideoFile($delete_lecture->url_path);
                            }
                        }
                        Course_lecture_views::where('course_lecture_id', $delete_lecture->id)->get()->map(function ($q) {
                            $q->delete();
                        });
                        $delete_lecture->delete();
                    }
                }

                $data = [
                    'title' => $details['title'],
                    'course_type' => $details['course_type'],
                    'subtitle' => $details['subtitle'],
                    'slug' => $details['slug'],
                    'description' => $details['description'],
                    'category_id' => $details['category_id'],
                    'subcategory_id' => $details['subcategory_id'],
                    'price' => $details['price'],
                    'old_price' => $details['old_price'],
                    'drip_content' => $details['drip_content'],
                    'access_period' => $details['access_period'],
                    'course_language_id' => $details['course_language_id'],
                    'difficulty_level_id' => $details['difficulty_level_id'],
                    'learner_accessibility' => $details['learner_accessibility'],
                    'image' => $details['image'],
                    'video' => $details['video'],
                    'intro_video_check' => $details['intro_video_check'],
                    'youtube_video_id' => $details['youtube_video_id'],
                ];
                $course->update($data);
                $course_version->update(['status' => APPROVED_COURSE_VERSION]);
            }
        });
        $this->showToastrMessage('success', __('Course Has Been Updated.'));
        return back();
    }

    public function delete($uuid)
    {
        $course = $this->model->getRecordByUuid($uuid);
        $order_item = Order_item::whereCourseId($course->id)->first();

        if ($order_item) {
            $this->showToastrMessage('error', __('You can not deleted. Because already student purchased this course!'));
            return redirect()->back();
        }
        //start:: Course lesson delete
        $lessons = Course_lesson::where('course_id', $course->id)->get();
        if (count($lessons) > 0) {
            foreach ($lessons as $lesson) {
                //start:: lecture delete
                $lectures = Course_lecture::where('lesson_id', $lesson->id)->get();
                if (count($lectures) > 0) {
                    foreach ($lectures as $lecture) {
                        $lecture = Course_lecture::find($lecture->id);
                        if ($lecture) {
                            $this->deleteFile($lecture->file_path); // delete file from server

                            if ($lecture->type == 'vimeo') {
                                if ($lecture->url_path) {
                                    $this->deleteVimeoVideoFile($lecture->url_path);
                                }
                            }

                            Course_lecture_views::where('course_lecture_id', $lecture->id)->get()->map(function ($q) {
                                $q->delete();
                            });

                            $this->lectureModel->delete($lecture->id); // delete record
                        }
                    }
                }
                //end:: lecture delete
                $this->lessonModel->delete($lesson->id);
            }
        }
        //end: lesson delete

        $this->deleteFile($course->image);
        $this->deleteVideoFile($course->video);
        $course->delete();
        $this->showToastrMessage('success', __('Course has been deleted.'));
        return redirect()->back();
    }

    public function deleteVersion(CourseVersion $course_version)
    {
        $course_version->delete();
        $this->showToastrMessage('success', __('Course Version Update was removed'));
        return redirect()->back();
    }

    public function courseUploadRuleIndex()
    {
        $data['title'] = 'Courses Upload Rules';
        $data['courseRules'] = CourseUploadRule::all();
        return view('admin.course.upload-rules', $data);
    }

    public function courseUploadRuleStore(Request $request)
    {
        $courseUploadRuleTitle = $request->courseUploadRuleTitle;
        if ($courseUploadRuleTitle) {
            $inputs = Arr::except($request->all(), ['_token']);
            $keys = [];

            foreach ($inputs as $k => $v) {
                $keys[$k] = $k;
            }

            foreach ($inputs as $key => $value) {
                $option = Setting::firstOrCreate(['option_key' => $key]);
                $option->option_value = $value;
                $option->save();
            }
        }


        $now = now();
        if ($request['course_upload_rules']) {

            if (count(@$request['course_upload_rules']) > 0) {
                foreach ($request['course_upload_rules'] as $course_upload_rules) {
                    if (@$course_upload_rules['description']) {
                        if (@$course_upload_rules['id']) {
                            $rule = CourseUploadRule::find($course_upload_rules['id']);
                        } else {
                            $rule = new CourseUploadRule();
                        }
                        $rule->description = @$course_upload_rules['description'];
                        $rule->updated_at = $now;
                        $rule->save();
                    }
                }
            }
        }

        CourseUploadRule::where('updated_at', '!=', $now)->get()->map(function ($q) {
            $q->delete();
        });

        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    public function courseEnroll()
    {
        $data['title'] = 'Course Enroll';
        $data['users'] = User::where('role', '!=', 1)->get();
        $data['courses'] = Course::all();

        return view('admin.course.enroll-student', $data);
    }

    public function courseEnrollStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'course_id' => 'required',
        ]);

        if ($request->course_id) {
            $courseOrderExits = Enrollment::where(['user_id' => $request->user_id, 'course_id' => $request->course_id, 'status' => ACCESS_PERIOD_ACTIVE])->whereDate('end_date', '>=', now())->first();

            if ($courseOrderExits) {
                $order = Order::find($courseOrderExits->order_id);
                if ($order) {
                    if ($order->payment_status == 'due') {
                        Order_item::whereOrderId($courseOrderExits->order_id)->get()->map(function ($q) {
                            $q->delete();
                        });
                        $order->delete();
                    } else {
                        $this->showToastrMessage('error', __("Student has already purchased the course!"));
                        return redirect()->back();
                    }
                }
            }
        }

        $ownCourseCheck = CourseInstructor::where('course_id', $request->course_id)->where('instructor_id', $request->user_id)->delete();

        if ($ownCourseCheck) {
            $this->showToastrMessage('error', __("He is a owner of the course. Can't purchase this course!"));
            return redirect()->back();
        }
        $course = Course::find($request->course_id);
        $order = new Order();
        $order->user_id = $request->user_id;
        $order->order_number = rand(100000, 999999);
        $order->payment_status = 'free';
        $order->created_by_type = 2;
        $order->save();

        $order_item = new Order_item();
        $order_item->order_id = $order->id;
        $order_item->user_id = $request->user_id;
        $order_item->course_id = $request->course_id;
        $order_item->owner_user_id = $course->user_id ?? null;
        $order_item->unit_price = 0;
        $order_item->admin_commission = 0;
        $order_item->owner_balance = 0;
        $order_item->sell_commission = 0;
        $order_item->save();


        set_instructor_ranking_level($course->user_id);

        /** ====== Send notification =========*/
        $text = __("New student enrolled");
        $target_url = route('instructor.all-student');
        foreach ($order->items as $item) {
            if ($item->course) {
                $this->send($text, 2, $target_url, $item->course->user_id);
            }

            setEnrollment($item);
        }

        $text = __("Course has been sold");
        $this->send($text, 1, null, null);

        $text = __("New course enrolled by Admin");
        $target_url = route('student.my-learning');
        $this->send($text, 3, $target_url, $request->user_id);

        /** ====== Send notification =========*/

        $this->showToastrMessage('success', __('Student enroll in course'));
        return redirect()->back();
    }
    public function approveCourseDelete(CourseDelete $course_delete){
        $course = Course::where('id', $course_delete->course_id)->firstOrFail();
        $order_item = Order_item::whereCourseId($course->id)->first();
        if ($order_item) {
            $this->showToastrMessage('error', __('You can not delete this course. Because student have already purchased it !'));
            return redirect()->back();
        }
        $lessons = Course_lesson::where('course_id', $course->id)->get();
        if (count($lessons) > 0) {
            foreach ($lessons as $lesson) {
                //start:: lecture delete
                $lectures = Course_lecture::where('lesson_id', $lesson->id)->get();
                if (count($lectures) > 0) {
                    foreach ($lectures as $lecture) {
                        $lecture = Course_lecture::find($lecture->id);
                        if ($lecture) {
                            $this->deleteFile($lecture->file_path); // delete file from server

                            if ($lecture->type == 'vimeo') {
                                if ($lecture->url_path) {
                                    $this->deleteVimeoVideoFile($lecture->url_path);
                                }
                            }

                            Course_lecture_views::where('course_lecture_id', $lecture->id)->get()->map(function ($q) {
                                $q->delete();
                            });

                            $this->lectureModel->delete($lecture->id); // delete record
                        }
                    }
                }
                //end:: lecture delete
                $this->lessonModel->delete($lesson->id);
            }
        }
        //end: lesson delete

        //Start:: Delete this course Wishlist, CartManagement
        Wishlist::where('course_id', $course->id)->delete();
        CartManagement::where('course_id', $course->id)->delete();
        CourseInstructor::where('course_id', $course->id)->delete();
        //End:: Delete this course wishList and addToCart

        $this->deleteFile($course->image);
        $this->deleteVideoFile($course->video);
        $course->delete();


        $this->showToastrMessage('success', __('Course was deleted'));
        return redirect()->back();
    }
    public function removeCourseDelete( CourseDelete $course_delete){
        $course_delete->delete();
        $this->showToastrMessage('success', __('Course deletion request was removed'));
        return redirect()->back();
    }
}
