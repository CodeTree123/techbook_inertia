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
        $changes = "Billing Paid " . "Reference code: " . $request->reference_code;

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

        $action = "Edit";
        $changes = sprintf(
            "Manual entry: Job: P%s, Date: %s, PO: %s, Term: %s | Previous: P%s, %s, %s, %s",
            $request->job ?? '',
            $request->date ?? '',
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

        $action = "Edit";
        $changes = sprintf("Manual entry: Work requested: %s | Previous: %s", $request->wo_req ?? '', $woReq ?? '');
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

        $action = "Edit";
        $changes = sprintf(
            "Manual entry: Work performed: %s | Previous: %s",
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

        $action = "Edit";
        $changes = sprintf(
            "Manual entry: Tax: P%s, Shipping: %s, PO: %s, Credit: %s | Previous: P%s, %s, %s, %s",
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
        $invoice = CustomerInvoice::where('work_order_id.site', $id)->first();

        $siteNum = $invoice->site_num ?? optional($invoice->workOrder->site)->site_id;

        $invoice->site_num = $request->site_num;

        $invoice->save();

        $action = "Edit";
        $changes = sprintf(
            "Manual entry: Site number: %s | Previous: %s",
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
            $existInvoiceProduct->price = $request->price;
            $existInvoiceProduct->is_primary = 1;

            $existInvoiceProduct->save();
            $action = "Edit";
            $changes = sprintf(
                "Manual entry: Description: %s, Price: %s | Previous: %s, %s",
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
            $invoiceProduct->price = $request->price;
            $invoiceProduct->is_primary = 1;

            $invoiceProduct->save();
            $action = "Created";
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
            $existInvoiceProduct->price = $request->price;
            $existInvoiceProduct->is_additional = 1;

            $existInvoiceProduct->save();
            $action = "Edit";
            $changes = sprintf(
                "Manual entry: Qty: %s, Description: %s, Price: %s | Previous: %s, %s, %s",
                $request->qty ?? '',
                $request->desc ?? '',
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
            $invoiceProduct->price = $request->price;
            $invoiceProduct->is_additional = 1;

            $invoiceProduct->save();
            $action = "Created";
            $changes = "Manual created additional hour" . " Quantity " . ($request->qty ?? '')  . " Description " . ($request->desc ?? '') . " Price: " . "$" . ($request->price ?? '');

            invoiceLog($woId, $action, $changes);
        }

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function getLogs($id,$page)
    {
        $logs = CustomerInvoiceLog::where('wo_id', $id)->with('user')
        ->latest()->paginate(5, ['*'], 'page', $page);
        return view('admin.customers.invoices.logs', compact('logs'))->render();
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

        if (!empty($productsArray)) {
            foreach ($productsArray as $product) {
                $invoiceProduct = new InvoiceProduct();

                $invoiceProduct->wo_id = $woId;
                $invoiceProduct->qty = $product['qty'];
                $invoiceProduct->desc = $product['desc'];
                $invoiceProduct->price = $product['price'];

                $invoiceProduct->save();

                $action = "Created";
                $changes = "Manual created extra hour. Quantity: " . $product['qty'] . ", Description: " . $product['desc'] . ", Price: $" . $product['price'];
    
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

        $invoiceProduct->qty = $request->qty;
        $invoiceProduct->desc = $request->desc;
        $invoiceProduct->price = $request->price;

        $invoiceProduct->save();

        $action = "Edit";
        $changes = sprintf(
            "Manual entry: Qty: %s, Description: %s, Price: %s | Previous: %s, %s, %s",
            $request->qty ?? '',
            $request->desc ?? '',
            $request->price ?? '',
            $qty ?? '',
            $desc ?? '',
            $price ?? ''
        );

        invoiceLog($id, $action, $changes);

        $notify[] = ['success', 'Invoice Updated Successfully'];
        return back()->withNotify($notify);
    }
}
