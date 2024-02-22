<div wire:ignore.self class="modal fade edit_modal" id="addNewLecture" tabindex="-1" aria-labelledby="addNewLectureLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered w-50" style="max-width: 800px">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="addNewLectureLabel">{{ __('Add New Lecture') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="instructor-profile-right-part instructor-upload-course-box-part">
                <div class="instructor-upload-course-box">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div id="msform">
                                    <!-- progressbar -->
                                    {{-- <ul id="progressbar"
                                        class="upload-course-item-block d-flex align-items-center justify-content-center">
                                        <li class="active" id="account"><strong>{{ __('Course Overview') }}</strong>
                                        </li>
                                        <li class="active" id="personal"><strong>{{ __('Upload Video') }}</strong></li>
                                        <li id="instructor"><strong>{{ __('Instructors') }}</strong></li>
                                        <li id="confirm"><strong>{{ __('Submit Process') }}</strong></li>
                                    </ul> --}}

                                    <!-- Upload Course Step-1 Item Start -->
                                    <div class="upload-course-step-item upload-course-overview-step-item">
                                        <!-- Upload Course Step-2 Item Start -->
                                        <div class="upload-course-step-item upload-course-video-step-item">
                                            <form wire:submit.prevent='storeLecture' {{-- method="POST" --}}
                                                {{-- action="{{ route('organization.store.lecture', [$course->uuid, $lesson->uuid]) }}" --}} class="row g-3 needs-validation" novalidate
                                                enctype="multipart/form-data">

                                                <!-- Upload Course Video-4 start -->
                                                <div id="upload-course-video-4">
                                                    <div
                                                        class="upload-course-item-block course-overview-step1 radius-8">

                                                        <div class="row mb-30">
                                                            <div class="col-md-12">
                                                                <div class="d-flex">
                                                                    <div
                                                                        class="label-text-title color-heading font-medium font-16 mb-3 mr-15">
                                                                        {{ __('Type') }}: </div>
                                                                    <div>
                                                                        <label class="mr-15"><input type="radio"
                                                                                wire:model='type' name="type"
                                                                                value="video" checked
                                                                                class="lecture-type">
                                                                            {{ __('Upload Video') }}</label>
                                                                        <label class="mr-15"><input type="radio"
                                                                                wire:model='type' name="type"
                                                                                value="youtube" class="lecture-type"
                                                                                id="lectureTypeYoutube">
                                                                            {{ __('Youtube') }} </label>
                                                                        @if (env('VIMEO_STATUS') == 'active')
                                                                            <label class="mr-15"><input type="radio"
                                                                                    wire:model='type' name="type"
                                                                                    value="vimeo" class="lecture-type">
                                                                                {{ __('Vimeo') }}</label>
                                                                        @endif
                                                                        <label class="mr-15"><input type="radio"
                                                                                wire:model='type' name="type"
                                                                                value="text" class="lecture-type"
                                                                                id="lectureTypeText">
                                                                            {{ __('Text') }} </label>
                                                                        <label class="mr-15"><input type="radio"
                                                                                wire:model='type' name="type"
                                                                                value="image" class="lecture-type"
                                                                                id="lectureTypeImage">
                                                                            {{ __('Image') }} </label>
                                                                        <label class="mr-15"><input type="radio"
                                                                                wire:model='type' name="type"
                                                                                value="pdf" class="lecture-type"
                                                                                id="lectureTypePDF">
                                                                            {{ __('PDF') }} </label>
                                                                        <label class="mr-15"><input type="radio"
                                                                                wire:model='type' name="type"
                                                                                value="slide_document"
                                                                                class="lecture-type"
                                                                                id="lectureTypePowerpoint">
                                                                            {{ __('Slide Document') }} </label>
                                                                        <label class="mr-15"><input type="radio"
                                                                                wire:model='type' name="type"
                                                                                value="audio" class="lecture-type"
                                                                                id="lectureTypeAudio">
                                                                            {{ __('Audio') }} </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="video" wire:ignore.self>
                                                            <label
                                                                class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Upload Video') }}<span
                                                                    class="text-danger">*</span></label>
                                                            <div
                                                                class="upload-course-video-4-wrap upload-introduction-box-content-left d-flex align-items-center flex-column">
                                                                <div class="upload-introduction-box-content-img mb-3">
                                                                    <img src="{{ asset('frontend/assets/img/instructor-img/upload-lesson-icon.png') }}"
                                                                        alt="upload">
                                                                </div>
                                                                <input type="hidden" id="file_duration"
                                                                    name="file_duration" wire:model='file_duration'>
                                                                <input type="file" name="video_file"
                                                                    wire:model='video_file' accept="video/mp4"
                                                                    class="form-control"
                                                                    value="{{ old('video_file') }}" id="video_file"
                                                                    title="{{ __('Upload lesson') }}" required>
                                                            </div>

                                                            <p class="font-14 color-gray text-center mt-3 pb-30">No
                                                                file selected
                                                                (MP4 or WMV)</p>
                                                        </div>

                                                        <div id="youtube" class="d-none" wire:ignore.self>
                                                            <label
                                                                class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Lesson Youtube Video ID') }}
                                                                <span class="text-danger">*</span></label>
                                                            <input type="text" name="youtube_url_path"
                                                                wire:model='youtube_url_path'
                                                                class="form-control youtube-url"
                                                                value="{{ old('youtube_url_path') }}"
                                                                id="youtube_url_path"
                                                                placeholder="{{ __('Type Your Youtube Video ID') }}">
                                                        </div>

                                                        <div id="vimeo" class="d-none" wire:ignore.self>
                                                            <div class="row mb-30">
                                                                <div class="col-md-12">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Vimeo Upload Type') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <select name="vimeo_upload_type"
                                                                        wire:model='vimeo_upload_type'
                                                                        class="form-select vimeo_upload_type">
                                                                        <option value="">
                                                                            --{{ __('Select Option') }}--</option>
                                                                        <option value="1">
                                                                            {{ __('Video File Upload') }}</option>
                                                                        <option value="2">
                                                                            {{ __('Vimeo Uploaded Video ID') }}
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="vimeo_Video_file_upload_div d-none">
                                                                <label
                                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Upload Video') }}<span
                                                                        class="text-danger">*</span></label>
                                                                <div
                                                                    class="upload-course-video-4-wrap upload-introduction-box-content-left d-flex align-items-center flex-column">
                                                                    <div
                                                                        class="upload-introduction-box-content-img mb-3">
                                                                        <img src="{{ asset('frontend/assets/img/instructor-img/upload-lesson-icon.png') }}"
                                                                            alt="upload">
                                                                    </div>
                                                                    <input type="file" name="vimeo_url_path"
                                                                        wire:model='vimeo_url_path' accept="video/mp4"
                                                                        class="form-control"
                                                                        value="{{ old('vimeo_url_path') }}"
                                                                        id="vimeo_url_path" title="Upload lesson">
                                                                </div>

                                                                @error('vimeo_url_path')
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i>
                                                                        {{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                            <div class="vimeo_uploaded_Video_id_div d-none">
                                                                <div class="row mb-30">
                                                                    <div class="col-md-12">
                                                                        <label
                                                                            class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Uploaded Video ID') }}<span
                                                                                class="text-danger">*</span></label>
                                                                        <div
                                                                            class="upload-course-video-4-wrap upload-introduction-box-content-left d-flex align-items-center flex-column">
                                                                            <input type="text"
                                                                                name="vimeo_url_uploaded_path"
                                                                                wire:model='vimeo_url_uploaded_path'
                                                                                placeholder="{{ __('Type your uploaded video ID (ex: 123654)') }}"
                                                                                class="form-control"
                                                                                value="{{ old('vimeo_url_uploaded_path') }}"
                                                                                id="vimeo_url_uploaded_path">
                                                                        </div>
                                                                    </div>
                                                                    @error('vimeo_url_uploaded_path')
                                                                        <span class="text-danger"><i
                                                                                class="fas fa-exclamation-triangle"></i>
                                                                            {{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="row mb-30">
                                                                    <div class="col-md-12">
                                                                        <label
                                                                            class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Lesson File Duration') }}
                                                                            (00:00) <span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text"
                                                                            name="vimeo_file_duration"
                                                                            wire:model='vimeo_file_duration'
                                                                            value="{{ old('vimeo_file_duration') }}"
                                                                            class="form-control customVimeoFileDuration"
                                                                            placeholder="{{ __('Type file duration') }}">
                                                                        @error('vimeo_file_duration')
                                                                            <span class="text-danger"><i
                                                                                    class="fas fa-exclamation-triangle"></i>
                                                                                {{ $message }}</span>
                                                                        @enderror

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="text" class="d-none" wire:ignore.self>
                                                            <label
                                                                class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Lesson Description') }}
                                                                <span class="text-danger">*</span></label>
                                                            <textarea name="text_description" wire:model='text_description' id="summernote" class="textDescription"
                                                                cols="30" rows="10">{{ old('text_description') }}</textarea>
                                                        </div>
                                                        <div id="imageDiv" class="d-none" wire:ignore.self>
                                                            <div class="row align-items-center">
                                                                <div class="col-12">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Lesson Image') }}
                                                                        <span class="text-danger">*</span></label>
                                                                </div>
                                                                <div class="col-md-6 mb-30">
                                                                    <div class="upload-img-box mt-3 height-200">
                                                                        <img src="">
                                                                        <input type="file" name="image"
                                                                            wire:model='image' id="image"
                                                                            accept="image/*"
                                                                            onchange="previewFile(this)">
                                                                        <div class="upload-img-box-icon">
                                                                            <i class="fa fa-camera"></i>
                                                                            <p class="m-0">{{ __('Image') }}</p>
                                                                        </div>
                                                                    </div>
                                                                    @error('image')
                                                                        <span class="text-danger"><i
                                                                                class="fas fa-exclamation-triangle"></i>
                                                                            {{ $message }}</span>
                                                                    @enderror

                                                                </div>
                                                                <div class="col-md-6 mb-30">
                                                                    <p class="font-14 color-gray">
                                                                        {{ __('Preferable image size:') }} (1MB)</p>
                                                                    <p class="font-14 color-gray">
                                                                        {{ __('Preferable filetype:') }} jpg, jpeg, png
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="pdfDiv" class="d-none" wire:ignore.self>
                                                            <label
                                                                class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Upload PDF') }}
                                                                <span class="text-danger">*</span></label>
                                                            <div
                                                                class="upload-course-video-4-wrap upload-introduction-box-content-left d-flex align-items-center flex-column">
                                                                <div class="upload-introduction-box-content-img mb-3">
                                                                    <img src="{{ asset('frontend/assets/img/instructor-img/upload-lesson-icon.png') }}"
                                                                        alt="upload">
                                                                </div>
                                                                <input type="file" name="pdf" wire:model='pdf'
                                                                    accept="application/pdf" class="form-control"
                                                                    id="pdf" title="Upload lesson pdf">
                                                            </div>
                                                        </div>
                                                        <div id="slide_documentDiv" class="d-none" wire:ignore.self>
                                                            <label
                                                                class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Write your Slide Embed Code') }}<span
                                                                    class="text-danger">*</span></label>
                                                            <div
                                                                class="upload-course-video-4-wrap upload-introduction-box-content-left d-flex align-items-center flex-column">
                                                                <input type="text" name="slide_document"
                                                                    wire:model='slide_document' class="form-control"
                                                                    value="{{ old('slide_document') }}"
                                                                    id="slide_document"
                                                                    title="Upload lesson slide document">
                                                            </div>
                                                        </div>
                                                        <div id="audioDiv" class="d-none" wire:ignore.self>
                                                            <label
                                                                class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Upload Audio') }}
                                                                <span class="text-danger">*</span></label>
                                                            <div
                                                                class="upload-course-video-4-wrap upload-introduction-box-content-left d-flex align-items-center flex-column">
                                                                <div class="upload-introduction-box-content-img mb-3">
                                                                    <img src="{{ asset('frontend/assets/img/instructor-img/upload-lesson-icon.png') }}"
                                                                        alt="upload">
                                                                </div>
                                                                <input type="file" name="audio"
                                                                    wire:model='audio' class="form-control"
                                                                    value="{{ old('audio') }}" id="audio"
                                                                    title="Upload lesson audio">
                                                            </div>
                                                        </div>
                                                        <div>
                                                            @if ($errors->has('video_file'))
                                                                <span class="text-danger"><i
                                                                        class="fas fa-exclamation-triangle"></i>
                                                                    {{ $errors->first('video_file') }}</span>
                                                            @endif
                                                            @if ($errors->has('youtube_url'))
                                                                <span class="text-danger"><i
                                                                        class="fas fa-exclamation-triangle"></i>
                                                                    {{ $errors->first('youtube_url') }}</span>
                                                            @endif
                                                            @if ($errors->has('vimeo_url'))
                                                                <span class="text-danger"><i
                                                                        class="fas fa-exclamation-triangle"></i>
                                                                    {{ $errors->first('vimeo_url') }}</span>
                                                            @endif
                                                            @if ($errors->has('text_description'))
                                                                <span class="text-danger"><i
                                                                        class="fas fa-exclamation-triangle"></i>
                                                                    {{ $errors->first('text_description') }}</span>
                                                            @endif
                                                            @if ($errors->has('image'))
                                                                <span class="text-danger"><i
                                                                        class="fas fa-exclamation-triangle"></i>
                                                                    {{ $errors->first('image') }}</span>
                                                            @endif
                                                            @if ($errors->has('pdf'))
                                                                <span class="text-danger"><i
                                                                        class="fas fa-exclamation-triangle"></i>
                                                                    {{ $errors->first('pdf') }}</span>
                                                            @endif
                                                            @if ($errors->has('slide_document'))
                                                                <span class="text-danger"><i
                                                                        class="fas fa-exclamation-triangle"></i>
                                                                    {{ $errors->first('slide_document') }}</span>
                                                            @endif
                                                            @if ($errors->has('audio'))
                                                                <span class="text-danger"><i
                                                                        class="fas fa-exclamation-triangle"></i>
                                                                    {{ $errors->first('audio') }}</span>
                                                            @endif
                                                        </div>

                                                        <div class="main-upload-video-processing-box">
                                                            <div class="d-flex main-upload-video-processing-item">
                                                                <div class="flex-grow-1 ms-3">
                                                                    <div class="row mb-30">
                                                                        <div class="col-md-12">
                                                                            <label
                                                                                class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Lesson Title') }}
                                                                                <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" name="title"
                                                                                wire:model='title'
                                                                                class="form-control"
                                                                                placeholder="{{ __('First steps') }}"
                                                                                required>
                                                                            @error('title')
                                                                                <span class="text-danger"><i
                                                                                        class="fas fa-exclamation-triangle"></i>
                                                                                    {{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-30">
                                                                        <div class="col-md-12">
                                                                            <label
                                                                                class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Learner\'s Visibility') }}
                                                                                <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select name="lecture_type"
                                                                                wire:model='lecture_type'
                                                                                class="form-select" required>
                                                                                <option value="">--Select
                                                                                    Option--</option>
                                                                                <option value="1"
                                                                                    @if (old('lecture_type') == 1) selected @endif>
                                                                                    {{ __('Show') }}</option>
                                                                                <option value="2"
                                                                                    @if (old('lecture_type') == 2) selected @endif>
                                                                                    {{ __('Lock') }}</option>
                                                                            </select>
                                                                            @error('lecture_type')
                                                                                <span class="text-danger"><i
                                                                                        class="fas fa-exclamation-triangle"></i>
                                                                                    {{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-30 d-none" id="fileDuration"
                                                                        wire:ignore.self>
                                                                        <div class="col-md-12">
                                                                            <label
                                                                                class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Lesson File Duration') }}
                                                                                (00:00) <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text"
                                                                                name="youtube_file_duration"
                                                                                wire:model='youtube_file_duration'
                                                                                value="{{ old('file_duration') }}"
                                                                                class="form-control customFileDuration"
                                                                                placeholder="{{ __('First file duration') }}">
                                                                            @error('youtube_file_duration')
                                                                                <span class="text-danger"><i
                                                                                        class="fas fa-exclamation-triangle"></i>
                                                                                    {{ $message }}</span>
                                                                            @enderror

                                                                        </div>
                                                                    </div>
                                                                    @if ($course->drip_content == DRIP_AFTER_DAY)
                                                                        <div class="row mb-30" id="drip-day">
                                                                            <div class="col-md-12">
                                                                                <label
                                                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Lesson available after x days') }}
                                                                                    <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="number" min=1 required
                                                                                    name="after_day"
                                                                                    wire:model='after_day'
                                                                                    value="{{ old('unlock_date', 0) }}"
                                                                                    class="form-control"
                                                                                    placeholder="Days">
                                                                                @error('after_day')
                                                                                    <span class="text-danger"><i
                                                                                            class="fas fa-exclamation-triangle"></i>
                                                                                        {{ $message }}</span>
                                                                                @enderror

                                                                            </div>
                                                                        </div>
                                                                    @elseif($course->drip_content == DRIP_UNLOCK_DATE)
                                                                        <div class="row mb-30" id="drip-date">
                                                                            <div class="col-md-12">
                                                                                <div class="input__group text-black">
                                                                                    <label
                                                                                        class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Lesson available by date') }}
                                                                                        <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="date"
                                                                                        name="unlock_date"
                                                                                        wire:model='unlock_date'
                                                                                        value="{{ old('unlock_date') }}"
                                                                                        class="form-control" required>
                                                                                    @error('unlock_date')
                                                                                        <span class="text-danger"><i
                                                                                                class="fas fa-exclamation-triangle"></i>
                                                                                            {{ $message }}</span>
                                                                                    @enderror

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @elseif($course->drip_content == DRIP_PRE_IDS)
                                                                        <div class="row mb-30"
                                                                            id="drip-pre-requisite">
                                                                            <div class="col-md-12">
                                                                                <label
                                                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Pre-requisites lesson') }}
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <select name="pre_ids[]" required
                                                                                    wire:model='pre_ids'
                                                                                    class="select2 form-control"
                                                                                    multiple>
                                                                                    @foreach ($lessons as $pr_lesson)
                                                                                        <optgroup
                                                                                            label="{{ $pr_lesson->name }}">
                                                                                            @foreach ($pr_lesson->lectures as $pr_lecture)
                                                                                                <option
                                                                                                    value="{{ $pr_lecture->id }}">
                                                                                                    {{ $pr_lecture->title }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </optgroup>
                                                                                    @endforeach
                                                                                </select>
                                                                                @error('pre_ids')
                                                                                    <span class="text-danger"><i
                                                                                            class="fas fa-exclamation-triangle"></i>
                                                                                        {{ $errors->first('pre_ids') }}</span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    <div class="row mb-30">
                                                                        <div
                                                                            class="col-md-12 main-upload-video-processing-item-btns">
                                                                            <button type="submit"
                                                                                class="theme-btn upload-video-processing-item-save-btn">{{ __('Save') }}</button>
                                                                            <button data-bs-dismiss="modal"
                                                                                class="theme-btn default-hover-btn default-back-btn theme-button3">{{ __('Cancel') }}</button>
                                                                            {{-- {{ dump($lesson) }} --}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- Upload Course Video-4 end -->
                                            </form>
                                        </div>
                                        <!-- Upload Course Step-6 Item End -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" value="{{ old('type') }}" class="oldTypeYoutube">
            </div>

        </div>
    </div>
</div>
