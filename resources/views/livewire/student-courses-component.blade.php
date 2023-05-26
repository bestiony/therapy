<div class="row">
    <div class="col-md-12">
        <div class="customers__area bg-style mb-30">
            <div class="item-title d-flex justify-content-between">
                <h2>{{ __("Students' Courses") }}</h2>
            </div>
            <div class="customers__table">
                <div class="input__group mb-25">
                    <select wire:model="selected_course" class="form-control" name="course_name" id="course_name">
                        <option value="">{{ __('Choose a Course') }}</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                    @if ($selected_course)
                        <div class="item-title d-flex justify-content-center">
                            <h3>{{ __('Course') }}: {{ $courses->where('id', $selected_course)->first()->title }}</h3>
                        </div>
                    @endif
                </div>
                <table id="" class="row-border data-table-filter table-style">
                    <thead>
                        <tr>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Details') }}</th>
                            <th>{{ __('Country') }}</th>
                            <th>{{ __('Address') }}</th>
                            <th>{{ __('Total Course Enroll') }}</th>
                            {{-- <th>{{ __('Status') }}</th> --}}
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr class="removable-item">
                                <td>
                                    <img src="{{ getImageFile($student->user ? @$student->user->image_path : '') }}"
                                        width="80">
                                </td>
                                <td>
                                    {{ __('Name') }}: {{ $student->name }}<br>
                                    {{ __('Email') }}: {{ $student->user->email }}<br>
                                    {{ __('Phone') }}:
                                    {{ $student->phone_number ?? @$student->user->phone_number }}<br>

                                </td>
                                <td>{{ $student->country ? $student->country->country_name : '' }}</td>
                                <td>{{ $student->address }}</td>
                                <td>{{ studentCoursesCount($student->user_id) }}</td>
                                {{-- <td>
                                    <span id="hidden_id" style="display: none">{{ $student->id }}</span>
                                    <select name="status"
                                        class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                        <option value="1" @if ($student->status == 1) selected @endif>
                                            {{ __('Approved') }}</option>
                                        <option value="2" @if ($student->status == 2) selected @endif>
                                            {{ __('Blocked') }}</option>
                                    </select>
                                </td> --}}
                                <td>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('student.view', [$student->uuid]) }}"
                                            class="btn-action mr-30" title="View Details">
                                            <img src="{{ asset('admin/images/icons/eye-2.svg') }}" alt="eye">
                                        </a>
                                        {{-- <a href="{{ route('student.edit', [$student->uuid]) }}"
                                            class="btn-action mr-30" title="Edit Details">
                                            <img src="{{ asset('admin/images/icons/edit-2.svg') }}" alt="edit">
                                        </a> --}}
                                        @if($selected_course)
                                        <a href="#" wire:click.prevent='confirm_remove({{ $student->user_id }})'>
                                            <span class="">
                                                <img src="{{ asset('admin/images/icons/trash-2.svg') }}"
                                                    alt="trash">
                                            </span>
                                        </a>
                                        @endif
                                        {{-- <button class="ms-3">
                                            <span data-formid="delete_row_form_{{ $student->id }}" class="deleteItem">
                                                <img src="{{ asset('admin/images/icons/trash-2.svg') }}"
                                                    alt="trash">
                                            </span>
                                        </button>

                                        <form action="{{ route('student.delete', [$student->uuid]) }}" method="post"
                                            id="delete_row_form_{{ $student->id }}">
                                            {{ method_field('DELETE') }}
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </form> --}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
