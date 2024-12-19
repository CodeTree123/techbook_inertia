<?php

namespace App\Http\Controllers\TechYeahTickets;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\Technician;
use App\Models\CustomerInvoice;
use App\Constants\Status;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    //open ticket
    public function openTicket()
    {
        $pageTitle = "Open Ticket";
        $tickets = WorkOrder::OpenTicket()->with('customer', 'technician')->searchable(['order_id'])->paginate(getPaginate());
        return $this->ticket($pageTitle, $tickets);
    }
    //dispatched ticket
    public function dispatchTicket()
    {
        $pageTitle = "Dispatched Ticket";
        $tickets = WorkOrder::DispatchedTicket()->with('customer', 'technician')->searchable(['order_id'])->paginate(getPaginate());
        return $this->ticket($pageTitle, $tickets);
    }
    //ticket onsite
    public function onsiteTicketView()
    {
        $pageTitle = "Onsite Ticket";
        $tickets = WorkOrder::OnsiteTicket()->with('customer', 'technician')->searchable(['order_id'])->paginate(getPaginate());
        return $this->ticket($pageTitle, $tickets);
    }
    public function onsiteTicket($id)
    {
        $status = WorkOrder::find($id);
        $status->status = Status::ONSITE;
        $status->save();
        $notify[] = ['success', 'Ticket is Onsite now!'];
        return redirect()->route('ticket.oTicket')->withNotify($notify);
    }
    //invoiced ticket
    public function invoicedTicketView()
    {
        $pageTitle = "Invoiced Ticket";
        $tickets = WorkOrder::InvoicedTicket()->with('customer', 'technician')->searchable(['order_id'])->paginate(getPaginate());
        return $this->ticket($pageTitle, $tickets);
    }
    public function invoicedTicket($id)
    {
        $status = WorkOrder::find($id);
        $status->status = Status::INVOICED;
        $status->save();
        $notify[] = ['success', 'Ticket is Invoiced now!'];
        return redirect()->route('ticket.iTicket')->withNotify($notify);
    }
    //Closed ticket
    public function closedTicketView()
    {
        $pageTitle = "Closed Ticket";
        $tickets = WorkOrder::ClosedTicket()->with('customer', 'technician')->searchable(['order_id'])->paginate(getPaginate());
        return $this->ticket($pageTitle, $tickets);
    }
    public function closedTicket($id)
    {
        $status = WorkOrder::find($id);
        $status->status = Status::CLOSED;
        $status->save();
        $tAvailable = Technician::find($status->ftech_id);
        $tAvailable->available = Status::ENABLE;
        $tAvailable->save();
        $paidI = CustomerInvoice::where('work_order_id', $status->id)->first();
        $paidI->status = Status::PAID;
        $paidI->save();
        $notify[] = ['success', 'Ticket is Closed now!'];
        return redirect()->route('ticket.cTicket')->withNotify($notify);
    }
    //hold ticket
    public function onHoldTicketView()
    {
        $pageTitle = "ON Hold Ticket";
        $tickets = WorkOrder::OnholdTicket()->with('customer', 'technician')->searchable(['order_id'])->paginate(getPaginate());
        return $this->ticket($pageTitle, $tickets);
    }
    public function onHoldTicket($id)
    {
        $status = WorkOrder::find($id);
        $status->status = Status::ON_HOLD;
        $status->save();
        $notify[] = ['success', 'Ticket is On Hold now!'];
        return redirect()->route('ticket.hTicket')->withNotify($notify);
    }
    //ticket blade
    private function ticket($pageTitle, $tickets)
    {
        return view('admin.tickets.ticket', compact('pageTitle', 'tickets'));
    }
}
