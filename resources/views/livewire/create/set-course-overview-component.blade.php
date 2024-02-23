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
                            <li id="confirm"><strong>{{ __('Submit Process') }}</strong></li>
                        </ul>

                        <!-- Upload Course Step-1 Item Start -->
                        <div class="upload-course-step-item upload-course-overview-step-item">

                            <!-- Upload Course Overview-1 start -->
                            <div id="upload-course-overview-1">
                                <form wire:submit.prevent="setCourseOverview" id="step1"
                                    class="row g-3 needs-validation" novalidate>
                                    @csrf

                                    @if (get_option('courseUploadRuleTitle'))
                                        <div class="upload-course-item-block course-overview-step1 radius-8 mb-30">
                                            <div class="upload-course-item-block-title mb-3">
                                                <h6 class="font-20">{{ __(get_option('courseUploadRuleTitle')) }}</h6>
                                            </div>
                                            <ul class="mb-30">
                                                @foreach ($conditions as $condition)
                                                    <li><span class="iconify"
                                                            data-icon="akar-icons:arrow-right"></span>{{ $condition->description }}
                                                    </li>
                                                @endforeach
                                            </ul>

                                        </div>
                                    @endif
                                    <div class="upload-course-item-block course-overview-step1 radius-8">
                                        <div class="upload-course-item-block-title mb-3">
                                            <h6 class="font-20">{{ __('Course Details') }}</h6>
                                        </div>

                                        <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">
                                                    {{ __('Course Type') }}
                                                    <span class="text-danger">*</span>
                                                </div>

                                                <select wire:model='course_type' name="course_type" id="course_type"
                                                    class="form-select" required>
                                                    <option value="">
                                                        {{ __('Select Course
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                Type') }}
                                                    </option>
                                                    <option value="{{ COURSE_TYPE_GENERAL }}"
                                                        {{ old('course_type') == COURSE_TYPE_GENERAL ? 'selected' : '' }}>
                                                        General</option>
                                                    <option value="{{ COURSE_TYPE_SCORM }}"
                                                        {{ old('course_type') == COURSE_TYPE_SCORM ? 'selected' : '' }}>
                                                        SCORM</option>
                                                </select>
                                                @error('course_type')
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                        {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">
                                                    {{ __('Course Title') }}
                                                    <span class="text-danger">*</span>
                                                </div>

                                                <input type="text" wire:model='courseTitle' name="courseTitle"
                                                    value="{{ old('courseTitle') }}" class="form-control"
                                                    placeholder="Type your course title" required>

                                                @error('courseTitle')
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                        {{ $message }}</span>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">
                                                    {{ __('Course Subtitle') }}
                                                    <span class="text-danger">*</span>
                                                </div>
                                                <textarea id="subtitle" wire:model='subtitle' class="form-control" name="subtitle" cols="30" rows="10"
                                                    required placeholder="Course subtitle in 1000 characters">{{ old('subtitle') }}</textarea>
                                                <p id="countshow">
                                                    {{ __('You have') }} <span id="charCount"
                                                        class=""></span>{{ __('characters') }}
                                                </p>
                                                @error('subtitle')
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                        {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        @if ($forOrganization)
                                            <div class="row mb-30">
                                                <div class="col-md-12">
                                                    <div
                                                        class="label-text-title color-heading font-medium font-16 mb-3">
                                                        {{ __('Private for organization') }}
                                                        <span class="text-danger">*</span>
                                                    </div>

                                                    <select wire:model='private_mode' name="private_mode"
                                                        id="private_mode" class="form-select" required>
                                                        <option value="{{ COURSE_PRIVATE_ACTIVE }}"
                                                            {{ old('private_mode') == COURSE_PRIVATE_ACTIVE ? 'selected' : '' }}>
                                                            {{ __('Enable') }}</option>
                                                        <option value="{{ COURSE_PRIVATE_DEACTIVATE }}"
                                                            {{ old('private_mode') == COURSE_PRIVATE_DEACTIVATE ? 'selected' : '' }}>
                                                            {{ __('Disabled') }}</option>
                                                    </select>
                                                    @error('private_mode')
                                                        <span class="text-danger"><i
                                                                class="fas fa-exclamation-triangle"></i>
                                                            {{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endif
                                        @if (get_option('subscription_mode'))
                                            <div class="row mb-30">
                                                <div class="col-md-12">
                                                    <div
                                                        class="label-text-title color-heading font-medium font-16 mb-3">
                                                        {{ __('Enable for subscription') }}
                                                        <span class="text-danger">*</span>
                                                    </div>

                                                    <select wire:model='is_subscription_enable'
                                                        name="is_subscription_enable" id="is_subscription_enable"
                                                        class="form-select" required>
                                                        <option value="{{ PACKAGE_STATUS_ACTIVE }}"
                                                            {{ old('is_subscription_enable') == PACKAGE_STATUS_ACTIVE ? 'selected' : '' }}>
                                                            {{ __('Enable') }}</option>
                                                        <option value="{{ PACKAGE_STATUS_DISABLED }}"
                                                            {{ old('is_subscription_enable') == PACKAGE_STATUS_DISABLED ? 'selected' : '' }}>
                                                            {{ __('Disabled') }}</option>
                                                    </select>
                                                    @error('is_subscription_enable')
                                                        <span class="text-danger"><i
                                                                class="fas fa-exclamation-triangle"></i>
                                                            {{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">
                                                    {{ __('Course Description Key Points') }}
                                                    <span class="text-danger">*</span>
                                                </div>
                                                <div id="add_repeater">
                                                    <div data-repeater-list="key_points" class="">
                                                        <label for="name" class="text-lg-right text-black">
                                                            {{ __('Name') }} </label>
                                                        @foreach ($key_points as $key => $point)
                                                            <div data-repeater-item=""
                                                                class="form-group row align-items-center">
                                                                <div class="custom-form-group mb-3 col-md-10">
                                                                    <input
                                                                        wire:model='key_points.{{ $key }}.name'
                                                                        wire:key='{{ $key }}' type="text"
                                                                        name="name" id="name"
                                                                        class="form-control"
                                                                        placeholder="Type key point name" required>
                                                                </div>

                                                                <div class="col mb-3">
                                                                    <button {{-- href="javascript:;" data-repeater-delete="" --}}
                                                                        wire:click.prevent='removeKeyPoint({{ $key }})'
                                                                        class="theme-btn theme-button1 default-delete-btn-red default-hover-btn
                                                                        frontend-remove-btn
                                                                         btn-danger">
                                                                        <span class="iconify"
                                                                            data-icon="akar-icons:cross"></span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        <div data-repeater-item=""
                                                            class="form-group row align-items-center">
                                                            <div class="custom-form-group mb-3 col-md-10">
                                                                <input wire:model='new_key_point' type="text"
                                                                    {{-- name="name" id="name" value="" --}} class="form-control"
                                                                    placeholder="Type key point name">
                                                            </div>

                                                            <div class="col mb-3">
                                                                <a href="javascript:;" data-repeater-delete=""
                                                                    class="theme-btn theme-button1 default-delete-btn-red default-hover-btn frontend-remove-btn btn-danger">
                                                                    <span class="iconify"
                                                                        data-icon="akar-icons:cross"></span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-2">
                                                        <a wire:click.prevent='addKeyPoint' {{-- id="add"
                                                            href="javascript:;" data-repeater-create="" --}}
                                                            class="theme-btn default-hover-btn theme-button1">
                                                            <span class="iconify" data-icon="akar-icons:plus"></span>
                                                            {{ __('Add') }}
                                                        </a>
                                                        @error('new_key_point')
                                                            <span class="text-danger"><i
                                                                    class="fas fa-exclamation-triangle"></i>
                                                                {{ $message }}</span>
                                                        @enderror
                                                    </div>


                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">
                                                    {{ __('Course Description') }}
                                                    <span class="text-danger">*</span>
                                                </div>
                                                <textarea wire:model='description' class="form-control" name="description" cols="30" rows="10" required
                                                    placeholder="Course description">{{ old('description') }}</textarea>
                                                @error('description')
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                        {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="stepper-action-btns">
                                        <a href="{{ route('organization.course.index') }}"
                                            class="theme-btn theme-button3">{{ __('Cancel') }}</a>
                                        <button type="submit"
                                            class="theme-btn default-hover-btn theme-button1">{{ __('Save and
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    continue') }}</button>
                                    </div>
                                </form>
                            </div>
                            <!-- Upload Course Overview-1 end -->

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('script')
        <script>
            function updateCharCount() {
                var count = $('#subtitle').val().length;
                $('#charCount').text(count);
                if (count) {
                    $('#countshow').show();
                } else {
                    $('#countshow').hide();
                }
                if (count > 1000) {
                    $('#charCount').addClass('text-danger').removeClass('text-success');
                } else {
                    $('#charCount').addClass('text-success').removeClass('text-danger');
                }
            }

            $(document).ready(function() {
                updateCharCount();
                $('#subtitle').on('input', updateCharCount);
            });
            $('#countshow').attr('wire:ignore', '')
            // document.addEventListener('livewire:load', function() {
            //     updateCharCount();
            //     @this.on('input', function() {
            //         $('#subtitle').off('input', updateCharCount);
            //         $('#subtitle').on('input', updateCharCount);
            //     });
            // });
        </script>
    @endpush
</div>
