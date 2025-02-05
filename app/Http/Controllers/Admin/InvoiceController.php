<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkOrder;
use App\Models\CustomerInvoice;
use App\Constants\Status;
use App\Models\InvoiceProduct;
use App\Models\CustomerInvoiceLog;

class InvoiceController extends Controller
{
    public function stageStatusClosedNeedsApproval($id)
    {
        $invoice = WorkOrder::find($id);
        $invoice->stage = Status::STAGE_CLOSED;
        $invoice->status = Status::NEEDS_APPROVAL;
        $invoice->save();

        $action = "Closed";
        $changes = "Closed Needs Approval";

        invoiceLog($id, $action, $changes);

        $notify[] = ['success', 'Invoice updated successfully'];
        return to_route('customer.invoice.history')->withNotify($notify);
    }

    public function stageStatusBillingInvoiced($id)
    {
        $invoice = WorkOrder::find($id);
        $invoice->stage = Status::STAGE_BILLING;
        $invoice->status = Status::INVOICED;
        $invoice->save();

        $action = "Invoice";
        $changes = "Billing Invoice";

        invoiceLog($id, $action, $changes);
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

        $action = "Paid";
        $changes = "Billing Paid " ."Reference code: " .$request->reference_code;

        invoiceLog($id, $action, $changes);

        return response()->json(['message' => 'Invoice updated successfully']);
    }

    public function revert($id)
    {
        $invoice = WorkOrder::find($id);

        $invoice->stage = Status::STAGE_BILLING;
        $invoice->status = Status::APPROVED;
        $invoice->save();

        $action = "Revert";
        $changes = "Billing Approved";

        invoiceLog($id, $action, $changes);

        $notify[] = ['success', 'Revert successfully'];
        return back()->withNotify($notify);
    }

    public function updateInvoiceOverview(Request $request, $id)
    {
        $invoice = CustomerInvoice::where('work_order_id', $id)->first();
    
        $invoice->job = $request->job;
        $invoice->date = $request->date;
        $invoice->p_o = $request->p_o;
        $invoice->terms = $request->terms;
    
        $invoice->save();
  
        $action = "Edit";
        $changes = "Manual entry" . 
                   " Job: " ."P" . ($request->job ?? '') . 
                   " Date: " . ($request->date ?? '') . 
                   " Purchase order: " . ($request->p_o ?? '') . 
                   " Term: " . ($request->terms ?? '');

        invoiceLog($id, $action, $changes);

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }
    

