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
                                    <tr>
                                        <td>{{ _('Key Points') }}</td>
                                        <td>{{ implode(', ', $course->keyPoints->pluck('name')->toArray()) }}</td>
                                        <td>{{ get_names('key_point', $course_version->details['new_learn_key_points']) }}
                                        </td>
                                    </tr>
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
                                    <tr>
                                        <td>{{ _('Tags') }}</td>
                                        <td>{{ implode(', ', $course->tags->pluck('name')->toArray()) }}</td>
                                        <td>{{ get_names('tag', $course_version->details['tags']) }}</td>
                                    </tr>
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
                                    <tr>
                                        <td>{{ _('Lessons') }}</td>
                                        <td style="max-width: 200px">
                                            {{ implode(', ', $course->lessons->pluck('name')->toArray()) }}</td>
                                        <td style="max-width: 200px">
                                            {{ get_names('lesson', $course_version->details['tags']) }}</td>
                                    </tr>
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
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{-- {{$course_version->links()}} --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Page content area end -->
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('admin/css/jquery.dataTables.min.css') }}">
@endpush

@push('script')
    <script src="{{ asset('admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/js/custom/data-table-page.js') }}"></script>
@endpush
