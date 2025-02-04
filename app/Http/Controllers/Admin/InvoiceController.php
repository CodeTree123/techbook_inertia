<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkOrder;
use App\Models\CustomerInvoice;
use App\Constants\Status;
use App\Models\InvoiceProduct;

class InvoiceController extends Controller
{
    public function stageStatusClosedNeedsApproval($id)
    {
        $invoice = WorkOrder::find($id);
        $invoice->stage = Status::STAGE_CLOSED;
        $invoice->status = Status::NEEDS_APPROVAL;
        $invoice->save();
        $notify[] = ['success', 'Invoice updated successfully'];
        return to_route('customer.invoice.history')->withNotify($notify);
    }
    
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

    public function updateInvoiceOverview(Request $request, $id)
    {
        $invoice = CustomerInvoice::where('work_order_id',$id)->first();

        $invoice->job = $request->job;
        $invoice->date = $request->date;
        $invoice->p_o = $request->p_o;
        $invoice->terms = $request->terms;

        $invoice->save();

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function updateWoReq(Request $request, $id)
    {
        $invoice = CustomerInvoice::where('work_order_id',$id)->first();

        $invoice->wo_req = $request->wo_req;

        $invoice->save();

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function updateWoPer(Request $request, $id)
    {
        $invoice = CustomerInvoice::where('work_order_id',$id)->first();

        $invoice->wo_per = $request->wo_per;

        $invoice->save();

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function updateInvoicePay(Request $request, $id)
    {
        $invoice = CustomerInvoice::where('work_order_id',$id)->first();

        $invoice->tax = $request->tax;
        $invoice->shipping = $request->shipping;
        $invoice->credit = $request->credit;

        $invoice->save();

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function updateSiteNumber(Request $request, $id)
    {
        $invoice = CustomerInvoice::where('work_order_id',$id)->first();

        $invoice->site_num = $request->site_num;

        $invoice->save();

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function updateFirstHourProduct(Request $request, $woId)
    {
        $existInvoiceProduct = InvoiceProduct::where('wo_id',$woId)->where('is_primary',1)->first();

        if($existInvoiceProduct)
        {
            $existInvoiceProduct->wo_id = $woId;
            $existInvoiceProduct->qty = 1;
            $existInvoiceProduct->desc = $request->desc;
            $existInvoiceProduct->price = $request->price;
            $existInvoiceProduct->is_primary = 1;

            $existInvoiceProduct->save();
        }else{
            $invoiceProduct = new InvoiceProduct();

            $invoiceProduct->wo_id = $woId;
            $invoiceProduct->qty = 1;
            $invoiceProduct->desc = $request->desc;
            $invoiceProduct->price = $request->price;
            $invoiceProduct->is_primary = 1;

            $invoiceProduct->save();
        }

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function updateAdditionalHourProduct(Request $request, $woId)
    {
        $existInvoiceProduct = InvoiceProduct::where('wo_id',$woId)->where('is_additional',1)->first();

        if($existInvoiceProduct)
        {
            $existInvoiceProduct->wo_id = $woId;
            $existInvoiceProduct->qty = $request->qty;
            $existInvoiceProduct->desc = $request->desc;
            $existInvoiceProduct->price = $request->price;
            $existInvoiceProduct->is_additional = 1;

            $existInvoiceProduct->save();
        }else{
            $invoiceProduct = new InvoiceProduct();

            $invoiceProduct->wo_id = $woId;
            $invoiceProduct->qty = $request->qty;
            $invoiceProduct->desc = $request->desc;
            $invoiceProduct->price = $request->price;
            $invoiceProduct->is_additional = 1;

            $invoiceProduct->save();
        }

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }
}