    public function updateWoReq(Request $request, $id)
    {
        $invoice = CustomerInvoice::where('work_order_id', $id)->first();

        $invoice->wo_req = $request->wo_req;

        $invoice->save();
        $action = "Edit";
        $changes = "Manual edit work requested: " . "-" . $request->wo_req;

        invoiceLog($id, $action, $changes);

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function updateWoPer(Request $request, $id)
    {
        $invoice = CustomerInvoice::where('work_order_id', $id)->first();

        $invoice->wo_per = $request->wo_per;

        $invoice->save();
        $action = "Edit";
        $changes = "Manual edit work performed: " . "-" . $request->wo_per;

        invoiceLog($id, $action, $changes);

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function updateInvoicePay(Request $request, $id)
    {
        $invoice = CustomerInvoice::where('work_order_id', $id)->first();

        $invoice->tax = $request->tax;
        $invoice->shipping = $request->shipping;
        $invoice->credit = $request->credit;

        $invoice->save();

        $action = "Edit";
        $changes = "Manual entry" . " Sales tax " ."$" . ($request->tax ?? '') . " Shipping cost " ."$" . ($request->shipping ?? '') . " Credit " ."$" . ($request->credit ?? '');

        invoiceLog($id, $action, $changes);

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function updateSiteNumber(Request $request, $id)
    {
        $invoice = CustomerInvoice::where('work_order_id', $id)->first();

        $invoice->site_num = $request->site_num;

        $invoice->save();

        $action = "Edit";
        $changes = "Manual edit customer site" . "-" . $request->site_num;

        invoiceLog($id, $action, $changes);

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function updateFirstHourProduct(Request $request, $woId)
    {
        $existInvoiceProduct = InvoiceProduct::where('wo_id', $woId)->where('is_primary', 1)->first();

        if ($existInvoiceProduct) {
            $existInvoiceProduct->wo_id = $woId;
            $existInvoiceProduct->qty = 1;
            $existInvoiceProduct->desc = $request->desc;
            $existInvoiceProduct->price = $request->price;
            $existInvoiceProduct->is_primary = 1;

            $existInvoiceProduct->save();
            $action = "Edit";
            $changes = "Manual edit first hour" . " Description " . ($request->desc ?? '') . " Price: " ."$" . ($request->price ?? '');

            invoiceLog($woId, $action, $changes);
        } else {
            $invoiceProduct = new InvoiceProduct();

            $invoiceProduct->wo_id = $woId;
            $invoiceProduct->qty = 1;
            $invoiceProduct->desc = $request->desc;
            $invoiceProduct->price = $request->price;
            $invoiceProduct->is_primary = 1;

            $invoiceProduct->save();
            $action = "Created";
            $changes = "Manual created first hour" . "-" . ($request->desc ?? '') . "Price: " ."$" . ($request->price ?? '');

            invoiceLog($woId, $action, $changes);
        }

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function updateAdditionalHourProduct(Request $request, $woId)
    {
        $existInvoiceProduct = InvoiceProduct::where('wo_id', $woId)->where('is_additional', 1)->first();

        if ($existInvoiceProduct) {
            $existInvoiceProduct->wo_id = $woId;
            $existInvoiceProduct->qty = $request->qty;
            $existInvoiceProduct->desc = $request->desc;
            $existInvoiceProduct->price = $request->price;
            $existInvoiceProduct->is_additional = 1;

            $existInvoiceProduct->save();
            $action = "Edit";
            $changes = "Manual edit additional hour " . "Quantity" . $request->qty ?? '' . "Description" . $request->desc ?? '' . "Price: " ."$" . $request->price ?? '';

            invoiceLog($woId, $action, $changes);
        } else {
            $invoiceProduct = new InvoiceProduct();

            $invoiceProduct->wo_id = $woId;
            $invoiceProduct->qty = $request->qty;
            $invoiceProduct->desc = $request->desc;
            $invoiceProduct->price = $request->price;
            $invoiceProduct->is_additional = 1;

            $invoiceProduct->save();
            $action = "Edit";
            $changes = "Manual created additional hour" . "Quantity" . $request->qty ?? ''  . "Description" . $request->desc ?? '' . "Price: " ."$" . $request->price ?? '';

            invoiceLog($woId, $action, $changes);
        }

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function viewInvoiceLogs($id)
    {
        $pageTitle = "Invoice Logs";
        $invoice = $id;
        $logs = CustomerInvoiceLog::where('wo_id', $id)
            ->with('user')
            ->latest()->paginate(8); 

        return view('admin.customers.invoices.logs', compact('pageTitle','logs','invoice'));
    }

    public function extraHourProduct(Request $request, $woId)
    {
        $qty = $request->input('qty');
        $desc = $request->input('desc');
        $price = $request->input('price');

        $productsArray = [];
        foreach ($qty as $index => $quantity) {
            $productsArray[] = [
                'qty' => $quantity,
                'desc' => $desc[$index],
                'price' => $price[$index],
            ];
        }

        if (!empty($productsArray)) 
        {
            foreach($productsArray as $product)
            {
                $invoiceProduct = new InvoiceProduct();

                $invoiceProduct->wo_id = $woId;
                $invoiceProduct->qty = $product['qty'];
                $invoiceProduct->desc = $product['desc'];
                $invoiceProduct->price = $product['price'];
        
                $invoiceProduct->save();
            }
        } 

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function updateExtraHour(Request $request, $id)
    {

        $invoiceProduct = InvoiceProduct::find($id);

        $invoiceProduct->qty = $request->qty;
        $invoiceProduct->desc = $request->desc;
        $invoiceProduct->price = $request->price;

        $invoiceProduct->save();

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }
}
