<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Mail\InstructorApprovedConsultationMail;
use App\Mail\InstructorEndedConsultation;
use App\Models\BookingHistory;
use App\Models\CertifiedParent;
use App\Models\ConsultationSlot;
use App\Models\Conversation;
use App\Models\Instructor;
use App\Models\InstructorConsultationDayStatus;
use App\Traits\General;
use App\Traits\SendNotification;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;

class ConsultationController extends Controller
{
    use General , SendNotification;
    public function dashboard()
    {
        $data['title'] = 'Consultation Dashboard';
        $data['navConsultationActiveClass'] = 'has-open';
        $data['subNavConsultationDashboardActiveClass'] = 'active';
        $data['instructor'] = Instructor::whereUserId(Auth::id())->first();
        return view('instructor.consultation.dashboard')->with($data);
    }

    public function parentAvailabilityStoreUpdate(Request $request)
    {
        $request->validate([
            'consultation_available' => 'required',
        ],);
        $instructor = CertifiedParent::whereUserId(Auth::id())->first();
        $instructor->consultation_available = $request->consultation_available;
        // $instructor->available_type = $request->available_type;
        // $instructor->hourly_rate = $request->hourly_rate;
        // $instructor->monthly_rate = $request->monthly_rate;
        // $instructor->hourly_old_rate = $request->hourly_old_rate;
        // $instructor->monthly_old_rate = $request->monthly_old_rate;
        // $instructor->availibity_range = $request->availibity_range;
        // $instructor->hours_per_month = $request->hours_per_month;
        // $instructor->booking_note = $request->booking_note;
        // $instructor->consultancy_area = $request->consultancy_area ?? 3;
        // $instructor->is_offline = $request->is_offline ?? 0;
        // $instructor->offline_message = $request->offline_message;
        // $instructor->is_subscription_enable = $request->is_subscription_enable ?? 0;
        $instructor->save();

        $this->showToastrMessage('success', __('Updated Successfully'));
        return redirect()->route('parent.dashboard');
    }

    public function slotStore(Request $request)
    {
        $count = ConsultationSlot::where('user_id', auth()->id())->count();
        if(hasLimitSaaS(PACKAGE_RULE_CONSULTANCY, PACKAGE_TYPE_SAAS_INSTRUCTOR, $count)){

            $request->validate([
                "starTimes"  => "required|min:1",
                "endTimes"  => "required|min:1",
            ]);

            $timeAddCheck = false;
            $startTimes = $request->starTimes;
            $endTimes = $request->endTimes;

            if (count($startTimes) == count($endTimes)) {
                /*
                * You can run any array of $startTime and $endTimes
                */

                foreach ($startTimes as $key => $value) {
                    $datetime1 = new DateTime(date('h:i:s A', strtotime($startTimes[$key])));
                    $datetime2 = new DateTime(date('h:i A', strtotime($endTimes[$key])));
                    $interval = $datetime1->diff($datetime2);
                    $hours = $interval->format('%h');
                    $minutes = $interval->format('%i');

                    $startTime =  date('h:i A', strtotime($startTimes[$key]));
                    $endTime =  date('h:i A', strtotime($endTimes[$key]));
                    $timeAddCheck = true;

                    $slot = new ConsultationSlot();
                    $slot->user_id = Auth::id();
                    $slot->time = $startTime . ' - ' . $endTime;
                    $slot->day = $request->day;
                    $slot->duration = $hours . ($hours > 1 ? " Hours " : " Hour ") . $minutes . ($minutes > 1 ? " Minutes" : " Minute") ;
                    $slot->hour_duration = $hours;
                    $slot->minute_duration = $minutes;
                    $slot->save();
                }
            }

        if ($timeAddCheck){
            $this->showToastrMessage('success', __('Slot Added successfully'));
        } else {
            $this->showToastrMessage('error', __('Something is wrong! Try again.'));
        }

            return redirect()->back();
        }
        else{
            $this->showToastrMessage('error', __('Your Consultancy Slot Create limit has been finish.'));
            return redirect()->back();
        }
    }

    public function slotView($day)
    {
        $data['slots'] = ConsultationSlot::whereUserId(Auth::id())->where('day', $day)->get();
        return view('instructor.consultation.partial.render-slot-list', $data);
    }

    public function slotDelete($id)
    {
        $slot = ConsultationSlot::whereUserId(Auth::id())->where('id', $id)->first();
        if (!$slot){
            return response()->json([
                'status' => 404,
                'msg' => __('Slot Not Found!')
            ]);
        }

        $slot->delete();
        return response()->json([
            'msg' => __('Deleted Successfully')
        ]);
    }

    public function dayAvailableStatusChange($day)
    {
        $item = InstructorConsultationDayStatus::whereUserId(Auth::id())->where('day', $day)->first();
        if (!$item){
            $item = new InstructorConsultationDayStatus();
            $item->user_id = Auth::id();
            $item->day = $day;
            $item->save();
        } else {
            $item->delete();
        }

        $this->showToastrMessage('success', __('Status Change Successfully'));
        return redirect()->back();
    }

    public function bookingRequest()
    {
        $data['title'] = 'Booking Request';
        $data['navConsultationActiveClass'] = 'has-open';
        $data['subNavBookingRequestActiveClass'] = 'active';
        $data['bookingHistories'] = BookingHistory::where('instructor_user_id', Auth::id())->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->pending()->orderBy('id','DESC')->paginate();

        return view('instructor.consultation.booking-request', $data);
    }

