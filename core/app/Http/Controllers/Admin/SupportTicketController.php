<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Traits\SupportTicketManager;

class SupportTicketController extends Controller
{
    use SupportTicketManager;

    public function __construct()
    {
        parent::__construct();
        $this->userType = 'admin';
        $this->column = 'admin_id';
        $this->user = auth()->guard('admin')->user();
    }

    protected function getTicketCounts()
    {
        return [
            'all'      => SupportTicket::count(),
            'pending'  => SupportTicket::whereIn('status', [Status::TICKET_OPEN, Status::TICKET_REPLY])->count(),
            'answered' => SupportTicket::where('status', Status::TICKET_ANSWER)->count(),
            'closed'   => SupportTicket::where('status', Status::TICKET_CLOSE)->count(),
            'urgent'   => SupportTicket::where('priority', Status::PRIORITY_HIGH)->where('status', '!=', Status::TICKET_CLOSE)->count(),
        ];
    }

    public function tickets()
    {
        $pageTitle = 'All Support Tickets';
        $activeTab = 'all';
        $counts = $this->getTicketCounts();
        $items = SupportTicket::searchable(['name','subject','ticket'])
            ->orderBy('id','desc')
            ->with('user')
            ->paginate(getPaginate());

        return view('admin.support.tickets', compact('items', 'pageTitle', 'counts', 'activeTab'));
    }

    public function pendingTicket()
    {
        $pageTitle = 'Pending Attention Support Tickets';
        $activeTab = 'pending';
        $counts = $this->getTicketCounts();
        $items = SupportTicket::searchable(['name','subject','ticket'])
            ->pending()
            ->orderBy('id','desc')
            ->with('user')
            ->paginate(getPaginate());

        return view('admin.support.tickets', compact('items', 'pageTitle', 'counts', 'activeTab'));
    }

    public function closedTicket()
    {
        $pageTitle = 'Resolved & Closed Support Tickets';
        $activeTab = 'closed';
        $counts = $this->getTicketCounts();
        $items = SupportTicket::searchable(['name','subject','ticket'])
            ->closed()
            ->orderBy('id','desc')
            ->with('user')
            ->paginate(getPaginate());

        return view('admin.support.tickets', compact('items', 'pageTitle', 'counts', 'activeTab'));
    }

    public function answeredTicket()
    {
        $pageTitle = 'Answered Support Tickets';
        $activeTab = 'answered';
        $counts = $this->getTicketCounts();
        $items = SupportTicket::searchable(['name','subject','ticket'])
            ->answered()
            ->orderBy('id','desc')
            ->with('user')
            ->paginate(getPaginate());

        return view('admin.support.tickets', compact('items', 'pageTitle', 'counts', 'activeTab'));
    }

    public function ticketReply($id)
    {
        $ticket = SupportTicket::with('user')->where('id', $id)->firstOrFail();
        $pageTitle = 'Reply Ticket';
        $messages = SupportMessage::with('ticket','admin','attachments')->where('support_ticket_id', $ticket->id)->orderBy('id','desc')->get();
        return view('admin.support.reply', compact('ticket', 'messages', 'pageTitle'));
    }

    public function ticketDelete($id)
    {
        $message = SupportMessage::findOrFail($id);
        $path = getFilePath('ticket');
        if ($message->attachments()->count() > 0) {
            foreach ($message->attachments as $attachment) {
                fileManager()->removeFile($path.'/'.$attachment->attachment);
                $attachment->delete();
            }
        }
        $message->delete();
        $notify[] = ['success', "Support ticket deleted successfully"];
        return back()->withNotify($notify);

    }

}
