@extends('layouts.certified_parent')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{ __('Profile') }} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{ route('parent.dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Profile') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <form method="POST" action="{{ route('save.profile', [$certified_parent->uuid]) }}" enctype="multipart/form-data">
            @csrf
            <div class="instructor-profile-info-box">
                <h6 class="instructor-info-box-title">{{ __('Personal Info') }}</h6>

                <div class="profile-top mb-4">
                    <div class="d-flex align-items-center">
                        <div class="profile-image radius-50">
                            <img class="avater-image" id="target1" src="{{ getImageFile($user->image_path) }}"
                                alt="img">
                            <div class="custom-fileuplode">
                                <label for="fileuplode" class="file-uplode-btn bg-hover text-white radius-50"><span
                                        class="iconify" data-icon="bx:bx-edit"></span></label>
                                <input type="file" id="fileuplode" name="image" accept="image/*" class="putImage1"
                                    onchange="previewFile(this)">
                            </div>
                        </div>
                        <div class="author-info">
                            <p class="font-medium font-15 color-heading">{{ __('Select Your Picture') }}</p>
                            <p class="font-14">{{ __('Accepted Image Files') }}: JPEG, JPG, PNG <br>
                                {{ __('Accepted Size') }}: 300 x 300 (1MB)</p>
                        </div>
                    </div>
                    @if ($errors->has('image'))
                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                            {{ $errors->first('image') }}</span>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('First Name') }}</label>
                        <input type="text" name="first_name" value="{{ $certified_parent->first_name }}" class="form-control"
                            placeholder="{{ __('First Name') }}">
                        @if ($errors->has('first_name'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('first_name') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Last Name') }}</label>
                        <input type="text" name="last_name" value="{{ $certified_parent->last_name }}" class="form-control"
                            placeholder="{{ __('Last Name') }}">
                        @if ($errors->has('last_name'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('last_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Email') }}</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="form-control"
                            placeholder="{{ __('Type your email') }}">
                        @if ($errors->has('email'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Professional Title') }}</label>
                        <input type="text" name="professional_title" value="{{ $certified_parent->professional_title }}"
                            class="form-control" placeholder="{{ __('Type your professional title') }}">
                        @if ($errors->has('professional_title'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('professional_title') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Phone Number') }}</label>
                        <input type="text" name="phone_number" value="{{ $certified_parent->phone_number }}"
                            class="form-control" placeholder="{{ __('Type your phone number') }}">

                        @if ($errors->has('phone_number'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('phone_number') }}</span>
                        @endif

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Bio') }}</label>
                        <textarea class="form-control" name="about_me" id="exampleFormControlTextarea1" rows="3"
                            placeholder="{{ __('Type about yourself') }}">{{ $certified_parent->about_me }}</textarea>
                        @if ($errors->has('about_me'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('about_me') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-30">
                    <label class="font-medium font-15 color-heading">{{ __('Gender') }}</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="inlineRadio1"
                                value="Male" {{ $certified_parent->gender == 'Male' ? 'checked' : '' }}>
                            <label class="form-check-label" for="inlineRadio1">{{ __('Male') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="inlineRadio2"
                                value="Female" {{ $certified_parent->gender == 'Female' ? 'checked' : '' }}>
                            <label class="form-check-label" for="inlineRadio2">{{ __('Female') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="inlineRadio3"
                                value="Others" {{ $certified_parent->gender == 'Others' ? 'checked' : '' }}>
                            <label class="form-check-label" for="inlineRadio3">{{ __('Others') }}</label>
                        </div>
                    </div>
                    @if ($errors->has('gender'))
                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                            {{ $errors->first('gender') }}</span>
                    @endif
                </div>
            </div>

            <div class="instructor-profile-info-box">
                <h6 class="instructor-info-box-title">{{ __('Social Links') }}</h6>

                @php
                    $social_link = json_decode($certified_parent->social_link);
                @endphp
                <div class="row">
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Facebook') }}</label>
                        <input type="text" name="social_link[facebook]"
                            value="{{ $certified_parent->social_link ? $social_link->facebook : '' }}" class="form-control"
                            placeholder="https://facebook.com">
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Twitter') }}</label>
                        <input type="text" name="social_link[twitter]"
                            value="{{ $certified_parent->social_link ? $social_link->twitter : '' }}" class="form-control"
                            placeholder="https://twitter.com">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Linkedin') }}</label>
                        <input type="text" name="social_link[linkedin]"
                            value="{{ $certified_parent->social_link ? $social_link->linkedin : '' }}" class="form-control"
                            placeholder="https://linkedin.com">
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Pinterest') }}</label>
                        <input type="text" name="social_link[pinterest]"
                            value="{{ $certified_parent->social_link ? $social_link->pinterest : '' }}" class="form-control"
                            placeholder="https://pinterest.com">
                    </div>
                </div>
            </div>
            {{-- <div class="instructor-profile-info-box">
                <h6 class="instructor-info-box-title">{{ __('Skills') }}</h6>
                <div class="row">
                    <div class="col-md-12 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Skills Name') }}</label>
                        <select name="skills[]" class="form-control select2" multiple>
                            @foreach ($skills as $skill)
                                <option value="{{ $skill->id }}"
                                    {{ in_array($skill->id, $certified_parent->skills->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ __($skill->title) }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('skills'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('skills') }}</span>
                        @endif
                    </div>
                </div>
            </div> --}}
            <div class="instructor-profile-info-box">
                <h6 class="instructor-info-box-title">{{ __('Languages') }}</h6>
                <div class="row">
                    <div class="col-md-12 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Language') }}</label>
                        <select name="languages[]" class="form-control select2" multiple>
                            @foreach ($languages as $language)
                                <option value="{{ $language->id }}"
                                    {{ in_array($language->id, $certified_parent->user->languages ?? []) ? 'selected' : '' }}>
                                    {{ $language->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('languages'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('languages') }}</span>
                        @endif
                    </div>
                </div>
            </div>


            <div class="row align-items-center">
                <div class="col-12">
                    <label
                        class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Introductory Video') }}
                        ({{ __('Optional') }})</label>
                </div>
                <div class="col-md-12 mb-30">
                    <input type="radio" {{ $certified_parent->intro_video_check == 1 ? 'checked' : '' }} id="video_check"
                        class="intro_video_check" name="intro_video_check" value="1">
                    <label for="video_check">{{ __('Video Upload') }}</label><br>
                    <input type="radio" {{ $certified_parent->intro_video_check == 2 ? 'checked' : '' }} id="youtube_check"
                        class="intro_video_check" name="intro_video_check" value="2">
                    <label for="youtube_check">{{ __('Youtube Video') }} ({{ __('write only video Id') }})</label><br>
                </div>
                <div class="col-md-12 mb-30">
                    <input type="file" name="video" id="video" accept="video/mp4" class="form-control d-none">
                    <input type="text" name="youtube_video_id" id="youtube_video_id"
                        placeholder="{{ __('Type your youtube video ID') }}" value="{{ $certified_parent->youtube_video_id }}"
                        class="form-control d-none">
                </div>
                @if ($certified_parent->video)
                    <div class="col-md-12 mb-30 d-none videoSource">
                        <div class="video-player-area ">
                            <video id="player" playsinline controls data-poster="{{ getImageFile(@$certified_parent->image) }}"
                                controlsList="nodownload">
                                <source src="{{ getVideoFile(@$certified_parent->video) }}" type="video/mp4">
                            </video>
                        </div>
                    </div>
                @endif
                @if ($certified_parent->youtube_video_id)
                    <div class="col-md-12 mb-30 d-none videoSourceYoutube">
                        <div class="video-player-area ">
                            <div class="plyr__video-embed" id="playerVideoYoutube">
                                <iframe src="https://www.youtube.com/embed/{{ @$certified_parent->youtube_video_id }}"
                                    allowfullscreen allowtransparency allow="autoplay">
                                </iframe>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->has('video'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                        {{ $errors->first('video') }}</span>
                @endif
            </div>





            <div class="col-12">
                <button type="submit"
                    class="theme-btn theme-button1 theme-button3 font-15 fw-bold">{{ __('Save Profile Now') }}</button>
            </div>
        </form>

    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('common/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/custom/image-preview.css') }}">
@endpush

@push('script')
    <script src="{{ asset('common/js/select2.min.js') }}"></script>
    <script src="{{ asset('admin/js/custom/image-preview.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/custom/instructor-profile.js') }}"></script>
    <script>
        $('.select2').select2({
            width: '100%'
        });
    </script>

    <script>
        "use strict"
        $(function (){
            var intro_video_check = "{{ $certified_parent->intro_video_check }}";
            console.log(intro_video_check)
            introVideoCheck(intro_video_check);
        })
        $(".intro_video_check").click(function(){
            var intro_video_check = $("input[name='intro_video_check']:checked").val();
            introVideoCheck(intro_video_check);
        });

        function introVideoCheck(intro_video_check){
            if(intro_video_check == 1){
                $('#video').removeClass('d-none');
                $('.videoSource').removeClass('d-none');
                $('.videoSourceYoutube').addClass('d-none');
                $('#youtube_video_id').addClass('d-none');
            }

            if(intro_video_check == 2){
                $('#video').addClass('d-none');
                $('.videoSource').addClass('d-none');
                $('.videoSourceYoutube').removeClass('d-none');
                $('#youtube_video_id').removeClass('d-none');
            }
        }
    </script>
@endpush