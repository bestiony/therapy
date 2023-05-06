@extends('frontend.layouts.app')

@section('content')

    <!-- Header Start -->
    <header class="hero-area gradient-bg position-relative">
        <video id="video_background" preload="auto" autoplay="autoplay" loop="true" muted="muted"
            style="position: absolute;top:2%;bottom:0; right:-140px; z-index: 100; width: 1536px; height: 724px;">
            <source src="https://my-therapists.com/uploads_demo/home/video.mp4" type="video/mp4">
            <source src="video/video.ogg" type="video/ogg">
            <source src="video/video.webm" type="video/webm">bgvideo
        </video>
        <div class="section-overlay">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="hero-content" style="z-index: 100">
                            <h6 class="come-for-learn-text">
                                @foreach (@$home->banner_mini_words_title ?? [] as $banner_mini_word)
                                    <span>{{ __($banner_mini_word) }}</span>
                                @endforeach
                            </h6>

                            <div class="text">
                                <h1 class="hero-heading">{{ __(@$home->banner_first_line_title) }}</h1>
                                <h1 class="hero-heading">
                                    <span class="main-middle-text">{{ __(@$home->banner_second_line_title) }}</span>
                                    @foreach (@$home->banner_second_line_changeable_words ?? [] as $banner_second_line_changeable_word)
                                        <span class="word">{{ __($banner_second_line_changeable_word) }}</span>
                                    @endforeach
                                </h1>
                                <h1 class="hero-heading hero-heading-last-word">{{ __(@$home->banner_third_line_title) }}
                                </h1>
                            </div>

                            <p>{{ __(@$home->banner_subtitle) }} </p>
                            @if (!get_option('private_mode') || !auth()->guest())
                                <div class="hero-btns">
                                    <a href="/instructor"
                                        class="theme-btn theme-button1">{{ __('Browse instructors') }} <i
                                            data-feather="arrow-right"></i></a>
                                </div>
                            @endif
                        </div>
                    </div>
