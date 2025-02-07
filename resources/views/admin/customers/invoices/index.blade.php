@extends('admin.layoutsNew.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('assetsNew/dist/css/jodit.fat.min.css') }}">
    <div class="content-wrapper" style="background-color: white;">
        @include('admin.includeNew.breadcrumb')
        <style>
            @font-face {
                font-family: 'CustomFont';
                src: url('../../../../../public/assets/font/Cambria-Font-For-Windows.ttf') format('ttf'),
                    /* Modern browsers */
                    url('../../../../../public/assets/font/Cambria-Font-For-Windows.ttf') format('woff'),
                    /* Older browsers */
                    url('../../../../../public/assets/font/Cambria-Font-For-Windows.ttf') format('truetype');
                /* Fallback */
                font-weight: normal;
                font-style: normal;
            }

            .content-wrapper {
                background-color: white;
            }

            .page-break {
                page-break-before: always;
                break-before: page;
            }

            .row {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .col-md-3,
            .col-md-6 {
                flex: 1;
                /* Make the columns flexible */
                padding: 10px;
            }

            .col-md-3.text-left {
                text-align: left !important;
            }

            .col-md-3.text-right {
                text-align: right !important;
            }

            /* Table styling */
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }

            .bg-teal-table {
                background-color: rgba(175, 225, 175, 0.5);
            }

            #wo-desc,
            .wo-per {
                overflow-y: hidden;
                resize: none;
                padding: 8px;
                font-size: 16px;
                outline: 0;
            }

            .table tr {
                border-bottom: 1px solid #DEE2E6;
            }

            .table td,
            .table th {
                border-top: 0 !important;
                border-bottom: 0 !important;
            }

            #scope_work {
                /* display: none; */
                line-height: 20px !important;
            }

            #scope_work p {
                font-family: 'CustomFont' !important;
                margin: 0 !important;
                line-height: 20px !important;
            }

            .inv_note {
                font-family: 'CustomFont' !important;
                line-height: 20px !important;
            }

            .inv_note p {
                font-family: 'CustomFont' !important;
                margin: 0 !important;
                line-height: 20px !important;
            }

            .inv_note span {
                font-family: 'CustomFont' !important
            }

            .inv_note br {
                display: none
            }

            .info-table th,
            .info-table td,
            .price-table th,
            .price-table td {
                width: 20%;
            }


            /* Print styling to ensure everything fits */
            @media print {

                h1,
                h2,
                h3,
                h4,
                h5,
                h6,
                p,
                span,
                th,
                td,
                input,
                textarea {
                    font-family: 'CustomFont !important'
                }

                address {
                    margin-bottom: 0 !important;
                }

                h4 {
                    font-size: 18px !important;
                    line-height: 18px;
                }

                th {
                    font-size: 14px !important;
                    line-height: 14px;
                }

                .price-table td {
                    padding: 0 12px !important;
                }

                td,
                span,
                p,
                input,
                textarea {
                    font-size: 13px !important;
                    line-height: 13px !important;
                }

                address {
                    margin-bottom: 0 !important;
                }

                .card-header {
                    display: none !important;
                }

                .card-body {
                    padding: 0 !important;
                }

                .addRowBtnCont {
                    display: none !important;
                }

                .price-box td {
                    padding: 0 !important;
                    border: 0;
                }

                .mt-5 {
                    margin-top: 0px !important
                }

                .mb-3 {
                    margin-bottom: 0 !important
                }

                /* Remove unnecessary margins */
                .content-wrapper {
                    margin: 0;
                    padding: 0;
                }

                #wo-desc {
                    display: none;
                }

                #scope_work {
                    display: block;
                }

                .inv_note {
                    display: block;
                    font-family: 'CustomFont !important'
                }

                .jodit-container {
                    display: none;
                }


                /* Ensure the invoice fits on the page */
                body {
                    margin: 0;
                    padding: 0;
                }


                .wo-req,
                .wo-perform {
                    page-break-inside: auto;
                    page-break-before: auto;
                    page-break-after: auto;
                    margin-bottom: 10px;
                    height: fit-content !important;
                }

                .wo-calc {
                    page-break-inside: avoid;
                }

                */ .container-fluid {
                    padding-left: 0;
                    padding-right: 0;
                }

                /* Adjust spacing */
                .row {
                    margin: 0;
                    padding: 0;
                }

                /* Text alignment fixes */
                .text-left {
                    text-align: left !important;
                }

                .text-right {
                    text-align: right !important;
                }

                .text-center {
                    text-align: center !important;
                }

                table th,
                table td {
                    padding: 3px;
                    font-size: 10px;
                }

                input[type="text"] {
                    width: 80%;
                    padding: 3px;
                    font-size: 10px;
                    box-sizing: border-box;
                }

                .wo-calc table {
                    margin: 0;
                }

                .wo-calc {
                    padding-top: 10px;
                }

                .wo-calc .p-5 {
                    padding: 0;
                }

                hr {
                    display: none;
                }

                .main-footer {
                    padding: 0;
                }

                .page-container {
                    height: 0;
                    margin: 0 !important;
                }

                .page-container .btn-dark {
                    display: none;
                }
            }
        </style>
        <style>
            @media print {
                .edit-site {
                    display: none !important
                }

                .cus {
                    padding-left: 120px;
                }

                .top-table {
                    width: max-content;
                }

                .top-table tr {
                    border-bottom: none !important;
                }

                .bg-teal-table th {
                    background-color: rgba(175, 225, 175, 0.5) !important;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .bg-teal-table2 th {
                    background-color: #ECF3F8 !important;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }


                thead {
                    background-color: #6C757D
                }

                .no-print {
                    display: none;
                }

                .plus-button {
                    display: none;
                }

                .removeBtn {
                    display: none;
                }

                .removeBtnTwo {
                    display: none;
                }

                .subtotal,
                .due,
                .credit-span,
                .taxprice,
                .shipping {
                    width: 140px !important;
                }

                .input-group {
                    display: flex;
                    gap: 10px;
                    align-items: center !important;
                    padding: 13px 0
                }

                .input-group span,
                .input-group input {
                    padding: 0 !important;
                    margin-bottom: 0
                }
            }
        </style>
        @include('admin.customers.invoices.button')
        <div class="card">
            <!-- Loader -->
            <div id="loader" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="card-header d-flex justify-content-end">
                <button class="btn btn-outline-secondary" id="viewLogDetailsBtn">
                    View Log
                </button>
                @if ($invoice->status == 15)
                    <button class="btn btn-success">Payment Complete</button>
                @else
                    @if ($invoice->status == 13)
                        <a href="{{ route('admin.revert', $invoice->id) }}"
                            class="btn btn-outline-secondary ml-2 no-print me-auto">Revert</a>
                    @endif
                    @if ($invoice->status == 12)
                        <a href="{{ route('admin.closed.needs-approval', $invoice->id) }}"
                            class="btn btn-outline-secondary ml-2 no-print me-auto">Revert</a>
                        <a href="{{ route('admin.billing.invoiced', $invoice->id) }}"
                            class="btn btn-outline-secondary ml-2 no-print" id="invoiceButton"
                            data-invoice-url="{{ route('admin.billing.invoiced', $invoice->id) }}">
                            Invoice
                        </a>
                    @endif
                    @if ($invoice->status == 13)
                        <a href="{{ route('admin.billing.paid', $invoice->id) }}"
                            class="btn btn-outline-secondary ml-2 no-print" id="referCode">Paid</a>
                    @endif
                @endif
                <button class="btn btn-outline-secondary ml-2 no-print" onclick="window.print()">Convert to PDF</button>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row align-items-start top-print-nav">
                        <div class="col-12 row justify-content-center">
                            <img src="{{ asset('assetsNew/dist/img/invoicelogo.png') }}" alt="Company Logo"
                                class="img-fluid" class="mx-1" style="width:140px">
                        </div>
                        <div class="col-md-3">
                            <h4>INVOICE: {{ @$invoice->invoice->invoice_number }}</h4>

                        </div>
                        <div class="col-md-6 text-center">
                            <address>
                                <span>1905 Marketview Dr. #226 <br>Yorkville, IL 60560</span>
                            </address>
                        </div>

                        <div class="col-md-3 text-left">
                            <div style="padding-left: 100px;">
                                <table class="top-table table mt-0 mb-3" style="border-collapse: collapse; width: 100%;">
                                    <tr>
                                        <td style="padding: 10px; text-align: left;">
                                            <span style="font-weight: bold;">
                                                <span class="tax" style="white-space: nowrap">Customer ID</span>
                                            </span>
                                        </td>
                                        <td style="padding: 10px; text-align: right;">
                                            <span style="color: #000000; white-space: nowrap">
                                                {{ @$invoice->customer->customer_id }} </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px; text-align: left;">
                                            <span class="tax" style="font-weight: bold; white-space: nowrap">Date</span>
                                        </td>
                                        <td style="padding: 10px; text-align: right;"><span
                                                style="color: #000000;"><?php
                                                echo \Carbon\Carbon::now('America/Chicago')->format('m/d/Y');
                                                ?>
                                            </span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px; text-align: left;"><span class="tax"
                                                style=" font-weight: bold; white-space: nowrap">Site Number</span></td>
                                        <td style="padding: 10px; text-align: right;" class="position-relative">
                                            <span style="color: #000000;" class="site-preview">
                                                @if ($invoice->invoice->site_num)
                                                    {{ $invoice->invoice->site_num }}
                                                @else
                                                    {{ isset($invoice->site->site_id) && strpos($invoice->site->site_id, '-') !== false ? explode('-', $invoice->site->site_id)[1] : $invoice->site->site_id }}
                                                @endif
                                            </span>
                                            <form id="siteNumForm"
                                                action="{{ route('admin.invoice.updateSiteNumber', $invoice->id) }}"
                                                method="post">
                                                @csrf
                                                <input name="site_num" type="text"
                                                    class="site-input border text-end outline-0 d-none"
                                                    value="{{ $invoice->invoice->site_num ?? (isset($invoice->site->site_id) && strpos($invoice->site->site_id, '-') !== false ? explode('-', $invoice->site->site_id)[1] : $invoice->site->site_id) }}">
                                            </form>

                                            <button
                                                class="edit-site addRowBtnCont bg-primary rounded-circle d-flex align-items-center justify-content-center position-absolute border-0"
                                                style="font-size: 10px; width: 20px; height: 20px; top: -3px; right: -3px"><i
                                                    class="fas fa-pencil-alt"></i></button>
                                            <button onclick="document.getElementById('siteNumForm').submit()"
                                                class="save-site addRowBtnCont bg-success rounded-circle d-none align-items-center justify-content-center position-absolute border-0"
                                                style="font-size: 10px; width: 20px; height: 20px; top: -3px; right: -3px"><i
                                                    class="fas fa-save"></i></button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-start">
                        <div class="col-md-3">
                            <h6 class="tax">Bill To:</h6>
                            <span>{{ @$invoice->customer->company_name }}<br>
                                {{ @$invoice->customer->address->address }}<br> {{ @$invoice->customer->address->city }}
                                {{ @$invoice->customer->address->state }}
                                {{ @$invoice->customer->address->zip_code }}</span>
                        </div>
                        <div class="col-md-6 text-center">
                            <address>
                                <span>Tax ID: 92-0586580 </span>
                            </address>
                        </div>
                        <div class="col-md-3 text-left">
                            <div class="margin-shop text-start" style="padding-left: 110px;">
                                <h6 class="tax">Ship To:</h6>
                                <span> {{ @$invoice->site->location }} @if (@$invoice->site->location)
                                        <br>
                                    @endif {{ @$invoice->site->address_1 }}<br>
                                    {{ @$invoice->site->city }} {{ @$invoice->site->state }}
                                    {{ @$invoice->site->zipcode }} </span>
                            </div>
                        </div>
                    </div>
                    <div class="page-container d-flex justify-content-end pb-2">
                        <button type="button" class="btn btn-dark" style="z-index: 99">Page Break</button>
                    </div>

                    <form action="{{ route('admin.invoice.updateInvoiceOverview', $invoice->id) }}" method="post">
                        @csrf
                        <table class="table table-hover mt-5 info-table">
                            <tbody>
                                <tr class="bg-teal-table">
                                    <th>Job</th>
                                    <th>Completed Date</th>
                                    <th>Purchase Order</th>
                                    <th>Terms</th>
                                    <th>Work Order Number</th>
                                    <th class="addRowBtnCont">
                                        <div class="d-flex">
                                            <button type="submit" class="btn btn-success mr-3 save-btn d-none">
                                                Save
                                            </button>
                                            <button type="button" class="btn btn-primary edit-btn">
                                                Edit
                                            </button>
                                        </div>

                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="job" class="my-input-disable-class editable d-none w-100"
                                            style="border:none">
                                            <option value="1" @selected(($invoice->invoice->job ?? $invoice->priority) == 1)>P1</option>
                                            <option value="2" @selected(($invoice->invoice->job ?? $invoice->priority) == 2)>P2</option>
                                            <option value="3" @selected(($invoice->invoice->job ?? $invoice->priority) == 3)>P3</option>
                                            <option value="4" @selected(($invoice->invoice->job ?? $invoice->priority) == 4)>P4</option>
                                            <option value="5" @selected(($invoice->invoice->job ?? $invoice->priority) == 5)>P5</option>
                                        </select>
                                        <span
                                            class="previewable">{{ $invoice->invoice->job ?? $invoice->priority ? 'P' . ($invoice->invoice->job ?? $invoice->priority) : '' }}</span>
                                    </td>

                                    <td><input type="date" class="my-input-disable-class editable d-none"
                                            value="{{ $invoice->invoice->date && strtotime($invoice->invoice->date) ? \Carbon\Carbon::parse($invoice->invoice->date)->setTimezone('America/Chicago')->format('Y-m-d') : '' }}"
                                            style="border:none" name="date">
                                        <span
                                            class="previewable">{{ @$invoice->invoice->date && strtotime($invoice->invoice->date) ? \Carbon\Carbon::parse($invoice->invoice->date)->setTimezone('America/Chicago')->format('m/d/Y') : '' }}</span>
                                    </td>
                                    <td>
                                        <input type="text" class="my-input-disable-class editable d-none"
                                            value="{{ $invoice->invoice->p_o ?? $invoice->p_o ? $invoice->invoice->p_o ?? $invoice->p_o : '' }}"
                                            style="border:none" name="p_o">
                                        <span
                                            class="previewable">{{ $invoice->invoice->p_o ?? $invoice->p_o ? $invoice->invoice->p_o ?? $invoice->p_o : '' }}</span>
                                    </td>
                                    <td>
                                        <select name="terms" class="my-input-disable-class editable d-none w-100"
                                            style="border:none">
                                            <option value="NET15" @selected(($invoice->invoice->terms ?? $invoice->customer->billing_term) == 'NET15')>NET15</option>
                                            <option value="NET30" @selected(($invoice->invoice->terms ?? $invoice->customer->billing_term) == 'NET30')>NET30</option>
                                            <option value="NET45" @selected(($invoice->invoice->terms ?? $invoice->customer->billing_term) == 'NET45')>NET45</option>
                                        </select>
                                        <span
                                            class="previewable">{{ @$invoice->invoice->terms ?? @$invoice->customer->billing_term }}</span>
                                    </td>
                                    <td>{{ @$invoice->order_id }}
                                    </td>
                                    <td class="addRowBtnCont"></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>

                    <div class="page-container d-flex justify-content-end pb-2">
                        <button type="button" class="btn btn-dark" style="z-index: 99">Page Break</button>
                    </div>
                    <div>
                        <table class="table mt-5 price-table mr-0" style="width:100%; justify-content: center;">
                            <thead>
                                <tr class="bg-teal-table2"
                                    style="background-color: rgba(61, 135, 188, 0.1); border-bottom: 0">
                                    <th>Qty.</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Price</th>
                                    <th>Amount</th>
                                    <th class="addRowBtnCont">
                                        <button onclick="document.getElementById('extraForm').submit()"
                                            class="btn btn-success save-extra d-none">Save</button>
                                        <button id="addRowBtn" class="btn btn-success plus-button float-end">Add</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyTwo">
                                @if (@$firstHour->work_order_id == $invoice->id)
                                    <form action="{{ route('admin.invoice.updateFirstHourProduct', $invoice->id) }}"
                                        method="post">
                                        @csrf
                                        <tr class="calc-tr">
                                            <td>
                                                <input type="text"
                                                    class="total-hours py-2 my-input-disable-class bg-white"
                                                    value="1" data-rate="" style="border:none" disabled>
                                            </td>
                                            <td>
                                                <textarea class="wo-per w-100 my-input-disable-class editable-three d-none"
                                                    style="border:none; height: 32px !important" name="desc">{{ $firstHourProduct->desc ?? @$firstHour->description }}</textarea>
                                                <div class="input-group d-flex align-items-center">
                                                    <span
                                                        class="previewable-three py-2">{{ $firstHourProduct->desc ?? @$firstHour->description }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" class="date py-2 my-input-disable-class bg-white"
                                                    value="{{ @$invoice->invoice->date && strtotime($invoice->invoice->date) ? \Carbon\Carbon::parse($invoice->invoice->date)->setTimezone('America/Chicago')->format('m/d/Y') : '' }}"
                                                    style="border:none" disabled>
                                            </td>
                                            <td>
                                                <div class="input-group d-flex align-items-center">
                                                    <span class="py-2 pr-2">$</span>
                                                    <input type="text"
                                                        class="calculated-rate my-input-disable-class editable-three d-none"
                                                        value="{{ $firstHourProduct->price ?? ($invoice->customer->s_rate_f ?? 0.0) }}"
                                                        style="border:none" name="price">
                                                    <span
                                                        class="previewable-three">{{ $firstHourProduct->price ?? ($invoice->customer->s_rate_f ?? 0.0) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="py-2 pr-2">$</span>
                                                    <input type="text" class="amount my-input-disable-class bg-white"
                                                        value="{{ @$firstHour->amount }}" style="border:none" readonly>
                                                </div>
                                            </td>
                                            <td class="addRowBtnCont d-flex gap-3">
                                                <button type="submit"
                                                    class="btn btn-success save-btn-three d-none">Save</button>
                                                <button type="button"
                                                    class="btn btn-primary edit-btn-three">Edit</button>
                                            </td>
                                        </tr>
                                    </form>
                                @endif
                                @if ($additionalHourProduct)
                                    @if ($aRate > 1 && $additionalHourProduct->soft_delete != 1)
                                        @foreach (@$wps as $wp)
                                            <form
                                                action="{{ route('admin.invoice.updateAdditionalHourProduct', $invoice->id) }}"
                                                method="post">
                                                @csrf
                                                <tr class="calc-tr additional-tr">
                                                    <td>
                                                        <input type="text"
                                                            class="total-hours py-2 my-input-disable-class bg-white editable-four d-none"
                                                            value="{{ $additionalHourProduct->qty ?? str_replace(':', '.', $aRate - 1) }}"
                                                            data-rate="" style="border:none" name="qty">

                                                        <div class="input-group d-flex align-items-center">
                                                            <span
                                                                class="previewable-four">{{ $additionalHourProduct->qty ?? str_replace(':', '.', $aRate - 1) }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <textarea name="desc" class="wo-per w-100 my-input-disable-class bg-white editable-four d-none"
                                                            style="border:none; height: 32px !important">{{ $additionalHourProduct->desc ?? @$wp->description }}</textarea>
                                                        <div class="input-group d-flex align-items-center">
                                                            <span
                                                                class="previewable-four py-2">{{ $additionalHourProduct->desc ?? @$wp->description }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="date py-2 my-input-disable-class bg-white"
                                                            value="{{ @$invoice->invoice->date && strtotime($invoice->invoice->date) ? \Carbon\Carbon::parse($invoice->invoice->date)->setTimezone('America/Chicago')->format('m/d/Y') : '' }}"
                                                            style="border:none" disabled>
                                                    </td>
                                                    <td>
                                                        <div class="input-group d-flex align-items-center">
                                                            <span class="py-2 pr-2">$</span>
                                                            <input type="text"
                                                                class="calculated-rate my-input-disable-class bg-white editable-four d-none"
                                                                value="{{ $additionalHourProduct->price ?? (optional($wp->workOrder?->customer)->s_rate_a ?? '0.00') }}"
                                                                style="border:none" name="price">
                                                            <span
                                                                class="previewable-four">{{ $additionalHourProduct->price ?? (optional($wp->workOrder?->customer)->s_rate_a ?? '0.00') }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="py-2 pr-2">$</span>
                                                            <input type="text"
                                                                class="amount my-input-disable-class bg-white"
                                                                value="{{ @$wp->amount }}" style="border:none" readonly>
                                                        </div>
                                                    </td>
                                                    <td class="addRowBtnCont d-flex gap-3">
                                                        <button type="submit"
                                                            class="btn btn-success save-btn-four d-none">Save</button>
                                                        <button type="button"
                                                            class="btn btn-primary edit-btn-four">Edit</button>
                                                        <button
                                                            onclick="document.getElementById('additionalDeleteForm').submit()"
                                                            type="button" class="btn btn-danger"
                                                            style=" border:none;">Delete</button>
                                                    </td> <!-- Hidden remove button -->
                                                </tr>
                                            </form>
                                            <form id="additionalDeleteForm"
                                                action="{{ route('admin.invoice.deleteAdditionalHourProduct', ['id' => $additionalHourProduct->id ?? 0, 'wo_id' => $invoice->id]) }}"
                                                method="post">
                                                @csrf
                                            </form>
                                        @endforeach
                                    @endif
                                @else()
                                    @if ($aRate > 1)
                                        @foreach (@$wps as $wp)
                                            <form
                                                action="{{ route('admin.invoice.updateAdditionalHourProduct', $invoice->id) }}"
                                                method="post">
                                                @csrf
                                                <tr class="calc-tr additional-tr">
                                                    <td>
                                                        <input type="text"
                                                            class="total-hours py-2 my-input-disable-class bg-white editable-four d-none"
                                                            value="{{ $additionalHourProduct->qty ?? str_replace(':', '.', $aRate - 1) }}"
                                                            data-rate="" style="border:none" name="qty">

                                                        <div class="input-group d-flex align-items-center">
                                                            <span
                                                                class="previewable-four">{{ $additionalHourProduct->qty ?? str_replace(':', '.', $aRate - 1) }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <textarea name="desc" class="wo-per w-100 my-input-disable-class bg-white editable-four d-none"
                                                            style="border:none; height: 32px !important">{{ $additionalHourProduct->desc ?? @$wp->description }}</textarea>
                                                        <div class="input-group d-flex align-items-center">
                                                            <span
                                                                class="previewable-four py-2">{{ $additionalHourProduct->desc ?? @$wp->description }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="date py-2 my-input-disable-class bg-white"
                                                            value="{{ @$invoice->invoice->date && strtotime($invoice->invoice->date) ? \Carbon\Carbon::parse($invoice->invoice->date)->setTimezone('America/Chicago')->format('m/d/Y') : '' }}"
                                                            style="border:none" disabled>
                                                    </td>
                                                    <td>
                                                        <div class="input-group d-flex align-items-center">
                                                            <span class="py-2 pr-2">$</span>
                                                            <input type="text"
                                                                class="calculated-rate my-input-disable-class bg-white editable-four d-none"
                                                                value="{{ $additionalHourProduct->price ?? (optional($wp->workOrder?->customer)->s_rate_a ?? '0.00') }}"
                                                                style="border:none" name="price">
                                                            <span
                                                                class="previewable-four">{{ $additionalHourProduct->price ?? (optional($wp->workOrder?->customer)->s_rate_a ?? '0.00') }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="py-2 pr-2">$</span>
                                                            <input type="text"
                                                                class="amount my-input-disable-class bg-white"
                                                                value="{{ @$wp->amount }}" style="border:none" readonly>
                                                        </div>
                                                    </td>
                                                    <td class="addRowBtnCont d-flex gap-3">
                                                        <button type="submit"
                                                            class="btn btn-success save-btn-four d-none">Save</button>
                                                        <button type="button"
                                                            class="btn btn-primary edit-btn-four">Edit</button>
                                                        <button
                                                            onclick="document.getElementById('additionalDeleteForm').submit()"
                                                            type="button" class="btn btn-danger"
                                                            style=" border:none;">Delete</button>
                                                    </td> <!-- Hidden remove button -->
                                                </tr>
                                            </form>
                                            <form id="additionalDeleteForm"
                                                action="{{ route('admin.invoice.deleteAdditionalHourProduct', ['id' => $additionalHourProduct->id ?? 0, 'wo_id' => $invoice->id]) }}"
                                                method="post">
                                                @csrf
                                            </form>
                                        @endforeach
                                    @endif
                                @endif

                                @foreach ($extraHourProducts as $extraHourProduct)
                                    <form action="{{ route('admin.invoice.updateExtraHour', $extraHourProduct->id) }}"
                                        method="post">
                                        @csrf
                                        <tr class="calc-tr">
                                            <td>
                                                <input type="text"
                                                    class="total-hours py-2 my-input-disable-class bg-white editable-five d-none"
                                                    value="{{ $extraHourProduct->qty }}" data-rate=""
                                                    style="border:none" name="qty">

                                                <div class="input-group d-flex align-items-center">
                                                    <span
                                                        class="previewable-five py-2">{{ $extraHourProduct->qty }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea name="desc" class="wo-per w-100 my-input-disable-class bg-white editable-five d-none"
                                                    style="border:none; height: 32px !important">{{ $extraHourProduct->desc }}</textarea>
                                                <div class="input-group d-flex align-items-center">
                                                    <span
                                                        class="previewable-five py-2">{{ $extraHourProduct->desc }}</span>
                                                </div>

                                            </td>
                                            <td>
                                                <input type="text" class="date py-2 my-input-disable-class bg-white"
                                                    value="{{ @$wp->date && strtotime($wp->date) ? \Carbon\Carbon::parse($wp->date)->setTimezone('America/Chicago')->format('m/d/Y') : '' }}"
                                                    style="border:none" disabled>
                                            </td>
                                            <td>
                                                <div class="input-group d-flex align-items-center">
                                                    <span class="py-2 pr-2">$</span>
                                                    <input type="text"
                                                        class="calculated-rate my-input-disable-class bg-white editable-five d-none"
                                                        value="{{ $extraHourProduct->price ?? '0.00' }}"
                                                        style="border:none" name="price">
                                                    <span
                                                        class="previewable-five">{{ $extraHourProduct->price ?? '0.00' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="py-2 pr-2">$</span>
                                                    <input type="text" class="amount my-input-disable-class bg-white"
                                                        value="{{ @$wp->amount }}" style="border:none" readonly>
                                                </div>
                                            </td>
                                            <td class="addRowBtnCont d-flex gap-3">
                                                <button type="submit"
                                                    class="btn btn-success save-btn-five d-none">Save</button>
                                                <button type="button" class="btn btn-primary edit-btn-five">Edit</button>
                                                <button onclick="document.getElementById('deleteExtraProduct').submit()"
                                                    type="button" class="btn btn-danger removeBtn"
                                                    style=" border:none;">Delete</button>
                                            </td> <!-- Hidden remove button -->
                                        </tr>
                                    </form>

                                    <form id="deleteExtraProduct"
                                        action="{{ route('admin.invoice.deleteExtraHour', $extraHourProduct->id) }}"
                                        method="post">
                                        @csrf
                                        @method('delete')
                                    </form>
                                @endforeach

                            </tbody>
                        </table>

                        <form id="extraForm" action="{{ route('admin.invoice.extraHourProduct', $invoice->id) }}"
                            method="post">
                            @csrf
                            <table class="table price-table mr-0 mt-0" style="width:100%; justify-content: center;">
                                <tbody id="tableBody"></tbody>
                            </table>
                        </form>
                    </div>

                    <form action="{{ route('admin.invoice.updateWoReq', $invoice->id) }}" method="post">
                        @csrf
                        <div class="page-container d-flex justify-content-end pb-2">
                            <button type="submit"
                                class="btn btn-success mr-3 save-btn-two d-none addRowBtnCont work-requested-save">
                                Save
                            </button>
                            <button type="button"
                                class="btn btn-primary mr-3 edit-btn-two addRowBtnCont work-requested-edit">
                                Edit
                            </button>
                            <button type="button" class="btn btn-dark" style="z-index: 99">Page Break</button>
                        </div>
                        @if ($invoice->status == 13 || $invoice->status == 15)
                            <div class="d-flex">
                                <div>
                                    <h6 class="fst-italic" style="white-space: nowrap;">Work Requested : </h6>
                                </div>
                                <div class="w-100 px-5 py-0 editable-two work-requested-editable d-none">
                                    <textarea class="wo_close_out w-100 p-0 my-input-disable-class" name="wo_req" id="" style="border:none">
                                        {!! $invoice->invoice->wo_req ?? $invoice->wo_requested
                                            ? $invoice->invoice->wo_req ?? $invoice->wo_requested
                                            : '' !!}
                                    </textarea>

                                </div>
                                <div id="scope_work"
                                    class="my-input-disable-class w-100 px-5 py-0 previewable-two work-requested-preview">
                                    {!! $invoice->invoice->wo_req ?? $invoice->wo_requested
                                        ? $invoice->invoice->wo_req ?? $invoice->wo_requested
                                        : '' !!}
                                </div>
                            </div>
                        @else
                            <div class="d-flex">
                                <div>
                                    <h6 class="fst-italic" style="white-space: nowrap;">Work Requested : </h6>
                                </div>
                                <div class="w-100 px-5 py-0 editable-two work-requested-editable d-none">
                                    <textarea class="w-100 p-0 my-input-disable-class" name="wo_req" id="wo-desc" style="border:none">
                                        {!! $invoice->invoice->wo_req ?? $invoice->wo_requested
                                            ? $invoice->invoice->wo_req ?? $invoice->wo_requested
                                            : '' !!}
                                    </textarea>

                                </div>
                                <div id="scope_work"
                                    class="my-input-disable-class w-100 px-5 py-0 previewable-two work-requested-preview">
                                    {!! $invoice->invoice->wo_req ?? $invoice->wo_requested
                                        ? $invoice->invoice->wo_req ?? $invoice->wo_requested
                                        : '' !!}
                                </div>
                            </div>
                        @endif
                    </form>

                    <form action="{{ route('admin.invoice.updateWoPer', $invoice->id) }}" method="post">
                        @csrf
                        <div class="page-container d-flex justify-content-end pb-2">
                            <button type="submit"
                                class="btn btn-success mr-3 save-btn-two d-none addRowBtnCont work-performed-save">
                                Save
                            </button>
                            <button type="button"
                                class="btn btn-primary mr-3 edit-btn-two addRowBtnCont work-performed-edit">
                                Edit
                            </button>
                            <button type="button" class="btn btn-dark" style="z-index: 99">Page Break</button>
                        </div>
                        <div class="d-flex">
                            <div>
                                <h6 class="fst-italic" style="white-space: nowrap;">Work Performed : </h6>
                            </div>
                            <div class="w-100 px-5 py-0  editable-two d-none work-performed-editable">
                                <textarea class="wo_close_out w-100 p-0 my-input-disable-class" style="border:none" name="wo_per">

                                    @if ($invoice->invoice->wo_per)
{!! $invoice->invoice->wo_per ? $invoice->invoice->wo_per : '' !!}
@else
@foreach ($invoice->notes as $note)
@if ($note->note_type == 'close_out_notes')
<p>{{ $note->note }}</p>
@endif
@endforeach
@endif
                                </textarea>
                            </div>
                            <div id="inv_note" class="inv_note w-100 px-5 py-0 previewable-tw work-performed-preview">
                                @if ($invoice->invoice->wo_per)
                                    {!! $invoice->invoice->wo_per ? $invoice->invoice->wo_per : '' !!}
                                @else
                                    @foreach ($invoice->notes as $note)
                                        @if ($note->note_type == 'close_out_notes')
                                            <p>{{ $note->note }}</p>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </form>

                    <div class="page-container d-flex justify-content-end pb-2">
                        <button type="button" class="btn btn-dark" style="z-index: 99">Page Break</button>
                    </div>
                    <hr>
                    <div class="wo-calc" style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <!-- Left Side Content -->
                        <div style="width: 50%;" style="margin-top:100px">

                            <div class="p-5">
                                <h4><b><i>All Fees Shown In US Dollars</i></b></h4>
                                <h6><i>Thank you for your business!</i></h6>
                                @php
                                    $referenceCode = optional($invoice->invoice)->reference_code;
                                    $maskedCode = $referenceCode
                                        ? str_repeat('*', max(0, strlen($referenceCode) - 4)) .
                                            substr($referenceCode, -4)
                                        : 'N/A';
                                @endphp
                                @if ($invoice->invoice->reference_code == !null)
                                    <div class="watermark-container">
                                        <h6><i>Reference code: {{ $maskedCode }}</i></h6>
                                        <img width="100px" src="{{ asset('assets/img/tpaid.jpg') }}" alt="">
                                    </div>
                                @else
                                @endif
                            </div>
                        </div>

                        <!-- Right Side Table -->
                        <form action="{{ route('admin.invoice.updateInvoicePay', $invoice->id) }}" method="post">
                            @csrf
                            <table class="price-box table table-hover" style="width: 500px;">
                                <tbody>
                                    <tr class="addRowBtnCont">
                                        <th></th>
                                        <th class="addRowBtnCont">
                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn-success mr-3 save-btn-pay d-none">
                                                    Save
                                                </button>
                                                <button type="button" class="btn btn-primary edit-btn-pay">
                                                    Edit
                                                </button>
                                            </div>

                                        </th>
                                    </tr>
                                    <tr class="tax">
                                        <td>
                                            <div class="d-flex align-items-center" style="height: 40px">Sub-total</div>
                                        </td>
                                        <td class="d-flex justify-content-end">
                                            <div class="input-group w-auto d-flex align-items-center">
                                                <span class="p-2">$</span>
                                                <input type="text"
                                                    class="subtotal decimal-input my-input-disable-class editable-pay d-none"
                                                    value="{{ isset($totalPrice) ? $totalPrice : '0.00' }}"
                                                    placeholder="0.00" style="border:none">
                                                <p class="subtotal-text decimal-input my-input-disable-class previewable-pay mb-0"
                                                    style="width: 190px;">0.00
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="tax">
                                        <td>
                                            <div class="d-flex align-items-center" style="height: 40px">Sales Tax</div>
                                        </td>
                                        <td class="d-flex justify-content-end">
                                            <div class="input-group w-auto d-flex align-items-center">
                                                <span class="p-2">$</span>
                                                <input name="tax" type="text"
                                                    class="taxprice decimal-input my-input-disable-class editable-pay d-none"
                                                    value="{{ $invoice->invoice->tax ?? 0.0 }}" placeholder="0.00"
                                                    style="border:none">
                                                <span class="previewable-pay"
                                                    style="width: 190px;">{{ $invoice->invoice->tax ?? '0.00' }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="shippingField">
                                        <td>
                                            <div class="d-flex align-items-center" style="height: 40px">Shipping &
                                                Handling
                                            </div>
                                        </td>
                                        <td class="d-flex justify-content-end">
                                            <div class="input-group w-auto d-flex align-items-center">
                                                <span class="p-2">$</span>
                                                <input name="shipping" type="text"
                                                    class="shipping decimal-input my-input-disable-class editable-pay d-none"
                                                    placeholder="0.00" value="{{ $invoice->invoice->shipping ?? 0.0 }}"
                                                    style="border:none">
                                                <span class="previewable-pay"
                                                    style="width: 190px;">{{ $invoice->invoice->shipping ?? '0.00' }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="tax">
                                        <td>
                                            <div class="d-flex align-items-center" style="height: 40px">Credit</div>
                                        </td>
                                        <td class="d-flex justify-content-end">
                                            <div class="input-group w-auto d-flex align-items-center justify-content-end">
                                                <span class="p-2">$</span>
                                                <span class="text-danger credit-span" style="width: 190px !important;">
                                                    (<input name="credit" type="text"
                                                        class="credit text-danger p-0 decimal-input my-input-disable-class editable-pay d-none"
                                                        value="{{ $invoice->invoice->credit ?? 0.0 }}"
                                                        style="border:none; width: 3.4ch; outline: 0 !important"
                                                        oninput="this.style.width = ((this.value.length + 1) * 0.87) + 'ch';"><span
                                                        class="previewable-pay">{{ $invoice->invoice->credit ?? '0.00' }}</span>)
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="tax">
                                        <td>
                                            <div class="d-flex align-items-center" style="height: 40px">Balance Due</div>
                                        </td>
                                        <td class="d-flex justify-content-end">
                                            <div class="input-group w-auto d-flex align-items-center">
                                                <span class="p-2">$</span>
                                                <input type="text"
                                                    class="due decimal-input my-input-disable-class editable-pay d-none"
                                                    style="border:none" placeholder="0.00">
                                                <span class="previewable-pay due-text" style="width: 190px;">0.00</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.customers.invoices.off_canvas')
    <script>
        // Function to format a number as a decimal (2 places)
        function formatAsDecimal(value) {
            const number = parseFloat(value);
            return isNaN(number) ? "" : number.toFixed(2);
        }

        // Event listener for inputs with the "decimal-input" class
        document.querySelectorAll(".decimal-input").forEach((input) => {
            // Apply formatting only when the user leaves the input field (on blur)
            input.addEventListener("blur", (event) => {
                const target = event.target;
                target.value = formatAsDecimal(target.value); // Format the value
            });

            // Allow typing without interference
            input.addEventListener("input", (event) => {
                const target = event.target;
                if (target.value === "") {
                    target.style.width = "19.7ch"; // Reset width for empty input
                }
            });
        });
    </script>



    <script>
        const textareaOne = document.getElementById('wo-desc');
        const scopeWorkParagraph = document.getElementById('scope_work');

        function adjustHeight(textarea) {
            textarea.style.height = '32px';
            const newHeight = Math.max(textarea.scrollHeight, 32);
            textarea.style.height = newHeight + 'px';
        }

        textareaOne.addEventListener('input', () => {
            adjustHeight(textareaOne);
            scopeWorkParagraph.textContent = textareaOne.value; // Update <p> tag with textarea value
        });

        // Adjust height on page load
        window.addEventListener('load', () => adjustHeight(textareaOne));

        // Function for initializing auto-resize on other textareas
        function setupTextareaAutoResize() {
            const textareaTwo = document.querySelectorAll('.wo-per'); // Select textareas with the class
            textareaTwo.forEach((textarea) => {
                textarea.style.height = '32px'; // Reset height
                textarea.addEventListener('input', () => adjustHeight(textarea)); // Adjust height on input
                adjustHeight(textarea); // Initial adjustment
            });
        }

        // Call setupTextareaAutoResize on page load
        window.addEventListener('load', setupTextareaAutoResize);
        setupTextareaAutoResize();

        function updateSubtotal() {
            var subtotal = 0;

            // Include both tableBody and tableBodyTwo rows
            document.querySelectorAll('.calc-tr').forEach(function(row) {
                var rowAmount = parseFloat(row.querySelector('.amount')?.value) || 0;
                subtotal += rowAmount;
            });

            console.log(subtotal);


            var tax = parseFloat(document.querySelector('.taxprice')?.value) || 0;
            var ship = parseFloat(document.querySelector('.shipping')?.value) || 0;
            var credit = parseFloat(document.querySelector('.credit')?.value) || 0;

            var due = subtotal + tax + ship - credit;

            document.querySelector('.subtotal-text').textContent = subtotal.toFixed(2);
            document.querySelector('.subtotal').value = subtotal.toFixed(2);
            document.querySelector('.due').value = due.toFixed(2);
            document.querySelector('.due-text').textContent = due.toFixed(2);
        }

        document.getElementById('addRowBtn').addEventListener('click', function() {
            const tableBody = document.getElementById('tableBody');
            const newRow = document.createElement('tr');
            newRow.classList.add('calc-tr');
            newRow.classList.add('extra-tr');
            newRow.innerHTML = `
            <td><input type="text" name='qty[]' class="total-hours p-2 bg-white" value="" style="border:none"></td>
            <td><textarea class="wo-per w-100 bg-white" name='desc[]' style="border:none;"></textarea></td>
            <td><input type="text" class="p-2 bg-white" value="" style="border:none"></td>
           <td><div class="input-group">
                                        <span class="p-2">$</span>
                                        <input type="text" name='price[]' class="calculated-rate bg-white" value="{{ @$wp->calculated_rate }}" style="border:none">
                                    </div></td>
            <td><div class="input-group">
                <span class="p-2">$</span>
                <input type="text" class="amount bg-white" value="" style="border:none" readonly>
            </div></td>
            <td class="addRowBtnCont d-flex gap-3">
                <button class="btn bg-transparent text-white">Edit</button>
                <button class="btn btn-danger removeBtn" style="border:none;">Delete</button>
            </td>
        `;

            tableBody.appendChild(newRow);

            setupTextareaAutoResize();

            const saveExtra = document.querySelector('.save-extra');
            if (saveExtra) {
                saveExtra.classList.remove('d-none');
            }

            const totalHoursInput = newRow.querySelector('.total-hours');
            const calculatedRateInput = newRow.querySelector('.calculated-rate');
            const amountInput = newRow.querySelector('.amount');

            function updateAmount() {
                const totalHours = parseFloat(totalHoursInput.value.replace(',', '.')) || 0;
                const calculatedRate = parseFloat(calculatedRateInput.value) || 0;
                const amount = totalHours * calculatedRate;
                amountInput.value = amount.toFixed(2);

                updateSubtotal();
            }




            totalHoursInput.addEventListener('input', updateAmount);
            calculatedRateInput.addEventListener('input', updateAmount);

            newRow.querySelector('.removeBtn').addEventListener('click', function() {
                tableBody.removeChild(newRow);
                updateSubtotal(); // Update subtotal after removing the row
            });
            updateSubtotal();
        });

        document.getElementById('tableBody').addEventListener('click', function(event) {
            if (event.target && event.target.classList.contains('removeBtn')) {
                const extraTr = document.querySelectorAll('.extra-tr')
                    .length; // Get the current number of extra-tr rows
                console.log(extraTr); // Log the current count
                if (extraTr === 0) { // If no extra rows are left, hide the 'save-extra' button
                    const saveExtra = document.querySelector('.save-extra');
                    if (saveExtra) {
                        saveExtra.classList.add('d-none');
                    }
                }
                const row = event.target.closest('tr');
                const tableBody = document.getElementById('tableBody');
                tableBody.removeChild(row);
                updateSubtotal();
            }
        });

        // document.querySelectorAll('.removeBtn').forEach(function(button) {
        //     button.addEventListener('click', function() {
        //         const row = this.closest('tr');
        //         const tableBody = document.getElementById('tableBodyTwo');
        //         tableBody.removeChild(row);
        //         updateSubtotal(); // Update subtotal after removing the row
        //     });
        // });

        document.getElementById('tableBodyTwo').addEventListener('click', function(event) {
            if (event.target && event.target.classList.contains('removeBtn')) {
                const row = event.target.closest('tr');
                row.remove(); // Remove the row
                updateSubtotal(); // Update subtotal after removing
            }
        });
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {

            function updateAmount(row) {
                var totalHours = parseFloat(row.find('.total-hours').val().replace(',', '.')) || 0;
                var calculatedRate = parseFloat(row.find('.calculated-rate').val()) || 0;
                var amount = totalHours * calculatedRate;

                row.find('.amount').val(amount.toFixed(2));

                updateSubtotal();
            }

            function updateSubtotal() {
                var subtotal = 0;
                // Loop through each row and accumulate the total amount
                $('.calc-tr').each(function() {
                    var rowAmount = parseFloat($(this).find('.amount').val()) || 0;
                    subtotal += rowAmount;
                });

                $('.subtotal-text').text(subtotal.toFixed(2));
                $('.subtotal').val(subtotal.toFixed(2));

                // Call checkoutTotal to update due amount
                checkoutTotal();
            }

            function checkoutTotal() {
                var subtotal = parseFloat($('.subtotal').val()) || 0;
                var tax = parseFloat($('.taxprice').val()) || 0;
                var ship = parseFloat($('.shipping').val()) || 0;
                var credit = parseFloat($('.credit').val()) || 0;

                var due = subtotal + tax + ship - credit;

                $('.due').val(due.toFixed(2));
                $('.due-text').text(due.toFixed(2));
            }

            // Event Listeners for input fields
            $('.calc-tr').each(function() {
                var row = $(this);
                row.find('.total-hours, .calculated-rate').on('input', function() {
                    updateAmount(row);
                });
                updateAmount(row);
            });

            $('.credit, .taxprice, .shipping').on('input', function() {
                checkoutTotal();
            });

            // Initial calculations on page load
            updateSubtotal();
        });
    </script>


    <script src="{{ asset('assetsNew/dist/js/jodit.fat.min.js') }}"></script>
    <script>
        const editor = Jodit.make('#wo-desc');
        editor.events.on('change', () => {
            const editorContent = editor.value;
            document.getElementById('scope_work').innerHTML = editorContent;
        });

        window.addEventListener('load', () => {
            editor.value = document.getElementById('scope_work').innerHTML;
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Get all textarea elements with the class `wo-per`
            const textareas = document.querySelectorAll('.wo_close_out');

            textareas.forEach((textarea, index) => {
                const editorId = `inv_note-${index}`;
                const editor = Jodit.make(textarea, {
                    toolbarSticky: false,
                    toolbarAdaptive: false,
                });

                // Sync changes from the editor to the respective div
                editor.events.on('change', () => {
                    const editorContent = editor.value;
                    const outputDiv = document.getElementById(editorId);
                    if (outputDiv) {
                        outputDiv.innerHTML = editorContent;
                    } else {
                        document.getElementById('inv_note').innerHTML = editorContent
                    }
                });

                // Set initial content on page load
                const outputDiv = document.getElementById(editorId);
                if (outputDiv) {
                    editor.value = outputDiv.innerHTML;
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Select all buttons inside .page-container elements
            const buttons = document.querySelectorAll('.page-container .btn-dark');

            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    const parentDiv = button.closest(
                        '.page-container'); // Find the parent .page-container
                    if (parentDiv) {
                        if (parentDiv.classList.contains('page-break')) {
                            // Remove the class and reset the button text
                            parentDiv.classList.remove('page-break');
                            button.textContent = 'Page Break';
                        } else {
                            // Add the class and update the button text
                            parentDiv.classList.add('page-break');
                            button.textContent = 'Break Applied';
                        }
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Select all buttons with the class .edit-btn
            const buttons = document.querySelectorAll('.edit-btn');

            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    // Find the closest parent <tr> element
                    const parentRow = button.closest('tr').nextElementSibling;

                    // Find all input elements with the class .editable within the parent row
                    const editables = parentRow.querySelectorAll('.editable');
                    const previewables = parentRow.querySelectorAll('.previewable');
                    const saveBtn = button.closest('th').querySelector('.save-btn');

                    editables.forEach(input => {
                        // Toggle the d-none class to show/hide the input
                        input.classList.toggle('d-none');
                    });

                    previewables.forEach(input => {
                        // Toggle the d-none class to show/hide the input
                        input.classList.toggle('d-none');
                    });

                    saveBtn.classList.toggle('d-none');

                    // Update the button text based on the visibility of the inputs
                    if (editables[0].classList.contains('d-none')) {
                        button.textContent = 'Edit';
                        button.classList.add('bg-primary');
                        button.classList.remove('bg-danger');
                    } else {
                        button.textContent = 'Cancel';
                        button.classList.add('bg-danger');
                        button.classList.remove('bg-primary');
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Handle "Work Requested" edit button
            document.querySelectorAll(".work-requested-edit").forEach(button => {
                button.addEventListener("click", function() {
                    // Toggle the visibility of editable and preview sections
                    document.querySelectorAll(".work-requested-editable").forEach(element => {
                        element.classList.toggle("d-none");
                    });
                    document.querySelectorAll(".work-requested-preview").forEach(element => {
                        element.classList.toggle("d-none");
                    });

                    // Toggle the visibility of the save button
                    document.querySelectorAll(".work-requested-save").forEach(button => {
                        button.classList.toggle("d-none");
                    });

                    // Toggle button text and styling
                    if (document.querySelector(".work-requested-editable.d-none")) {
                        button.textContent = 'Edit';
                        button.classList.add('bg-primary');
                        button.classList.remove('bg-danger');
                    } else {
                        button.textContent = 'Cancel';
                        button.classList.add('bg-danger');
                        button.classList.remove('bg-primary');
                    }
                });
            });

            // Handle "Work Performed" edit button
            document.querySelectorAll(".work-performed-edit").forEach(button => {
                button.addEventListener("click", function() {
                    // Toggle the visibility of editable and preview sections
                    document.querySelectorAll(".work-performed-editable").forEach(element => {
                        element.classList.toggle("d-none");
                    });
                    document.querySelectorAll(".work-performed-preview").forEach(element => {
                        element.classList.toggle("d-none");
                    });

                    // Toggle the visibility of the save button
                    document.querySelectorAll(".work-performed-save").forEach(button => {
                        button.classList.toggle("d-none");
                    });

                    // Toggle button text and styling
                    if (document.querySelector(".work-performed-editable.d-none")) {
                        button.textContent = 'Edit';
                        button.classList.add('bg-primary');
                        button.classList.remove('bg-danger');
                    } else {
                        button.textContent = 'Cancel';
                        button.classList.add('bg-danger');
                        button.classList.remove('bg-primary');
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editButton = document.querySelector(".edit-btn-three");
            const saveButton = document.querySelector(".save-btn-three");
            const editableFields = document.querySelectorAll(".editable-three");
            const previewableFields = document.querySelectorAll(".previewable-three");

            editButton.addEventListener("click", function() {
                // Toggle visibility of input fields
                editableFields.forEach(field => field.classList.toggle("d-none"));
                previewableFields.forEach(field => field.classList.toggle("d-none"));
                // Toggle Save button visibility
                saveButton.classList.toggle("d-none");

                // Toggle text and button color
                if (editButton.textContent.trim() === "Edit") {
                    editButton.textContent = "Cancel";
                    editButton.classList.remove("btn-primary");
                    editButton.classList.add("btn-danger");
                } else {
                    editButton.textContent = "Edit";
                    editButton.classList.remove("btn-danger");
                    editButton.classList.add("btn-primary");
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editButton = document.querySelector(".edit-btn-four");
            const saveButton = document.querySelector(".save-btn-four");
            const editableFields = document.querySelectorAll(".editable-four");
            const previewableFields = document.querySelectorAll(".previewable-four");

            editButton.addEventListener("click", function() {
                // Toggle visibility of input fields
                editableFields.forEach(field => field.classList.toggle("d-none"));
                previewableFields.forEach(field => field.classList.toggle("d-none"));
                // Toggle Save button visibility
                saveButton.classList.toggle("d-none");

                // Toggle text and button color
                if (editButton.textContent.trim() === "Edit") {
                    editButton.textContent = "Cancel";
                    editButton.classList.remove("btn-primary");
                    editButton.classList.add("btn-danger");
                } else {
                    editButton.textContent = "Edit";
                    editButton.classList.remove("btn-danger");
                    editButton.classList.add("btn-primary");
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Select all edit buttons (for each row)
            const editButtons = document.querySelectorAll(".edit-btn-five");
            editButtons.forEach((editButton) => {
                editButton.addEventListener("click", function() {
                    // Get the parent row (tr) for this button
                    const row = this.closest("tr");
                    // Select the editable and previewable fields in this row
                    const editableFields = row.querySelectorAll(".editable-five");
                    const previewableFields = row.querySelectorAll(".previewable-five");
                    // Select the Save button for this row
                    const saveButton = row.querySelector(".save-btn-five");
                    // Toggle visibility of input fields
                    editableFields.forEach(field => field.classList.toggle("d-none"));
                    previewableFields.forEach(field => field.classList.toggle("d-none"));
                    // Toggle Save button visibility
                    saveButton.classList.toggle("d-none");
                    // Toggle the text and button color of the edit button
                    if (editButton.textContent.trim() === "Edit") {
                        editButton.textContent = "Cancel";
                        editButton.classList.remove("btn-primary");
                        editButton.classList.add("btn-danger");
                    } else {
                        editButton.textContent = "Edit";
                        editButton.classList.remove("btn-danger");
                        editButton.classList.add("btn-primary");
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editButton = document.querySelector(".edit-site");
            const saveButton = document.querySelector(".save-site");
            const editIcon = editButton.querySelector("i"); // Select the <i> tag inside the button
            const sitePreview = document.querySelector(".site-preview");
            const siteInput = document.querySelector(".site-input");

            if (editButton && editIcon && sitePreview && siteInput) {
                editButton.addEventListener("click", function() {
                    editButton.classList.toggle("d-none");
                    saveButton.classList.add("d-flex");
                    sitePreview.classList.toggle("d-none");
                    siteInput.classList.toggle("d-none");

                });
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editButtonPay = document.querySelector(".edit-btn-pay");
            const saveButtonPay = document.querySelector(".save-btn-pay");
            const editableFieldsPay = document.querySelectorAll(".editable-pay");
            const previewableFieldsPay = document.querySelectorAll(".previewable-pay");
            editButtonPay.addEventListener("click", function() {
                // Toggle visibility of input fields
                editableFieldsPay.forEach(field => field.classList.toggle("d-none"));
                previewableFieldsPay.forEach(field => field.classList.toggle("d-none"));
                // Toggle Save button visibility
                saveButtonPay.classList.toggle("d-none");
                // Toggle text and button color
                if (editButtonPay.textContent.trim() === "Edit") {
                    editButtonPay.textContent = "Cancel";
                    editButtonPay.classList.remove("btn-primary");
                    editButtonPay.classList.add("btn-danger");
                } else {
                    editButtonPay.textContent = "Edit";
                    editButtonPay.classList.remove("btn-danger");
                    editButtonPay.classList.add("btn-primary");
                }
            });
        });
    </script>

@endsection
