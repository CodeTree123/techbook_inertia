<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>
    <style>
        .tax {
            border: 1px solid rgb(189, 166, 166);
            padding: 10px;
            background-color: rgb(189, 166, 166);
            color: black;

        }

        .m {
            margin: 10 px;
        }

        table tr {
            color: white;
            border: 2px solid white;
            color: black;


        }

        hr {
            height: 4px;
            background-color: black;
            opacity: 2;
        }

        .margin-shop {
            margin-left: 12px;
        }









        .table-container {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table {
            width: 100%;
            table-layout: fixed;
        }

        .table-cell {
            display: table-cell;
            padding: 10px;
            vertical-align: top;
            border: 1px solid white;
            /* Add your desired border style */
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        img.img-fluid {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        address {
            font-style: normal;
        }

        .inner-table {
            width: 100%;
        }

        .inner-cell {
            display: table-cell;
            padding: 10px;
            vertical-align: top;
        }

        .inner-cell.left {
            text-align: left;
            font-weight: bold;
        }

        .inner-cell.right {
            text-align: right;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .table-container {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table {
            width: 100%;
            table-layout: fixed;
        }

        .table-cell {
            display: table-cell;
            padding: 10px;
            vertical-align: top;
            border: 1px solid white;
            /* Add your desired border style */
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .ship-to-section {
            display: table-cell;
            /* Make it a cell to align with the rest of the content */
        }

        .margin-shop {
            margin: 0;
        }

        .tax {
            font-weight: bold;
        }
    </style>

    <body>
        <div class="card">
            <!-- <div class="card-header">
            <a class="button btn btn-outline-success" href="{{ route('customer.pdf', ['id' => $invoice]) }}">Download Pdf</a>
        </div> -->
            <div class="card-body">
                <div class="container-fluid mt-5">
                    <div class="table-container">
                        <table class="custom-table">
                            <tr>
                                <td class="table-cell">
                                    <h4>INVOICE: {{@$invoice->invoice->invoice_number}}</h4>
                                </td>
                                <td class="table-cell text-center">
                                    <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents('assetsNew/dist/img/invoicelogo.png')) }}" alt="Company Logo" class="img-fluid" style="width: 140px">
                                    <address>
                                        <span>Tech Yeah</span>
                                        <span>1905 Marketview Dr.</span>
                                        <p>Yorkville, IL 60560</p>
                                    </address>
                                </td>
                                <td class="table-cell text-right">
                                    <table class="inner-table">
                                        <tr>
                                            <td class="inner-cell left"><span class="font-weight-bold">Customer ID</span></td>
                                            <td class="inner-cell right"><span style="color: #008000;">{{@$invoice->customer->customer_id}}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="inner-cell left"><span class="font-weight-bold">Date</span></td>
                                            <td class="inner-cell right"><span style="color: #ff6600;">{{$invoice->open_date}}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="inner-cell left"><span class="font-weight-bold">Site Number</span></td>
                                            <td class="inner-cell right"><span style="color: #800080;">{{@$invoice->site->site_id}}</span></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="table-container mt-5">
                        <table class="custom-table">
                            <tr>
                                <td class="table-cell">
                                    <h6 class="tax">Bill To:</h6>
                                    <span>{{@$invoice->customer->company_name}}. {{@$invoice->customer->address->address}}, {{@$invoice->customer->address->city}}, {{@$invoice->customer->address->state}}, {{@$invoice->customer->address->zip_code}}, {{@$invoice->customer->address->country}}</span>
                                </td>
                                <td class="table-cell text-center">
                                    <address>
                                        <span>Tax ID: 92-0586580</span>
                                    </address>
                                </td>
                                <td class="table-cell text-left ship-to-section">
                                    <div class="margin-shop">
                                        <h6 class="tax">Ship To:</h6>
                                        <span>{{@$invoice->site->location}}. {{@$invoice->site->city}}, {{@$invoice->site->state}}, {{@$invoice->site->zipcode}}</span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tbody>
                            <tr style="background-color: #f5f5f5;">
                                <th style="padding: 10px; text-align: left;">Job</th>
                                <th style="padding: 10px; text-align: left;">Complete Date</th>
                                <th style="padding: 10px; text-align: left;">Reference</th>
                                <th style="padding: 10px; text-align: left;">Terms</th>
                                <th style="padding: 10px; text-align: left;">Work Order Number</th>
                            </tr>
                            <tr style="background-color: #ffffff;">
                                <td style="padding: 10px; text-align: left;">p3</td>
                                <td style="padding: 10px; text-align: left;">10/6/2023</td>
                                <td style="padding: 10px; text-align: left;">{{@$invoice->customer->project_manager}}</td>
                                <td style="padding: 10px; text-align: left;">{{@$invoice->customer->billing_term}}</td>
                                <td style="padding: 10px; text-align: left;">{{@$invoice->order_id}}</td>
                            </tr>
                        </tbody>
                    </table>


                    <table style="border-collapse: collapse; width: 100%;">
                        <thead>
                            <tr style="background-color: #f5f5f5;">
                                <th style="padding: 10px; text-align: left;">Qty</th>
                                <th style="padding: 10px; text-align: left;">Description</th>
                                <th style="padding: 10px; text-align: left;">Date</th>
                                <th style="padding: 10px; text-align: left;">Price</th>
                                <th style="padding: 10px; text-align: left;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wps as $wp)
                            <tr style="background-color: #ffffff;">
                                <td style="padding: 10px; text-align: left;">{{$wp->quantity}}</td>
                                <td style="padding: 10px; text-align: left;">{{$wp->description}}</td>
                                <td style="padding: 10px; text-align: left;">{{$wp->date}}</td>
                                <td style="padding: 10px; text-align: left;">{{$wp->price}}</td>
                                <td style="padding: 10px; text-align: left;">{{$wp->price}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-3">
                            <h6 style="margin: 0; padding: 10px; background-color: #f5f5f5;">Work Request</h6>
                        </div>
                        <div class="col-md-9">
                            @foreach($wps as $wp)
                            <address style="padding: 2px;">
                                <ul style="list-style-type: square; margin: 0;">
                                    <li style="margin: 5px 0;">{{$wp->work_request}}</li>
                                </ul>
                            </address>
                            @endforeach
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <h6 style="margin: 0; padding: 10px; background-color: #f5f5f5;">Performed</h6>
                        </div>
                        <div class="col-md-9">
                            @foreach($wps as $wp)
                            <address style="padding: 2px;">
                                <ul style="list-style-type: square; margin: 0;">
                                    <li style="margin: 5px 0;">{{$wp->work_perform}}</li>
                                </ul>
                            </address>
                            @endforeach
                        </div>
                    </div>
                    <table class="table table-hover" style="width: 500px; float: right; margin-top: 10px;">
                        <tbody>
                            <tr class="tax">
                                <td style="padding: 5px;">Sub-total</td>
                                <td class="text-right" style="padding: 5px;">${{$price}}</td>
                            </tr>
                            <tr class="tax">
                                <td style="padding: 5px;">Sales Tax</td>
                                <td class="text-right" style="padding: 5px;">$0.26</td>
                            </tr>
                            <tr class="tax">
                                <td style="padding: 5px;">Amount Paid</td>
                                @if($invoice->invoice->status == 1)
                                <td class="text-right" style="padding: 5px;">${{$totalPrice}}</td>
                                @else
                                <td class="text-right" style="padding: 5px;">$00.00</td>
                                @endif
                            </tr>
                            <tr class="tax">
                                <td style="padding: 5px;">Balance Due</td>
                                @if($invoice->invoice->status == 0)
                                <td class="text-right" style="padding: 5px;">${{$totalPrice}}</td>
                                @else
                                <td class="text-right" style="padding: 5px;">$00.00</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </body>
</body>

</html>