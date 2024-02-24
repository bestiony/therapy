<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\CourseUpdateCategoryRequest;
use App\Http\Requests\Instructor\StoreCourseRequest;
use App\Models\CartManagement;
use App\Models\Category;
use App\Models\Course;
use App\Models\Organization;
use App\Models\Course_language;
use App\Models\Course_lecture;
use App\Models\Course_lecture_views;
use App\Models\Course_lesson;
use App\Models\CourseInstructor;
use App\Models\CourseUploadRule;
use App\Models\CourseVersion;
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
use Illuminate\Support\Facades\Gate;
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
        $data['title'] = 'My Course';
        $data['courses'] = Course::where('organization_id', auth()->user()->organization->id)->orderBy('id', 'DESC')->paginate();
        $data['navCourseActiveClass'] = 'active';
        $data['number_of_course'] = count($data['courses']);
        return view('organization.course.index', $data);
    }
    public function createEmpty()
    {
        /**
         * @var User $user
         */
        $user = auth()->user();
        $count = Course::where('user_id', $user->id)->whereNot('status', EMPTY_COURSE)->count();
        if (!hasLimitSaaS(PACKAGE_RULE_COURSE, PACKAGE_TYPE_SAAS_ORGANIZATION, $count)) {
            $this->showToastrMessage('error', __('Your Course Create limit has been finish.'));
            return redirect()->back();
        }
        $course = $user->courses()->create(['status' => EMPTY_COURSE]);
        return redirect(route('organization.course.set-overview', [$course->uuid]));
    }
    public function setOverview($uuid)
    {
        $course = Course::withoutGlobalScope('excludeEmptyCourses')->where('courses.uuid', $uuid)->firstOrFail();

        $data['title'] = 'Upload Course';
        $data['navCourseUploadActiveClass'] = 'active';
        $data['rules'] = CourseUploadRule::all();
        $data['course'] = $course;
        return view('organization.course.create', $data);
    }
    public function setCategory($uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
        $this->authorize('update', $course);
        $data['course'] = $course;
        return view('organization.course.edit-category', $data);
    }
    public function addLessons($uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
        $this->authorize('update', $course);
        $data['course'] = $course;
        return view('organization.course.lesson', $data);
    }
    public function addInstructors($uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
        $this->authorize('update', $course);
        $organization = auth()->user()->organization;
        if (!$organization) {
            $this->showToastrMessage('error', __('You don\'t have organization \' permissions to edit this'));
            return redirect()->back();
        }
        $data['instructors'] = $organization->users;
        $data['course'] = $course;
        return view('organization.course.instructors', $data);
    }
    public function submitDraft($uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
        $this->authorize('update', $course);
        $data['course'] = $course;
        return view('organization.course.submit-lesson', $data);
    }
    public function finishDraft($uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
        $this->authorize('update', $course);

        if ($course->user_id != auth()->id()) {
            //TODO: notify from here to multi instructor;
            $text = __("You have been selected as co-instructor");
            $target_url = route('organization.multi_instructor');
            $courseInstructors = $course->course_instructors->where('status', STATUS_PENDING)->where('instructor_id', '!=', $course->user_id);

            foreach ($courseInstructors as $courseInstructor) {
                $this->send($text, 2, $target_url, $courseInstructor->instructor->user_id);
            }
        }

        $course->status = WAITING_FOR_REVIEW_COURSE;
        $course->save();

        // notify admin for review course
        $text = __("Course") . " " . $course->title . " " . __(" have a pending update by the instructor");
        $target_url = route('admin.course.review_pending');
        $this->send($text, 1, $target_url, null);


        return redirect(route('organization.course.index'));
    }

    // ! Deprecated
    public function store(StoreCourseRequest $request)
    {
        if (Course::where('slug', Str::slug($request->title))->count() > 0) {
            $slug = Str::slug($request->title) . '-' . rand(100000, 999999);
        } else {
            $slug = Str::slug($request->title);
        }

        $data = [
            'title' => $request->title,
            'private_mode' => $request->private_mode,
            'course_type' => $request->course_type,
            'subtitle' => $request->subtitle,
            'slug' => $slug,
            'status' => DRAFT_COURSE,
            'description' => $request->description
        ];

        $data['is_subscription_enable'] = 0;

        if (get_option('subscription_mode')) {
            $data['is_subscription_enable'] = $request->is_subscription_enable;
        }

        if ($data['is_subscription_enable']) {
            $count = Course::where('user_id', auth()->id())->count();
            if (!hasLimitSaaS(PACKAGE_RULE_SUBSCRIPTION_COURSE, PACKAGE_TYPE_SAAS_ORGANIZATION, $count)) {
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
        return redirect(route('organization.course.edit', [$course->uuid, 'step=category']));
    }

    public function edit($uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
        if ($course->isStillNew()) {
            return redirect(route('organization.course.set-overview', [$course->uuid]));
        } else {
            return redirect(route('organization.course.update-overview', [$course->uuid]));
        }
        $data['navCourseUploadActiveClass'] = 'active';
        $data['title'] = 'Upload Course';
        $data['rules'] = CourseUploadRule::all();
        $data['course'] = $course;

        $user_id = auth()->id();

        if (!$data['course']->user_id == $user_id) {
            $this->showToastrMessage('error', __('You don\'t have permission to edit this'));
            return redirect()->back();
        }

        $pending_course_version = CourseVersion::where('course_id', $data['course']->id)->where('status', PENDING_COURSE_VERSION)->first();
        if ($pending_course_version || $course->status == WAITING_FOR_REVIEW_COURSE) {
            $this->showToastrMessage('error', __('You have a pending edit. wait for the admin response !'));
            return redirect()->back();
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
        $data['course_version_id'] = $course_version->id;
        $data['course_version'] = $course_version;
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
            if (isset($details['updated_course_lessons'])) {
                $data['updated_course_lessons'] = Course_lesson::whereIn('id', array_keys($details['updated_course_lessons']))
                    ->get()
                    ->mapWithKeys(function ($model) {
                        return [$model->id => $model];
                    })
                    ->toArray();
            }
            if (isset($details['deleted_lessons'])) {
                $data['deleted_lessons'] = Course_lesson::whereIn('id', $details['deleted_lessons'])
                    ->get()
                    ->mapWithKeys(function ($model) {
                        return [$model->uuid => $model];
                    })
                    ->toArray();
            }
        }
        $added_key_points = LearnKeyPoint::whereIn('id', data_get($course_version->details, 'new_learn_key_points', []))->get();
        $data['keyPoints'] = LearnKeyPoint::whereCourseId($data['course']->id)->get()->concat($added_key_points);
        $added_lessons = Course_lesson::whereIn('id', data_get($course_version->details, 'lessons', []))->get();
        $data['lessons'] = Course_lesson::whereCourseId($data['course']->id)->get()->concat($added_lessons);
        $added_lectures = Course_lecture::with('lesson')->whereIn('id', data_get($course_version->details, 'lectures', []))->get();
        $data['lectures'] = Course_lecture::whereCourseId($data['course']->id)->get()->concat($added_lectures);
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

            return view('organization.course.edit-category', $data);
        } elseif (\request('step') == 'lesson') {
            if ($data['course']->course_type == COURSE_TYPE_GENERAL) {
                return view('organization.course.lesson', $data);
            } else {
                return view('organization.course.scorm_upload', $data);
            }
        } elseif (\request('step') == 'instructors') {
            if ($data['course']->user_id != auth()->id()) {
                return view('organization.course.submit-lesson', $data);
            }

            $organization_id = auth()->user()->organization->id;
            $data['instructors'] = User::where('role', USER_ROLE_INSTRUCTOR)->where('instructors.organization_id', $organization_id)->where('instructors.status', STATUS_APPROVED)->join('instructors', 'instructors.user_id', 'users.id')->where('users.id', '!=', $data['course']->user_id)->where('users.id', '!=', auth()->id())->select('users.id', 'users.name')->get();
            return view('organization.course.instructors', $data);
        } elseif (\request('step') == 'submit') {
            return view('organization.course.submit-lesson', $data);
        } else {
            return view('organization.course.edit', $data);
        }
    }

    public function updateOverview($uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
        $this->authorize('update', $course);
        if (Gate::denies('createNewVersion', $course)) {
            $this->showToastrMessage('error', __('This course was not submited yet. finish creating the course !'));
            return redirect()->back();
        }

        $user = auth()->user();
        $pending_course_version = $course->courseVersions()->where('status', PENDING_COURSE_VERSION)->first();
        if ($pending_course_version || $course->status == WAITING_FOR_REVIEW_COURSE) {
            $this->showToastrMessage('error', __('You have a pending edit. wait for the admin response !'));
            return redirect()->back();
        }

        $course_version = $course->activeCourseVersion();
        if (!$course_version) {
            $last_course_version = $course->courseVersions()->latest()->first();
            $course_version = $course->courseVersions()->create([
                'instructor_id' => $user->id,
                'version' => $last_course_version ? $last_course_version->version + 1 : 1,
                'status' => INCOMPLETED_COURSE_VERSION,
                'details' => [],
            ]);
        }

        $data['title'] = 'Upload Course';
        $data['navCourseUploadActiveClass'] = 'active';
        $data['rules'] = CourseUploadRule::all();
        $data['course'] = $course;
        return view('organization.course.edit.create', $data);


        $data['navCourseUploadActiveClass'] = 'active';
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
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
            'private_mode' => $request->private_mode,
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
            if (!hasLimitSaaS(PACKAGE_RULE_SUBSCRIPTION_COURSE, PACKAGE_TYPE_SAAS_ORGANIZATION, $count)) {
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
        $details = $course_version->details;
        // $this->model->updateByUuid($data, $uuid); // update category
        $new_learn_key_points = data_get($details, 'new_learn_key_points', []);
        $updated_learn_key_points = data_get($details, 'updated_learn_key_points', []);

        $keyPoints = $request->key_points;
        if ($keyPoints) {
            foreach ($keyPoints as $item) {
                $name = data_get($item, 'name');
                $id = data_get($item, 'id');
                if ($name) {
                    if ($id) {
                        $key_point = LearnKeyPoint::find($item['id']);
                        if ($key_point->name != $name) {
                            $updated_learn_key_points[$key_point->id] = [
                                'name' => $name,
                            ];
                        }
                    } else {
                        $key_point = LearnKeyPoint::create([
                            'name' => $name,
                        ]);
                        if (!in_array($key_point->id, $new_learn_key_points)) {
                            $new_learn_key_points[] = $key_point->id;
                        }
                    }
                }
            }
        }
        $data['new_learn_key_points'] = $new_learn_key_points;
        $data['updated_learn_key_points'] = $updated_learn_key_points;

        $data['course_instructors'] = isset($data['course_instructors']) ? $data['course_instructors'] : [];
        $data['tags'] = isset($data['tags']) ? $data['tags'] : [];
        $course_version->details = $data;
        $course_version->update();

        // LearnKeyPoint::where('course_id', $course->id)->where('updated_at', '!=', $now)->get()->map(function ($q) {
        //     $q->delete();
        // });

        if ($course->status != 0) {
            $text = __("Course overview has been updated");
            $target_url = route('admin.course.index');
            $this->send($text, 1, $target_url, null);
        }

        return redirect(route('organization.course.edit', [$course->uuid, 'step=category', "course_version_id" => $course_version]));
    }

    public function updateCategory(Request $request, $uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
        $this->authorize('update', $course);
        $course_version = $course->activeCourseVersion();
        if (!$course_version) {
            $this->showToastrMessage('error', __('Start from the first page !'));
            return redirect()->back();
        }
        $data = [
            'course' => $course,
            'course_version' => $course_version,
        ];
        return view('organization.course.edit.edit-category', $data);




        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
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

        // if ($course->status != 0) {
        //     $text = __("Course category has been updated");
        //     $target_url = route('admin.course.index');
        //     $this->send($text, 1, $target_url, null);
        // }


        return redirect(route('organization.course.edit', [$course->uuid, 'step=lesson', 'course_version_id' => ($course_version ?  $course_version->id : null)]));
    }
    public function updateLessons($uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
        $this->authorize('update', $course);
        $data['course'] = $course;
        return view('organization.course.edit.lesson', $data);
    }
    public function updateInstructors($uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
        $this->authorize('update', $course);
        $organization = auth()->user()->organization;
        if (!$organization) {
            $this->showToastrMessage('error', __('You don\'t have organization \' permissions to edit this'));
            return redirect()->back();
        }
        $data['instructors'] = $organization->users;
        $data['course'] = $course;
        return view('organization.course.edit.instructors', $data);
    }
    public function updateCourseInstructors(Request $request, $uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
        $this->authorize('update', $course);
        $totalShare = 0;
        abort_if($course->user_id != auth()->id(), 403);

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
                $courseInstructor = CourseInstructor::updateOrCreate([
                    'instructor_id' => $id,
                    'course_id' => $course->id,
                ], [
                    'instructor_id' => $id,
                    'course_id' => $course->id,
                    'share' => $data['share'][$id],
                ]);
                array_push($courseInstructorIds, $courseInstructor->id);
            }
        } else {
            $totalShare = 0;
        }

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
        $this->showToastrMessage('success', __('Instructors has been added successfully.'));
        return redirect(route('organization.course.submit-version', $course->uuid));
    }
    public function submitVersion($uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
        $this->authorize('update', $course);
        $data['course'] = $course;
        return view('organization.course.edit.submit-lesson', $data);
    }
    public function uploadFinished($uuid, Request $request)
    {
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
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

            if ($course->user_id != auth()->id()) {
                //TODO: notify from here to multi instructor;
                $text = __("You have been selected as co-instructor");
                $target_url = route('organization.multi_instructor');
                $courseInstructors = $course->course_instructors->where('status', STATUS_PENDING)->where('instructor_id', '!=', $course->user_id);

                foreach ($courseInstructors as $courseInstructor) {
                    $this->send($text, 2, $target_url, $courseInstructor->instructor->user_id);
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
        return redirect(route('organization.course.index'));
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

        //Start:: Delete this course Wishlist, CartManagement
        Wishlist::where('course_id', $course->id)->delete();
        CartManagement::where('course_id', $course->id)->delete();
        CourseInstructor::where('course_id', $course->id)->delete();
        //End:: Delete this course wishList and addToCart

        $this->deleteFile($course->image);
        $this->deleteVideoFile($course->video);
        $course->delete();
        $this->showToastrMessage('success', __('Course has been deleted.'));
        return redirect()->back();
    }

    public function setInstructors(Request $request, $uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
        $this->authorize('update', $course);
        $totalShare = 0;
        abort_if($course->user_id != auth()->id(), 403);

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
                $courseInstructor = CourseInstructor::updateOrCreate([
                    'instructor_id' => $id,
                    'course_id' => $course->id,
                ], [
                    'instructor_id' => $id,
                    'course_id' => $course->id,
                    'share' => $data['share'][$id],
                ]);
                array_push($courseInstructorIds, $courseInstructor->id);
            }
        } else {
            $totalShare = 0;
        }

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
        $this->showToastrMessage('success', __('Instructors has been added successfully.'));
        return redirect(route('organization.course.submit-draft', $course->uuid));
    }
    public function storeInstructor(Request $request, $uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
        $this->authorize('update', $course);
        $course_version_id = $request->course_version_id;
        $course_version = CourseVersion::find($course_version_id);
        $details = $course_version ? $course_version->details : [];
        $totalShare = 0;
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

                if (!$course_version) {
                    foreach ($data['instructor_id'] as $id => $instructor) {
                        $courseInstructor = CourseInstructor::updateOrCreate([
                            'instructor_id' => $id,
                            'course_id' => $course->id,
                        ], [
                            'instructor_id' => $id,
                            'course_id' => $course->id,
                            'share' => $data['share'][$id],
                        ]);
                        array_push($courseInstructorIds, $courseInstructor->id);
                    }
                } else {
                    foreach ($data['instructor_id'] as $id => $instructor) {
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

            return redirect(route('organization.course.edit', [$course->uuid, 'step=submit', 'course_version_id' => $course_version_id]));
        }
    }
}
