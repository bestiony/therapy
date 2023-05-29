<?php

namespace App\Http\Livewire;

use App\Models\Conversation;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ConversationsComponent extends Component
{
    use WithPagination;
    public $search;
    public $user;
    public $selected_conversation;
    public $user_timezone;
    public $conversation;
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function mount($selected_conversation, $user_timezone)
    {
        $this->user = auth()->user();

        $this->selected_conversation = $selected_conversation;
        $this->user_timezone = $user_timezone;
        $this->conversation = Conversation::where('id', $this->selected_conversation)
            ->first();
        // if ($this->is_patient) {
        //         ->wherePatientId($this->user->id)
        // } else {
        //     $this->conversation = Conversation::where('id', $this->selected_conversation)
        //         ->whereTherapistId($this->user->id)
        //         ->first();
        // }
    }
    public function render()
    {
        $search_term = '%' . $this->search . '%';
        $data['conversations'] = Conversation::with(['messages', 'therapist', 'patient', 'order'])
            ->select('conversations.*')
            ->leftJoin(DB::raw('(SELECT MAX(created_at) AS last_message_created_at, conversation_id FROM messages GROUP BY conversation_id) AS last_messages'), function ($join) {
                $join->on('conversations.id', '=', 'last_messages.conversation_id');
            })
            ->whereHas('therapist', function ($query) use ($search_term) {
                $query->where('name', 'like', $search_term)
                    ->orWhere('email', 'LIKE', $search_term);
            })
            ->orderByDesc('last_messages.last_message_created_at')
            ->paginate(7);
        return view('livewire.conversations-component', $data);
    }
}
