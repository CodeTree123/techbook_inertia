<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkOrder;
use App\Models\CustomerInvoice;
use App\Constants\Status;
use App\Models\InvoiceProduct;
use App\Models\CustomerInvoiceLog;
use Carbon\Carbon;
class InvoiceController extends Controller
{
    
    public function stageStatusBillingPastDue($id)
    {
        $invoice = WorkOrder::find($id);
        $invoice->stage = Status::STAGE_BILLING;
        $invoice->status = Status::PAST_DUE;
        $invoice->save();

        $action = "Invoice status Past due";
        $changes = "Changes to Billing Past Due | Previous: Billing Invoiced";

        invoiceLog($id, $action, $changes);

        $notify[] = ['success', 'Invoice updated successfully'];
        return to_route('customer.invoice.history')->withNotify($notify);
    }
    public function stageStatusClosedNeedsApproval($id)
    {
        $invoice = WorkOrder::find($id);
        $invoice->stage = Status::STAGE_CLOSED;
        $invoice->status = Status::NEEDS_APPROVAL;
        $invoice->save();

        $action = "Invoice status Revert";
        $changes = "Changes to Closed Needs Approval | Previous: Billing Approved";

        invoiceLog($id, $action, $changes);

        $notify[] = ['success', 'Invoice updated successfully'];
        return to_route('customer.invoice.history')->withNotify($notify);
    }

    public function stageStatusBillingInvoiced($id)
    {
        $invoice = WorkOrder::with('customer')->find($id);
        $term = $invoice->customer->billing_term;
        if(!empty($term)){
            $invoice->stage = Status::STAGE_BILLING;
            $invoice->status = Status::INVOICED;
            $invoice->save();
    
            $invoiceDate = CustomerInvoice::where('work_order_id', $id)->first();
            $invoiceDate->invoice_date = now();
            $invoiceDate->save();
    
            $action = "Status change to billing invoiced";
            $changes = "Changes to Billing Invoiced | Previous: Billing Approved";
    
            invoiceLog($id, $action, $changes);
            $notify[] = ['success', 'Invoice updated successfully'];
            return back()->withNotify($notify);
        }else{
            $notify[] = ['error', 'Please update billing term first!'];
            return back()->withNotify($notify);
        }
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

        $action = "Status change to billing paid";
        $changes = "Changes to Billing Paid | Previous: Billing Invoiced " . "Reference code: " . $request->reference_code;

        invoiceLog($id, $action, $changes);

        return response()->json(['message' => 'Invoice updated successfully']);
    }

    public function revert($id)
    {
        $invoice = WorkOrder::find($id);

        $invoice->stage = Status::STAGE_BILLING;
        $invoice->status = Status::APPROVED;
        $invoice->save();

        $action = "Invoice status Revert";
        $changes = "Changes to Billing Approved | Previous: Billing Invoiced";

        invoiceLog($id, $action, $changes);

        $notify[] = ['success', 'Revert successfully'];
        return back()->withNotify($notify);
    }

    public function updateInvoiceOverview(Request $request, $id)
    {
        $invoice = CustomerInvoice::with('workOrder')->where('work_order_id', $id)->first();

        if (!$invoice) {
            return back()->withErrors(['Invoice not found.']);
        }

        $job = $invoice->job ?? optional($invoice->workOrder)->priority;
        $date = $invoice->date;
        $pO = $invoice->p_o ?? optional($invoice->workOrder)->p_o;
        $term = $invoice->terms ?? optional($invoice->workOrder->customer)->billing_term;

        $invoice->job = $request->job;
        $invoice->date = $request->date;
        $invoice->p_o = $request->p_o;
        $invoice->terms = $request->terms;
        $invoice->save();

        $action = "Updated overview";
        $changes = sprintf(
            "Manual entry: Job: P%s, Date: %s, PO: %s, Term: %s | Previous: Job: P%s, Date: %s, PO: %s, Term: %s",
            $request->job ?? '',
            Carbon::parse($request->date)->setTimezone('America/Chicago')->format('m/d/Y') ?? '',
            $request->p_o ?? '',
            $request->terms ?? '',
            $job ?? '',
            $date ?? '',
            $pO ?? '',
            $term ?? ''
        );

        invoiceLog($id, $action, $changes);

        return back()->withNotify([['success', 'Invoice Updated Successfully']]);
    }



