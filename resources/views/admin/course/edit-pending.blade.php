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
                                <h2>{{__('Courses Edits')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('Courses Edits')}}</li>
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
                        <div class="customers__table">
                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{__('Image')}}</th>
                                    <th>{{__('Title')}}</th>
                                    <th>{{__('Instructor')}}</th>
                                    <th>{{__('Category')}}</th>
                                    <th>{{__('Subcategory')}}</th>
                                    <th>{{__('Price')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($versions as $course_version)
                                    @php
                                        $details = $course_version->details;
                                        $course = $course_version->course;
                                    @endphp
                                    <tr class="removable-item">
                                        <td>
                                            <a href="#"> <img src="{{getImageFile($details['image'])}}" width="80"> </a>
                                        </td>
                                        <td>
                                            {{$details['title']}}
                                        </td>


                                        <td>
                                            {{$course->instructor ? $course->instructor->name : '' }}
                                        </td>
                                        <td>
                                            {{$categories[$details['category_id']] }}
                                        </td>
                                        <td>
                                            {{$subcategories[$details['subcategory_id']] }}
                                        </td>
                                        <td>
                                            @if(get_currency_placement() == 'after')
                                                {{$details['price']}} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{$details['price']}}
                                            @endif
                                        </td>
                                        <td>

                                            <div class="action__buttons">

                                                <a href="{{route('admin.course.version-status-change', [$course_version->id, APPROVED_COURSE_VERSION])}}" class="btn-action approve-btn mr-30" title="Make as Active">
                                                    {{__('Approve')}}
                                                </a>

                                                <a href="{{route('admin.course.version-view', [$course_version->id])}}" target="_blank" class="btn-action mr-30" title="View Details">
                                                    <img src="{{asset('admin/images/icons/eye-2.svg')}}" alt="eye">
                                                </a>

                                                <button class="btn-action ms-2 deleteItem" data-formid="delete_row_form_{{$course_version->id}}">
                                                    <img src="{{asset('admin/images/icons/trash-2.svg')}}" alt="trash">
                                                </button>

                                                <form action="{{route('admin.course.delete-version', [$course_version->id])}}" method="get" id="delete_row_form_{{ $course_version->id }}">

                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
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
                            <h2>{{ __('Pending Course Deletion Requests') }}</h2>
                        </div>
                        <div class="customers__table">
                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{__('Image')}}</th>
                                    <th>{{__('Title')}}</th>
                                    <th>{{__('Instructor')}}</th>
                                    <th>{{__('Category')}}</th>
                                    <th>{{__('Subcategory')}}</th>
                                    <th>{{__('Price')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($course_deletes as $delete_request)
                                    @php
                                        $course = $delete_request->course;
                                    @endphp
                                    <tr class="removable-item">
                                        <td>
                                            <a href="#"> <img src="{{getImageFile($course->image)}}" width="80"> </a>
                                        </td>
                                        <td>
                                            {{$course->title}}
                                        </td>


                                        <td>
                                            {{$course->instructor ? $course->instructor->name : '' }}
                                        </td>
                                        <td>
                                            {{$categories[$course->category_id] }}
                                        </td>
                                        <td>
                                            {{$subcategories[$course->subcategory_id] }}
                                        </td>
                                        <td>
                                            @if(get_currency_placement() == 'after')
                                                {{$course->price}} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{$course->price}}
                                            @endif
                                        </td>
                                        <td>

                                            <div class="action__buttons">

                                                <a href="{{route('admin.course.approve-course-delete', [$delete_request->id])}}" class="btn-action approve-btn mr-30" title="Make as Active">
                                                    {{__('Approve')}}
                                                </a>



                                                <button class="btn-action ms-2 deleteItem" data-formid="delete_row_form_{{$delete_request->id}}">
                                                    <img src="{{asset('admin/images/icons/trash-2.svg')}}" alt="trash">
                                                </button>

                                                <form action="{{route('admin.course.remove-course-delete', [$delete_request->id])}}" method="get" id="delete_row_form_{{ $delete_request->id }}">

                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
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
    <link rel="stylesheet" href="{{asset('admin/css/jquery.dataTables.min.css')}}">
@endpush

@push('script')
    <script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/js/custom/data-table-page.js')}}"></script>
@endpush
