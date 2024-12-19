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
            display: none;
        }

        .inv_note {
            display: none;
        }

        .inv_note p {
            margin: 0 !important;
        }

        .inv_note br {
            display: none
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

            td {
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
                display: none;
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
            }

            .jodit-container {
                display: none;
            }


            /* Ensure the invoice fits on the page */
            body {
                margin: 0;
                padding: 0;
            }

            /* Avoid breaking the table rows between pages */
            /* table {
                page-break-inside: avoid;
            } */

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

            */

            /* .top-print-nav {
                page-break-inside: break;
            }

            /* Print layout specific to margins */
            .container-fluid {
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

            .subtotal,
            .due,
            .credit-span,
            .taxprice,
            .shipping {
                width: 140px !important;
            }
        }
    </style>
    <div class="card">
        <div class="card-header d-flex justify-content-end">
            <button class="btn btn-outline-secondary ml-2 no-print" onclick="window.print()">Convert to PDF</button>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div class="row align-items-start top-print-nav">
                    <div class="col-12 row justify-content-center">
                        <img src="{{asset('assetsNew/dist/img/invoicelogo.png')}}" alt="Company Logo" class="img-fluid" class="mx-1" style="width:140px">
                    </div>
                    <div class="col-md-3">
                        <h4>INVOICE: {{@$invoice->invoice->invoice_number}}</h4>

                    </div>
                    <div class="col-md-6 text-center">
                        <address>
                            <span>1905 Marketview Dr. #226</span>

                        </address>
                        <address>
                            <span>Tax ID: 92-0586580</span>
                        </address>
                        <p>Yorkville, IL 60560</p>
                    </div>
                    <div class="col-md-3 text-right">
                        <table class="table mt-0 mb-3" style="border-collapse: collapse; width: 100%;">
                            <tr>
                                <td style="padding: 10px; text-align: left;"><span style="font-weight: bold;"><span class="tax">Customer ID</span></span></td>
                                <td style="padding: 10px; text-align: right;"><span style="color: #008000;">{{@$invoice->customer->customer_id}}</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; text-align: left;"><span class="tax" style="font-weight: bold;">Date</span></td>
                                <td style="padding: 10px; text-align: right;"><span style="color: #ff6600;">{{$invoice->open_date}}</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; text-align: left;"><span class="tax" style=" font-weight: bold;">Site Number</span></td>
                                <td style="padding: 10px; text-align: right;">
                                    <span style="color: #800080;">
                                        {{ isset($invoice->site->site_id) ? explode('-', $invoice->site->site_id)[1] : '' }}
                                    </span>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
                <div class="row align-items-start">
                    <div class="col-md-3">
                        <h6 class="tax">Bill To:</h6>
                        <span>{{@$invoice->customer->company_name}}<br> {{@$invoice->customer->address->address}},<br> {{@$invoice->customer->address->city}}, {{@$invoice->customer->address->state}}-{{@$invoice->customer->address->zip_code}}<br> {{@$invoice->customer->address->country}}</span>
                    </div>
                    <div class="col-md-6 text-center">
                       
                    </div>
                    <div class="col-md-3 text-left">
                        <div class="margin-shop text-start" style="padding-left: 10px;">
                            <h6 class="tax">Ship To:</h6>
                            <span>{{@$invoice->site->location}}<br>{{@$invoice->site->address_1}}<br> {{@$invoice->site->city}}, {{@$invoice->site->state}}-{{@$invoice->site->zipcode}} </span>
                        </div>
                    </div>
                </div>
                <div class="page-container d-flex justify-content-end pb-2">
                    <button type="button" class="btn btn-dark" style="z-index: 99">Page Break</button>
                </div>
                <table class="table table-hover mt-5 info-table">
                    <tbody>
                        <tr style="background-color: rgba(175, 225, 175, 0.5);">
                            <th>Job</th>
                            <th>Completed Date</th>
                            <th>Purchase Order</th>
                            <th>Terms</th>
                            <th>Work Order Number</th>
                        </tr>
                        <tr>
                            @if($invoice->order_type == 1)
                            @if($invoice->priority == 1)
                            <td><input type="text" style="border:none" value="P1"></td>
                            @elseif($invoice->priority == 2)
                            <td><input type="text" style="border:none" value="P2"></td>
                            @elseif($invoice->priority == 3)
                            <td><input type="text" style="border:none" value="P3"></td>
                            @elseif($invoice->priority == 4)
                            <td><input type="text" style="border:none" value="P4"></td>
                            @elseif($invoice->priority == 5)
                            <td><input type="text" style="border:none" value="P5"></td>
                            @else
                            <td><input type="text" style="border:none" value=""></td>
                            @endif
                            @elseif($invoice->order_type == 2)
                            <td><input type="text" style="border:none" value="Project"></td>
                            @elseif($invoice->order_type == 3)
                            <td><input type="text" style="border:none" value="Install"></td>
                            @endif
                            <td><input type="text" value="{{ @$invoice->updated_at->format('m/d/Y') }}" style="border:none"></td>
                            <td><input type="text" value="{{@$invoice->p_o}}" style="border:none"></td>
                            <td><input type="text" value="{{@$invoice->customer->billing_term}}" style="border:none"></td>
                            <td>{{@$invoice->order_id}}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="page-container d-flex justify-content-end pb-2">
                    <button type="button" class="btn btn-dark" style="z-index: 99">Page Break</button>
                </div>
                <div>
                    <div style="text-align: right;" class="addRowBtnCont">
                        <button id="addRowBtn" class="btn btn-success mb-3 plus-button">+</button>
                    </div>
                    <table class="table mt-5 price-table" style="width:100%; justify-content: center;">
                        <thead>
                            <tr style="background-color: rgba(61, 135, 188, 0.1);">
                                <th>Qty.</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Price</th>
                                <th>Amount</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @foreach(@$wps as $wp)
                            <tr class="calc-tr">
                                <td>
                                    <input type="text" class="total-hours p-2" value="{{ str_replace(':', '.', @$wp->total_hours) }}" data-rate="{{ @$wp->calculated_rate }}" style="border:none">
                                </td>
                                <td>
                                    <textarea class="wo-per w-100" style="border:none; height: 32px !important">{{ @$wp->description }}</textarea>
                                </td>
                                <td>
                                    <input type="text" class="date p-2" value="{{ @$wp->date }}" style="border:none">
                                </td>
                                <td>
                                    <div class="input-group">
                                        <span class="p-2">$</span>
                                        <input type="text" class="calculated-rate" value="{{ @$wp->calculated_rate }}" style="border:none">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <span class="p-2">$</span>
                                        <input type="text" class="amount" value="{{ @$wp->amount }}" style="border:none" readonly>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-danger removeBtn" style="display: none; border:none;">✖</button>
                                </td> <!-- Hidden remove button -->
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="page-container d-flex justify-content-end pb-2">
                    <button type="button" class="btn btn-dark" style="z-index: 99">Page Break</button>
                </div>

                <div class="d-flex">
                    <div>
                        <h6 class="fst-italic" style="white-space: nowrap;">Work Requested : </h6>
                    </div>
                    <div class="w-100 px-5 py-0">

                        <address class="w-100">
                            <textarea class="w-100 p-0" name="" id="wo-desc" value="" style="border:none">{{$invoice->scope_work}}</textarea>
                            <div id="scope_work">{!! $invoice->scope_work !!}</div>
                        </address>

                    </div>
                </div>
                <div class="page-container d-flex justify-content-end pb-2">
                    <button type="button" class="btn btn-dark" style="z-index: 99">Page Break</button>
                </div>
                <div class="d-flex">
                    <div>
                        <h6 class="fst-italic" style="white-space: nowrap;">Work Performed : </h6>
                    </div>
                    <div class="w-100 px-5 py-0">

                        @if(isset($invoice->notes) && $invoice->notes->count() > 0)
                        @foreach($invoice->notes->filter(fn($note) => !empty($note->close_out_notes)) as $note)
                        <textarea id="wo-per-{{ $loop->index }}" class="wo_close_out w-100 p-0" style="border:none;">
                        {{ $note->close_out_notes }}
                        </textarea>
                        <div id="inv_note-{{ $loop->index }}" class="inv_note">
                            {!! nl2br(e($note->close_out_notes)) !!}
                        </div>
                        @endforeach
                        @else
                        <textarea class="wo_close_out w-100 p-0" style="border:none"></textarea>
                        @endif
                    </div>
                </div>
                <div class="page-container d-flex justify-content-end pb-2">
                    <button type="button" class="btn btn-dark" style="z-index: 99">Page Break</button>
                </div>
                <!-- <div class="d-flex mt-5 wo-req">
                    <div>
                        <h6 class="fst-italic" style="white-space: nowrap;">Work Requested : </h6>
                    </div>
                    <div class="w-100 px-5 py-0">
                        <address class="w-100">
                            <textarea class="w-100 p-0" name="" id="wo-desc" value="" style="border:none">{{strip_tags($invoice->scope_work)}}</textarea>
                        </address>
                    </div>
                </div> -->
                <!-- <div class="d-flex mt-5 wo-perform">
                    <div>
                        <h6 class="fst-italic" style="white-space: nowrap;">Work Performed</h6>
                    </div>
                    <div class="w-100 px-5 py-0">
                        <address class="w-100">
                            

                        </address>
                    </div>
                </div> -->

                <hr>
                <div class="wo-calc" style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <!-- Left Side Content -->
                    <div style="width: 50%;" style="margin-top:100px">

                        <div class="p-5">
                            <h4><b><i>All Fees Shown In US Dollars</i></b></h4>
                            <h6><i>Thank you for your business!</i></h6>
                        </div>
                    </div>

                    <!-- Right Side Table -->
                    <table class="price-box table table-hover" style="width: 500px;">
                        <tbody>
                            <tr class="tax">
                                <td>Sub-total</td>
                                <td class="d-flex justify-content-end">
                                    <div class="input-group w-auto">
                                        <span class="p-2">$</span>
                                        <input type="text" class="subtotal decimal-input" value="${{$totalPrice}}" style="border:none">
                                    </div>
                                </td>
                            </tr>
                            <tr class="tax">
                                <td>Sales Tax</td>
                                <td class="d-flex justify-content-end">
                                    <div class="input-group w-auto">
                                        <span class="p-2">$</span>
                                        <input type="text" class="taxprice decimal-input" value="0.26" style="border:none">
                                    </div>
                                </td>
                            </tr>
                            <tr class="shippingField">
                                <td>Shipping & Handling</td>
                                <td class="d-flex justify-content-end">
                                    <div class="input-group w-auto">
                                        <span class="p-2">$</span>
                                        <input type="text" class="shipping decimal-input" value="0.00" style="border:none">
                                    </div>
                                </td>
                            </tr>
                            <tr class="tax">
                                <td>Credit</td>
                                <td class="d-flex justify-content-end">
                                    <div class="input-group w-auto d-flex align-items-center justify-content-end">
                                        <span class="p-2">$</span>
                                        <span class="text-danger credit-span" style="width: 190px;">(<input
                                                type="text"
                                                class="credit text-danger p-0 decimal-input"
                                                value="0.00"
                                                style="border:none; width: 3.4ch; outline: 0 !important"
                                                oninput="this.style.width = ((this.value.length + 1) * 0.87) + 'ch';">)</span>
                                    </div>
                                </td>
                            </tr>
                            <tr class="tax">
                                <td>Balance Due</td>
                                <td class="d-flex justify-content-end">
                                    <div class="input-group w-auto">
                                        <span class="p-2">$</span>
                                        <input type="text" class="due decimal-input" style="border:none">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

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
                target.style.width = "3.4ch"; // Reset width for empty input
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

    document.getElementById('addRowBtn').addEventListener('click', function() {
        const tableBody = document.getElementById('tableBody');
        const newRow = document.createElement('tr');
        newRow.classList.add('calc-tr'); // Add class to identify rows for subtotal calculation
        newRow.innerHTML = `
            <td><input type="text" class="total-hours p-2" value="" style="border:none"></td>
            <td><textarea class="wo-per w-100" style="border:none;"></textarea></td>
            <td><input type="text" class="p-2" value="" style="border:none"></td>
            <td><div class="input-group">
                                        <span class="p-2">$</span>
                                        <input type="text" class="calculated-rate" value="{{ @$wp->calculated_rate }}" style="border:none">
                                    </div></td>
            <td><div class="input-group">
                <span class="p-2">$</span>
                <input type="text" class="amount" value="" style="border:none" readonly>
            </div></td>
            <td><button class="btn btn-danger removeBtn" style="border:none;">✖</button></td>
        `;

        tableBody.appendChild(newRow);

        setupTextareaAutoResize();

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

        function updateSubtotal() {
            var subtotal = 0;
            $('.calc-tr').each(function() {
                var rowAmount = parseFloat($(this).find('.amount').val()) || 0;
                subtotal += rowAmount;
            });

            var tax = parseFloat($('.taxprice').val());
            var ship = parseFloat($('.shipping').val());
            var credit = parseFloat($('.credit').val());

            var due = subtotal + tax + ship - credit;

            $('.subtotal').val(subtotal.toFixed(2));
            $('.due').val(due.toFixed(2));
        }

        totalHoursInput.addEventListener('input', updateAmount);
        calculatedRateInput.addEventListener('input', updateAmount);

        newRow.querySelector('.removeBtn').addEventListener('click', function() {
            tableBody.removeChild(newRow);
            updateSubtotal(); // Update subtotal after removing the row
        });
        updateSubtotal();
    });

    document.querySelectorAll('.removeBtn').forEach(function(button) {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const tableBody = document.getElementById('tableBody');
            tableBody.removeChild(row);
            updateSubtotal(); // Update subtotal after removing the row
        });
    });
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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

            var tax = parseFloat($('.taxprice').val());
            var ship = parseFloat($('.shipping').val());

            var due = subtotal + tax + ship;

            $('.subtotal').val(subtotal.toFixed(2));
            $('.due').val(due.toFixed(2));
        }

        $('.calc-tr').each(function() {
            var row = $(this);
            row.find('.total-hours, .calculated-rate').on('input', function() {
                updateAmount(row);
            });
            updateAmount(row);
            updateSubtotal();
        });
    });
</script>
<script>
    $(document).ready(function() {

        $('.credit').on('input', function() {
            checkoutTotal();
        });

        $('.taxprice').on('input', function() {
            checkoutTotal();
        });

        $('.shipping').on('input', function() {
            checkoutTotal();
        });

        function checkoutTotal() {
            var subtotal = parseFloat($('.subtotal').val()) || 0;
            var tax = parseFloat($('.taxprice').val()) || 0;
            var ship = parseFloat($('.shipping').val()) || 0;
            var credit = parseFloat($('.credit').val()) || 0;

            var due = subtotal + tax + ship - credit;

            $('.due').val(due.toFixed(2));
        }

        checkoutTotal();
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
                const parentDiv = button.closest('.page-container'); // Find the parent .page-container
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
@endsection