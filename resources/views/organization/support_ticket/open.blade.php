@extends('layouts.organization')

@section('content')
    <!-- Page content area start -->
    <div class="page-content mt-0">
        <div class="container-fluid">
            {{-- <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb__content">
                        <div class="breadcrumb__content__left">
                            <div class="breadcrumb__title">
                                <h2>{{ __(@$title) }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __(@$title) }}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{ __(@$title) }}</h2>
                        </div>
                        <div class="customers__table">
                            <table style="width:100%" id="support-tickets-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Name')}}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Subject') }}</th>
                                    <th>{{ __('Concerned Instructor') }}</th>
                                    <th>{{__('Action')}}</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tickets as $ticket)
                                    <tr class="removable-item">
                                        <td>
                                            <span id="hidden_id" style="display: none">{{$ticket->id}}</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($ticket->status == 1) selected @endif>{{ __('Open') }}</option>
                                                <option value="2" @if($ticket->status == 2) selected @endif>{{ __('Closed') }}</option>
                                            </select>
                                        </td>

                                        <td>{{$ticket->name}} - {{$ticket->id}}</td>
                                        <td>{{$ticket->email}}</td>
                                        <td>{{substr($ticket->subject,0,20)}}...</td>
                                        <td>{{$ticket->instructor ? $ticket->instructor->name : ''}}</td>
                                        <td>
                                            <div class="action__buttons">
                                                <a href="{{ route('organization.support-ticket.show', $ticket->uuid) }}" class=" btn-action mr-1" title="View Ticket Details">
                                                    <img src="{{asset('admin/images/icons/eye-2.svg')}}" alt="eye">
                                                </a>
                                                <a href="javascript:void(0);" data-url="{{route('organization.support-ticket.delete', [$ticket->uuid])}}" class="btn-action delete" title="Delete">
                                                    <img src="{{asset('admin/images/icons/trash-2.svg')}}" alt="trash">
                                                </a>
                                            </div>
                                        </td>
                                        {{-- <td>
                                            {{ @$ticket->priority->name }}
                                        </td> --}}

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{$tickets->links()}}
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
    <link rel="stylesheet" href="{{ asset('admin/styles/main.css') }}">

    <link rel="stylesheet" href="{{asset('admin/css/jquery.dataTables.min.css')}}">
    <style>
        th{
            max-width: 150px !important;
            width: 10% !important;
            min-width: 50px !important;
        }

    </style>
@endpush

@push('script')
    <script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/js/custom/data-table-page.js')}}"></script>
    <script>
        'use strict'
        $(".status").change(function () {
            var id = $(this).closest('tr').find('#hidden_id').html();
            var status_value = $(this).closest('tr').find('.status option:selected').val();
            console.log(id, status_value)
            Swal.fire({
                title: "{{ __('Are you sure to change status?') }}",
                text: "{{ __('You won`t be able to revert this!') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "{{__('Yes, Change it!')}}",
                cancelButtonText: "{{__('No, cancel!')}}",
                reverseButtons: true
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('organization.support-ticket.changeTicketStatus') }}",
                        data: {"status": status_value, "id": id, "_token": "{{ csrf_token() }}",},
                        datatype: "json",
                        success: function (data) {
                            console.log(data);
                            toastr.success('', "{{ __('Status has been updated') }}");
                        },
                        error: function () {
                            alert("Error!");
                        },
                    });
                } else if (result.dismiss === "cancel") {
                }
            });
        });
    </script>
    <script>
         $('#support-tickets-table').DataTable({
    "paging": false,
    "info": false,
    // searching: false,
    // "select":t/rue,
    language: {
        searchPlaceholder: "Type..."
    }
});
    </script>
@endpush
