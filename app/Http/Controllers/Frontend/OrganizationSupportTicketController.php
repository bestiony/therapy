<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\TicketRequest;
use App\Models\Organization;
use App\Models\SupportTicketQuestion;
use App\Models\Ticket;
use App\Models\TicketDepartment;
use App\Models\TicketMessages;
use App\Models\TicketPriority;
use App\Models\TicketRelatedService;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Illuminate\Http\Request;

class OrganizationSupportTicketController extends Controller
{
    use General, ImageSaveTrait;
    use SendNotification;
    public function supportTicketFAQ()
    {
        $data['pageTitle'] = 'Support Ticket';
        $data['metaData'] = staticMeta(9);
        $data['faqQuestions'] = SupportTicketQuestion::all();
        return view('frontend.student.support_ticket.organization.faq', $data);
    }

    public function create(Request $request, Organization $organization)
    {
        $data['pageTitle'] = 'Support Ticket';
        $data['metaData'] = staticMeta(9);
        $data['tickets'] = Ticket::where('user_id', auth()->id())->where('organization_user_id', $organization->user_id)->paginate();
        // $data['departments'] = TicketDepartment::all();
        // $data['priorities'] = TicketPriority::all();
        // $data['services'] = TicketRelatedService::all();
        $data['organization_instructors'] = $organization->instructors;
        $data['organization'] = $organization;
        $data['user'] = $organization->user;
        return view('frontend.student.support_ticket.organization.create-tickets', $data);
    }

    public function paginationFetchData(Request $request)
    {

        if ($request->ajax()) {
            $data['tickets'] = Ticket::where('user_id', auth()->id())->paginate();
            return view('frontend.student.support_ticket.organization.render-ticket-list')->with($data);
        }
    }

    public function store(TicketRequest $request, Organization $organization)
    {

        $ticket = new Ticket();
        $ticket->ticket_number = 100;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->subject = $request->subject;
        $ticket->instructor_user_id = $request->instructor_user_id;
        $ticket->organization_user_id = $organization->user_id;
        // $ticket->department_id = $request->department_id;
        // $ticket->related_service_id = $request->related_service_id;
        // $ticket->priority_id = $request->priority_id;
        $ticket->save();
        $ticket->ticket_number = $ticket->id + 100;
        $ticket->save();

        $message = new TicketMessages();
        $message->ticket_id = $ticket->id;
        $message->sender_user_id = auth()->id();
        $message->message = $request->message;

        if ($request->hasFile('file')) {
            $message->file = $this->saveImage('ticket_message', $request->file, 'null', 'null');
        }

        $message->save();
        // send notification to admin
        $text = 'new support ticket';
        $target_url = route('organization.support-ticket.show', $ticket->uuid);
        $this->send($text, 2 , $target_url, $organization->user_id);
        // end notification
        $this->showToastrMessage('success', __('Ticket Created Successfully'));
        return redirect()->back();
    }

    public function show($uuid)
    {
        $data['pageTitle'] = 'Support Ticket Details';
        $data['ticket'] = Ticket::where('uuid', $uuid)->firstOrFail();
        $data['ticketMessages'] = TicketMessages::where('ticket_id', $data['ticket']->id)->get();

        $data['last_message'] = TicketMessages::where('ticket_id', $data['ticket']->id)->whereNotNull('reply_admin_user_id')->latest()->first();

        return view('frontend.student.support_ticket.organization.ticket-details', $data);
    }

    public function messageStore(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'file' => 'mimes:jpeg,jpg,png,gif|max:10000'
        ]);

        $message = new TicketMessages();
        $message->ticket_id = $request->ticket_id;
        $message->sender_user_id = auth()->id();
        $message->message = $request->message;

        if ($request->hasFile('file')) {
            $message->file = $this->saveImage('ticket_message', $request->file, 'null', 'null');
        }

        $message->save();

        return redirect()->back();
    }
}
