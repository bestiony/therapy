<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\CourseUpdateCategoryRequest;
use App\Http\Requests\Instructor\StoreCourseRequest;
use App\Models\CartManagement;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseDelete;
use App\Models\CourseVersion;
use App\Models\Course_language;
use App\Models\Course_lecture;
use App\Models\Course_lecture_views;
use App\Models\Course_lesson;
use App\Models\CourseInstructor;
use App\Models\CourseUploadRule;
use App\Models\Difficulty_level;
use App\Models\Instructor;
use App\Models\LearnKeyPoint;
use App\Models\Order_item;
use App\Models\Subcategory;
use App\Models\Tag;
use App\Models\User;
use App\Models\Wishlist;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $data['title'] = 'My Courses';
        $courseInstructorIds = CourseInstructor::where('course_instructor.instructor_id', auth()->id())->join('courses', 'courses.id', '=', 'course_instructor.course_id')->select('user_packages.*')->whereNull('courses.organization_id')->where('course_instructor.status', STATUS_APPROVED)->select('course_id')->get()->pluck('course_id')->toArray();
        $data['courses'] = Course::whereIn('id', $courseInstructorIds)->whereNull('organization_id')->orderBy('id', 'DESC')->paginate(10);
        $data['navCourseActiveClass'] = 'active';
        $data['number_of_course'] = count($courseInstructorIds);
        return view('instructor.course.index', $data);
    }

    public function organizationCourses()
    {
        $data['title'] = 'Organization Courses';
        $courseInstructorIds = CourseInstructor::query()
            ->join('courses', 'courses.id', '=', 'course_instructor.course_id')
            ->select('user_packages.*')
            ->where('courses.organization_id', auth()->user()->instructor->organization_id)
            ->where('course_instructor.status', STATUS_APPROVED)->select('course_id')
            ->groupBy('courses.id')
            ->get()
            ->pluck('course_id')
            ->toArray();
        $data['courses'] = Course::query()
            ->with('organization', 'instructor')
            ->whereIn('courses.id', $courseInstructorIds)
            ->join('course_instructor', 'course_instructor.course_id', '=', 'courses.id')
            ->where('course_instructor.status', STATUS_APPROVED)
            ->where('organization_id', auth()->user()->instructor->organization_id)
            ->groupBy('courses.id')
            ->paginate(10);
        $data['navCourseOrganizationActiveClass'] = 'active';
        $data['number_of_course'] = count($courseInstructorIds);
        return view('instructor.course.index', $data);
    }

    public function create()
    {
        $countCourseInstructor = CourseInstructor::where('instructor_id', auth()->id())->count();
        $countCourse = Course::where('user_id', auth()->id())->count();

        $count = ($countCourseInstructor > $countCourse) ? $countCourseInstructor  : $countCourse;
        $packageType = auth()->user()->role == USER_ROLE_INSTRUCTOR ?  PACKAGE_TYPE_SAAS_INSTRUCTOR : PACKAGE_TYPE_SAAS_ORGANIZATION;
        if (hasLimitSaaS(PACKAGE_RULE_COURSE, $packageType, $count)) {
            $data['title'] = 'Upload Course';
            $data['navCourseUploadActiveClass'] = 'active';
            $data['rules'] = CourseUploadRule::all();
            return view('instructor.course.create', $data);
        } else {
            $this->showToastrMessage('error', __('Your Course Create limit has been finish.'));
            return redirect()->back();
        }
    }

    public function store(StoreCourseRequest $request)
    {
        if (Course::where('slug', Str::slug($request->title))->count() > 0) {
            $slug = Str::slug($request->title) . '-' . rand(100000, 999999);
        } else {
            $slug = Str::slug($request->title);
        }

        $data = [
            'title' => $request->title,
            'course_type' => $request->course_type,
            'subtitle' => $request->subtitle,
            'slug' => $slug,
            'status' => 4,
            'description' => $request->description
        ];

        $data['is_subscription_enable'] = 0;

        if (get_option('subscription_mode')) {
            $data['is_subscription_enable'] = $request->is_subscription_enable;
        }

        if ($data['is_subscription_enable']) {
            $count = Course::where('user_id', auth()->id())->count();
            if (!hasLimitSaaS(PACKAGE_RULE_SUBSCRIPTION_COURSE, PACKAGE_TYPE_SAAS_INSTRUCTOR, $count)) {
                $this->showToastrMessage('error', __('Your Subscription Enable Course Create limit has been finish.'));
                return redirect()->back();
            }
        }

        $course = $this->model->create($data);

        if ($request['key_points']) {
            if (count(@$request['key_points']) > 0) {
                foreach ($request['key_points'] as $item) {
                    if (@$item['name']) {
                        $key_point = new LearnKeyPoint();
                        $key_point->course_id = $course->id;
                        $key_point->name = @$item['name'];
                        $key_point->save();
                    }
                }
            }
        }
        return redirect(route('instructor.course.edit', [$course->uuid, 'step=category']));
    }

    public function edit($uuid)
    {
        $data['navCourseUploadActiveClass'] = 'active';
        $data['title'] = 'Upload Course';
        $data['rules'] = CourseUploadRule::all();
        $data['course'] = Course::where('courses.uuid', $uuid)->whereNull('organization_id')->firstOrFail();
        $user_id = auth()->id();

        if (!$data['course']->user_id == $user_id) {

            $courseInstructor = $data['course']->course_instructors()->where('instructor_id', $user_id)->where('status', STATUS_ACCEPTED)->first();
            if (!$courseInstructor) {
                $this->showToastrMessage('error', __('You don\'t have permission to edit this'));
                return redirect()->back();
            }
        }
        $pending_course_version = CourseVersion::where('course_id', $data['course']->id)->where('status', PENDING_COURSE_VERSION)->first();
        if ($pending_course_version) {
            $this->showToastrMessage('error', __('You have a pending edit. wait for the admin response !'));
            return redirect()->back();
        }

        $data['course_version_id'] = null;
        if (\request('course_version_id')) {

            $course_version_id = \request('course_version_id');
            $data['course_version_id'] = $course_version_id;
            $course_version = CourseVersion::find($course_version_id);
            $details = $course_version->details;
            if (isset($details['lessons'])) {

                $edited_lessons = Course_lesson::whereIn('id', $details['lessons'])->get();
                $data['edited_lessons'] = $edited_lessons;
            }
            if (isset($details['deleted_lectures'])) {
                $data['deleted_lectures'] = Course_lecture::whereIn('id', $details['deleted_lectures'])->pluck('uuid')->toArray();
            }
            if (isset($details['deleted_lessons'])) {
                $data['deleted_lessons'] = Course_lesson::whereIn('id', $details['deleted_lessons'])->pluck('uuid')->toArray();
            }
        }
        $data['keyPoints'] = LearnKeyPoint::whereCourseId($data['course']->id)->get();

        if (\request('step') == 'category') {

            $data['categories'] = Category::active()->orderBy('name', 'asc')->select('id', 'name')->get();
            $data['tags'] = Tag::orderBy('name', 'asc')->select('id', 'name')->get();
            $data['course_languages'] = Course_language::orderBy('name', 'asc')->select('id', 'name')->get();
            $data['difficulty_levels'] = Difficulty_level::orderBy('name', 'asc')->select('id', 'name')->get();
            if (old('category_id')) {
                $data['subcategories'] = Subcategory::where('category_id', old('category_id'))->select('id', 'name')->orderBy('name', 'asc')->get();
            } elseif ($data['course']->category_id) {
                $data['subcategories'] = Subcategory::where('category_id', $data['course']->category_id)->select('id', 'name')->orderBy('name', 'asc')->get();
            } else {
                $data['subcategories'] = [];
            }
            $selected_tags = [];
            if (old('tag')) {
                $selected_tags = old('tag');
            } elseif ($data['course']->tags->count() > 0) {
                foreach ($data['course']->tags as $tag) {
                    $selected_tags[] = $tag->id;
                }
            } else {
                $selected_tags = [];
            }
            $data['selected_tags'] = $selected_tags;
            return view('instructor.course.edit-category', $data);
        } elseif (\request('step') == 'lesson') {

            if ($data['course']->course_type == COURSE_TYPE_GENERAL) {
                return view('instructor.course.lesson', $data);
            } else {
                return view('instructor.course.scorm_upload', $data);
            }
        } elseif (\request('step') == 'instructors') {

            if ($data['course']->user_id != auth()->id()) {
                return view('instructor.course.submit-lesson', $data);
            }
            $data['instructors'] = User::where('role', USER_ROLE_INSTRUCTOR)->where('id', '!=', $data['course']->user_id)->where('id', '!=', auth()->id())->select('id', 'name')->get();
            return view('instructor.course.instructors', $data);
        } elseif (\request('step') == 'submit') {

            return view('instructor.course.submit-lesson', $data);
        } else {
            // dd($data);
            return view('instructor.course.edit', $data);
        }
    }

    public function updateOverview(StoreCourseRequest $request, $uuid)
    {
        $data['navCourseUploadActiveClass'] = 'active';
        $course = Course::where('courses.uuid', $uuid)->first();
        $user_id = auth()->id();

        if (!$course->user_id == $user_id) {

            $courseInstructor = $course->course_instructors()->where('instructor_id', $user_id)->where('status', STATUS_ACCEPTED)->first();
            if (!$courseInstructor) {
                $this->showToastrMessage('error', __('You don\'t have permission to edit this'));
                return redirect()->back();
            }
        }


        if (Course::where('slug', getSlug($request->title))->where('id', '!=', $course->id)->count() > 0) {
            $slug = getSlug($request->title) . '-' . rand(100000, 999999);
        } else {
            $slug = getSlug($request->title);
        }

        $data = [
            'title' => $request->title,
            'course_type' => $request->course_type,
            'subtitle' => $request->subtitle,
            'slug' => $slug,
            'description' => $request->description
        ];

        $data['is_subscription_enable'] = 0;

        if (get_option('subscription_mode')) {
            $data['is_subscription_enable'] = $request->is_subscription_enable;
        }

        if ($data['is_subscription_enable']) {
            if ($course->status == STATUS_APPROVED) {
                $count = CourseInstructor::join('courses', 'courses.id', '=', 'course_instructor.course_id')->where('is_subscription_enable', STATUS_ACCEPTED)->where('course_instructor.instructor_id', auth()->id())->groupBy('course_id')->count();
            } else {
                $count = Course::where('user_id', auth()->id())->count();
            }
            if (!hasLimitSaaS(PACKAGE_RULE_SUBSCRIPTION_COURSE, PACKAGE_TYPE_SAAS_INSTRUCTOR, $count)) {
                $this->showToastrMessage('error', __('Your Subscription Enable Course Create limit has been finish.'));
                return redirect()->back();
            }
        }
        $course_version = CourseVersion::where('course_id', $course->id)->whereIn('status', [INCOMPLETED_COURSE_VERSION, REFUSED_COURSE_VERSION])->first();
        if (!$course_version) {
            $last_course_version = CourseVersion::where('course_id', $course->id)->latest()->first();
            $course_version = CourseVersion::create([
                'course_id' => $course->id,
                'instructor_id' => $user_id,
                'version' => $last_course_version ? $last_course_version->version + 1 : 1,
                'status' => INCOMPLETED_COURSE_VERSION,
                'details' => [],
            ]);
        }

        // $this->model->updateByUuid($data, $uuid); // update category
        $new_learn_key_points = [];
        $now = now();
        if ($request['key_points']) {
            if (count(@$request['key_points']) > 0) {
                foreach ($request['key_points'] as $item) {
                    if (@$item['name']) {
                        if (@$item['id']) {
                            $key_point = LearnKeyPoint::find($item['id']);
                        } else {
                            $key_point = new LearnKeyPoint();
                        }
                        // $key_point->course_id = $course->id;
                        $key_point->name = @$item['name'];
                        $key_point->updated_at = $now;
                        $key_point->save();
                        $new_learn_key_points[] = $key_point->id;
                    }
                }
            }
        }
        $data['new_learn_key_points'] = $new_learn_key_points;
        $data['course_instructors'] = isset($data['course_instructors']) ? $data['course_instructors'] : [];
        $data['tags'] = isset($data['tags']) ? $data['tags'] : [];
        $course_version->details = $data;
        $course_version->update();
        // LearnKeyPoint::where('course_id', $course->id)->where('updated_at', '!=', $now)->get()->map(function ($q) {
        //     $q->delete();
        // });

        if ($course->status != 0) {
            $text = __("Course") . " " . $course->title . " " . __("Course overview has been updated");
            $target_url = route('admin.course.index');
            $this->send($text, 1, $target_url, null);
        }

        return redirect(route('instructor.course.edit', [$course->uuid, 'step=category', "course_version_id" => $course_version]));
    }

    public function updateCategory(Request $request, $uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->first();
        $user_id = auth()->id();

        if (!$course->user_id == $user_id) {

            $courseInstructor = $course->course_instructors()->where('instructor_id', $user_id)->where('status', STATUS_ACCEPTED)->first();
            if (!$courseInstructor) {
                $this->showToastrMessage('error', __('You don\'t have permission to edit this'));
                return redirect()->back();
            }
        }
        $course_version = CourseVersion::find($request->course_version_id);

        if ($request->image) {
            $request->validate([
                'image' => 'dimensions:min_width=575,min_height=450,max_width=575,max_height=450'
            ]);
            if (!$course_version) {
                $this->deleteFile($course->image); // delete file from server
            }

            $image = $this->saveImage('course', $request->image, null, null); // new file upload into server
        } else {
            $image = $course->image;
        }

        if ($request->video) {
            if (!$course_version) {
                $this->deleteVideoFile($course->video); // delete file from server
            }
            $file_details = $this->uploadFileWithDetails('course', $request->video);
            if (!$file_details['is_uploaded']) {
                $this->showToastrMessage('error', __('Something went wrong! Failed to upload file'));
                return redirect()->back();
            }
            $video = $file_details['path'];
        } else {
            $video = $course->video;
        }

        $data = [
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'price' => $request->price,
            'old_price' => $request->old_price,
            'drip_content' => $request->drip_content,
            'access_period' => ($request->access_period || $request->access_period < 0) ? NULL : $request->access_period,
            'course_language_id' => $request->course_language_id,
            'difficulty_level_id' => $request->difficulty_level_id,
            'learner_accessibility' => $request->learner_accessibility,
            'image' => $image ?? null,
            'video' => $video ?? null,
            'intro_video_check' => $request->intro_video_check,
            'youtube_video_id' => $request->youtube_video_id ?? null,
        ];
        if ($course_version) {
            $new_details = array_merge($course_version->details, $data);
            $new_details['tags'] = $request->tag ? $request->tag : [];
            $course_version->details = $new_details;
            $course_version->update();
        } else {
            $this->model->updateByUuid($data, $uuid); // update category
            if ($request->tag) {
                $course->tags()->sync($request->tag);
            }
        }


        if ($course->status != 0) {
            $text = __("Course") . " " . $course->title . " " . __("Course category has been updated");
            $target_url = route('admin.course.index');
            $this->send($text, 1, $target_url, null);
        }


        return redirect(route('instructor.course.edit', [$course->uuid, 'step=lesson', 'course_version_id' => ($course_version ?  $course_version->id : null)]));
    }

    public function uploadFinished($uuid, Request $request)
    {

        $course = Course::where('courses.uuid', $uuid)->first();
        $user_id = auth()->id();

        if (!$course->user_id == $user_id) {

            $courseInstructor = $course->course_instructors()->where('instructor_id', $user_id)->where('status', STATUS_ACCEPTED)->first();
            if (!$courseInstructor) {
                $this->showToastrMessage('error', __('You don\'t have permission to edit this'));
                return redirect()->back();
            }
        }
        $course_version_id = $request->course_version_id;
        if ($course_version_id) {
            $course_version = CourseVersion::find($course_version_id);
            $course_version->status = PENDING_COURSE_VERSION;
            $course_version->update();
        }

        if ($course->status == 1) {
            if (!$course_version_id) {

                if ($course->user_id != auth()->id()) {
                    //TODO: notify from here to multi instructor;
                    $text = __("You have selected as co-instructor");
                    $target_url = route('instructor.multi_instructor');
                    $courseInstructors = $course->course_instructors->where('status', STATUS_PENDING)->where('instructor_id', '!=', $course->user_id);

                    foreach ($courseInstructors as $courseInstructor) {
                        $this->send($text, 2, $target_url, $courseInstructor->instructor->user_id);
                    }
                }
            }
        } else {
            if (!$course_version_id) {
                $course->status = 2;
            }
        }
        $course->save();

        if ($course_version_id) {
            if ($course->status != 0) {
                $text = __("Course") . " " . $course->title . " " . __(" have a pending update by the instructor");
                $target_url = route('admin.course.version-view', ['id' => $course_version_id]);
                $this->send($text, 1, $target_url, null);
            }
        }
        return redirect(route('instructor.course'));
    }

    public function getSubcategoryByCategory($category_id)
    {
        return Subcategory::where('category_id', $category_id)->select('id', 'name')->get()->toJson();
    }

    public function delete($uuid)
    {
        $course = Course::where('user_id', auth()->id())->whereUuid($uuid)->firstOrFail();
        $order_item = Order_item::whereCourseId($course->id)->first();
        if ($order_item) {
            $this->showToastrMessage('error', __('You can not deleted. Because already student purchased this course !'));
            return redirect()->back();
        }
        $course_delete = CourseDelete::where('course_id', $course->id)->first();
        if($course_delete){
            $this->showToastrMessage('error', __('You already have a deletion request pending. wait for admin apporval !'));
            return redirect()->back();
        }else{
            $course_delete = CourseDelete::create([
                'course_id'=> $course->id,
                'instructor_id'=> auth()->id(),
                'reason'=>'',
            ]);
        }


        //start:: Course lesson delete

        $this->showToastrMessage('success', __('Course deletion request has been submitted .'));
        return redirect()->back();
    }

    public function storeInstructor(Request $request, $uuid)
    {
        $course = Course::where('user_id', auth()->id())->whereUuid($uuid)->firstOrFail();
        $course_version_id = $request->course_version_id;
        $course_version = CourseVersion::find($course_version_id);
        $details = $course_version ? $course_version->details : [];
        if ($course->user_id == auth()->id()) {
            $request->validate([
                'share.*' => 'bail|required|min:0|max:100'
            ]);

            $data = $request->all();
            $courseInstructorIds = [];
            if ($request->instructor_id) {

                $totalShare = array_sum($request->share);
                if ($totalShare > 100) {
                    $this->showToastrMessage('error', 'The total percentage should not be grater than 100');
                    return back()->withInput();
                }

                foreach ($data['instructor_id'] as $id => $instructor) {

                    if (!$course_version) {
                        $courseInstructor = CourseInstructor::updateOrCreate([
                            'instructor_id' => $id,
                            'course_id' => $course->id,
                        ], [
                            'instructor_id' => $id,
                            'course_id' => $course->id,
                            'share' => $data['share'][$id],
                        ]);

                        array_push($courseInstructorIds, $courseInstructor->id);
                    } else {
                        $details['course_instructors'][$id] = [
                            'instructor_id' => $id,
                            'share' => $data['share'][$id],
                        ];
                    }
                }
            } else {
                $totalShare = 0;
            }


            if (!$course_version) {

                $courseInstructor = CourseInstructor::updateOrCreate([
                    'instructor_id' => $course->user_id,
                    'course_id' => $course->id,
                ], [
                    'instructor_id' => $course->user_id,
                    'course_id' => $course->id,
                    'share' => (100 - $totalShare),
                    'status' => STATUS_ACCEPTED
                ]);
                array_push($courseInstructorIds, $courseInstructor->id);

                CourseInstructor::whereNotIn('id', $courseInstructorIds)->where('course_id', $course->id)->delete();
            } else {
                $details['course_main_instructor'] = [
                    'instructor_id' => $course->user_id,
                    'course_id' => $course->id,
                    'share' => (100 - $totalShare),
                ];
            }

            if ($course_version) {
                $course_version->details = $details;
                $course_version->update();
            }


            return redirect(route('instructor.course.edit', [$course->uuid, 'step=submit', 'course_version_id' => $course_version_id]));
        }
    }
}
