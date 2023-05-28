<section id="messages" class="chat col-12 col-sm-8  vh-100 align-items-end">
    <div class="header-chat col-12 justify-content-between pr-6">
        <div class="d-flex ">

            <i class="icon fa fa-user-o" aria-hidden="true"></i>
            <p class="name">{{ $reciever_name }}</p>
        </div>
        @if (!$is_patient)
            <i class="icon clickable fa fa-ellipsis-h mx-4 " aria-hidden="true" data-bs-toggle="dropdown"
                aria-expanded="false" wire:ignore></i>

            <ul class="dropdown-menu">

                <li>
                    @if (isset($current_conversation))
                        @if ($current_conversation->status == 'active')
                            <button wire:click='stop_conversation' class="dropdown-item" href="#">end
                                conversation</button>
                        @else
                            <button wire:click='resume_conversation' class="dropdown-item" href="#">resume
                                conversation</button>
                        @endif
                    @endif

                </li>

            </ul>
        @endif
        {{-- <i class="icon clickable fa fa-ellipsis-h mx-4 " aria-hidden="true" data-bs-toggle="dropdown"
                        aria-expanded="false"></i>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">book another session</a></li>

                    </ul> --}}
    </div>
    <div id="messages_container" class="messages-chat overflow-auto" wire:poll>

        @php
            $sender = 0;
            $previous_message_id = 1;
        @endphp
        @forelse($messages as $message)
            @if ($sender != $message->sender_id && $sender != 0)
                @php
                    $created_at = $messages->find($previous_message_id)->created_at;
                    $prevoius_message_time = Carbon\Carbon::parse($created_at)->timezone($user_timezone);
                    // $prevoius_message_time = Carbon\Carbon::createFromTimestamp()->timezone($user_timezone);
                    // $prevoius_message_time->setTimeZone($user_timezone);
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
                                href="{{ asset($message->file) }}"> <i class="fa fa-download" aria-hidden="true"></i>
                                downlaod file</a>
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
                                href="{{ asset($message->file) }}"> <i class="fa fa-download" aria-hidden="true"></i>
                                downlaod file</a>
                        </div>
                    @endif
                @else
                    <div class="message">
                        <div class="photo" style="background-image: url({{ asset($message->sender->image) }});">
                            {{-- <div class="online"></div> --}}
                        </div>
                        <p class="text"> {{ $message->content }} </p>
                    </div>
                    @if ($message->file)
                        <div class="message text-only">

                            <a download class=" text mt-2  round border border-dark d-block"
                                href="{{ asset($message->file) }}"> <i class="fa fa-download" aria-hidden="true"></i>
                                downlaod file</a>
                        </div>
                    @endif
                @endif
            @endif
            @if ($message->id == $messages->last()->id)
                <p class="time {{ $message->sender_id == $user->id ? 'text-end me-5' : 'text-start' }}">
                    {{ Carbon\Carbon::parse($message->created_at)->timezone($user_timezone) }}</p>
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
            <form enctype="multipart/form-data" wire:submit.prevent='send_message'
                action="{{ route('student.send_message') }}" method="POST" class="">
                @csrf
                <div class="footer-chat col-12 mt-10 p-20">
                    <label for="file-upload" class="custom-file-upload">
                        <i class="fa fa-cloud-upload">Upload</i>
                    </label>
                    <input wire:model='file' id="file-upload" type="file" name="shared_file" />
                    <input wire:model.defer='content' name="content" type="text" class="write-message col-10"
                        placeholder="Type your message here" required />
                    <button type="submit" class="ms-auto" id="submit_button">
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
    @if (session()->has('message'))
        @push('toastr_messages')
            <script>
                toastr.success("Comment successfully.")

            </script>
        @endpush
    @endif
    @push('script')
            <script>
                var messages_container = document.getElementById('messages_container');
                messages_container.scrollTop = messages_container.scrollHeight;
            </script>
    @endpush
</section>
