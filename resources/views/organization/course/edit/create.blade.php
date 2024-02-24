@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{ __('Upload Course') }} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{ route('organization.dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Upload Course') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    @livewire('edit.set-course-overview-component', [
        'title' => $title,
        'navCourseUploadActiveClass' => $navCourseUploadActiveClass,
        'conditions' => $rules,
        'course' => $course,
    ])
@endsection
<style>
    #course_type option:last-child {
        display: none;
    }
</style>
@push('script')
    @livewireScripts
    <script src="{{ asset('frontend/assets/js/custom/upload-course.js') }}"></script>
    <script src="{{ asset('common/js/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('common/js/add-repeater.js') }}"></script>
@endpush
@push('style')
    @livewireStyles
@endpush
