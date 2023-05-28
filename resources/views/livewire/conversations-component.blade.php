<section id="discussions" class="discussions col-12 col-sm-4  overflow-auto position-relative">
    <div class="discussion search ">
        <div class="searchbar">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input wire:model='search' type="text" placeholder="Search..."></input>
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
        <div class="discussion {{ $selected_conversation == $convo->id ? 'message-active' : '' }} position-relative">
            <div class="photo" style="background-image: url({{ asset($convo->patient->image) }});">
                {{-- <div class="online"></div> --}}
                <a class="stretched-link" href="{{ route('admin.messages', ['convo' => $convo->id]) }}"></a>
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
    <div class="d-flex justify-content-center">
        {{ $conversations->onEachSide(1)->links() }}
    </div>
</section>
