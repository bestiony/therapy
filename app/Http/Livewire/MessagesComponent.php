<?php

namespace App\Http\Livewire;

use App\Models\Conversation;
use App\Models\Messages;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class MessagesComponent extends Component
{
    use WithFileUploads;
    public $selected_conversation;
    public $conversation;
    public $user_timezone;
    public $content;
    public $file;
    public $user;
    public $is_patient = false ;
    public function mount($selected_conversation, $user_timezone)
    {
        $this->user = auth()->user();
        if($this->user->role == USER_ROLE_STUDENT){
            $this->is_patient = true;
        }

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
    public function send_message()
    {
        $this->validate([
            'content' => 'required'
        ]);
        $fileName = null;
        if ($this->file) {
            $fileName = time() . '-' . rand(1, 10000) . '.' . $this->file->extension();
            $this->file->storeAs('uploads/conversations', $fileName, 'public');
            $fileName = 'storage/' . CONVERSATIONS_FILES_STORAGE . $fileName;
        }


        $last_message = $this->conversation->messages ? $this->conversation->messages->last() : null;
        $user_timezone = session('timezone');
        $now = Carbon::now($user_timezone);
        $online_since = $now->diffInSeconds($this->conversation->therapist->last_activity);

        if ((!$last_message || $last_message->is_seen) && $online_since > 300) {
            notify_user_about_chat_message($this->conversation, $this->user, $this->is_patient);
        }
        $message = Messages::create([
            'sender_id' => $this->user->id,
            'conversation_id' => $this->selected_conversation,
            'content' => $this->content,
            'file' => $fileName,
        ]);
        $this->reset('content');
    }
    public function stop_conversation(){

        $this->conversation->status = "inactive";
        $this->conversation->save();
        session()->flash('message', 'conversation statuse changed successfully');
    }
    public function resume_conversation(){

        $this->conversation->status = "active";
        $this->conversation->save();
        session()->flash('message', 'conversation statuse changed successfully');
    }
    public function render()
    {
        $data['reciever_name'] = '';
        if($this->conversation){
            if($this->is_patient){
                $data['reciever_name'] = $this->conversation->therapist->name;
            }else{
                $data['reciever_name'] = $this->conversation->patient->name;
            }
        }
        $data['current_conversation'] = Conversation::find($this->selected_conversation);
        $data['messages'] = Messages::whereConversationId($this->selected_conversation)->get();
        $data['messages']->each(function ($item, $key) {
            if ($item->sender_id != $this->user->id) {
                $item->is_seen = true;
                $item->update();
            }
        });

        return view('livewire.messages-component', $data);
    }
}