    public function updateWoReq(Request $request, $id)
    {
        $invoice = CustomerInvoice::where('work_order_id', $id)->first();

        $woReq = $invoice->wo_req ?? optional($invoice->workOrder)->wo_requested;

        $invoice->wo_req = $request->wo_req;

        $invoice->save();

        $action = "Updated work requested";
        $changes = sprintf("Manual entry: Work requested: %s | Previous: Work requested: %s", $request->wo_req ?? '', $woReq ?? '');
        invoiceLog($id, $action, $changes);

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function updateWoPer(Request $request, $id)
    {
        $invoice = CustomerInvoice::with('workOrder.notes')->where('work_order_id', $id)->first();

        if ($invoice->wo_per) {
            $woPer = $invoice->wo_per;
        } else {
            $woPerNote = $invoice->workOrder->notes->where('note_type', 'close_out_notes')->first();
            $woPer = $woPerNote ? $woPerNote->note : '';
        }

        $invoice->wo_per = $request->wo_per;
        $invoice->save();

        $action = "Updated work performed";
        $changes = sprintf(
            "Manual entry: Work performed: %s | Previous: Work performed: %s",
            $request->wo_per ?? '',
            $woPer ?? ''
        );

        invoiceLog($id, $action, $changes);

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }


    public function updateInvoicePay(Request $request, $id)
    {
        $invoice = CustomerInvoice::where('work_order_id', $id)->first();

        $tax = $invoice->tax ?? '';
        $shipping = $invoice->shipping ?? '';
        $credit = $invoice->credit ?? '';

        $invoice->tax = $request->tax;
        $invoice->shipping = $request->shipping;
        $invoice->credit = $request->credit;

        $invoice->save();

        $action = "Updated pay sheet";
        $changes = sprintf(
            "Manual entry: Tax: %s, Shipping: %s, Credit: %s | Previous: Tax: %s, Shipping: %s, Credit: %s",
            $request->tax ?? '',
            $request->shipping ?? '',
            $request->credit ?? '',
            $tax ?? '',
            $shipping ?? '',
            $credit ?? ''
        );

        invoiceLog($id, $action, $changes);

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function updateSiteNumber(Request $request, $id)
    {
        $invoice = CustomerInvoice::where('work_order_id', $id)->with('workOrder.site')->first();

        $siteNum = $invoice->site_num ?? optional($invoice->workOrder->site)->site_id;

        $invoice->site_num = $request->site_num;

        $invoice->save();

        $action = "Updated site number";
        $changes = sprintf(
            "Manual entry: Site number: %s | Previous: Site number: %s",
            $request->site_num ?? '',
            $siteNum ?? ''
        );

        invoiceLog($id, $action, $changes);

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function updateFirstHourProduct(Request $request, $woId)
    {
        $existInvoiceProduct = InvoiceProduct::with('workOrder.customer')->where('wo_id', $woId)->where('is_primary', 1)->first();

        if ($existInvoiceProduct) {

            $desc = $existInvoiceProduct->desc ?? '';

            $price = $existInvoiceProduct->price ?? optional($existInvoiceProduct->workOrder->customer)->s_rate_f;

            $existInvoiceProduct->wo_id = $woId;
            $existInvoiceProduct->qty = 1;
            $existInvoiceProduct->desc = $request->desc;
            $existInvoiceProduct->date = $request->date;
            $existInvoiceProduct->price = $request->price;
            $existInvoiceProduct->is_primary = 1;

            $existInvoiceProduct->save();
            $action = "Updated first hour rate";
            $changes = sprintf(
                "Manual entry: Description: %s, Price: %s | Previous: Description: %s, Price: %s",
                $request->desc ?? '',
                $request->price ?? '',
                $desc ?? '',
                $price ?? ''
            );

            invoiceLog($woId, $action, $changes);
        } else {
            $invoiceProduct = new InvoiceProduct();

            $invoiceProduct->wo_id = $woId;
            $invoiceProduct->qty = 1;
            $invoiceProduct->desc = $request->desc;
            $invoiceProduct->date = $request->date;
            $invoiceProduct->price = $request->price;
            $invoiceProduct->is_primary = 1;

            $invoiceProduct->save();
            $action = "Created first hour rate";
            $changes = "Manual created first hour" . "-" . ($request->desc ?? '') . "Price: " . "$" . ($request->price ?? '');

            invoiceLog($woId, $action, $changes);
        }

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function updateAdditionalHourProduct(Request $request, $woId)
    {
        $existInvoiceProduct = InvoiceProduct::with('workOrder.customer')->where('wo_id', $woId)->where('is_additional', 1)->first();

        if ($existInvoiceProduct) {

            $desc = $existInvoiceProduct->desc ?? '';
            $price = $existInvoiceProduct->price ?? optional($existInvoiceProduct->workOrder->customer)->s_rate_a;

            $existInvoiceProduct->wo_id = $woId;
            $existInvoiceProduct->qty = $request->qty;
            $existInvoiceProduct->desc = $request->desc;
            $existInvoiceProduct->date = $request->date;
            $existInvoiceProduct->price = $request->price;
            $existInvoiceProduct->is_additional = 1;

            $existInvoiceProduct->save();
            $action = "Updated additional hour rate";
            $changes = sprintf(
                "Manual entry: Qty: %s, Description: %s, Date: %s, Price: %s | Previous: Qty: %s, Description: %s, Price: %s",
                $request->qty ?? '',
                $request->desc ?? '',
                $request->date ?? '',
                $request->price ?? '',
                $existInvoiceProduct->qty ?? '',
                $desc ?? '',
                $price ?? ''
            );

            invoiceLog($woId, $action, $changes);
        } else {
            $invoiceProduct = new InvoiceProduct();

            $invoiceProduct->wo_id = $woId;
            $invoiceProduct->qty = $request->qty;
            $invoiceProduct->desc = $request->desc;
            $invoiceProduct->date = $request->date;
            $invoiceProduct->price = $request->price;
            $invoiceProduct->is_additional = 1;

            $invoiceProduct->save();
            $action = "Created additional hour rate";
            $changes = "Manual created additional hour" . " Quantity " . ($request->qty ?? '') . " Date " . ($request->date ?? '')  . " Description " . ($request->desc ?? '') . " Price: " . "$" . ($request->price ?? '');

            invoiceLog($woId, $action, $changes);
        }

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function deleteAdditionalHourProduct($invProId = null, $wo_id)
    {
        // dd($invProId , $wo_id);
        if ($invProId) {
            $invProduct = InvoiceProduct::find($invProId);

            $invProduct->soft_delete = 1;

            $invProduct->save();
        } else {
            $newInvProduct = new InvoiceProduct();

            $newInvProduct->wo_id = $wo_id;
            $newInvProduct->soft_delete = 1;
            $newInvProduct->is_additional = 1;

            $newInvProduct->save();
        }
        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    //invoice logs dynamic data
    public function getLogs($id, $page)
    {
        $logs = CustomerInvoiceLog::where('wo_id', $id)->with('user')
            ->latest()->paginate(10, ['*'], 'page', $page);
        return view('admin.customers.invoices.logs', compact('logs'))->render();
    }


    public function extraHourProduct(Request $request, $woId)
    {
        $qty = $request->input('qty');
        $desc = $request->input('desc');
        $date = $request->input('date');
        $price = $request->input('price');

        $productsArray = [];
        foreach ($qty as $index => $quantity) {
            $productsArray[] = [
                'qty' => $quantity,
                'desc' => $desc[$index],
                'date' => $date[$index],
                'price' => $price[$index],
            ];
        }

        if (!empty($productsArray)) {
            foreach ($productsArray as $product) {
                $invoiceProduct = new InvoiceProduct();

                $invoiceProduct->wo_id = $woId;
                $invoiceProduct->qty = $product['qty'];
                $invoiceProduct->desc = $product['desc'];
                $invoiceProduct->date = $product['date'];
                $invoiceProduct->price = $product['price'];

                $invoiceProduct->save();

                $action = "Created extra hour";
                $changes = "Manual created extra hour. Quantity: " . $product['qty'] . ", Description: " . $product['desc'] . $product['date'] . ", Price: $" . $product['price'];

                invoiceLog($woId, $action, $changes);
            }
        }

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function updateExtraHour(Request $request, $id)
    {

        $invoiceProduct = InvoiceProduct::find($id);

        $qty = $invoiceProduct->qty ?? '';
        $desc = $invoiceProduct->desc ?? '';
        $price = $invoiceProduct->price ?? '';
        $date = $invoiceProduct->date ?? '';

        $invoiceProduct->qty = $request->qty;
        $invoiceProduct->desc = $request->desc;
        $invoiceProduct->price = $request->price;
        $invoiceProduct->date = $request->date;

        $invoiceProduct->save();

        $action = "Updated extra hour";
        $changes = sprintf(
            "Manual entry: Qty: %s, Description: %s, Price: %s | Previous: Qty: %s, Description: %s, Price: %s",
            $request->qty ?? '',
            $request->desc ?? '',
            $request->price ?? '',
            $request->date ?? '',
            $qty ?? '',
            $desc ?? '',
            $price ?? ''
        );

        invoiceLog($id, $action, $changes);

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function deleteExtraHour($id)
    {

        $invoiceProduct = InvoiceProduct::find($id);

        $invoiceProduct->delete();

        $notify[] = ['success', 'Invoice Deleted Successfully'];
        return back()->withNotify($notify);
    }

}
