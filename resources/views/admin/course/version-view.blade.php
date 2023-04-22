@extends('layouts.admin')

@section('content')
    <!-- Page content area start -->
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb__content">
                        <div class="breadcrumb__content__left">
                            <div class="breadcrumb__title">
                                <h2>{{ __('Courses Edit Details') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Courses Edits') }}</li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Details') }}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            {{-- @dd($course_version->details) --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{ __('Edit Pending') }}</h2>
                        </div>
                        <div class="">
                            <table id="-table" class="row-border data-table-filter table-style">
                                <thead>
                                    <tr>
                                        <th>{{ __('Element') }}</th>
                                        <th>{{ __('Current Version') }}</th>
                                        <th>{{ __('New Version') }}</th>
                                        {{-- <th>{{__('Category')}}</th>
                                    <th>{{__('Subcategory')}}</th>
                                    <th>{{__('Price')}}</th>
                                    <th>{{__('Action')}}</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ _('Title') }}</td>
                                        <td>{{ $course->title }}</td>
                                        <td>{{ $course_version->details['title'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ _('Subtitle') }}</td>
                                        <td>{{ $course->subtitle }}</td>
                                        <td>{{ $course_version->details['subtitle'] }}</td>
                                    </tr>

                                    @if(isset($course_version->details['new_learn_key_points']) && count($course_version->details['new_learn_key_points']))
                                    <tr>
                                        <td>{{ _('Key Points') }}</td>
                                        <td>{{ implode(', ', $course->keyPoints->pluck('name')->toArray()) }}</td>
                                        <td>
                                            New: {{ get_names('key_point', $course_version->details['new_learn_key_points']) }}

                                            @if(isset($course_version->details['updated_learn_key_points']) && count($course_version->details['updated_learn_key_points']))
                                            Updated : {{ get_names('key_point', $course_version->details['updated_learn_key_points']) }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td>{{ _('Description') }}</td>
                                        <td>{{ $course->description }}</td>
                                        <td>{{ $course_version->details['description'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ _('Category') }}</td>
                                        <td>{{ __($course->category->name) }}</td>
                                        <td>{{ __(get_names('category', [$course_version->details['category_id']])) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ _('Subategory') }}</td>
                                        <td>{{ __($course->subcategory->name) }}</td>
                                        <td>{{ __(get_names('subcategory', [$course_version->details['subcategory_id']])) }}
                                        </td>
                                    </tr>
                                    @if (isset($course_version->details['tags']) && $course_version->details['tags'])
                                        <tr>
                                            <td>{{ _('Tags') }}</td>
                                            <td>{{ implode(', ', $course->tags->pluck('name')->toArray()) }}</td>
                                            <td>{{ get_names('tag', $course_version->details['tags']) }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td>{{ _('Drip Content') }}</td>
                                        <td>{{ dripType($course->drip_content) }}</td>
                                        <td>{{ dripType($course_version->details['drip_content']) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ _('Therapy Tutorial Access Period') }}</td>
                                        <td>{{ $course->access_period }}</td>
                                        <td>{{ $course_version->details['access_period'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ _('Learners Accessibility') }}</td>
                                        <td>{{ $course->learner_accessibility }}</td>
                                        <td>{{ $course_version->details['learner_accessibility'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ _('Price') }}</td>
                                        <td>{{ $course->price }}</td>
                                        <td>{{ $course_version->details['price'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ _('Language') }}</td>
                                        <td>{{ get_names('course_language', [$course->course_language_id]) }}</td>
                                        <td>{{ get_names('course_language', [$course_version->details['course_language_id']]) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ _('Difficulty Level') }}</td>
                                        <td>{{ get_names('difficulty_level', [$course->difficulty_level_id]) }}</td>
                                        <td>{{ get_names('difficulty_level', [$course_version->details['difficulty_level_id']]) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ _('Therapy Tutorial Thumbnail') }}</td>
                                        <td><img src="{{ getImageFile($course->image) }}" width="200"></td>
                                        <td><img src="{{ getImageFile($course_version->details['image']) }}"
                                                width="200"></td>
                                    </tr>
                                    <tr>
                                        <td>{{ _('Course Introduction Video') }}</td>
                                        <td>
                                            @if ($course->video)
                                                <div class="col-md-12 mb-30  videoSource">
                                                    <div class="video-player-area ">
                                                        <video id="player" playsinline controls
                                                            data-poster="{{ getImageFile(@$course->image) }}"
                                                            controlsList="nodownload">
                                                            <source src="{{ getVideoFile(@$course->video) }}"
                                                                type="video/mp4">
                                                        </video>
                                                    </div>
                                                </div>
                                            @elseif($course->youtube_video_id)
                                                <div class="col-md-12 mb-30  videoSourceYoutube">
                                                    <div class="video-player-area ">
                                                        <div class="plyr__video-embed" id="playerVideoYoutube">
                                                            <iframe
                                                                src="https://www.youtube.com/embed/{{ @$course->youtube_video_id }}"
                                                                allowfullscreen allowtransparency allow="autoplay">
                                                            </iframe>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($course_version->details['video']))
                                                <div class="col-md-12 mb-30  videoSource">
                                                    <div class="video-player-area ">
                                                        <video id="player" playsinline controls
                                                            @isset($course_version->details['image'])
                                                        data-poster="{{ getImageFile(@$course_version->details['image']) }}"
                                                        @endisset
                                                            controlsList="nodownload">
                                                            <source
                                                                src="{{ getVideoFile(@$course_version->details['video']) }}"
                                                                type="video/mp4">
                                                        </video>
                                                    </div>
                                                </div>
                                            @elseif(isset($course_version->details['youtube_video_id']))
                                                <div class="col-md-12 mb-30  videoSourceYoutube">
                                                    <div class="video-player-area ">
                                                        <div class="plyr__video-embed" id="playerVideoYoutube">
                                                            <iframe
                                                                src="https://www.youtube.com/embed/{{ @$course_version->details['youtube_video_id'] }}"
                                                                allowfullscreen allowtransparency allow="autoplay">
                                                            </iframe>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @isset($course_version->details['lessons'])
                                        <tr>
                                            <td>{{ _('Lessons') }}</td>
                                            <td style="max-width: 200px">
                                                {{ implode(', ', $course->lessons->pluck('name')->toArray()) }}</td>
                                            <td style="max-width: 200px">
                                                {{ get_names('lesson', $course_version->details['lessons']) }}</td>
                                        </tr>
                                    @endisset
                                    @if (isset($course_version->details['course_instructors']))
                                        <tr>
                                            <td>{{ _('Course Instructors') }}</td>
                                            <td>
                                                @foreach ($course->course_instructors as $instructor)
                                                    {{ $instructor->user->name }} | {{ _('Share') }} :
                                                    {{ $instructor->share }} ,
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($course_version->details['course_instructors'] as $instructor)
                                                    {{ get_names('user', [$instructor['instructor_id']]) }} |
                                                    {{ _('Share') }} : {{ $instructor['share'] }} ,
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{-- {{$course_version->links()}} --}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{ __('Course Content') }}</h2>
                        </div>
                        <div class="upload-course-step-item upload-course-overview-step-item">
                            <!-- Upload Course Step-2 Item Start -->
                            <div class="upload-course-step-item upload-course-video-step-item">

                                @if ($course->lessons->count() > 0)
                                    <!-- Upload Course Video-2 start -->

                                    <div id="upload-course-video-2">
                                        <div class="upload-course-item-block course-overview-step1 radius-8">
                                            <div class="upload-course-item-block-title mb-3">
                                                <p class="color-para">{{ _('Current Content of the course') }} <span
                                                        class="color-heading">“{{ $course->title }}”</span></p>
                                            </div>
                                            @if ($course->lectures->count() > 0)
                                                <div id="upload-course-video-6" class="upload-course-video-6">
                                                    <div class="accordion mb-30" id="video-upload-done-phase">
                                                        @foreach ($course->lessons as $lesson)
                                                            {{-- <p> deleted-- {{$lesson->uuid}}</p> --}}
                                                            @php
                                                                $key = $lesson->id;
                                                            @endphp
                                                            <div
                                                                class="accordion-item video-upload-final-item mb-2
                                                            @if (in_array($lesson->id, $deleted_lessons)) border border-danger
                                                            @elseif(in_array($lesson->id, $updated_course_lessons))
                                                            border border-warning @endif

                                                            p-4">
                                                                <div class="accordion-header mb-0" id="headingOne">
                                                                    <button
                                                                        class="accordion-button upload-introduction-title-box d-flex align-items-center justify-content-between"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#collapse{{ $key }}"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapse{{ $key }}">
                                                                        <span class="font-16 ps-4">
                                                                            @if (in_array($lesson->id, $updated_course_lessons))
                                                                                {{ $details['updated_course_lessons'][$lesson->id]['name'] }}
                                                                            @else
                                                                                {{ $lesson->name }}
                                                                            @endif
                                                                        </span>
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
                                                                                class="main-upload-video-processing-item removable-item
                                                                                @if (in_array($lecture->id, $deleted_lectures)) border border-danger
                                                                                @elseif (in_array($lecture->id, $updated_lessons))
                                                                                border border-warning @endif
                                                                                ">

                                                                                <div
                                                                                    class="main-upload-video-processing-img-wrap-edit-img">

                                                                                    @if ($lecture->type == 'video')
                                                                                        <a data-normal_video="
                                                                                        @if (in_array($lecture->id, $updated_lessons)) {{ getVideoFile($details['updated_lessons'][$lecture->id]['model']['file_path']) }}
                                                                                        @else
                                                                                        {{ getVideoFile($lecture->file_path) }} @endif

                                                                                        "
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
                                                                                                    @if (in_array($lecture->id, $updated_lessons))
                                                                                                        {{ $details['updated_lessons'][$lecture->id]['model']['title'] }}
                                                                                                    @else
                                                                                                        {{ $lecture->title }}
                                                                                                    @endif
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
                                                                                            data-href="https://www.youtube.com/embed/{{ in_array($lecture->id, $updated_lessons) ? $details['updated_lessons'][$lecture->id]['model']['url_path'] : $lecture->url_path }}">
                                                                                            <div
                                                                                                class="d-flex flex-grow-1">
                                                                                                <div><img
                                                                                                        src="{{ asset('frontend/assets/img/courses-img/play.svg') }}"
                                                                                                        alt="play">
                                                                                                </div>
                                                                                                <div
                                                                                                    class="font-medium font-16 lecture-edit-title">
                                                                                                    @if (in_array($lecture->id, $updated_lessons))
                                                                                                        {{ $details['updated_lessons'][$lecture->id]['model']['title'] }}
                                                                                                    @else
                                                                                                        {{ $lecture->title }}
                                                                                                    @endif
                                                                                                    {{-- {{ $lecture->title }} --}}
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
                                                                                            data-href="https://vimeo.com/{{ in_array($lecture->id, $updated_lessons) ? $details['updated_lessons'][$lecture->id]['model']['url_path'] : $lecture->url_path }}">
                                                                                            <div
                                                                                                class="d-flex flex-grow-1">
                                                                                                <div><img
                                                                                                        src="{{ asset('frontend/assets/img/courses-img/play.svg') }}"
                                                                                                        alt="play">
                                                                                                </div>
                                                                                                <div
                                                                                                    class="font-medium font-16 lecture-edit-title">
                                                                                                    @if (in_array($lecture->id, $updated_lessons))
                                                                                                        {{ $details['updated_lessons'][$lecture->id]['model']['title'] }}
                                                                                                    @else
                                                                                                        {{ $lecture->title }}
                                                                                                    @endif
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
                                                                                            data-lecture_text="
                                                                                            @if (in_array($lecture->id, $updated_lessons)) {{ $details['updated_lessons'][$lecture->id]['model']['text'] }}
                                                                                            @else
                                                                                            {{ $lecture->text }} @endif
                                                                                            "
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
                                                                                                    @if (in_array($lecture->id, $updated_lessons))
                                                                                                        {{ $details['updated_lessons'][$lecture->id]['model']['title'] }}
                                                                                                    @else
                                                                                                        {{ $lecture->title }}
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>

                                                                                            <div
                                                                                                class="upload-course-video-6-text flex-shrink-0">
                                                                                                <span
                                                                                                    class="see-preview-video font-medium font-16">{{ __('Preview') }}</span>
                                                                                            </div>
                                                                                        </a>
                                                                                    @elseif($lecture->type == 'image')
                                                                                        <a data-lecture_image="
                                                                                        @if (in_array($lecture->id, $updated_lessons)) {{ getImageFile($details['updated_lessons'][$lecture->id]['model']['image']) }}
                                                                                        @else
                                                                                        {{ getImageFile($lecture->image) }} @endif
                                                                                        "
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
                                                                                                    @if (in_array($lecture->id, $updated_lessons))
                                                                                                        {{ $details['updated_lessons'][$lecture->id]['model']['title'] }}
                                                                                                    @else
                                                                                                        {{ $lecture->title }}
                                                                                                    @endif
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
                                                                                            data-maxwidth="800px"
                                                                                            target="_blank"
                                                                                            href="{{ in_array($lecture->id, $updated_lessons) ? getImageFile($details['updated_lessons'][$lecture->id]['model']['pdf']) : getImageFile($lecture->pdf) }}">
                                                                                            {{-- <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                                data-bs-toggle="modal"
                                                                                                href="#pdfModal"> --}}
                                                                                                <div
                                                                                                    class="d-flex flex-grow-1">
                                                                                                    <div><img
                                                                                                            src="{{ asset('frontend/assets/img/courses-img/file-pdf.svg') }}"
                                                                                                            alt="PDF">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="font-medium font-16 lecture-edit-title">
                                                                                                        @if (in_array($lecture->id, $updated_lessons))
                                                                                                            {{ $details['updated_lessons'][$lecture->id]['model']['title'] }}
                                                                                                        @else
                                                                                                            {{ $lecture->title }}
                                                                                                        @endif
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div
                                                                                                    class="upload-course-video-6-text flex-shrink-0">
                                                                                                    <span
                                                                                                        class="see-preview-video font-medium font-16">{{ __('Preview PDF') }}</span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @elseif($lecture->type == 'slide_document')
                                                                                            <a data-lecture_slide="
                                                                                        @if (in_array($lecture->id, $updated_lessons)) {{ $details['updated_lessons'][$lecture->id]['model']['slide_document'] }}
                                                                                        @else
                                                                                        {{ $lecture->slide_document }} @endif
                                                                                        "
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
                                                                                                        @if (in_array($lecture->id, $updated_lessons))
                                                                                                            {{ $details['updated_lessons'][$lecture->id]['model']['title'] }}
                                                                                                        @else
                                                                                                            {{ $lecture->title }}
                                                                                                        @endif
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div
                                                                                                    class="upload-course-video-6-text flex-shrink-0">
                                                                                                    <span
                                                                                                        class="see-preview-video font-medium font-16">{{ __('Preview Slide') }}</span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @elseif($lecture->type == 'audio')
                                                                                            <a data-lecture_audio="
                                                                                                    @if (in_array($lecture->id, $updated_lessons)) {{ getVideoFile($details['updated_lessons'][$lecture->id]['model']['audio']) }}
                                                                                                    @else
                                                                                        {{ getVideoFile($lecture->audio) }} @endif

                                                                                        "
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
                                                                                                        @if (in_array($lecture->id, $updated_lessons))
                                                                                                            {{ $details['updated_lessons'][$lecture->id]['model']['title'] }}
                                                                                                        @else
                                                                                                            {{ $lecture->title }}
                                                                                                        @endif
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
                                                                                        {{-- <div
                                                                                                class="video-upload-done-phase-action-btns font-14 color-heading text-center font-medium">
                                                                                                <a href="{{ route('edit.lecture', [$course->uuid, $lesson->uuid, $lecture->uuid, 'course_version_id' => ($course_version_id ?? null)]) }}"
                                                                                                    type="button"
                                                                                                    class="upload-course-video-edit-btn upload-course-video-main-edit-btn font-14 color-para font-medium bg-transparent border-0 mx-2"><span
                                                                                                        class="iconify"
                                                                                                        data-icon="clarity:note-edit-line"></span>{{ __('Edit') }}</a>
                                                                                                <a href="javascript:void(0);"
                                                                                                    data-url="{{ route('delete.lecture', [$course->uuid, $lecture->uuid, 'course_version_id' => ($course_version_id ?? null)]) }}"
                                                                                                    class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 mx-2 delete"><span
                                                                                                        class="iconify"
                                                                                                        data-icon="ant-design:delete-outlined"></span>{{ __('Delete') }}</a>
                                                                                            </div> --}}
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        @endforeach
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center">
                                                                            {{-- <div
                                                                                        class="upload-introduction-box-content-img">
                                                                                        <img src="{{ asset('frontend/assets/img/instructor-img/upload-lesson-icon.png') }}"
                                                                                            alt="upload">
                                                                                    </div> --}}

                                                                            <div>
                                                                                {{-- <button type="button"
                                                                                            data-name="{{ $lesson->name }}"
                                                                                            data-route="{{ route('lesson.update', [$course->uuid, $lesson->uuid]) }}"
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
                                                                                        </button> --}}
                                                                                {{-- <form
                                                                                            action="{{ route('lesson.delete', [$lesson->uuid]) }}"
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
                                                                                        </form> --}}
                                                                            </div>
                                                                        </div>



                                                                        {{-- <div class="mt-3 lecture-edit-upload-btn">
                                                                                <a href="{{ route('upload.lecture', [$course->uuid, $lesson->uuid, 'course_version_id' => $course_version_id]) }}"
                                                                                    class="common-upload-video-btn color-heading font-13 font-medium ms-0 mt-4">
                                                                                    <span class="iconify"
                                                                                        data-icon="feather:upload-cloud"></span>{{ __('Upload lesson') }}</a>
                                                                            </div> --}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                    @if (session('edited_lessons') || isset($edited_lessons))
                                                        @if (count($edited_lessons))
                                                            <div class="upload-course-item-block-title mb-3">
                                                                <p class="color-para">
                                                                    {{ _('Newly Added Content for the course :') }}
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
                                                                <div class="accordion-item video-upload-final-item mb-2">
                                                                    <div class="accordion-header mb-0" id="headingOne">
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
                                                                                            data-maxwidth="800px"
                                                                                            target="_blank"
                                                                                            href="{{ in_array($lecture->id, $updated_lessons) ? getImageFile($details['updated_lessons'][$lecture->id]['model']['pdf']) : getImageFile($lecture->pdf) }}">
                                                                                            {{-- <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                                data-bs-toggle="modal"
                                                                                                href="#pdfModal"> --}}
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

                                                                                    {{-- <div class="d-flex">
                                                                                            <div class="flex-shrink-0">
                                                                                                <div
                                                                                                    class="video-upload-done-phase-action-btns font-14 color-heading text-center font-medium">
                                                                                                    <a href="{{ route('edit.lecture', [$course->uuid, $lesson->uuid, $lecture->uuid, 'course_version_id' => $course_version_id]) }}"
                                                                                                        type="button"
                                                                                                        class="upload-course-video-edit-btn upload-course-video-main-edit-btn font-14 color-para font-medium bg-transparent border-0 mx-2"><span
                                                                                                            class="iconify"
                                                                                                            data-icon="clarity:note-edit-line"></span>{{ __('Edit') }}</a>
                                                                                                    <a href="javascript:void(0);"
                                                                                                        data-url="{{ route('delete.lecture', [$course->uuid, $lecture->uuid, 'course_version_id'=>($course_version_id ?? null)]) }}"
                                                                                                        class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 mx-2 delete"><span
                                                                                                            class="iconify"
                                                                                                            data-icon="ant-design:delete-outlined"></span>{{ __('Delete') }}</a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div> --}}

                                                                                </div>
                                                                            @endforeach
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center">
                                                                                {{-- <div
                                                                                            class="upload-introduction-box-content-img">
                                                                                            <img src="{{ asset('frontend/assets/img/instructor-img/upload-lesson-icon.png') }}"
                                                                                                alt="upload">
                                                                                        </div> --}}

                                                                                <div>
                                                                                    {{-- <button type="button"
                                                                                                data-name="{{ $lesson->name }}"
                                                                                                data-route="{{ route('lesson.update', [$course->uuid, $lesson->uuid]) }}"
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
                                                                                            </button> --}}
                                                                                    {{-- <form
                                                                                                action="{{ route('lesson.delete', [$lesson->uuid]) }}"
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
                                                                                            </form> --}}
                                                                                </div>
                                                                            </div>


                                                                            {{-- <div class="mt-3 lecture-edit-upload-btn">
                                                                                    <a href="{{ route('upload.lecture', [$course->uuid, $lesson->uuid, 'course_version_id' => $course_version_id]) }}"
                                                                                        class="common-upload-video-btn color-heading font-13 font-medium ms-0 mt-4">
                                                                                        <span class="iconify"
                                                                                            data-icon="feather:upload-cloud"></span>{{ __('Upload lesson') }}</a>
                                                                                </div> --}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                @foreach ($course->lessons as $lesson)
                                                    <div class="upload-video-introduction-box-wrap">

                                                        <!-- upload-video-introduction-box -->
                                                        <div class="upload-video-introduction-box">

                                                            <div
                                                                class="upload-introduction-title-box d-flex align-items-center justify-content-between">
                                                                <h6 class="font-16">{{ $lesson->name }}</h6>
                                                                <div class="d-flex upload-course-video-6-duration-count">
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
                                                                {{-- <div
                                                                        class="upload-introduction-box-content-left d-flex align-items-center">
                                                                        <div class="upload-introduction-box-content-img">
                                                                            <img src="{{ asset('frontend/assets/img/instructor-img/upload-lesson-icon.png') }}"
                                                                                alt="upload">
                                                                        </div>
                                                                        <a href="{{ route('upload.lecture', [$course->uuid, $lesson->uuid]) }}"
                                                                            class="common-upload-lesson-btn font-13 font-medium">
                                                                            <span class="iconify"
                                                                                data-icon="feather:upload-cloud"></span>{{ __('Upload lesson') }}</a>
                                                                    </div> --}}
                                                                <div class="d-flex">
                                                                    {{-- <button type="button"
                                                                            data-name="{{ $lesson->name }}"
                                                                            data-route="{{ route('lesson.update', [$course->uuid, $lesson->uuid]) }}"
                                                                            class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 editLesson"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#becomeAnInstructor">
                                                                            <span class="iconify"
                                                                                data-icon="clarity:note-edit-line"></span>{{ __('Edit') }}</button>
                                                                        <button
                                                                            class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 deleteItem"
                                                                            data-formid="delete_row_form_{{ $lesson->uuid }}">
                                                                            <span class="iconify"
                                                                                data-icon="ant-design:delete-outlined"></span>{{ __('Delete') }}</button> --}}

                                                                    {{-- <form
                                                                            action="{{ route('lesson.delete', [$lesson->uuid]) }}"
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
                                                                        </form> --}}
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
                                                                    {{-- <div
                                                                            class="upload-introduction-box-content-left d-flex align-items-center">
                                                                            <div
                                                                                class="upload-introduction-box-content-img">
                                                                                <img src="{{ asset('frontend/assets/img/instructor-img/upload-lesson-icon.png') }}"
                                                                                    alt="upload">
                                                                            </div>
                                                                            <a href="{{ route('upload.lecture', [$course->uuid, $lesson->uuid]) }}"
                                                                                class="common-upload-lesson-btn font-13 font-medium">
                                                                                <span class="iconify"
                                                                                    data-icon="feather:upload-cloud"></span>{{ __('Upload lesson') }}</a>
                                                                        </div> --}}
                                                                    <div class="d-flex">
                                                                        {{-- <button type="button"
                                                                                data-name="{{ $lesson->name }}"
                                                                                data-route="{{ route('lesson.update', [$course->uuid, $lesson->uuid]) }}"
                                                                                class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 editLesson"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#becomeAnInstructor">
                                                                                <span class="iconify"
                                                                                    data-icon="clarity:note-edit-line"></span>{{ __('Edit') }}</button>
                                                                            <button
                                                                                class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 deleteItem"
                                                                                data-formid="delete_row_form_{{ $lesson->uuid }}">
                                                                                <span class="iconify"
                                                                                    data-icon="ant-design:delete-outlined"></span>{{ __('Delete') }}</button> --}}

                                                                        {{-- <form
                                                                                action="{{ route('lesson.delete', [$lesson->uuid]) }}"
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
                                                                            </form> --}}
                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </div>

                                        <div class="add-more-section-wrap d-none">
                                            {{-- <form method="POST"
                                                    action="{{ route('lesson.store', [$course->uuid]) }}"
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
                                                </form> --}}
                                        </div>

                                        {{-- <div
                                                class="stepper-action-btns d-flex justify-content-between align-items-center add-more-lesson-box">
                                                <div>
                                                    <a href="{{ route('instructor.course.edit', [$course->uuid, 'step=category', 'course_version_id' => $course_version_id]) }}"
                                                        class="theme-btn theme-button3">{{ __('Back') }}</a>
                                                    @if ($course->lectures->count() > 0)
                                                        <a href="{{ route('instructor.course.edit', [$course->uuid, 'step=instructors', 'course_version_id' => $course_version_id]) }}"
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
                                            </div> --}}

                                    </div>

                                    <!-- Upload Course Video-2 end -->

                                    {{-- <input type="button" name="previous"
                                            class="previous action-button-previous action-button theme-btn theme-button3"
                                            value="Back" /> <input type="button" name="next"
                                            class="next action-button default-hover-btn theme-btn theme-button1"
                                            value="Save & continue" /> --}}
                                @else
                                    <!-- Upload Course Video-1 start -->
                                    {{-- <form method="POST" action="{{ route('lesson.store', [$course->uuid]) }}"
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
                                                    <a href="{{ route('instructor.course.edit', [$course->uuid, 'step=category', 'course_version_id' => $course_version_id]) }}"
                                                        class="theme-btn theme-button3">{{ __('Back') }}</a>
                                                    <button type="submit"
                                                        class="theme-btn default-hover-btn theme-button1">{{ __('Save and continue') }}</button>
                                                </div>

                                            </div>
                                        </form> --}}

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
    <!-- Page content area end -->
@endsection



@section('modal')
    <!--  Lesson Update Modal Start -->
    <div class="modal fade edit_modal" id="becomeAnInstructor" tabindex="-1" aria-labelledby="becomeAnInstructorLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="becomeAnInstructorLabel">{{ __('Edit Lesson Name') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" id="updateEditModal" action="" class="needs-validation">
                    @csrf
                    <div class="modal-body">

                        <div class="row mb-30">
                            <div class="col-md-12">
                                <label
                                    class="label-text-title color-heading font-medium font-16 mb-2">{{ __('Name') }}</label>
                                <input type="text" name="name" class="form-control" id="lessonName"
                                    placeholder="Write your lesson name" value="" required>
                            </div>
                            @if ($errors->has('name'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                    {{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="modal-footer d-flex justify-content-center align-items-center">
                            <button type="submit"
                                class="theme-btn theme-button1 default-hover-btn">{{ __('Submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Lesson Update Modal End -->

    <!--  Text Upload Modal Start -->
    <div class="modal fade textUploadModal venoBoxTypeModal" id="textUploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-header border-bottom-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify"
                    data-icon="akar-icons:cross"></span></button>
        </div>
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="getLectureText"></p>
                </div>
            </div>
        </div>
    </div>
    <!-- Text Upload Modal End -->

    <!-- Image Upload Modal Start -->
    <div class="modal fade textUploadModal venoBoxTypeModal" id="imageUploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-header border-bottom-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify"
                    data-icon="akar-icons:cross"></span></button>
        </div>
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="" alt="" class="img-fluid getLectureImage">
                </div>
            </div>
        </div>
    </div>
    <!-- Image Upload Modal End -->

    <!-- Slide Show Upload Modal Start-->
    <div class="modal fade venoBoxTypeModal" id="slideModal" tabindex="-1" aria-hidden="true">
        <div class="modal-header border-bottom-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify"
                    data-icon="akar-icons:cross"></span></button>
        </div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <iframe class="getLectureSlide" src="" width="100%" height="400" frameborder="0"
                        scrolling="no"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- Slide Show Upload Modal End-->

    <!-- PDF Show Upload Modal Start-->
    <div class="modal fade venoBoxTypeModal" id="pdfModal" tabindex="-1" aria-hidden="true">
        <div class="modal-header border-bottom-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify"
                    data-icon="akar-icons:cross"></span></button>
        </div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <embed src="{{ getImageFile(@$lecture->pdf) }}" class="pdf-reader-frame">
                </div>
            </div>
        </div>
    </div>
    <!-- PDF Show Upload Modal End-->

    <!-- Audio Player Modal Start-->
    <div class="modal fade venoBoxTypeModal" id="audioPlayerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-header border-bottom-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify"
                    data-icon="akar-icons:cross"></span></button>
        </div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <!--Audio -->
                    <audio class="getLectureAudio js-player" src="" type="audio/mp3" style="width: 550px"
                        controls controlsList="nodownload">
                    </audio>
                </div>
            </div>
        </div>
    </div>
    <!-- Audio Player Modal End-->

    <!-- HTML5 Video Player Modal Start-->
    <div class="modal fade VideoTypeModal" id="html5VideoPlayerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-header border-bottom-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify"
                    data-icon="akar-icons:cross"></span></button>
        </div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="video-player-area">
                        <!-- HTML 5 Video -->
                        <video width="738" height="80%" class="getNormalVideo js-player" controls
                            controlsList="nodownload">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- HTML5 Video Player Modal End-->
@endsection

@push('style')





    <link href="{{ asset('frontend/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('frontend/assets/css/jquery-ui.min.css') }}" rel="stylesheet">

    <!-- Font Awesome for this template -->
    <link href="{{ asset('frontend/assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet"
        type="text/css">

    <!-- Custom fonts for this template -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @if (get_option('app_font_design_type') == 2)
        @if (empty(get_option('app_font_link')))
            <link
                href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
                rel="stylesheet">
        @else
            {!! get_option('app_font_link') !!}
        @endif
    @else
        <link
            href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
            rel="stylesheet">
    @endif
    <link rel="stylesheet" href="{{ asset('frontend/assets/fonts/feather/feather.css') }}">

    <!-- Animate Css-->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/animate.min.css') }}">

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/owl.theme.default.min.css') }}">

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/venobox.min.css') }}">

    <!-- Sweet Alert css -->
    <link rel="stylesheet" href="{{ asset('admin/sweetalert2/sweetalert2.css') }}">

    <!-- Custom styles for this template -->
    <link href="{{ asset('frontend/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/extra.css') }}" rel="stylesheet">

    <!-- Responsive Css-->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/responsive.css') }}">

    <!-- FAVICONS -->
    <link rel="icon" href="{{ getImageFile(get_option('app_fav_icon')) }}" type="image/png" sizes="16x16">
    <link rel="shortcut icon" href="{{ getImageFile(get_option('app_fav_icon')) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ getImageFile(get_option('app_fav_icon')) }}">

    <link rel="apple-touch-icon-precomposed" type="image/x-icon" href="{{ getImageFile(get_option('app_fav_icon')) }}"
        sizes="72x72">
    <link rel="apple-touch-icon-precomposed" type="image/x-icon" href="{{ getImageFile(get_option('app_fav_icon')) }}"
        sizes="114x114">
    <link rel="apple-touch-icon-precomposed" type="image/x-icon" href="{{ getImageFile(get_option('app_fav_icon')) }}"
        sizes="144x144">
    <link rel="apple-touch-icon-precomposed" type="image/x-icon" href="{{ getImageFile(get_option('app_fav_icon')) }}">






    <link rel="stylesheet" href="{{ asset('admin/css/jquery.dataTables.min.css') }}">

    <!-- Video Player css -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/vendor/video-player/plyr.css') }}">

    <!-- Summernote CSS - CDN Link -->
    <link href="{{ asset('common/css/summernote/summernote.min.css') }}" rel="stylesheet">
    <link href="{{ asset('common/css/summernote/summernote-lite.min.css') }}" rel="stylesheet">
    <!-- //Summernote CSS - CDN Link -->
@endpush
@push('script')
    <!-- Bootstrap core JavaScript -->
    {{-- <script src="{{ asset('frontend/assets/vendor/jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/vendor/jquery/popper.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script> --}}

    <!-- ==== Plugin JavaScript ==== -->
    <script src="{{ asset('frontend/assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <script src="{{ asset('frontend/assets/js/jquery-ui.min.js') }}"></script>

    <!--WayPoints JS Script-->
    <script src="{{ asset('frontend/assets/js/waypoints.min.js') }}"></script>

    <!--Counter Up JS Script-->
    <script src="{{ asset('frontend/assets/js/jquery.counterup.min.js') }}"></script>

    <script src="{{ asset('frontend/assets/js/owl.carousel.min.js') }}"></script>

    <!-- Range Slider -->
    <script src="{{ asset('frontend/assets/js/price_range_script.js') }}"></script>

    <!--Feather Icon-->
    <script src="{{ asset('frontend/assets/js/feather.min.js') }}"></script>

    <!--Iconify Icon-->
    {{-- <script src="{{ asset('common/js/iconify.min.js') }}"></script> --}}

    <!--Venobox-->
    <script src="{{ asset('frontend/assets/js/venobox.min.js') }}"></script>

    <!-- Menu js -->
    <script src="{{ asset('frontend/assets/js/menu.js') }}"></script>

    <!-- Custom scripts for this template -->
    <script src="{{ asset('frontend/assets/js/frontend-custom.js') }}"></script>

    {{-- <script src="{{ asset('admin/sweetalert2/sweetalert2.all.js') }}"></script> --}}
    <input type="hidden" id="base_url" value="{{ url('/') }}">
    <!-- Start:: Navbar Search  -->
    <input type="hidden" class="search_route" value="{{ route('search-course.list') }}">
    <script src="{{ asset('frontend/assets/js/custom/search-course.js') }}"></script>






    <script src="{{ asset('admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/js/custom/data-table-page.js') }}"></script>

    <script>
        $(function() {
            'use strict'
            $('.editLesson').on('click', function(e) {
                e.preventDefault();
                $('#lessonName').val($(this).data('name'))
                let route = $(this).data('route');
                $('#updateEditModal').attr("action", route)
            })
        })
    </script>
    {{-- <script src="{{ asset('frontend/assets/js/custom/form-validation.js') }}"></script> --}}
    {{-- <script src="{{ asset('frontend/assets/js/custom/upload-lesson.js') }}"></script> --}}
    <script src="{{ asset('frontend/assets/js/custom/index.js') }}"></script>

    <!-- Video Player js -->
    <script src="{{ asset('frontend/assets/vendor/video-player/plyr.js') }}"></script>
    <script>
        "use strict"
        const zai_player = new Plyr('#player');
        const zai_player1 = new Plyr('#playerVideoYoutube');
        const zai_player2 = new Plyr('#playerVideoHTML5');
        const zai_player3 = new Plyr('#playerVideoVimeo');
    </script>
    <!-- Video Player js -->

    <script>
        const players = Array.from(document.querySelectorAll('.js-player')).map((p) => new Plyr(p));
    </script>

    <!-- Summernote JS - CDN Link -->
    <script src="{{ asset('common/js/summernote/summernote-lite.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#summernote").summernote({
                dialogsInBody: true
            });
            $('.dropdown-toggle').dropdown();
        });
    </script>
    <!-- //Summernote JS - CDN Link -->

    <script>
        "use strict"
        $('.lectureText').on('click', function() {
            var text = $(this).data("lecture_text")
            $('.getLectureText').html(text)
        })

        $('.lectureImage').on('click', function() {
            var image = $(this).data("lecture_image")
            $('.getLectureImage').attr('src', image)
        })

        $('.lectureSlide').on('click', function() {
            var slide = $(this).data("lecture_slide")
            $('.getLectureSlide').attr('src', slide)
        })

        $('.normalVideo').on('click', function() {
            var video = $(this).data("normal_video")
            $('.getNormalVideo').attr('src', video)
        })

        $('.lectureAudio').on('click', function() {
            var audio = $(this).data("lecture_audio")
            $('.getLectureAudio').attr('src', audio)
        })
    </script>
@endpush