    public function cancelReason(Request $request, $uuid)
    {
        $request->validate([
            'cancel_reason' => 'required'
        ]);

        $booking = BookingHistory::where(['instructor_user_id' => Auth::id(), 'uuid'=> $uuid])->firstOrFail();
        $booking->cancel_reason =  $request->cancel_reason;
        $booking->status = 2;
        $booking->save();

        $text = __("Your consultation booking request cancelled");
        $target_url = route('student.my-consultation');
        $this->send($text, 3, $target_url, $booking->student_user_id);

        $this->showToastrMessage('success', __('Status Change Successfully'));
        return redirect()->back();
    }

    public function bookingHistory(Request $request)
    {
        $data['title'] = 'Booking History';
        $data['navConsultationActiveClass'] = 'has-open';
        $data['subNavBookingHistoryActiveClass'] = 'active';

        if($request->completed) {
            $data['completedActive'] = 'active';
            $data['completedShowActive'] = 'show active';
        } elseif ($request->cancelled) {
            $data['cancelledActive'] = 'active';
            $data['cancelledShowActive'] = 'show active';
        } else {
            $data['upcomingActive'] = 'active';
            $data['upcomingShowActive'] = 'show active';
        }
        $data['bookingHistoryUpcoming'] = BookingHistory::where('instructor_user_id', Auth::id())->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->approved()->orderBy('id','DESC')->paginate(15, ['*'], 'upcoming');

        $data['bookingHistoryCompleted'] = BookingHistory::where('instructor_user_id', Auth::id())->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->completed()->orderBy('id','DESC')->paginate(15, ['*'], 'completed');

        $data['bookingHistoryCancelled'] = BookingHistory::where('instructor_user_id', Auth::id())->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->cancelled()->orderBy('id','DESC')->paginate(15, ['*'], 'cancelled');

        return view('instructor.consultation.booking-history', $data);
    }

    public function bookingStatus($uuid, $status)
    {
        /*
         * 0=pending
         * 1=Approve
         * 2=Cancel
         * 3=Completed
         */

        $booking = BookingHistory::where('uuid', $uuid)->firstOrFail();
        $booking->status = $status;
        $booking->save();
        if($status == STATUS_APPROVED){
            $conversation  = Conversation::create([
                'patient_id'=>$booking->student_user_id ,
                'therapist_id'=>$booking->instructor_user_id,
                'order_id'=>$booking->order_id,
            ]);
            $text = "Your Consultation booking request was approved by the instructor";
            $target_url = route('student.my-consultation');
            $this->send($text, 3, $target_url, $booking->student_user_id);
            $email_data =[
                'email_title'=>'Consultation Approved by '. $booking->instructorUser->name .' on '. get_option('app_name'),
                'sender_name' => $booking->instructorUser->name,
                'user_name'=>$booking->user->name,
                'conversation_id'=>$conversation->id,
            ];
            Mail::to($booking->user)->send(new InstructorApprovedConsultationMail($email_data));
        }elseif($status == BOOKING_HISTORY_STATUS_COMPLETED){
            $conversation = Conversation::whereOrderId($booking->order_id)->first();
            if($conversation){
                $conversation->status = "completed";
                $conversation->save();
            }
            $email_data =[
                'email_title'=>'Consultation Ended by '. $booking->instructorUser->name .' on '. get_option('app_name'),
                'sender_name' => $booking->instructorUser->name,
                'user_name'=>$booking->user->name,
            ];
            Mail::to($booking->user)->send(new InstructorEndedConsultation($email_data));
        }
        $this->showToastrMessage('success', __('Status Change Successfully'));
        return response()->json([
            'msg' => 'success',
            'redirect' => route('instructor.messages', ['convo'=>$conversation->id]),

        ]);
    }

    public function bookingMeetingStore(Request $request, $uuid)
    {
        $request->validate([
            'moderator_pw' => 'nullable|min:6',
            'attendee_pw' => 'nullable|min:6',
        ]);

        $booking = BookingHistory::where('uuid', $uuid)->firstOrFail();
        $booking->start_url = $request->start_url;
        $booking->join_url = $request->join_url;
        $booking->meeting_id = $request->meeting_host_name == 'jitsi' ? $request->jitsi_meeting_id : $booking->id . rand();
        $booking->meeting_password = $request->meeting_password;
        $booking->meeting_host_name = $request->meeting_host_name;
        $booking->moderator_pw = $request->moderator_pw;
        $booking->attendee_pw = $request->attendee_pw;
        $booking->save();

        $this->showToastrMessage('success', __('Meeting Create  Successfully'));
        return redirect()->back();
    }

    public function jitsiJoinMeeting($uuid)
    {
        $data['title'] = 'Jitsi Meet';
        $data['bookingHistory'] = BookingHistory::where('uuid', $uuid)->firstOrFail();
        return view('instructor.consultation.jitsi-consultation')->with($data);
    }

    public function studentBigBlueButtonJoinMeeting($id)
    {
        $bookingHistory = BookingHistory::find($id);
        if ($bookingHistory) {
            return redirect()->to(
                Bigbluebutton::join([
                    'meetingID' => $bookingHistory->meeting_id,
                    'userName' => auth()->user()->student()->name ?? auth()->user()->name,
                    'password' => $bookingHistory->attendee_pw //which user role want to join set password here
                ])
            );
        } else {
            $this->showToastrMessage('error', __('Meet Link is not found'));
            return redirect()->back();
        }
    }

    public function instructorBigBlueButtonJoinMeeting($id)
    {
        $bookingHistory = BookingHistory::find($id);
        if ($bookingHistory){
            return redirect()->to(
                Bigbluebutton::join([
                    'meetingID' => $bookingHistory->meeting_id,
                    'userName' => auth()->user()->instructor()->name ?? auth()->user()->student()->name ?? auth()->user()->name,
                    'password' => $bookingHistory->attendee_pw //which user role want to join set password here
                ])
            );
        } else {
            $this->showToastrMessage('error', __('Meet Link is not found'));
            return redirect()->back();
        }
    }
}