<div class="hidden-lg">
                       <video id="video_background" preload="auto" autoplay="autoplay" loop="true" muted="muted"
            style=" z-index: 100; width: auto; height: 224px;">
            <source src="https://theraphy.almustashar.net/uploads_demo/home/video2.mp4" type="video/mp4">
            <source src="video/video2.ogg" type="video/ogg">
            <source src="video/video2.webm" type="video/webm">bgvideo
        </video></div>
                </div>
            </div>
        </div>
    </header>
    <!-- Header End -->

    <!-- Course Instructor and Support Area Start -->
    <section
        class="course-instructor-support-area bg-light section-t-space {{ @$home->instructor_support_area == 1 ? '' : 'd-none' }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title text-center">
                        <h3 class="section-heading">{{ __(@$aboutUsGeneral->instructor_support_title) }}</h3>
                        <p class="section-sub-heading">{{ __(@$aboutUsGeneral->instructor_support_subtitle) }}</p>
                    </div>
                </div>
            </div>
            <div class="row course-instructor-support-wrap">
                @foreach ($instructorSupports as $instructorSupport)
                    <!-- Instructor Support Item start-->
                    <div class="col-md-4">
                        <div class="instructor-support-item bg-white radius-3 text-center">
                            <div class="instructor-support-img-wrap">
                                <img src="{{ getImageFile($instructorSupport->image_path) }}" alt="support">
                            </div>
                            <h6>{{ __($instructorSupport->title) }}</h6>
                            <p>{{ __($instructorSupport->subtitle) }} </p>
                            <a href="{{ $instructorSupport->button_link ?? '#' }}"
                                class="theme-btn theme-button1 theme-button3">{{ __($instructorSupport->button_name) }} <i
                                    data-feather="arrow-right"></i></a>
                        </div>
                    </div>
                    <!-- Instructor Support Item End-->
                @endforeach
            </div>


        </div>
    </section>
    <!-- Course Instructor and Support Area End -->

    <!-- Special Feature Area Start -->
    <section class="special-feature-area gradient-bg  {{ @$home->special_feature_area == 1 ? '' : 'd-none' }}"
        style="padding-top:100px;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title text-center">
                        <div class="section-heading-img"><img src="{{ asset(get_option('top_category_logo')) }}"
                                alt="Our categories"></div>
                        <h3 class="section-heading section-heading-light">{{ __(get_option('top_category_title')) }}</h3>
                        <p class="section-sub-heading section-sub-heading-light">
                            {{ __(get_option('top_category_subtitle')) }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Single Feature Item start-->
                <div class="col-md-4">
                    <div class="single-feature-item d-flex align-items-center">
                        <div class="flex-shrink-0 feature-img-wrap">
                            <img src="{{ getImageFile(get_option('home_special_feature_first_logo')) }}" alt="feature">
                        </div>
                        <div class="flex-grow-1 ms-3 feature-content">
                            <h6>{{ __(get_option('home_special_feature_first_title')) }}</h6>
                            <p>{{ __(get_option('home_special_feature_first_subtitle')) }}</p>
                        </div>
                    </div>
                </div>
                <!-- Single Feature Item End-->
                <!-- Single Feature Item start-->
                <div class="col-md-4">
                    <div class="single-feature-item d-flex align-items-center">
                        <div class="flex-shrink-0 feature-img-wrap">
                            <img src="{{ getImageFile(get_option('home_special_feature_second_logo')) }}" alt="feature">
                        </div>
                        <div class="flex-grow-1 ms-3 feature-content">
                            <h6>{{ __(get_option('home_special_feature_second_title')) }}</h6>
                            <p>{{ __(get_option('home_special_feature_second_subtitle')) }}</p>
                        </div>
                    </div>
                </div>
                <!-- Single Feature Item End-->
                <!-- Single Feature Item start-->
                <div class="col-md-4">
                    <div class="single-feature-item d-flex align-items-center">
                        <div class="flex-shrink-0 feature-img-wrap">
                            <img src="{{ getImageFile(get_option('home_special_feature_third_logo')) }}" alt="feature">
                        </div>
                        <div class="flex-grow-1 ms-3 feature-content">
                            <h6>{{ __(get_option('home_special_feature_third_title')) }}</h6>
                            <p>{{ __(get_option('home_special_feature_third_subtitle')) }}</p>
                        </div>
                    </div>
                </div>
                <!-- Single Feature Item End-->

            </div>
        </div>
    </section>
    <!-- Special Feature Area End -->










    <!-- Achievement Area Start -->
    <section class="achievement-area {{ @$home->achievement_area == 1 ? '' : 'd-none' }}">
        <div class="container">
            <div class="row achievement-content-area">
                <!-- Achievement Item start-->
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="achievement-item d-flex align-items-center">
                        <div class="flex-shrink-0 achievement-img-wrap">
                            <img src="{{ getImageFile(get_option('achievement_first_logo')) }}" alt="achievement">
                        </div>
                        <div class="flex-grow-1 ms-3 achievement-content">
                            <h6>{{ __(get_option('achievement_first_title')) }}</h6>
                            <p>{{ __(get_option('achievement_first_subtitle')) }}</p>
                        </div>
                    </div>
                </div>
                <!-- Achievement Item End-->

                <!-- Achievement Item start-->
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="achievement-item d-flex align-items-center">
                        <div class="flex-shrink-0 achievement-img-wrap">
                            <img src="{{ getImageFile(get_option('achievement_second_logo')) }}" alt="achievement">
                        </div>
                        <div class="flex-grow-1 ms-3 achievement-content">
                            <h6>{{ __(get_option('achievement_second_title')) }}</h6>
                            <p>{{ __(get_option('achievement_second_subtitle')) }}</p>
                        </div>
                    </div>
                </div>
                <!-- Achievement Item End-->

                <!-- Achievement Item start-->
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="achievement-item d-flex align-items-center">
                        <div class="flex-shrink-0 achievement-img-wrap">
                            <img src="{{ getImageFile(get_option('achievement_third_logo')) }}" alt="achievement">
                        </div>
                        <div class="flex-grow-1 ms-3 achievement-content">
                            <h6>{{ __(get_option('achievement_third_title')) }}</h6>
                            <p>{{ __(get_option('achievement_third_subtitle')) }}</p>
                        </div>
                    </div>
                </div>
                <!-- Achievement Item End-->

                <!-- Achievement Item start-->
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="achievement-item d-flex align-items-center">
                        <div class="flex-shrink-0 achievement-img-wrap">
                            <img src="{{ getImageFile(get_option('achievement_four_logo')) }}" alt="achievement">
                        </div>
                        <div class="flex-grow-1 ms-3 achievement-content">
                            <h6>{{ __(get_option('achievement_four_title')) }}</h6>
                            <p>{{ __(get_option('achievement_four_subtitle')) }}</p>
                        </div>
                    </div>
                </div>
                <!-- Achievement Item End-->
            </div>
        </div>
    </section>
    <!-- Achievement Area End -->

    <!-- FAQ Area Start -->
    <section style="display:none;"
        class="faq-area home-page-faq-area section-t-space {{ @$home->faq_area == 1 ? '' : 'd-none' }}">
        <div class="container">

            <!-- FAQ Shape Image Start-->
            <div class="faq-area-shape"></div>
            <!-- FAQ Shape Image End-->

            <div class="row align-items-center">
                <div class="col-md-6 col-lg-6 col-xl-5">

                    <div class="section-title">
                        <h3 class="section-heading">{{ __(get_option('faq_title')) }}</h3>
                        <p class="section-sub-heading">{{ __(get_option('faq_subtitle')) }}</p>
                    </div>

                    <div class="accordion" id="accordionExample">
                        @foreach ($faqQuestions as $key => $faqQuestion)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading_{{ $key }}">
                                    <button
                                        class="accordion-button font-medium font-18 {{ $key == 0 ? '' : 'collapsed' }}"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse_{{ $key }}"
                                        aria-expanded="{{ $key == 0 ? 'true' : 'false' }}"
                                        aria-controls="collapse_{{ $key }}">
                                        {{ $key + 1 }}. {{ __($faqQuestion->question) }}
                                    </button>
                                </h2>
                                <div id="collapse_{{ $key }}"
                                    class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}"
                                    aria-labelledby="heading_{{ $key }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        {{ __($faqQuestion->answer) }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-7">
                    <div class="faq-area-right position-relative">
                        <img src="{{ getImageFile(get_option('faq_image')) }}" alt="faq" class="img-fluid">
                        <div class="still-no-luck radius-3 bg-white position-absolute">
                            <h6>{{ __(get_option('faq_image_title')) }}</h6>
                            <p>{{ __('Then feel free to') }} <a href="{{ route('contact') }}"
                                    class="text-decoration-underline color-heading">{{ __('Contact With Us') }}</a>,
                                {{ __('We are 24/7 for you') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- FAQ Area End -->
   @if(!get_option('private_mode') || !auth()->guest())
    @if(count($consultationInstructors) > 0)
    <!-- One to One Consultation Area Start -->
    <section class="courses-area courses-bundels-area one-to-one-consultation-area section-t-space section-b-85-space bg-page {{ @$home->consultation_area == 1 ? '' : 'd-none' }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- section-left-align-->
                    <div class="section-left-title-with-btn d-flex justify-content-between align-items-end">
                        <div class="section-title section-title-left d-flex align-items-start">
                            <div class="section-heading-img">
                                <img src="{{ asset('uploads_demo/about_us_general/team-members-heading-img.png') }}" alt="Consultant">
                            </div>
                            <div>
                                <h3 class="section-heading">{{ __('One to one consultation') }}</h3>
                                <p class="section-sub-heading">{{ __('Consult with your favorite consultant!') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('consultationInstructorList') }}" class="theme-btn theme-button2 theme-button3">{{ __('View All') }} <i data-feather="arrow-right"></i></a>
                    </div>
                    <!-- section-left-align-->
                </div>
            </div>

            <!-- One to one consultation Slider start -->
            <div class="row">
                <div class="col-12">
                    <!-- Consultation instructor slider items wrap -->
                    <div class="course-slider-items one-to-one-slider-items owl-carousel owl-theme">
                        @foreach($consultationInstructors as $instructorUser)
                            <!-- Course item start -->
                            <div class="col-12 col-sm-4 col-lg-3 w-100 mt-0 mb-25">
                                <x-frontend.instructor :user="$instructorUser" :type=INSTRUCTOR_CARD_TYPE_TWO />
                            </div>
                            <!-- Course item end -->
                        @endforeach
                    </div>
                    <!-- Consultation instructor slider items wrap -->
                </div>
            </div>
            <!-- One to one consultation Slider end -->

        </div>
    </section>
    <!-- One to One Consultation Area End -->
    @endif
    @endif
    <!-- Video Area Start -->
    <section class="video-area {{ @$home->video_area == 1 ? '' : 'd-none' }}"
        style="padding-top:88px;background:#fafafa;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7 col-xl-8">
                    <div class="video-area-left position-relative d-flex align-items-center justify-content-center">
                        <img src="{{ getImageFile(get_option('become_instructor_video_preview_image')) }}"
                            alt="video" class="img-fluid">
                        <button type="button" class="play-btn position-absolute" data-bs-toggle="modal"
                            data-bs-target="#newVideoPlayerModal">
                            <img src="{{ asset('frontend/assets/img/icons-svg/play.svg') }}" alt="play">
                        </button>
                    </div>
                </div>
                <div class="col-md-6 col-lg-5 col-xl-4">
                    <div class="video-area-right position-relative">
                        <div class="section-title">
                            <h3 class="section-heading">
                                {{ Str::limit(__(get_option('become_instructor_video_title')), 100) }}</h3>
                        </div>

                        <div class="video-floating-img-wrap pe-2 position-relative">
                            <p>{{ Str::limit(get_option('become_instructor_video_subtitle'), 450) }}</p>
                            <img src="{{ getImageFile(get_option('become_instructor_video_logo')) }}" alt="video"
                                class="position-absolute">
                        </div>

                        <!-- section button start-->
                        <div class="col-12 section-btn">
                            <a href="{{ route('student.become-an-instructor') }}"
                                class="theme-btn theme-button1">{{ __('Become an Instructor') }} <i
                                    data-feather="arrow-right"></i></a>
                        </div>
                        <!-- section button end-->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Video Area End -->
    <!-- Customers Says/ testimonial Area Start -->
    <section class="customers-says-area gradient-bg p-0 {{ @$home->customer_says_area == 1 ? '' : 'd-none' }}">
        <div class="section-overlay section-t-space section-b-space">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title text-center">
                            <div class="section-heading-img"><img
                                    src="{{ getImageFile(get_option('customer_say_logo')) }}" alt="Our categories">
                            </div>
                            <h3 class="section-heading section-heading-light mx-auto">
                                {{ __(get_option('customer_say_title')) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="row testimonial-content-wrap">

                    <!-- Single Testimonial Item start-->
                    <div class="col-md-4">
                        <div class="testimonial-item">
                            <div class="testimonial-top-content d-flex align-items-center">
                                <div class="flex-shrink-0 quote-img-wrap">
                                    <img src="{{ asset('frontend/assets/img/icons-svg/quote.svg') }}" alt="quote">
                                </div>
                                <div class="flex-grow-1 ms-3 testimonial-content">
                                    <h6 class="font-16">{{ __(get_option('customer_say_first_name')) }}</h6>
                                    <p class="font-13 font-medium">{{ __(get_option('customer_say_first_position')) }}
                                    </p>
                                </div>
                            </div>
                            <div class="testimonial-bottom-content">
                                <h6 class="text-white">{{ __(get_option('customer_say_first_comment_title')) }}</h6>
                                <p class="font-17">{{ __(get_option('customer_say_first_comment_description')) }}</p>
                                @include('frontend.home.partial.customer-say-first-comment-rating')
                            </div>

                        </div>
                    </div>
                    <!-- Single Testimonial Item End-->

                    <!-- Single Testimonial Item start-->
                    <div class="col-md-4">
                        <div class="testimonial-item">
                            <div class="testimonial-top-content d-flex align-items-center">
                                <div class="flex-shrink-0 quote-img-wrap">
                                    <img src="{{ asset('frontend/assets/img/icons-svg/quote.svg') }}" alt="quote">
                                </div>
                                <div class="flex-grow-1 ms-3 testimonial-content">
                                    <h6 class="font-16">{{ __(get_option('customer_say_second_name')) }}</h6>
                                    <p class="font-13 font-medium">{{ __(get_option('customer_say_second_position')) }}
                                    </p>
                                </div>
                            </div>
                            <div class="testimonial-bottom-content">
                                <h6 class="text-white">{{ __(get_option('customer_say_second_comment_title')) }}</h6>
                                <p class="font-17">{{ __(get_option('customer_say_second_comment_description')) }}</p>
                                @include('frontend.home.partial.customer-say-second-comment-rating')
                            </div>

                        </div>
                    </div>
                    <!-- Single Testimonial Item End-->

                    <!-- Single Testimonial Item start-->
                    <div class="col-md-4">
                        <div class="testimonial-item">
                            <div class="testimonial-top-content d-flex align-items-center">
                                <div class="flex-shrink-0 quote-img-wrap">
                                    <img src="{{ asset('frontend/assets/img/icons-svg/quote.svg') }}" alt="quote">
                                </div>
                                <div class="flex-grow-1 ms-3 testimonial-content">
                                    <h6 class="font-16">{{ __(get_option('customer_say_third_name')) }}</h6>
                                    <p class="font-13 font-medium">{{ __(get_option('customer_say_third_position')) }}
                                    </p>
                                </div>
                            </div>
                            <div class="testimonial-bottom-content">
                                <h6 class="text-white">{{ __(get_option('customer_say_third_comment_title')) }}</h6>
                                <p class="font-17">{{ __(get_option('customer_say_third_comment_description')) }}</p>
                                @include('frontend.home.partial.customer-say-third-comment-rating')
                            </div>

                        </div>
                    </div>
                    <!-- Single Testimonial Item End-->

                </div>
            </div>
        </div>
    </section>
    <!-- Customers Says/ testimonial Area End -->

    <!-- Course Instructor and Support Area Start -->
    <section style="display:none;"
        class="course-instructor-support-area bg-light section-t-space {{ @$home->instructor_support_area == 1 ? '' : 'd-none' }}">
        <div class="container">

            <div class="section-title">
                <h3 class="section-heading">Our Company</h3>
                <p class="section-sub-heading">{{ __(get_option('faq_subtitle')) }}</p>
            </div>


            <!-- Client Logo Area start-->
            <div class="row client-logo-area">
                @foreach ($clients as $client)
                    <div class="col">
                        <div class="client-logo-item text-center">
                            <img src="{{ getImageFile($client->image_path) }}" alt="{{ $client->name }}">
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Client Logo Area end-->
        </div>
    </section>
    <!-- Course Instructor and Support Area End -->

    @include('frontend.home.partial.consultation-booking-schedule-modal')

    <!-- New Video Player Modal Start-->
    <div class="modal fade VideoTypeModal" id="newVideoPlayerModal" tabindex="-1" aria-labelledby="newVideoPlayerModal"
        aria-hidden="true">

        <div class="modal-header border-bottom-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify"
                    data-icon="akar-icons:cross"></span></button>
        </div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="video-player-area">
                        <!-- HTML 5 Video -->
                        <video id="player" playsinline controls
                            data-poster="{{ getImageFile(get_option('become_instructor_video_preview_image')) }}"
                            controlsList="nodownload">
                            <source src="{{ getVideoFile(get_option('become_instructor_video')) }}" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- New Video Player Modal End-->
@endsection

@push('style')
    <!-- Video Player css -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/vendor/video-player/plyr.css') }}">
@endpush

@push('script')
    <!--Hero text effect-->
    <script src="{{ asset('frontend/assets/js/hero-text-effect.js') }}"></script>

    <script src="{{ asset('frontend/assets/js/course/addToCart.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/course/addToWishlist.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/custom/booking.js') }}"></script>

    <!-- Video Player js -->
    <script src="{{ asset('frontend/assets/vendor/video-player/plyr.js') }}"></script>
    <script>
        const zai_player = new Plyr('#player');
    </script>
    <!-- Video Player js -->
@endpush
