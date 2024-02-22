<div class="instructor-profile-right-part instructor-upload-course-box-part">
    <div class="instructor-upload-course-box">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div id="msform">
                        <!-- progressbar -->
                        <ul id="progressbar"
                            class="upload-course-item-block d-flex align-items-center justify-content-center">
                            <li class="active" id="account"><strong>{{ __('Course Overview') }}</strong></li>
                            <li class="active" id="personal"><strong>{{ __('Upload Video') }}</strong></li>
                            <li id="instructor"><strong>{{ __('Instructor') }}</strong></li>
                            <li id="confirm"><strong>{{ __('Submit Process') }}</strong></li>
                        </ul>

                        <!-- Upload Course Step-1 Item Start -->
                        <div class="upload-course-step-item upload-course-overview-step-item">
                            <!-- Upload Course Step-2 Item Start -->
                            <div class="upload-course-step-item upload-course-video-step-item">


                                @if ($lessons->count() > 0)
                                    <!-- Upload Course Video-2 start -->

                                    <div id="upload-course-video-2">
                                        <div class="upload-course-item-block course-overview-step1 radius-8">
                                            <div class="upload-course-item-block-title mb-3">
                                                <p class="color-para">Section list of <span
                                                        class="color-heading">“{{ $course->title }}”</span></p>
                                            </div>
                                            @if ($lectures->count() > 0)
                                                <div id="upload-course-video-6" class="upload-course-video-6">
                                                    <div class="accordion mb-30" id="video-upload-done-phase">
                                                        @foreach ($lessons as $key => $lesson)
                                                            @php
                                                                $key = $lesson->id;
                                                                $lesson_was_edited = array_key_exists($lesson->id, $updated_course_lessons);
                                                                $lesson_was_deleted = array_key_exists($lesson->uuid, $deleted_lessons);
                                                            @endphp
                                                            <div class="accordion-item video-upload-final-item mb-2">
                                                                <div class="accordion-header mb-0 " id="headingOne">

                                                                    <button
                                                                        class="accordion-button upload-introduction-title-box d-flex align-items-center justify-content-between
                                                                            {{ $lesson_was_deleted ? 'bg-danger text-light text-decoration-line-through ' : '' }}
                                                                            "
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#collapse{{ $key }}"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapse{{ $key }}">
                                                                        <div>
                                                                            @if ($lesson_was_deleted)
                                                                                <span
                                                                                    class="text-decoration-none">[{{ __('DELETED') }}]</span>
                                                                            @endif

                                                                            <span
                                                                                class="font-16 ps-4 {{ $lesson_was_edited ? 'text-decoration-line-through' : '' }}">{{ $lesson->name }}</span>
                                                                            @if ($lesson_was_edited)
                                                                                <span class="font-16 ps-4 ">
                                                                                    --->
                                                                                    {{ __('New title') . ': ' . $updated_course_lessons[$lesson->id]['name'] }}
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                        <span
                                                                            class="d-flex upload-course-video-6-duration-count ">
                                                                            <span
                                                                                class="upload-course-duration-text font-14 color-para font-medium"><span
                                                                                    class="iconify"
                                                                                    data-icon="octicon:device-desktop-24"></span>{{ __('Video') }}
                                                                                <span
                                                                                    class="color-heading">({{ $lesson->lectures->count() }})</span></span>
                                                                            <span
                                                                                class="upload-course-duration-text font-14 color-para font-medium"><span
                                                                                    class="iconify"
                                                                                    data-icon="ant-design:clock-circle-outlined"></span>{{ __('Duration') }}
                                                                                <span
                                                                                    class="color-heading">({{ @$lesson->lectures->count() > 0 ? lessonVideoDuration($course->id, $lesson->id) : '0 min' }})</span></span>
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                                <div id="collapse{{ $key }}"
                                                                    class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }} "
                                                                    aria-labelledby="heading{{ $key }}"
                                                                    data-bs-parent="#video-upload-done-phase">
                                                                    <div class="accordion-body">
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center">
                                                                            {{-- <div
                                                                                    class="upload-introduction-box-content-img">
                                                                                    <img src="{{ asset('frontend/assets/img/instructor-img/upload-lesson-icon.png') }}"
                                                                alt="upload">
                                                            </div> --}}
                                                                            <div
                                                                                class="
                                                                                {{-- mt-3 lecture-edit-upload-btn --}}
                                                                                ">
                                                                                <a href="{{ route('organization.upload.lecture', [$course->uuid, $lesson->uuid, 'course_version_id' => $course_version_id]) }}"
                                                                                    class="common-upload-video-btn color-heading font-13 font-medium ms-0 mt-4">
                                                                                    <span class="iconify"
                                                                                        data-icon="feather:upload-cloud">
                                                                                    </span>{{ __('Upload lesson') }}</a>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button"
                                                                                    data-name="{{ $lesson->name }}"
                                                                                    data-route="{{ route('organization.lesson.update', [$course->uuid, $lesson->uuid]) }}"
                                                                                    class=" upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 editLesson"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#becomeAnInstructor">
                                                                                    <span class="iconify"
                                                                                        data-icon="clarity:note-edit-line"></span>{{ __('Edit') }}
                                                                                </button>
                                                                                @if ($lesson_was_deleted)
                                                                                    <button
                                                                                        class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 restoreItm"
                                                                                        data-formid="restore_row_form_{{ $lesson->uuid }}">
                                                                                        <span class="iconify"
                                                                                            data-icon="tabler:restore"></span>{{ __('Restore') }}
                                                                                    </button>
                                                                                    <form
                                                                                        action="{{ route('organization.lesson.restore', [$lesson->uuid]) }}"
                                                                                        method="post"
                                                                                        id="restore_row_form_{{ $lesson->uuid }}">
                                                                                        {{ method_field('DELETE') }}
                                                                                        <input type="hidden"
                                                                                            name="_token"
                                                                                            value="{{ csrf_token() }}">
                                                                                        @if ($course_version_id)
                                                                                            <input type="hidden"
                                                                                                name="course_version_id"
                                                                                                value="{{ $course_version_id }}">
                                                                                        @endif
                                                                                    </form>
                                                                                @else
                                                                                    <button
                                                                                        class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 deleteItem"
                                                                                        data-formid="delete_row_form_{{ $lesson->uuid }}">
                                                                                        <span class="iconify"
                                                                                            data-icon="ant-design:delete-outlined"></span>{{ __('Delete') }}
                                                                                    </button>
                                                                                    <form
                                                                                        action="{{ route('organization.lesson.delete', [$lesson->uuid]) }}"
                                                                                        method="post"
                                                                                        id="delete_row_form_{{ $lesson->uuid }}">
                                                                                        {{ method_field('DELETE') }}
                                                                                        <input type="hidden"
                                                                                            name="_token"
                                                                                            value="{{ csrf_token() }}">
                                                                                        @if ($course_version_id)
                                                                                            <input type="hidden"
                                                                                                name="course_version_id"
                                                                                                value="{{ $course_version_id }}">
                                                                                        @endif
                                                                                    </form>
                                                                                @endif
                                                                            </div>
                                                                        </div>


                                                                        @forelse($lesson->lectures as $lecture)
                                                                            @if (in_array($lecture->uuid, $deleted_lectures))
                                                                                {{-- <p> deleted lecture --- {{$lecture->uuid}}</p> --}}
                                                                                @continue
                                                                            @endif
                                                                            <div
                                                                                class="main-upload-video-processing-item removable-item">

                                                                                <div
                                                                                    class="main-upload-video-processing-img-wrap-edit-img">

                                                                                    @if ($lecture->type == 'video')
                                                                                        <a data-normal_video="{{ getVideoFile($lecture->file_path) }}"
                                                                                            title="See video preview"
                                                                                            class="normalVideo edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                            data-bs-toggle="modal"
                                                                                            href="#html5VideoPlayerModal">
                                                                                            <div
                                                                                                class="d-flex flex-grow-1">
                                                                                                <div><img
                                                                                                        src="{{ asset('frontend/assets/img/courses-img/play.svg') }}"
                                                                                                        alt="play">
                                                                                                </div>
                                                                                                <div
                                                                                                    class="font-medium font-16 lecture-edit-title">
                                                                                                    {{ $lecture->title }}
                                                                                                </div>
                                                                                            </div>

                                                                                            <div
                                                                                                class="upload-course-video-6-text flex-shrink-0">
                                                                                                <span
                                                                                                    class="see-preview-video font-medium font-16">{{ __('Preview Video') }}</span>
                                                                                            </div>
                                                                                        </a>
                                                                                    @elseif($lecture->type == 'youtube')
                                                                                        <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3 venobox"
                                                                                            data-autoplay="true"
                                                                                            data-maxwidth="800px"
                                                                                            data-vbtype="video"
                                                                                            data-href="https://www.youtube.com/embed/{{ $lecture->url_path }}">
                                                                                            <div
                                                                                                class="d-flex flex-grow-1">
                                                                                                <div><img
                                                                                                        src="{{ asset('frontend/assets/img/courses-img/play.svg') }}"
                                                                                                        alt="play">
                                                                                                </div>
                                                                                                <div
                                                                                                    class="font-medium font-16 lecture-edit-title">
                                                                                                    {{ $lecture->title }}
                                                                                                </div>
                                                                                            </div>

                                                                                            <div
                                                                                                class="upload-course-video-6-text flex-shrink-0">
                                                                                                <span
                                                                                                    class="see-preview-video font-medium font-16">{{ __('Preview Video') }}</span>
                                                                                            </div>
                                                                                        </a>
                                                                                    @elseif($lecture->type == 'vimeo')
                                                                                        <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3 venobox"
                                                                                            data-autoplay="true"
                                                                                            data-maxwidth="800px"
                                                                                            data-vbtype="video"
                                                                                            data-href="https://vimeo.com/{{ $lecture->url_path }}">
                                                                                            <div
                                                                                                class="d-flex flex-grow-1">
                                                                                                <div>
                                                                                                    <img src="{{ asset('frontend/assets/img/courses-img/play.svg') }}"
                                                                                                        alt="play">
                                                                                                </div>
                                                                                                <div
                                                                                                    class="font-medium font-16 lecture-edit-title">
                                                                                                    {{ $lecture->title }}
                                                                                                </div>
                                                                                            </div>

                                                                                            <div
                                                                                                class="upload-course-video-6-text flex-shrink-0">
                                                                                                <span
                                                                                                    class="see-preview-video font-medium font-16">{{ __('Preview Video') }}</span>
                                                                                            </div>
                                                                                        </a>
                                                                                    @elseif($lecture->type == 'text')
                                                                                        <a type="button"
                                                                                            data-lecture_text="{{ $lecture->text }}"
                                                                                            class="lectureText edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                            data-bs-toggle="modal"
                                                                                            href="#textUploadModal">
                                                                                            <div
                                                                                                class="d-flex flex-grow-1">
                                                                                                <div><img
                                                                                                        src="{{ asset('frontend/assets/img/courses-img/text.svg') }}"
                                                                                                        alt="text">
                                                                                                </div>
                                                                                                <div
                                                                                                    class="font-medium font-16 lecture-edit-title">
                                                                                                    {{ $lecture->title }}
                                                                                                </div>
                                                                                            </div>

                                                                                            <div
                                                                                                class="upload-course-video-6-text flex-shrink-0">
                                                                                                <span
                                                                                                    class="see-preview-video font-medium font-16">{{ __('Preview') }}</span>
                                                                                            </div>
                                                                                        </a>
                                                                                    @elseif($lecture->type == 'image')
                                                                                        <a data-lecture_image="{{ getImageFile($lecture->image) }}"
                                                                                            class="lectureImage edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                            data-bs-toggle="modal"
                                                                                            href="#imageUploadModal">
                                                                                            <div
                                                                                                class="d-flex flex-grow-1">
                                                                                                <div><img
                                                                                                        src="{{ asset('frontend/assets/img/courses-img/preview-image.svg') }}"
                                                                                                        alt="image">
                                                                                                </div>
                                                                                                <div
                                                                                                    class="font-medium font-16 lecture-edit-title">
                                                                                                    {{ $lecture->title }}
                                                                                                </div>
                                                                                            </div>

                                                                                            <div
                                                                                                class="upload-course-video-6-text flex-shrink-0">
                                                                                                <span
                                                                                                    class="see-preview-video font-medium font-16">{{ __('Preview Image') }}
                                                                                                </span>
                                                                                            </div>
                                                                                        </a>
                                                                                    @elseif($lecture->type == 'pdf')
                                                                                        <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                            data-bs-toggle="modal"
                                                                                            href="#pdfModal">
                                                                                            <div
                                                                                                class="d-flex flex-grow-1">
                                                                                                <div><img
                                                                                                        src="{{ asset('frontend/assets/img/courses-img/file-pdf.svg') }}"
                                                                                                        alt="PDF">
                                                                                                </div>
                                                                                                <div
                                                                                                    class="font-medium font-16 lecture-edit-title">
                                                                                                    {{ $lecture->title }}
                                                                                                </div>
                                                                                            </div>

                                                                                            <div
                                                                                                class="upload-course-video-6-text flex-shrink-0">
                                                                                                <span
                                                                                                    class="see-preview-video font-medium font-16">{{ __('Preview PDF') }}</span>
                                                                                            </div>
                                                                                        </a>
                                                                                    @elseif($lecture->type == 'slide_document')
                                                                                        <a data-lecture_slide="{{ $lecture->slide_document }}"
                                                                                            class="lectureSlide edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                            data-bs-toggle="modal"
                                                                                            href="#slideModal">
                                                                                            <div
                                                                                                class="d-flex flex-grow-1">
                                                                                                <div><img
                                                                                                        src="{{ asset('frontend/assets/img/courses-img/slide-preview.svg') }}"
                                                                                                        alt="Slide Doc">
                                                                                                </div>
                                                                                                <div
                                                                                                    class="font-medium font-16 lecture-edit-title">
                                                                                                    {{ $lecture->title }}
                                                                                                </div>
                                                                                            </div>

                                                                                            <div
                                                                                                class="upload-course-video-6-text flex-shrink-0">
                                                                                                <span
                                                                                                    class="see-preview-video font-medium font-16">{{ __('Preview Slide') }}</span>
                                                                                            </div>
                                                                                        </a>
                                                                                    @elseif($lecture->type == 'audio')
                                                                                        <a data-lecture_audio="{{ getVideoFile($lecture->audio) }}"
                                                                                            class=" lectureAudio edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                            data-bs-toggle="modal"
                                                                                            href="#audioPlayerModal">
                                                                                            <div
                                                                                                class="d-flex flex-grow-1">
                                                                                                <div>
                                                                                                    <img src="{{ asset('frontend/assets/img/courses-img/preview-audio-o.svg') }}"
                                                                                                        alt="play">
                                                                                                </div>
                                                                                                <div
                                                                                                    class="font-medium font-16 lecture-edit-title">
                                                                                                    {{ $lecture->title }}
                                                                                                </div>
                                                                                            </div>

                                                                                            <div
                                                                                                class="upload-course-video-6-text flex-shrink-0">
                                                                                                <span
                                                                                                    class="see-preview-video font-medium font-16">{{ __('Preview') }}</span>
                                                                                            </div>
                                                                                        </a>
                                                                                    @endif


                                                                                </div>

                                                                                <div class="d-flex">
                                                                                    <div class="flex-shrink-0">
                                                                                        <div
                                                                                            class="video-upload-done-phase-action-btns font-14 color-heading text-center font-medium">
                                                                                            <a href="{{ route('organization.edit.lecture', [$course->uuid, $lesson->uuid, $lecture->uuid, 'course_version_id' => $course_version_id ?? null]) }}"
                                                                                                type="button"
                                                                                                class="upload-course-video-edit-btn upload-course-video-main-edit-btn font-14 color-para font-medium bg-transparent border-0 mx-2"><span
                                                                                                    class="iconify"
                                                                                                    data-icon="clarity:note-edit-line"></span>{{ __('Edit') }}</a>
                                                                                            <a href="javascript:void(0);"
                                                                                                data-url="{{ route('organization.delete.lecture', [$course->uuid, $lecture->uuid, 'course_version_id' => $course_version_id ?? null]) }}"
                                                                                                class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 mx-2 delete"><span
                                                                                                    class="iconify"
                                                                                                    data-icon="ant-design:delete-outlined"></span>{{ __('Delete') }}</a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        @empty
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center">
                                                                                <div
                                                                                    class="upload-introduction-box-content-img">
                                                                                    <img src="{{ asset('frontend/assets/img/instructor-img/upload-lesson-icon.png') }}"
                                                                                        alt="upload">
                                                                                </div>

                                                                                <div>
                                                                                    <button type="button"
                                                                                        data-name="{{ $lesson->name }}"
                                                                                        data-route="{{ route('organization.lesson.update', [$course->uuid, $lesson->uuid]) }}"
                                                                                        class=" upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 editLesson"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#becomeAnInstructor">
                                                                                        <span class="iconify"
                                                                                            data-icon="clarity:note-edit-line"></span>{{ __('Edit') }}
                                                                                    </button>
                                                                                    <button
                                                                                        class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 deleteItem"
                                                                                        data-formid="delete_row_form_{{ $lesson->uuid }}">
                                                                                        <span class="iconify"
                                                                                            data-icon="ant-design:delete-outlined"></span>{{ __('Delete') }}
                                                                                    </button>
                                                                                    <form
                                                                                        action="{{ route('organization.lesson.delete', [$lesson->uuid]) }}"
                                                                                        method="post"
                                                                                        id="delete_row_form_{{ $lesson->uuid }}">
                                                                                        {{ method_field('DELETE') }}
                                                                                        <input type="hidden"
                                                                                            name="_token"
                                                                                            value="{{ csrf_token() }}">
                                                                                        @if ($course_version_id)
                                                                                            <input type="hidden"
                                                                                                name="course_version_id"
                                                                                                value="{{ $course_version_id }}">
                                                                                        @endif
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        @endforelse

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                    @if (session('edited_lessons') || isset($edited_lessons))
                                                        @if (count($edited_lessons))
                                                            <div class="upload-course-item-block-title mb-3">
                                                                <p class="color-para">{{ _('New Section list of') }}
                                                                    <span
                                                                        class="color-heading">“{{ $course->title }}”</span>
                                                                </p>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    <div class="accordion mb-30" id="video-upload-done-phase">
                                                        @if (session('edited_lessons') || isset($edited_lessons))
                                                            @php
                                                                $edited_lessons = session('edited_lessons') ?? $edited_lessons;
                                                            @endphp
                                                            @foreach ($edited_lessons as $lesson)
                                                                @php
                                                                    $key = $lesson->id;
                                                                @endphp
                                                                <div
                                                                    class="accordion-item video-upload-final-item mb-2">
                                                                    <div class="accordion-header mb-0"
                                                                        id="headingOne">
                                                                        <button
                                                                            class="accordion-button upload-introduction-title-box d-flex align-items-center justify-content-between collapsed"
                                                                            type="button" data-bs-toggle="collapse"
                                                                            data-bs-target="#collapse{{ $key }}"
                                                                            aria-expanded="false"
                                                                            aria-controls="collapse{{ $key }}">
                                                                            <span
                                                                                class="font-16 ps-4">{{ $lesson->name }}</span>
                                                                            <span
                                                                                class="d-flex upload-course-video-6-duration-count">
                                                                                <span
                                                                                    class="upload-course-duration-text font-14 color-para font-medium"><span
                                                                                        class="iconify"
                                                                                        data-icon="octicon:device-desktop-24"></span>{{ __('Video') }}
                                                                                    <span
                                                                                        class="color-heading">({{ $lesson->lectures->count() }})</span></span>
                                                                                <span
                                                                                    class="upload-course-duration-text font-14 color-para font-medium"><span
                                                                                        class="iconify"
                                                                                        data-icon="ant-design:clock-circle-outlined"></span>{{ __('Duration') }}
                                                                                    <span
                                                                                        class="color-heading">({{ @$lesson->lectures->count() > 0 ? lessonVideoDuration($course->id, $lesson->id) : '0 min' }})</span></span>
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                    <div id="collapse{{ $key }}"
                                                                        class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }} "
                                                                        aria-labelledby="heading{{ $key }}"
                                                                        data-bs-parent="#video-upload-done-phase">
                                                                        <div class="accordion-body">
                                                                            @foreach ($lesson->lectures as $lecture)
                                                                                <div
                                                                                    class="main-upload-video-processing-item removable-item">

                                                                                    <div
                                                                                        class="main-upload-video-processing-img-wrap-edit-img">

                                                                                        @if ($lecture->type == 'video')
                                                                                            <a data-normal_video="{{ getVideoFile($lecture->file_path) }}"
                                                                                                title="See video preview"
                                                                                                class="normalVideo edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                                data-bs-toggle="modal"
                                                                                                href="#html5VideoPlayerModal">
                                                                                                <div
                                                                                                    class="d-flex flex-grow-1">
                                                                                                    <div><img
                                                                                                            src="{{ asset('frontend/assets/img/courses-img/play.svg') }}"
                                                                                                            alt="play">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="font-medium font-16 lecture-edit-title">
                                                                                                        {{ $lecture->title }}
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div
                                                                                                    class="upload-course-video-6-text flex-shrink-0">
                                                                                                    <span
                                                                                                        class="see-preview-video font-medium font-16">{{ __('Preview Video') }}</span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @elseif($lecture->type == 'youtube')
                                                                                            <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3 venobox"
                                                                                                data-autoplay="true"
                                                                                                data-maxwidth="800px"
                                                                                                data-vbtype="video"
                                                                                                data-href="https://www.youtube.com/embed/{{ $lecture->url_path }}">
                                                                                                <div
                                                                                                    class="d-flex flex-grow-1">
                                                                                                    <div><img
                                                                                                            src="{{ asset('frontend/assets/img/courses-img/play.svg') }}"
                                                                                                            alt="play">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="font-medium font-16 lecture-edit-title">
                                                                                                        {{ $lecture->title }}
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div
                                                                                                    class="upload-course-video-6-text flex-shrink-0">
                                                                                                    <span
                                                                                                        class="see-preview-video font-medium font-16">{{ __('Preview Video') }}</span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @elseif($lecture->type == 'vimeo')
                                                                                            <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3 venobox"
                                                                                                data-autoplay="true"
                                                                                                data-maxwidth="800px"
                                                                                                data-vbtype="video"
                                                                                                data-href="https://vimeo.com/{{ $lecture->url_path }}">
                                                                                                <div
                                                                                                    class="d-flex flex-grow-1">
                                                                                                    <div><img
                                                                                                            src="{{ asset('frontend/assets/img/courses-img/play.svg') }}"
                                                                                                            alt="play">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="font-medium font-16 lecture-edit-title">
                                                                                                        {{ $lecture->title }}
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div
                                                                                                    class="upload-course-video-6-text flex-shrink-0">
                                                                                                    <span
                                                                                                        class="see-preview-video font-medium font-16">{{ __('Preview Video') }}</span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @elseif($lecture->type == 'text')
                                                                                            <a type="button"
                                                                                                data-lecture_text="{{ $lecture->text }}"
                                                                                                class="lectureText edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                                data-bs-toggle="modal"
                                                                                                href="#textUploadModal">
                                                                                                <div
                                                                                                    class="d-flex flex-grow-1">
                                                                                                    <div><img
                                                                                                            src="{{ asset('frontend/assets/img/courses-img/text.svg') }}"
                                                                                                            alt="text">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="font-medium font-16 lecture-edit-title">
                                                                                                        {{ $lecture->title }}
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div
                                                                                                    class="upload-course-video-6-text flex-shrink-0">
                                                                                                    <span
                                                                                                        class="see-preview-video font-medium font-16">{{ __('Preview') }}</span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @elseif($lecture->type == 'image')
                                                                                            <a data-lecture_image="{{ getImageFile($lecture->image) }}"
                                                                                                class="lectureImage edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                                data-bs-toggle="modal"
                                                                                                href="#imageUploadModal">
                                                                                                <div
                                                                                                    class="d-flex flex-grow-1">
                                                                                                    <div><img
                                                                                                            src="{{ asset('frontend/assets/img/courses-img/preview-image.svg') }}"
                                                                                                            alt="image">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="font-medium font-16 lecture-edit-title">
                                                                                                        {{ $lecture->title }}
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div
                                                                                                    class="upload-course-video-6-text flex-shrink-0">
                                                                                                    <span
                                                                                                        class="see-preview-video font-medium font-16">{{ __('Preview Image') }}
                                                                                                    </span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @elseif($lecture->type == 'pdf')
                                                                                            <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                                data-bs-toggle="modal"
                                                                                                href="#pdfModal">
                                                                                                <div
                                                                                                    class="d-flex flex-grow-1">
                                                                                                    <div><img
                                                                                                            src="{{ asset('frontend/assets/img/courses-img/file-pdf.svg') }}"
                                                                                                            alt="PDF">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="font-medium font-16 lecture-edit-title">
                                                                                                        {{ $lecture->title }}
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div
                                                                                                    class="upload-course-video-6-text flex-shrink-0">
                                                                                                    <span
                                                                                                        class="see-preview-video font-medium font-16">{{ __('Preview PDF') }}</span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @elseif($lecture->type == 'slide_document')
                                                                                            <a data-lecture_slide="{{ $lecture->slide_document }}"
                                                                                                class="lectureSlide edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                                data-bs-toggle="modal"
                                                                                                href="#slideModal">
                                                                                                <div
                                                                                                    class="d-flex flex-grow-1">
                                                                                                    <div><img
                                                                                                            src="{{ asset('frontend/assets/img/courses-img/slide-preview.svg') }}"
                                                                                                            alt="Slide Doc">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="font-medium font-16 lecture-edit-title">
                                                                                                        {{ $lecture->title }}
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div
                                                                                                    class="upload-course-video-6-text flex-shrink-0">
                                                                                                    <span
                                                                                                        class="see-preview-video font-medium font-16">{{ __('Preview Slide') }}</span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @elseif($lecture->type == 'audio')
                                                                                            <a data-lecture_audio="{{ getVideoFile($lecture->audio) }}"
                                                                                                class=" lectureAudio edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                                data-bs-toggle="modal"
                                                                                                href="#audioPlayerModal">
                                                                                                <div
                                                                                                    class="d-flex flex-grow-1">
                                                                                                    <div>
                                                                                                        <img src="{{ asset('frontend/assets/img/courses-img/preview-audio-o.svg') }}"
                                                                                                            alt="play">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="font-medium font-16 lecture-edit-title">
                                                                                                        {{ $lecture->title }}
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div
                                                                                                    class="upload-course-video-6-text flex-shrink-0">
                                                                                                    <span
                                                                                                        class="see-preview-video font-medium font-16">{{ __('Preview') }}</span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @endif


                                                                                    </div>

                                                                                    <div class="d-flex">
                                                                                        <div class="flex-shrink-0">
                                                                                            <div
                                                                                                class="video-upload-done-phase-action-btns font-14 color-heading text-center font-medium">
                                                                                                <a href="{{ route('organization.edit.lecture', [$course->uuid, $lesson->uuid, $lecture->uuid, 'course_version_id' => $course_version_id]) }}"
                                                                                                    type="button"
                                                                                                    class="upload-course-video-edit-btn upload-course-video-main-edit-btn font-14 color-para font-medium bg-transparent border-0 mx-2"><span
                                                                                                        class="iconify"
                                                                                                        data-icon="clarity:note-edit-line"></span>{{ __('Edit') }}</a>
                                                                                                <a href="javascript:void(0);"
                                                                                                    data-url="{{ route('organization.delete.lecture', [$course->uuid, $lecture->uuid, 'course_version_id' => $course_version_id ?? null]) }}"
                                                                                                    class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 mx-2 delete"><span
                                                                                                        class="iconify"
                                                                                                        data-icon="ant-design:delete-outlined"></span>{{ __('Delete') }}</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                            @endforeach
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center">
                                                                                <div
                                                                                    class="upload-introduction-box-content-img">
                                                                                    <img src="{{ asset('frontend/assets/img/instructor-img/upload-lesson-icon.png') }}"
                                                                                        alt="upload">
                                                                                </div>

                                                                                <div>
                                                                                    <button type="button"
                                                                                        data-name="{{ $lesson->name }}"
                                                                                        data-route="{{ route('organization.lesson.update', [$course->uuid, $lesson->uuid]) }}"
                                                                                        class=" upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 editLesson"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#becomeAnInstructor">
                                                                                        <span class="iconify"
                                                                                            data-icon="clarity:note-edit-line"></span>{{ __('Edit') }}
                                                                                    </button>
                                                                                    <button
                                                                                        class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 deleteItem"
                                                                                        data-formid="delete_row_form_{{ $lesson->uuid }}">
                                                                                        <span class="iconify"
                                                                                            data-icon="ant-design:delete-outlined"></span>{{ __('Delete') }}
                                                                                    </button>
                                                                                    <form
                                                                                        action="{{ route('organization.lesson.delete', [$lesson->uuid]) }}"
                                                                                        method="post"
                                                                                        id="delete_row_form_{{ $lesson->uuid }}">
                                                                                        {{ method_field('DELETE') }}
                                                                                        <input type="hidden"
                                                                                            name="_token"
                                                                                            value="{{ csrf_token() }}">
                                                                                        @if ($course_version_id)
                                                                                            <input type="hidden"
                                                                                                name="course_version_id"
                                                                                                value="{{ $course_version_id }}">
                                                                                        @endif
                                                                                    </form>
                                                                                </div>
                                                                            </div>


                                                                            <div class="mt-3 lecture-edit-upload-btn">
                                                                                <a href="{{ route('organization.upload.lecture', [$course->uuid, $lesson->uuid, 'course_version_id' => $course_version_id]) }}"
                                                                                    class="common-upload-video-btn color-heading font-13 font-medium ms-0 mt-4">
                                                                                    <span class="iconify"
                                                                                        data-icon="feather:upload-cloud"></span>{{ __('Upload lesson') }}</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>




                                                </div>
                                            @else
                                                @foreach ($lessons as $lesson)
                                                    <div class="upload-video-introduction-box-wrap">

                                                        <!-- upload-video-introduction-box -->
                                                        <div class="upload-video-introduction-box">

                                                            <div
                                                                class="upload-introduction-title-box d-flex align-items-center justify-content-between">
                                                                <h6 class="font-16">{{ $lesson->name }}</h6>
                                                                <div
                                                                    class="d-flex upload-course-video-6-duration-count">
                                                                    <div
                                                                        class="upload-course-duration-text font-14 color-para font-medium">
                                                                        <span class="iconify"
                                                                            data-icon="octicon:device-desktop-24"></span>Video<span
                                                                            class="color-heading">(0)</span>
                                                                    </div>
                                                                    <div
                                                                        class="upload-course-duration-text font-14 color-para font-medium">
                                                                        <span class="iconify"
                                                                            data-icon="ant-design:clock-circle-outlined"></span>Duration<span
                                                                            class="color-heading">(0)</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="upload-introduction-box-content d-flex align-items-center justify-content-between">
                                                                <div
                                                                    class="upload-introduction-box-content-left d-flex align-items-center">
                                                                    <div class="upload-introduction-box-content-img">
                                                                        <img src="{{ asset('frontend/assets/img/instructor-img/upload-lesson-icon.png') }}"
                                                                            alt="upload">
                                                                    </div>
                                                                    <a href="{{ route('organization.upload.lecture', [$course->uuid, $lesson->uuid]) }}"
                                                                        class="common-upload-lesson-btn font-13 font-medium">
                                                                        <span class="iconify"
                                                                            data-icon="feather:upload-cloud"></span>{{ __('Upload lesson') }}</a>
                                                                </div>
                                                                <div class="d-flex">
                                                                    <button type="button"
                                                                        data-name="{{ $lesson->name }}"
                                                                        data-route="{{ route('organization.lesson.update', [$course->uuid, $lesson->uuid]) }}"
                                                                        class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 editLesson"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#becomeAnInstructor">
                                                                        <span class="iconify"
                                                                            data-icon="clarity:note-edit-line"></span>{{ __('Edit') }}</button>
                                                                    <button
                                                                        class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 deleteItem"
                                                                        data-formid="delete_row_form_{{ $lesson->uuid }}">
                                                                        <span class="iconify"
                                                                            data-icon="ant-design:delete-outlined"></span>{{ __('Delete') }}</button>

                                                                    <form
                                                                        action="{{ route('organization.lesson.delete', [$lesson->uuid]) }}"
                                                                        method="post"
                                                                        id="delete_row_form_{{ $lesson->uuid }}">
                                                                        {{ method_field('DELETE') }}
                                                                        <input type="hidden" name="_token"
                                                                            value="{{ csrf_token() }}">
                                                                        @if ($course_version_id)
                                                                            <input type="hidden"
                                                                                name="course_version_id"
                                                                                value="{{ $course_version_id }}">
                                                                        @endif
                                                                    </form>
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>
                                                @endforeach
                                                @if (session('edited_lessons'))
                                                    @php
                                                        $edited_lessons = session('edited_lessons');
                                                    @endphp
                                                    @foreach ($edited_lessons as $lesson)
                                                        <div class="upload-video-introduction-box-wrap">

                                                            <!-- upload-video-introduction-box -->
                                                            <div class="upload-video-introduction-box">

                                                                <div
                                                                    class="upload-introduction-title-box d-flex align-items-center justify-content-between">
                                                                    <h6 class="font-16">{{ $lesson->name }}</h6>
                                                                    <div
                                                                        class="d-flex upload-course-video-6-duration-count">
                                                                        <div
                                                                            class="upload-course-duration-text font-14 color-para font-medium">
                                                                            <span class="iconify"
                                                                                data-icon="octicon:device-desktop-24"></span>Video<span
                                                                                class="color-heading">(0)</span>
                                                                        </div>
                                                                        <div
                                                                            class="upload-course-duration-text font-14 color-para font-medium">
                                                                            <span class="iconify"
                                                                                data-icon="ant-design:clock-circle-outlined"></span>Duration<span
                                                                                class="color-heading">(0)</span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div
                                                                    class="upload-introduction-box-content d-flex align-items-center justify-content-between">
                                                                    <div
                                                                        class="upload-introduction-box-content-left d-flex align-items-center">
                                                                        <div
                                                                            class="upload-introduction-box-content-img">
                                                                            <img src="{{ asset('frontend/assets/img/instructor-img/upload-lesson-icon.png') }}"
                                                                                alt="upload">
                                                                        </div>
                                                                        <a href="{{ route('organization.upload.lecture', [$course->uuid, $lesson->uuid]) }}"
                                                                            class="common-upload-lesson-btn font-13 font-medium">
                                                                            <span class="iconify"
                                                                                data-icon="feather:upload-cloud"></span>{{ __('Upload lesson') }}</a>
                                                                    </div>
                                                                    <div class="d-flex">
                                                                        <button type="button"
                                                                            data-name="{{ $lesson->name }}"
                                                                            data-route="{{ route('organization.lesson.update', [$course->uuid, $lesson->uuid]) }}"
                                                                            class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 editLesson"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#becomeAnInstructor">
                                                                            <span class="iconify"
                                                                                data-icon="clarity:note-edit-line"></span>{{ __('Edit') }}</button>
                                                                        <button
                                                                            class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 deleteItem"
                                                                            data-formid="delete_row_form_{{ $lesson->uuid }}">
                                                                            <span class="iconify"
                                                                                data-icon="ant-design:delete-outlined"></span>{{ __('Delete') }}</button>

                                                                        <form
                                                                            action="{{ route('organization.lesson.delete', [$lesson->uuid]) }}"
                                                                            method="post"
                                                                            id="delete_row_form_{{ $lesson->uuid }}">
                                                                            {{ method_field('DELETE') }}
                                                                            <input type="hidden" name="_token"
                                                                                value="{{ csrf_token() }}">
                                                                            @if ($course_version_id)
                                                                                <input type="hidden"
                                                                                    name="course_version_id"
                                                                                    value="{{ $course_version_id }}">
                                                                            @endif
                                                                        </form>
                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>
                                                    @endforeach
                                                @endif
                                            @endif

                                        </div>

                                        <div class="add-more-section-wrap d-none">
                                            {{-- form to add new sections --}}
                                            <form method="POST"
                                                action="{{ route('organization.lesson.store', [$course->uuid]) }}"
                                                class="row g-3 needs-validation" novalidate>
                                                @csrf
                                                @if ($course_version_id)
                                                    <input type="hidden" name="course_version_id"
                                                        value="{{ $course_version_id }}">
                                                @endif
                                                <div class="row mb-30">
                                                    <div class="col-md-12">
                                                        <label
                                                            class="label-text-title color-heading font-medium font-16 mb-3">Section
                                                            title of the course “ {{ $course->title }}
                                                            ”</label>
                                                        <input type="text" name="name"
                                                            value="{{ old('name') }}" required
                                                            class="form-control" placeholder="Section title">
                                                        @if ($errors->has('name'))
                                                            <span class="text-danger"><i
                                                                    class="fas fa-exclamation-triangle"></i>
                                                                {{ $errors->first('name') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div
                                                    class="stepper-action-btns d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="theme-btn theme-button3 cancel-add-more-section">{{ __('Cancel') }}</a>
                                                        <button type="submit"
                                                            class="theme-btn default-hover-btn theme-button1">{{ __('Save') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div
                                            class="stepper-action-btns d-flex justify-content-between align-items-center add-more-lesson-box">
                                            <div>
                                                <a href="{{ route('organization.course.edit', [$course->uuid, 'step=category', 'course_version_id' => $course_version_id]) }}"
                                                    class="theme-btn theme-button3">{{ __('Back') }}</a>
                                                @if ($lectures->count() > 0)
                                                    <a href="{{ route('organization.course.edit', [$course->uuid, 'step=instructors', 'course_version_id' => $course_version_id]) }}"
                                                        type="button"
                                                        class="theme-btn theme-button1">{{ __('Save and continue') }}</a>
                                                @endif
                                            </div>

                                            <!-- When click this button show: "add-more-section-wrap" -->
                                            <a href="javascript:void (0);" type="button"
                                                class="add-more-section-btn font-14 color-heading font-medium"><span
                                                    class="iconify"
                                                    data-icon="akar-icons:circle-plus"></span>{{ __('Add More Section') }}
                                            </a>
                                        </div>

                                    </div>

                                    <!-- Upload Course Video-2 end -->

                                    {{-- this takes you to thenext page . only apears when  sections are created  --}}
                                    <input type="button" name="previous"
                                        class="previous action-button-previous action-button theme-btn theme-button3"
                                        value="Back" /> <input type="button" name="next"
                                        class="next action-button default-hover-btn theme-btn theme-button1"
                                        value="Save & continue" />
                                @else
                                    <!-- Upload Course Video-1 start -->
                                    <form method="POST"
                                        action="{{ route('organization.lesson.store', [$course->uuid]) }}"
                                        class="row g-3 needs-validation" novalidate>
                                        @csrf
                                        <div id="upload-course-video-1">
                                            @if ($course_version_id)
                                                <input type="hidden" name="course_version_id"
                                                    value="{{ $course_version_id }}">
                                            @endif
                                            <div class="upload-course-item-block course-overview-step1 radius-8">
                                                <div class="upload-course-item-block-title mb-3">
                                                    <p class="color-para">
                                                        {{ __('To Upload your course videos please create your section and lesson details first!') }}
                                                    </p>
                                                </div>

                                                <div class="row mb-30">
                                                    <div class="col-md-12">
                                                        <label
                                                            class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Section title of the coures') }}
                                                            “ {{ $course->title }}
                                                            ”</label>
                                                        <input type="text" name="name"
                                                            value="{{ old('name') }}" class="form-control"
                                                            placeholder="Introduction" required>
                                                        @if ($errors->has('name'))
                                                            <span class="text-danger"><i
                                                                    class="fas fa-exclamation-triangle"></i>
                                                                {{ $errors->first('name') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="stepper-action-btns">
                                                <a href="{{ route('organization.course.edit', [$course->uuid, 'step=category', 'course_version_id' => $course_version_id]) }}"
                                                    class="theme-btn theme-button3">{{ __('Back') }}</a>
                                                <button type="submit"
                                                    class="theme-btn default-hover-btn theme-button1">{{ __('Save and continue') }}</button>
                                            </div>

                                        </div>
                                    </form>

                                    <!-- Upload Course Video-1 end -->
                                @endif
                            </div>
                            <!-- Upload Course Step-6 Item End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>