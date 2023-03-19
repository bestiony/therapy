<?php

namespace App\Http\Livewire;

use App\Models\Conversation;
use App\Models\Messages;
use Livewire\Component;

class MessagesComponent extends Component
{
    public $selected_conversation;
    public $conversation;
    public $user_timezone;
    public $content;
    public $file;
    public $user ;
    public function mount($selected_conversation, $user_timezone){
        $this->selected_conversation = $selected_conversation;
        $this->user_timezone = $user_timezone;
        $this->user = auth()->user();
        $this->conversation =Conversation::
                where('id',$this->selected_conversation)
                ->wherePatientId($this->user->id)
                ->whereStatus('active')
                ->first();
    }
    public function patient_sends_message(){
        $this->validate([
            'content'=>'required'
        ]);
        $fileName = null;
        if ($this->file) {
            $fileName = time() . '-' . rand(1, 10000) . '.' . $this->file->extension();
            $this->file->storeAs('uploads/conversations', $fileName, 'public');
            $fileName = 'storage/' . CONVERSATIONS_FILES_STORAGE . $fileName;
        }


        $last_message = $this->conversation->messages->last();
        if (!$last_message || $last_message->is_seen) {
            notify_therapist_about_patient_message($this->conversation, $this->user);
        }
        $message = Messages::create([
            'sender_id' => $this->user->id,
            'conversation_id' => $this->selected_conversation,
            'content' => $this->content,
            'file' => $fileName,
        ]);
        $this->reset('content');


    }
    public function render()
    {

        $data['current_conversation'] = Conversation::find($this->selected_conversation);
        $data['messages'] = Messages::whereConversationId($this->selected_conversation)->get();
        $data['messages']->each(function ($item, $key)  {
            if ($item->sender_id != $this->user->id) {
                $item->is_seen = true;
                $item->update();
            }
        });

        return view('livewire.messages-component',$data);
    }
}
