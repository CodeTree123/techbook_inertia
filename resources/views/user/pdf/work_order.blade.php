<!DOCTYPE html>
<html>

<head>
    <style>
        .custom-table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 5px;
        }

        .custom-table th {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }

        .header-text {
            margin-left: 30px;
        }

        hr {
            all: initial;
            display: block;
            border-bottom: 2px dotted black;
            margin-top: 20px;
        }

        th {
            font-weight: normal;
        }

        #work-order {
            padding-left: 20px;
        }

        @page {
            margin: 30px;

        }

        header {
            /* position: fixed; */
            /* top: -60px; */
            left: 0px;
            right: 0px;
            height: 50px;
            padding: 20px;
        }

        footer {
            position: fixed;
            bottom: -60px;
            height: 50px;
        }

        .second-page-content,
        .fourth-page-content {
            page-break-before: always;
        }

        .first-page-header {
            display: block;
        }

        .second-page-header,
        .third-page-header,
        .fourth-page-header {
            display: none;
        }

        @media print {
            .print>.markdown-preview-view {
                width: 100%;
                text-align: justify !important;
                padding: 20px;
                border: 2px solid lightgrey !important;
            }
        }

        @media print {

            .second-page-content,
            .fourth-page-content {
                margin-top: 100px;
            }

            .first-page-header {
                display: block;
            }

            .second-page-header,
            .third-page-header,
            .fourth-page-header {
                display: none;
            }

            .second-page-content::before,
            .fourth-page-content::before {
                content: "";
                display: block;
                height: 50px;
                margin-bottom: -50px;
            }

            @page :first {
                header {
                    content: element(first-page-header);
                }
            }

            @page :not(:first) {
                header {
                    content: element(second-page-header);
                }
            }

            .first-page-header {
                border: 1px solid black;
            }

            body {
                border: 1px solid black;
            }


            @media print {
                footer {
                    position: fixed;
                    bottom: 0;
                    width: 100%;
                    height: 50px;
                    background-color: white;
                    /* Ensure the footer has a background to avoid overlap */
                    padding: 10px;
                    text-align: center;
                    border-top: 1px solid black;
                    /* Optional: Add a border to separate it from the content */
                }
            }
    </style>
</head>

<body>
    <header>
        <div>
            <div style="clear: both;">
                <img style="width: 160px; float: left; margin-left:-12px;" src="data:image/jpeg;base64,{{ base64_encode(file_get_contents('assetsNew/dist/img/mainlogo.png')) }}" alt="">
                <div style="float: right; text-align: right; ">
                    <h5 class="header-text">1905 Marketview Dr. <br>Suite 226 <br> Yorkville, IL 60560 <br>
                        <u>www.techyeahinc.com</u>
                    </h5>
                </div>
            </div>
        </div>
        <div class="second-page-header">
            <div style="clear: both;">
                <img style="width: 160px; float: left; margin-right: 15px;" src="data:image/jpeg;base64,{{ base64_encode(file_get_contents('assetsNew/dist/img/mainlogo.png')) }}" alt="">
                <div style="float: right; text-align: right;">
                    <h5 class="header-text">Second Page Header</h5>
                </div>
            </div>
        </div>
    </header>
    <footer>
        <p>TECH YEAH</p>

    </footer>

    <main style="padding: 40px;">
        <table class="custom-table" style="margin-top: 50px">
            <tbody>
                <tr class="headerline">
                    <th class="header-cell" id="work-order" style="background-color:#1e90ff;font-size:30px;font-weight:bold;">Tech Yeah Work Order #:
                        {{ $views->order_id }}
                    </th>
                </tr>
                <tr>
                    <th><b>Scheduled date: </b> {{ Carbon\Carbon::parse(@$scheduled->on_site_by)->format('m/d/y') }}</th>
                </tr>
                <tr>
                    <th><b>Scheduled time: </b> {{ Carbon\Carbon::parse(@$scheduled->scheduled_time)->format('h:i a') }}</th>
                </tr>
                <tr>
                    <th><b>Location Name: </b> {{ @$views->site->location }}
                    </th>
                </tr>
                <tr>
                    <th><b>Address, City, State & Zip: </b> {{ @$views->site->address_1 }},
                        {{ @$views->site->address_2 }}, {{ @$views->site->city }}, {{ @$views->site->state }} -
                        {{ @$views->site->zipcode }}
                    </th>
                </tr>
                <tr>
                    <th><b>Site Hours of Operation: </b> {{ @$views->h_operation }}</th>
                </tr>
                <tr>
                    <th><b>Site Contact:</b> {{ @$views->site_contact_name }}</th>
                </tr>
                <tr>
                    <th><b>Main Telephone:</b> {{ $views->main_tel }}</th>
                </tr>
            </tbody>
        </table>
        <!-- ------------------------------------------- -->

        <!-- ---------------------------------------------- -->
        <div style="margin-top: 30px">
            <h4 style="font-weight: bold; color: black; margin-bottom: 5px;">Project Manager:
                {{ @$views->employee->name }}
            </h4>
            <h4 style="font-weight: bold; color: black; margin: -8px 0 5px 0;">Phone:
                {{ @$views->employee->mobile }}
            </h4>
            <h4 style="font-weight: bold; color: black; margin: -8px 0 0 0;">Email:
                {{ @$views->employee->email }}
            </h4>
        </div>
        <!-- ---------------------------------------------- -->
        <p>Contact Tech Yeah upon arrival at the site to check-in and upon work completion to check-out or your payment may be forfeited.</p>
        <!-- ------------------------------------------- -->
        <h5>{{ $views->a_instruction }}</h5>
        <!-- <div>
            @if ($views->e_checkin == null)
            <h4 style="margin-top: -25px">Earliest Check-in: N/A</h4>
            @else
            <h4 style="margin-top: -25px">Earliest Check-in: {{ $views->e_checkin }}</h4>
            @endif
            @if ($views->l_checkin == null)
            <h4 style="margin-top: -15px">Latest Check-in: N/A</h4>
            @else
            <h4 style="margin-top: -15px">Latest Check-in: {{ $views->l_checkin }}</h4>
            @endif
        </div> -->

        <!-- ---------------------------------------- -->
        <div>
            <h5 style="margin-top: -15px">REQUIRED TOOLS: <span>{!! $views->r_tools !!}</span></h5>
        </div>
        <!-- ----------------------------------------------------------- -->
        <div class="second-page-content">
            <h4 style="margin-top: 50px">Upon Arrival on Site: </h4>
            <ul>
                <li class="p-2">Call Tech Yeah to check in.
                </li>
                <li class="p-2">Complete the scope of work as described in this Work Order.</li>
                <li class="p-2">Send deliverables to Tech Yeah.</a>.</li>
                <li class="p-2">Have the onsite contact sign the last page of this Work Order.</li>
                <li class="p-2">Send photo of signed WO to Tech Yeah.
                </li>
                <li class="p-2">Call Tech Yeah to Check Out.
                </li>
            </ul>
            <p><b>Scope Of Work:</b> <span> {!! $views->scope_work !!}</span> </p>
        </div>
        <!-- ----------------------------------------------------------- -->

        @if ($imageFileNames != null)
        @foreach ($imageFileNames as $imageName)
        <div class="third-page-content" style="position: relative; height: 980px">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; text-align: center">
                <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents( $imageName)) }}" alt="Random Image" style="border: 2px solid #555; width: 100%; height: auto; object-fit: contain;">
            </div>
        </div>
        @endforeach
        @else
        @endif


        <div class="fourth-page-content">

            <!-- --------------------------------------------------From --------------------------->


            <div style="clear: both;">
                <img style="width: 160px; float: left; margin-left:-12px;margin-top:12px;" src="data:image/jpeg;base64,{{ base64_encode(file_get_contents('assetsNew/dist/img/mainlogo.png')) }}" alt="">
                <div style="float: right; text-align: right; ">
                    <h5 class="header-text">1905 Marketview Dr. <br>Suite 226 <br> Yorkville, IL 60560 <br>
                        <u>www.techyeahinc.com</u>
                    </h5>
                </div>
            </div>

            <div>
                <h3 style="margin-top: 120px">Description of Work Performed (Please list by date):</h3>

                <input type="text" class="form-input" value="" style="width:650px; border: none; border-bottom: 1px solid black;">
                <br><br>
                <input type="text" class="form-input" value="" style="width:650px; border: none; border-bottom: 1px solid black;">
                <br><br>
                <input type="text" class="form-input" value="" style="width:650px; border: none; border-bottom: 1px solid black;">
                <br><br>
                <input type="text" class="form-input" value="" style="width:650px; border: none; border-bottom: 1px solid black;"><br><br>
                <input type="text" class="form-input" value="" style="width:650px; border: none; border-bottom: 1px solid black;">
            </div>
            <h4>Equipment/Materials Used:</h4>

            <div style="display: table; width: 100%; border-collapse: collapse;">
                <div style="display: table-row;">
                    <div style="display: table-cell; vertical-align: middle;"><b>QTY</b><br><br>______ <br><br>______<br><br>______<br><br>______<br><br>______</div>

                    <div style="display: table-cell; padding: 10px; vertical-align: middle;"><b>Description</b><br><br>__________________________________________________________________________ <br><br>__________________________________________________________________________<br><br>__________________________________________________________________________<br><br>__________________________________________________________________________<br><br>__________________________________________________________________________</div>
                </div>
            </div>

            <!-- ---------------------------- -->
            <br>
            <br>



            <div style="display: table; width: 100%; border-collapse: collapse;">
                <div style="display: table-row;">
                    <div style="display: table-cell; vertical-align: middle;"><b>Technician Name (print):</b><br><br>________________________________________</div>

                    <div style="display: table-cell; padding: 10px; vertical-align: middle;"><b>Signature:</b><br><br>________________________________________</div>
                </div>
            </div>

            <p>The work described above has been completed to my satisfaction:</p>

            <div style="display: table; width: 100%; border-collapse: collapse;">
                <div style="display: table-row;">
                    <div style="display: table-cell; vertical-align: middle;"><b>Customer Name (please print):</b><br><br>________________________________________</div>

                    <div style="display: table-cell; padding: 10px; vertical-align: middle;"><b>Customer Signature:</b><br><br>________________________________________</div>
                </div>
            </div>
            <div style="display: table; width: 100%; border-collapse: collapse;">
                <div style="display: table-row;">
                    <div style="display: table-cell; vertical-align: middle;"><b>Date:</b><br><br>________________________________________</div>
                </div>
            </div>



            <div style="align-items: center;  text-align: center;margin-top:20px">
                <b> If you are not satisfied with the work performed on this order, <br>
                    please contact Tech Yeah at (833) 832-4002</b>
            </div>
            <small style="float:left">Tech Yeah</small>
            <small style="float:right">2024</small>

    </main>
</body>

</html>