<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Technician;
use Illuminate\Http\Request;
use App\Constants\Status;

class DispatchTeamController extends Controller
{
    public function index($id)
    {
        $pageTitle = "Assign Technician";
        $workOrder = WorkOrder::with('site')->findOrFail($id);

        $fullAdress = [];
        $fullAdress[] = $workOrder->site->address_1;
        $fullAdress[] = $workOrder->site->city;
        $fullAdress[] = $workOrder->site->state;
        $addressString = implode(", ", $fullAdress);

        return view('admin.distanceMeasure.workOrderAssign', compact('pageTitle', 'addressString'));
    }

    public function assignTech(Request $request)
    {
        $orderId = $request->workOrderId;
        $workOrder = WorkOrder::find($orderId);
        $workOrder->ftech_id = $request->ftech_id;
        $workOrder->status = Status::DISPATCHED;
        $workOrder->save();
        $tAvailable = Technician::find($workOrder->ftech_id);
        $tAvailable->available = Status::DISABLE;
        $tAvailable->save();
        $notify[] = ['success', 'Technician Assigned!'];
        return redirect()->route('ticket.dTicket')->withNotify($notify);
    }
}
