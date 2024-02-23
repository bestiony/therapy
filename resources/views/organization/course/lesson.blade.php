@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{ __('Upload Course') }} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{ route('organization.dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item font-14"><a
                        href="{{ route('organization.course.index') }}">{{ __('My Courses') }}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Upload Course') }}</li>
            </ol>
        </nav>
    </div>
@endsection
@section('content')
    @php
        $deleted_lessons = $deleted_lessons ?? [];
        $deleted_lectures = $deleted_lectures ?? [];
        $updated_course_lessons = $updated_course_lessons ?? [];
        // dd($deleted_lessons);
        // dd($updated_course_lessons);
    @endphp

    @livewire('create.add-course-lessons-component', [
        'course' => $course,
    ])
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
    @livewireStyles
    <!-- Video Player css -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/vendor/video-player/plyr.css') }}">

    <!-- Summernote CSS - CDN Link -->
    <link href="{{ asset('common/css/summernote/summernote.min.css') }}" rel="stylesheet">
    <link href="{{ asset('common/css/summernote/summernote-lite.min.css') }}" rel="stylesheet">
    <!-- //Summernote CSS - CDN Link -->
@endpush


@push('script')
    @livewireScripts
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
    <script src="{{ asset('frontend/assets/js/custom/form-validation.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/custom/upload-lesson.js') }}"></script>
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
        // $(document).ready(function() {
        //     $("#summernote").summernote({
        //         dialogsInBody: true
        //     });
        //     $('.dropdown-toggle').dropdown();
        // });
        document.addEventListener('livewire:load', function() {
            // initializeSummernote();

            // Livewire.on('contentUpdated', function() {
            //     initializeSummernote();
            // });
        });

        // function initializeSummernote() {
        //     $("#summernote").summernote({
        //         dialogsInBody: true
        //     });
        // }
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
        window.addEventListener('pageReload', () => {
            location.reload();
        });
    </script>
@endpush
