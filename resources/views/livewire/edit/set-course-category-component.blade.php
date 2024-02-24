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
                            <li id="personal"><strong>{{ __('Upload Video') }}</strong></li>
                            <li id="organization"><strong>{{ __('Instructor') }}</strong></li>
                            <li id="confirm"><strong>{{ __('Submit process') }}</strong></li>
                        </ul>

                        <div class="upload-course-step-item upload-course-overview-step-item">
                            <!-- Upload Course Overview-2 start -->
                            <form {{-- method="POST" action="{{route('organization.course.update.category', [$course->uuid])}}" --}} wire:submit.prevent="setCourseCategory"
                                enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                                @csrf
                                {{-- <input type="hidden" name="course_version_id" value="{{$course_version_id}}"> --}}

                                <div id="upload-course-overview-2">

                                    <div class="upload-course-item-block course-overview-step1 radius-8">
                                        <div class="upload-course-item-block-title mb-3">
                                            <h6 class="font-20">{{ __('Category & Tags') }}</h6>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Course Category') }}
                                                    <span
                                                        class="cursor tooltip-show-btn share-referral-big-btn primary-btn get-referral-btn border-0"
                                                        data-toggle="popover" data-bs-placement="bottom"
                                                        data-bs-content="">
                                                        !
                                                    </span>
                                                </label>
                                                <select wire:model="category_id" {{-- name="category_id"  --}} id="category_id"
                                                    class="form-select" required>
                                                    <option value="">{{ __('Select Category') }}</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                        {{ $message }}</span>
                                                @enderror


                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Course Subcategory') }}
                                                    <span
                                                        class="cursor tooltip-show-btn share-referral-big-btn primary-btn get-referral-btn border-0"
                                                        data-toggle="popover" data-bs-placement="bottom"
                                                        data-bs-content="">
                                                        !
                                                    </span>
                                                </label>
                                                <select wire:model='subcategory_id' name="subcategory_id"
                                                    id="subcategory_id" class="form-select" required>
                                                    <option value="">{{ __('Select Subcategory') }}</option>
                                                    @foreach ($subcategories as $subcategory)
                                                        <option value="{{ $subcategory->id }}">
                                                            {{ $subcategory->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('subcategory_id')
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                        {{ $message }}</span>
                                                @enderror


                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Tags') }}
                                                    <span
                                                        class="cursor tooltip-show-btn share-referral-big-btn primary-btn get-referral-btn border-0"
                                                        data-toggle="popover" data-bs-placement="bottom"
                                                        data-bs-content="">
                                                        !
                                                    </span>
                                                </label>
                                                <select wire:model='selectedTags' class="select2" multiple>
                                                    @foreach ($tags as $tag)
                                                        <option value="{{ $tag->id }}">{{ $tag->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                {{-- <div class="upload-course-item-block-title mb-3">
                                                    {{ var_dump($selectedTags) }}
                                                </div> --}}
                                                @error('selectedTags')
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                        {{ $message }}</span>
                                                @enderror


                                            </div>
                                        </div>
                                    </div>
                                    <div class="upload-course-item-block course-overview-step1 radius-8">
                                        <div class="upload-course-item-block-title mb-3">
                                            <h6 class="font-20">{{ __('Learners Accessibility & others') }}</h6>
                                        </div>
                                        @if ($course->course_type == COURSE_TYPE_GENERAL)
                                            <div class="row">
                                                <div class="col-md-12 mb-30">
                                                    <label
                                                        class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Drip Content') }}
                                                        <span
                                                            class="cursor tooltip-show-btn share-referral-big-btn primary-btn get-referral-btn border-0"
                                                            data-toggle="popover" data-bs-placement="bottom"
                                                            data-bs-content="">
                                                            !
                                                        </span>
                                                    </label>
                                                    <select wire:model='drip_content' name="drip_content"
                                                        class="form-select drip_content" required>
                                                        @foreach (dripType() as $key => $value)
                                                            <option value="{{ $key }}">{{ $value }}
                                                            </option>
                                                        @endforeach
                                                        {{-- <option value="{{ DRIP_SHOW_ALL }}"
                                                            {{ old('drip_content', $course->drip_content) == DRIP_SHOW_ALL ? 'selected' : '' }}>
                                                            {{ dripType(DRIP_SHOW_ALL) }}</option>
                                                        <option value="{{ DRIP_SEQUENCE }}"
                                                            {{ old('drip_content', $course->drip_content) == DRIP_SEQUENCE ? 'selected' : '' }}>
                                                            {{ dripType(DRIP_SEQUENCE) }}</option>
                                                        <option value="{{ DRIP_AFTER_DAY }}"
                                                            {{ old('drip_content', $course->drip_content) == DRIP_AFTER_DAY ? 'selected' : '' }}>
                                                            {{ dripType(DRIP_AFTER_DAY) }}</option>
                                                        <option value="{{ DRIP_UNLOCK_DATE }}"
                                                            {{ old('drip_content', $course->drip_content) == DRIP_UNLOCK_DATE ? 'selected' : '' }}>
                                                            {{ dripType(DRIP_UNLOCK_DATE) }}</option>
                                                        <option value="{{ DRIP_PRE_IDS }}"
                                                            {{ old('drip_content', $course->drip_content) == DRIP_PRE_IDS ? 'selected' : '' }}>
                                                            {{ dripType(DRIP_PRE_IDS) }}</option> --}}
                                                    </select>
                                                    @foreach (dripTypeHelpText() as $key => $value)
                                                        <div id="drip-help-text-{{ $key }}"
                                                            class="d-none drip-help-text form-text">
                                                            {{ $value }}
                                                        </div>
                                                    @endforeach
                                                    {{-- <div id="drip-help-text-{{ DRIP_SHOW_ALL }}"
                                                        class="d-none drip-help-text form-text">
                                                        {{ dripTypeHelpText(DRIP_SHOW_ALL) }}
                                                    </div>
                                                    <div id="drip-help-text-{{ DRIP_SEQUENCE }}"
                                                        class="d-none drip-help-text form-text">
                                                        {{ dripTypeHelpText(DRIP_SEQUENCE) }}
                                                    </div>
                                                    <div id="drip-help-text-{{ DRIP_AFTER_DAY }}"
                                                        class="d-none drip-help-text form-text">
                                                        {{ dripTypeHelpText(DRIP_AFTER_DAY) }}
                                                    </div>
                                                    <div id="drip-help-text-{{ DRIP_UNLOCK_DATE }}"
                                                        class="d-none drip-help-text form-text">
                                                        {{ dripTypeHelpText(DRIP_UNLOCK_DATE) }}
                                                    </div>
                                                    <div id="drip-help-text-{{ DRIP_PRE_IDS }}"
                                                        class="d-none drip-help-text form-text">
                                                        {{ dripTypeHelpText(DRIP_PRE_IDS) }}
                                                    </div> --}}
                                                </div>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Course Access Period') }}
                                                </label>
                                                <input type="number" name="access_period" wire:model="access_period"
                                                    {{-- value="{{ old('access_period', $course->access_period) }}" --}} min="0" class="form-control"
                                                    placeholder="{{ __('If there is no expiry duration, leave the field blank.') }} ">
                                                @error('access_period')
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                        {{ $message }}</span>
                                                @enderror

                                                <div class="form-text">
                                                    {{ __('Enrollment will expire after this number of days. Set 0 for no expiration') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Learners Accessibility') }}
                                                    <span
                                                        class="cursor tooltip-show-btn share-referral-big-btn primary-btn get-referral-btn border-0"
                                                        data-toggle="popover" data-bs-placement="bottom"
                                                        data-bs-content="">
                                                        !
                                                    </span>
                                                </label>
                                                <select wire:model='learner_accessibility' name="learner_accessibility"
                                                    class="form-select learner_accessibility" required>
                                                    <option value="">{{ __('Select Option') }}</option>
                                                    <option value="paid">{{ __('Paid') }}</option>
                                                    <option value="free">{{ __('Free') }}</option>
                                                </select>
                                                @error('learner_accessibility')
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                        {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row priceDiv">
                                            <div class="col-md-12 mb-30">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Course Price') }}
                                                    <span
                                                        class="cursor tooltip-show-btn share-referral-big-btn primary-btn get-referral-btn border-0"
                                                        data-toggle="popover" data-bs-placement="bottom"
                                                        data-bs-content="">
                                                        !
                                                    </span>
                                                </label>
                                                <input wire:model='price' type="number" name="price"
                                                    min="1" maxlength="11" class="form-control price"
                                                    placeholder="price" required="required">
                                                @error('price')
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                        {{ $message }}</span>
                                                @enderror


                                            </div>
                                            <div class="col-md-12 mb-30">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Old Price') }}
                                                    <span
                                                        class="cursor tooltip-show-btn share-referral-big-btn primary-btn get-referral-btn border-0"
                                                        data-toggle="popover" data-bs-placement="bottom"
                                                        data-bs-content="">
                                                        !
                                                    </span>
                                                </label>
                                                <input wire:model='old_price' type="number" name="old_price"
                                                    value="{{ $course->old_price == '0' ? '' : $course->old_price }}"
                                                    min="1" maxlength="11" class="form-control old_price"
                                                    placeholder="Old Price" required="required">
                                                @error('old_price')
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                        {{ $message }}</span>
                                                @enderror

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Language') }}
                                                    <span
                                                        class="cursor tooltip-show-btn share-referral-big-btn primary-btn get-referral-btn border-0"
                                                        data-toggle="popover" data-bs-placement="bottom"
                                                        data-bs-content="">
                                                        !
                                                    </span>
                                                </label>
                                                <select wire:model='course_language_id' name="course_language_id"
                                                    id="course_language_id" class="form-select" required>
                                                    <option value="">Select Language</option>
                                                    @foreach ($course_languages as $course_language)
                                                        <option value="{{ $course_language->id }}">
                                                            {{ $course_language->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('course_language_id')
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                        {{ $message }}</span>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Difficulty Level') }}
                                                    <span
                                                        class="cursor tooltip-show-btn share-referral-big-btn primary-btn get-referral-btn border-0"
                                                        data-toggle="popover" data-bs-placement="bottom"
                                                        data-bs-content="">
                                                        !
                                                    </span>
                                                </label>
                                                <select wire:model='difficulty_level_id' name="difficulty_level_id"
                                                    id="difficulty_level_id" class="form-select" required>
                                                    <option value="">{{ __('Select Difficulty Level') }}
                                                    </option>
                                                    @foreach ($difficulty_levels as $difficulty_level)
                                                        <option value="{{ $difficulty_level->id }}"
                                                            @if (old('difficulty_level_id')) {{ old('difficulty_level_id') == $difficulty_level->id ? 'selected' : '' }} @else {{ $course->difficulty_level_id == $difficulty_level->id ? 'selected' : '' }} @endif>
                                                            {{ $difficulty_level->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('difficulty_level_id')
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                        {{ $message }}</span>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Course Thumbnail') }}
                                                    <span
                                                        class="cursor tooltip-show-btn share-referral-big-btn primary-btn get-referral-btn border-0"
                                                        data-toggle="popover" data-bs-placement="bottom"
                                                        data-bs-content="">
                                                        !
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-md-6 mb-30">
                                                <div class="upload-img-box mt-3 height-200">
                                                    @if ($image)
                                                        <img src="{{ $image->temporaryUrl() }}">
                                                    @elseif ($course->image)
                                                        <img src="{{ getImageFile($course->image) }}">
                                                    @else
                                                        <img src="">
                                                    @endif
                                                    <input wire:model='image' type="file" name="image"
                                                        id="image" accept="image/*" {{-- onchange="previewFile(this)" --}}
                                                        {{-- @if (!$course->image)
                                                        @endif --}} required>
                                                    <div class="upload-img-box-icon">
                                                        <i class="fa fa-camera"></i>
                                                        <p class="m-0">{{ __('Image') }}</p>
                                                    </div>
                                                </div>
                                                @error('image')
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                        {{ $message }}</span>
                                                @enderror

                                            </div>
                                            <div class="col-md-6 mb-30">
                                                <p class="font-14 color-gray">
                                                    {{ __('Accepted image format & size') }}:
                                                    575px X 450px (1MB)</p>
                                                <p class="font-14 color-gray">{{ __('Accepted image filetype') }}:
                                                    jpg,
                                                    jpeg, png</p>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Course Introduction Video') }}
                                                    ({{ __('Optional') }})</label>
                                            </div>
                                            <div class="col-md-12 mb-30">
                                                <input type="radio" wire:model='intro_video_check' id="video_check"
                                                    class="intro_video_check" name="intro_video_check"
                                                    value="{{ VIDEO_TYPE_LOCAL }}">
                                                <label for="video_check">{{ __('Video Upload') }}</label><br>
                                                <input type="radio" wire:model='intro_video_check'
                                                    id="youtube_check" class="intro_video_check"
                                                    name="intro_video_check" value="{{ VIDEO_TYPE_YOUTUBE }}">
                                                <label for="youtube_check">{{ __('Youtube Video') }}
                                                    ({{ __('write only video Id') }})</label><br>
                                                @error('intro_video_check')
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                        {{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-12 mb-30">
                                                @if ($intro_video_check == VIDEO_TYPE_LOCAL)
                                                    <input wire:model='video' type="file" name="video"
                                                        id="video" accept="video/mp4" class="form-control ">
                                                    @error('video')
                                                        <span class="text-danger"><i
                                                                class="fas fa-exclamation-triangle"></i>
                                                            {{ $message }}</span>
                                                    @enderror
                                                @elseif($intro_video_check == VIDEO_TYPE_YOUTUBE)
                                                    <input wire:model='youtube_video_id' type="text"
                                                        name="youtube_video_id" id="youtube_video_id"
                                                        placeholder="{{ __('Type your youtube video ID') }}"
                                                        value="{{ $course->youtube_video_id }}"
                                                        class="form-control ">
                                                    @error('youtube_video_id')
                                                        <span class="text-danger"><i
                                                                class="fas fa-exclamation-triangle"></i>
                                                            {{ $message }}</span>
                                                    @enderror
                                                @endif
                                            </div>
                                            @if ($video)
                                                <div class="col-md-12 mb-30  videoSource">
                                                    <div class="video-player-area ">
                                                        <video id="player" playsinline controls
                                                            data-poster="{{ $image ? $image->temporaryUrl() : getImageFile($course->image) }}"
                                                            controlsList="nodownload">
                                                            <source src="{{ $video->temporaryUrl() }}"
                                                                type="{{ $video->getMimeType() }}">
                                                        </video>
                                                    </div>
                                                </div>
                                            @elseif($course->video)
                                                <div class="col-md-12 mb-30  videoSource">
                                                    <div class="video-player-area ">
                                                        <video id="player" playsinline controls
                                                            data-poster="{{ getImageFile($image) }}"
                                                            controlsList="nodownload">
                                                            <source src="{{ getStorageVideoFile($course->video) }}"
                                                                type="video/mp4">
                                                        </video>
                                                    </div>
                                                </div>
                                            @elseif ($youtube_video_id)
                                                <div class="col-md-12 mb-30  videoSourceYoutube">
                                                    <div class="video-player-area ">
                                                        <div class="plyr__video-embed" id="playerVideoYoutube">
                                                            <iframe
                                                                src="https://www.youtube.com/embed/{{ $youtube_video_id }}"
                                                                allowfullscreen allowtransparency allow="autoplay">
                                                            </iframe>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @error('video')
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                    {{ $message }}</span>
                                            @enderror

                                        </div>

                                    </div>

                                    <a href="{{ route('organization.course.update-overview', ['uuid' => $course->uuid]) }}"
                                        class="theme-btn theme-button3 show-last-phase-back-btn">{{ __('Back') }}</a>
                                    <button type="submit"
                                        class="theme-btn default-hover-btn theme-button1">{{ __('Save and continue') }}</button>
                                    {{-- <div class="upload-course-item-block-title mb-3">
                                        {{ var_dump($errors->getMessages()) }}
                                    </div> --}}
                                </div>
                            </form>
                            <!-- Upload Course Overview-2 end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('style')
        <script>
            document.addEventListener('livewire:load', function() {
                $('.select2').select2({
                    width: '100%'
                });
                $('.select2-search__field').css('width', '10%');

                $('.select2').on('change', function(e) {
                    let data = $(this).select2("val");
                    @this.set('selectedTags', data);
                });

                Livewire.on('reinitializeSelect2', function() {

                    $('.select2').select2({
                        width: '100%'
                    });

                    $('.select2-search__field').css('width', '10%');
                });
            });
        </script>
    @endpush
</div>
