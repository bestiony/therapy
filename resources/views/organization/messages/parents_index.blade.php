@extends('layouts.organization')

@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/messages.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
        <style>
            .discussions {
                box-shadow: none;
                max-height: 90% !important;
            }

            @media only screen and (max-width: 600px) {
                .send {
                    margin-left: 0px !important;
                }

            }
        </style>
        @livewireStyles
    @endpush
    @push('script')
        @livewireScripts
    @endpush
    <div class="inner-container min-vh-100">
        <div class="row flex-wrap vh-100">
            @php
                $user_timezone = session('timezone');
            @endphp

            <section id="discussions" class="discussions col-12 col-sm-4  overflow-auto position-relative">
                <div class="discussion search ">
                    <div class="searchbar">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <input type="text" placeholder="Search..."></input>
                    </div>
                </div>
                @forelse ($conversations as $convo)
                    @php
                        $latestMessage = $convo->messages->last();
                        $time = $latestMessage ? $latestMessage->created_at : false;
                        $now = Carbon\Carbon::now('Asia/Qatar');
                        $timeSinceLatestMessage = $time ? $now->diffForHumans($time, true) : '';
                        // dd($time);
                    @endphp
                    <div
                        class="discussion {{ $selected_conversation == $convo->id ? 'message-active' : '' }} position-relative">
                        <div class="photo" style="background-image: url({{ asset($convo->patient->image) }});">
                            {{-- <div class="online"></div> --}}
                            <a class="stretched-link"
                                href="{{ route('organization.organization_parents_messages_index', ['convo' => $convo->id]) }}"></a>
                        </div>
                        <div class="desc-contact">
                            <p class="name">{{ $convo->patient->name }}
                                @if ($convo->order)
                                    [O-{{ $convo->order->id }}]
                                @endif
                            </p>
                            <p class="message">{{ $latestMessage ? $latestMessage->content : '' }}</p>
                        </div>
                        @if ($time)
                            <div class="timer text-center">{{ $timeSinceLatestMessage }}</div>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-danger my-4">No conversations available </p>
                @endforelse

            </section>
            @livewire('messages-component', ['selected_conversation' => $selected_conversation, 'user_timezone' => $user_timezone])

            {{-- <section id="messages" class="chat col-12 col-sm-8  vh-100 align-items-end">
                <div class="header-chat col-12 justify-content-between pr-6">
                    <div class="d-flex ">

                        <i class="icon fa fa-user-o" aria-hidden="true"></i>
                        <p class="name">{{ $current_conversation ? $current_conversation->patient->name : '' }}</p>
                    </div>
                    <i class="icon clickable fa fa-ellipsis-h mx-4 " aria-hidden="true" data-bs-toggle="dropdown"
                        aria-expanded="false"></i>

                    <ul class="dropdown-menu">

                        <li>
                            @if (isset($current_conversation))
                                @if ($current_conversation->status == 'active')
                                    <form action="{{ route('organization.stop-conversation') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="conversation_id" value="{{ $selected_conversation }}">
                                        <button class="dropdown-item" href="#">stop conversation</button>
                                    </form>
                                @else
                                    <form action="{{ route('organization.resume-conversation') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="conversation_id" value="{{ $selected_conversation }}">
                                        <button class="dropdown-item" href="#">resume conversation</button>
                                    </form>
                                @endif
                            @endif

                        </li>

                    </ul>
                </div>
                <div id="messages_container" class="messages-chat overflow-auto  ">

                    @php
                        $sender = 0;
                        $prevoius_message_time = 0;
                    @endphp
                    @forelse($messages as $message)
                        @if ($sender != $message->sender_id && $sender != 0)
                            @php
                                $created_at = $messages->find($previous_message_id)->created_at;
                                $prevoius_message_time = Carbon\Carbon::parse($created_at)->timezone($user_timezone);
                            @endphp
                            <p class="time {{ $sender == $user->id ? 'text-end me-5' : 'text-start' }}">
                                {{ $prevoius_message_time }}</p>
                        @endif
                        @if ($message->sender_id == $user->id)
                            <div class="message text-only">
                                <div class="response">
                                    <p class="text">{{ $message->content }}</p>
                                    @if ($message->file)
                                        <a download class=" text mt-2  round border border-dark d-block"
                                            href="{{ asset($message->file) }}"> <i class="fa fa-download"
                                                aria-hidden="true"></i> downlaod file</a>
                                    @endif
                                </div>
                            </div>
                        @else
                            @if ($sender == $message->sender_id)
                                <div class="message text-only">
                                    <p class="text">{{ $message->content }}</p>
                                </div>
                                @if ($message->file)
                                    <div class="message text-only">
                                        <a download class=" text mt-2  round border border-dark d-block"
                                            href="{{ asset($message->file) }}"> <i class="fa fa-download"
                                                aria-hidden="true"></i> downlaod file</a>
                                    </div>
                                @endif
                            @else
                                <div class="message">
                                    <div class="photo"
                                        style="background-image: url({{ asset($message->sender->image) }});">
                                    </div>
                                    <p class="text"> {{ $message->content }} </p>
                                </div>
                                @if ($message->file)
                                    <div class="message text-only">

                                        <a download class=" text mt-2  round border border-dark d-block"
                                            href="{{ asset($message->file) }}"> <i class="fa fa-download"
                                                aria-hidden="true"></i> downlaod file</a>
                                    </div>
                                @endif
                            @endif
                        @endif
                        @if ($message->id == $messages->last()->id)
                            <p class="time {{ $message->sender_id == $user->id ? 'text-end me-5' : 'text-start' }}">
                                {{ $message->created_at->timezone($user_timezone) }}</p>
                        @endif
                        @php
                            $sender = $message->sender_id;
                            $previous_message_id = $message->id;

                        @endphp
                    @empty
                        <p class="text-center text-danger">No messages available </p>
                    @endforelse

                </div>
                @if (isset($current_conversation))
                    @if ($current_conversation->status == 'active')
                        <form enctype="multipart/form-data" action="{{ route('organization.send_message') }}" method="POST"
                            class="">
                            @csrf
                            <div class="footer-chat col-12 mt-10 p-20">
                                <input type="hidden" name="conversation_id" value="{{ $selected_conversation }}">
                                <label for="file-upload" class="custom-file-upload">
                                    <i class="fa fa-cloud-upload">Upload</i>
                                </label>
                                <input id="file-upload" type="file" name="shared_file" />
                                <input name="content" type="text" class="write-message col-10"
                                    placeholder="Type your message here" />
                                <button type="submit" class="ms-auto">
                                    <i class="icon send fa fa-paper-plane-o clickable   mb-2" aria-hidden="true"></i>
                                </button>
                            </div>
                        </form>
                    @elseif($current_conversation->status == 'completed')
                    <div class="alert alert-success text-center" role="alert">
                        this conversation has ended !
                    </div>
                    @else
                        <div class="alert alert-danger text-center" role="alert">
                            this conversation was paused by the instructor
                        </div>
                    @endif
                @endif
            </section> --}}


        </div>
    </div>
@endsection
