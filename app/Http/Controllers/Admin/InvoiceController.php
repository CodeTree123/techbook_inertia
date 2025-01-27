<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkOrder;
use App\Models\CustomerInvoice;
use App\Constants\Status;

class InvoiceController extends Controller
{
    public function stageStatusBillingInvoiced($id)
    {

        $invoice = WorkOrder::find($id);
        $invoice->stage = Status::STAGE_BILLING;
        $invoice->status = Status::INVOICED;
        $invoice->save();
        $notify[] = ['success', 'Invoice updated successfully'];
        return back()->withNotify($notify);
    }

    public function stageStatusBillingPaid(Request $request, $id)
    {
        $referCode = CustomerInvoice::where('work_order_id', $id)->first();
        $referCode->reference_code = $request->reference_code;
        $referCode->save();

        $invoice = WorkOrder::find($id);
        $invoice->stage = Status::STAGE_BILLING;
        $invoice->status = Status::PAID;
        $invoice->save();
        return response()->json(['message' => 'Invoice updated successfully']);
    }

    public function revert($id)
    {
        $invoice = WorkOrder::find($id);

        $invoice->stage = Status::STAGE_BILLING;
        $invoice->status = Status::APPROVED;
        $invoice->save();
        $notify[] = ['success', 'Revert successfully'];
        return back()->withNotify($notify);
    }
}
