<?php

namespace App\Http\Controllers;

use App\Mail\NewMessageFromInstructorMail;
use App\Mail\NewMessageFromStudentMail;
use App\Models\Conversation;
use App\Models\Messages;
use App\Models\User;
use App\Traits\General;
use App\Traits\SendNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MessagesController extends Controller
{
    use General;
    use SendNotification;
    public  $views = [USER_ROLE_INSTRUCTOR => 'instructor', USER_ROLE_ORGANIZATION => 'organization'];

    public function patient_index(Request $request){
        $selected_conversation = $request->convo;

        $user = auth()->user();
        if(!in_array($user->role , [USER_ROLE_STUDENT , USER_ROLE_INSTRUCTOR])){
            return back();
        }
        $conversation = Conversation::find($selected_conversation);
        if ($conversation) {
            if ($conversation->patient_id != $user->id) {
                $this->showToastrMessage('warning', 'you are not a part of this conversation');
                return back();
            }
        }
        $data['conversations'] = Conversation::with(['messages','therapist','patient','order'])->wherePatientId($user->id)->orderBy('id','desc')->get();
        $data['selected_conversation'] = $selected_conversation;

        // $data['current_conversation'] = Conversation::find($selected_conversation);
        // $data['messages'] = Messages::whereConversationId($selected_conversation)->get();
        // $data['messages']->each(function($item, $key) use($user){
        //     if($item->sender_id != $user->id){
        //         $item->is_seen = true;
        //         $item->update();
        //     }
        // });
        $data['user'] = $user;
        return view('frontend.student.messages.index',$data);
    }
    public function patient_sends_message(Request $request){
        $user = auth()->user();
        $file = $request->file('shared_file');

        $request->validate([
            'content'=>'required',
            'conversation_id'=>'required',
        ],[
            'content'=>'the message content is required',
            'conversation_id'=>'please choose a conversation first',
        ]);

        $conversation = Conversation::where('id',$request->conversation_id)->wherePatientId($user->id)->whereStatus('active')->first();
        if(!$conversation){
        $this->showToastrMessage('warning','conversation was paused by the instructor');

            return back();
        }
        $fileName = null;
        if($file){
            $fileName =time().'-'.rand(1,10000).'.'.$file->extension();
            $file->storeAs('uploads/conversations', $fileName , 'public' );
            $fileName = 'storage/'. CONVERSATIONS_FILES_STORAGE . $fileName;
        }
        $last_message = $conversation->messages->last();
            if(!$last_message || $last_message->is_seen){
            notify_user_about_chat_message($conversation, $user, true);
                // $text = 'you have a new message from ' . $user->name ;
                // $url = route('instructor.messages',['convo'=>$conversation->id]);
                // $reciever = $conversation->therapist_id;
                // $this->send($text, USER_ROLE_INSTRUCTOR,$url, $reciever);
                // $email_data =[
                //     'email_title'=>'New Message from '. $user->name .' on '. get_option('app_name'),
                //     'sender_name' => $user->name,
                //     'user_name'=>User::find($reciever)->name,
                //     'conversation_id'=>$conversation->id,
                // ];
                // Mail::to(User::find($reciever))->send(new NewMessageFromStudentMail($email_data));
        }
        $message = Messages::create([
            'sender_id'=> $user->id,
            'conversation_id'=> $conversation->id,
            'content'=> $request->content,
            'file'=>$fileName,
        ]);
        return redirect()->route('student.messages',['convo'=>$conversation->id]);
    }

    public function therapist_index(Request $request){
        $selected_conversation = $request->convo;
        $user = auth()->user();
        if( !in_array( $user->role ,[USER_ROLE_INSTRUCTOR, USER_ROLE_ORGANIZATION] )){
            return back();
        }
        $conversation = Conversation::where('id', $request->conversation_id)->wherePatientId($user->id)->whereStatus('active')->first();
        if ($conversation) {
            if ($conversation->therapist_id != $user->id) {
                $this->showToastrMessage('warning', 'you are not a part of this conversation');
                return back();
            }
        }
        $data['conversations'] = Conversation::with(['messages','therapist','patient','order'])->whereTherapistId($user->id)->orderBy('id','desc')->get();
        $data['selected_conversation'] = $selected_conversation;
        $data['current_conversation'] = Conversation::find($selected_conversation);

        $data['messages'] = Messages::whereConversationId($selected_conversation)->get();
        $data['messages']->each(function($item, $key) use($user){
            if($item->sender_id != $user->id){
                $item->is_seen = true;
                $item->update();
            }
        });
        $data['user'] = $user;
        return view( $this->views[$user->role].'.messages.index',$data);
    }

    public function therapsit_sends_message(Request $request){
        $user = auth()->user();
        $file = $request->file('shared_file');

        $request->validate([
            'content'=>'required',
            'conversation_id'=>'required',
        ],[
            'content'=>'the message content is required',
            'conversation_id'=>'please choose a conversation first',
        ]);

        $conversation = Conversation::where('id',$request->conversation_id)->whereTherapistId($user->id)->whereStatus('active')->first();
        if(!$conversation){
            return back();
        }
        $fileName = null;
        if($file){
            $fileName =time().'-'.rand(1,10000).'.'.$file->extension();
            $file->storeAs('uploads/conversations', $fileName , 'public' );
            $fileName = 'storage/'. CONVERSATIONS_FILES_STORAGE . $fileName;
        }
        $last_message = $conversation->messages->last();
            if(!$last_message || $last_message->is_seen){
                $text = 'you have a new message from ' . $user->name ;
                $url = route('student.messages',['convo'=>$conversation->id]);
                $reciever = $conversation->patient_id;
                $this->send($text, USER_ROLE_STUDENT,$url, $reciever);
                $email_data =[
                    'email_title'=>'New Message from '. $user->name .' on '. get_option('app_name'),
                    'sender_name' => $user->name,
                    'user_name'=>User::find($reciever)->name,
                    'conversation_id'=>$conversation->id,
                ];
                Mail::to(User::find($reciever))->send(new NewMessageFromInstructorMail($email_data));

        }
        $message = Messages::create([
            'sender_id'=> $user->id,
            'conversation_id'=> $conversation->id,
            'content'=> $request->content,
            'file'=>$fileName,
        ]);

        return redirect()->route($this->views[$user->role].'.messages',['convo'=>$conversation->id]);
    }

    public function therapsit_resume_conversation(Request $request ){
        if(!auth()->user()->role == USER_ROLE_ADMIN){

            $conversation = Conversation::whereTherapistId(auth()->user()->id)->whereId($request->conversation_id)->first();
        }else{
            $conversation = Conversation::find($request->conversation_id);
        }

        if(!$conversation){
            return back();
        }
        $conversation->status = "active";
        $conversation->save();
        $this->showToastrMessage('success','conversation was resumed successfully');
        return back();
    }

    public function therapsit_stop_conversation(Request $request ){
        if(!auth()->user()->role == USER_ROLE_ADMIN){

            $conversation = Conversation::whereTherapistId(auth()->user()->id)->whereId($request->conversation_id)->first();
        }else{
            $conversation = Conversation::find($request->conversation_id);
        }

        if(!$conversation){
            return back();
        }
        $conversation->status = "inactive";
        $conversation->save();
        $this->showToastrMessage('success','conversation was stopped successfully');
        return back();
    }

    public function admin_index(Request $request){
        $selected_conversation = $request->convo;
        $user = auth()->user();
        if(  $user->role != USER_ROLE_ADMIN ){
            return back();
        }
        $data['conversations'] = Conversation::with(['messages','therapist','patient','order'])->orderBy('id','desc')->get();
        $data['selected_conversation'] = $selected_conversation;
        $data['current_conversation'] = Conversation::find($selected_conversation);
        $data['messages'] = Messages::whereConversationId($selected_conversation)->get();
        $data['user'] = $user;
        return view('admin.messages.index',$data);
    }

    public function organization_index(Request $request){
        $user = auth()->user();
        if ($user->role !=  USER_ROLE_ORGANIZATION) {
            return back();
        }
        $selected_conversation = $request->convo;
        $user = auth()->user();
        if ($user->role != USER_ROLE_ORGANIZATION) {
            return back();
        }
        $org_therapists = $user->organization->instructors->pluck('user_id')->toArray();
        $data['conversations'] = Conversation::with(['messages', 'therapist', 'patient', 'order'])->whereIn('therapist_id', $org_therapists)->orderBy('id', 'desc')->get();
        $data['navInstructorActiveClass'] = 'has-open';

        $data['subNavInstructorMessagesActiveClass'] = 'active';
        $data['selected_conversation'] = $selected_conversation;
        $data['current_conversation'] = Conversation::find($selected_conversation);
        $data['messages'] = Messages::whereConversationId($selected_conversation)->get();
        $data['user'] = $user;
        return view('organization.messages.instructors_index', $data);
    }
}

