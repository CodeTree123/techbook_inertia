<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assetsNew/main_css/customer/work_order_view.css') }}">
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

    .download-btn {
        background-color: #E9814C;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        text-align: center;
        text-decoration: none;
        border-radius: 5px;
        display: block;
        margin: 20px auto;
        cursor: pointer;
    }

    .download-btn:hover {
        background-color: #d16f3d;
    }
</style>

<body>
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-8">
                <img style="width: 250px;  margin-left:-15px; " src="data:image/jpeg;base64,{{ base64_encode(file_get_contents('assetsNew/dist/img/mainlogo.png')) }}">
            </div>
            <div class="col-md-4">
                <p class="header-text text-nowrap" style="font-size:18px;color:black;">1905 Marketview Dr.<br>Suite 226
                    <br>Yorkville, IL 60560
                </p>
                <p style="font-size:18px;" class="header-text"><u>www.techyeahinc.com</u></p>
            </div>
        </div>
        <table class="custom-table mt-4">

            <tbody>
                <tr class="headerline">
                    <th class="header-cell" id="work-order" style="background-color:#1e90ff;font-size:35px;font-weight:bold;">Tech Yeah Work Order #:
                        {{ $views->order_id }}
                    </th>
                </tr>
                <tr>
                    <th><b>Scheduled date: </b> {{ $views->on_site_by }}</th>
                </tr>
                <tr>
                    <th><b>Scheduled time: </b> {{ @$views->scheduled_time }}</th>
                </tr>
                <tr>
                    <th><b>Location Name: </b> {{ @$views->site->location }}
                    </th>
                </tr>
                <tr>
                    <th><b>Address, City, State & Zip:</b> {{ @$views->site->address_1 }},
                        {{ @$views->site->address_2 }} {{ @$views->site->city }}, {{ @$views->site->state }} -
                        {{ @$views->site->zipcode }}
                    </th>
                </tr>
                <tr>
                    <th><b>Site Hours of Operation: </b> {{ @$views->h_operation }}</th>
                </tr>
                <tr>
                    <th><b>Site Contact: </b> {{ @$views->site_contact_name }}</th>
                </tr>
                <tr>
                    <th><b>Main Telephone: </b> {{ @$views->main_tel }}</th>
                </tr>
            </tbody>
        </table>
        <section class=" others-section mt-4">
            <div class="contact-info mt-4">
                <h5 style="font-weight: bold; color: black;">Project Manager: {{ @$views->employee->name }}</h5>
                <h5 style="font-weight: bold; color: black;">Phone: {{ @$views->employee->mobile }}</h5>
                <h5 style="font-weight: bold; color: black;">Email: {{ @$views->employee->email }}</h5>
            </div>

            <div class="my-4">
                <p>Contact Tech Yeah upon arrival at the site to check-in and upon work completion to check-out or your payment may be forfeited.</p>
                <h5>{{ @$views->a_instruction }}</h5>
                <!-- <div>
                    @if (@$views->e_checkin == null)
                    <h5>Earliest Check-in: N/A</h5>
                    @else
                    <h4>Earliest Check-in: {{ @$views->e_checkin }}</h4>
                    @endif
                    @if (@$views->l_checkin == null)
                    <h5>Latest Check-in: N/A</h5>
                    @else
                    <h5>Latest Check-in: {{ @$views->l_checkin }}</h5>
                    @endif
                </div> -->
                <hr>
                <div class="">
                    <h5 class="my-5">REQUIRED TOOLS:</h5>
                    {!! @$views->r_tools !!}
                </div>
            </div>
            <div class="">
                <h5 class="my-5"> <span class="badge-warning py-2">Upon Arrival on Site:</span> </h5>
                <ul>
                    <li class="py-2">Call Tech Yeah to check in.
                    </li>
                    <li class="py-2">Complete the scope of work as described in this Work Order.</li>
                    <li class="py-2">Send deliverables to Tech Yeah.</li>
                    <li class="py-2">Have the onsite contact sign the last page of this Work Order.</li>
                    <li class="py-2">Send photo of signed WO to Tech Yeah.
                    </li>
                    <li class="py-2">Call Tech Yeah to Check Out.
                    </li>
                </ul>
                <div>
                    <h5 class="my-5"> <span class="p-2 ">Scope Of Work: </span> </h5>

                    {!! @$views->scope_work !!}
                </div>
            </div>

    </div>
    @if ($imageFileNames)
    <div class="container">
        <div class="row mb-1">
            <div class="col-md-8">
                <img style="width: 250px;  margin-left:-15px; " src="data:image/jpeg;base64,{{ base64_encode(file_get_contents('assetsNew/dist/img/mainlogo.png')) }}">
            </div>

            <div class="col-md-4">
                <p class="header-text text-nowrap" style="font-size:18px;color:black;">1905 Marketview Dr.<br>Suite 226
                    <br>Yorkville, IL 60560
                </p>
                <p style="font-size:18px;" class="header-text"><u>www.techyeahinc.com</u></p>
            </div>
        </div>
        <div class="mt-5">
            <div style="text-align: center;">
                @foreach (@$imageFileNames as $imageName)
                @php
                $filePath = public_path('imgs/' . $imageName);
                if (file_exists($filePath)) {
                @$imageData = base64_encode(file_get_contents($filePath));
                } else {
                \Log::error('File not found: ' . $filePath);
                @$imageData = null;
                }
                @endphp

                @if (@$imageData)
                <div style="display: inline-block; margin: 5px;">
                    <img src="data:image/jpeg;base64,{{ $imageData }}" alt="Image" style="border: 1px solid #555; width: 100%; height: 100%;">
                    <form action="{{ route('work.order.image.delete') }}" method="POST" style="margin-top: 5px;">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="imageName" value="{{ $imageName }}">
                        <input type="hidden" name="workOrderId" value="{{ $views->id }}"> <!-- Pass the workOrderId -->
                        <button type="submit" style="background-color: red; color: white; border: none; padding: 5px; cursor: pointer;">
                            Delete
                        </button>
                    </form>
                </div>
                @endif
                @endforeach
            </div>
        </div>

        <!-- footer -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top:60px">
            <div>
                <b>Tech Yeah</b>
            </div>
            <div>
                <b>2024</b>
            </div>
        </div>
    </div>
    @endif
    <div class="container">
        <div class="row mb-1">
            <div class="col-md-8">
                <img style="width: 250px;  margin-left:-15px; " src="data:image/jpeg;base64,{{ base64_encode(file_get_contents('assetsNew/dist/img/mainlogo.png')) }}">
            </div>

            <div class="col-md-4">
                <p class="header-text text-nowrap" style="font-size:18px;color:black;">1905 Marketview Dr.<br>Suite 226
                    <br>Yorkville, IL 60560
                </p>
                <p style="font-size:18px;" class="header-text"><u>www.techyeahinc.com</u></p>
            </div>
        </div>
        <div class="mt-5">
            <h5>Description of Work Performed (Please list by date):</h5>
            <div class="mt-4">
                <input type="text" class="lineInput" style=" border: none; border-bottom: 1px solid black;width:770px"><br>
                <input type="text" class="lineInput" style=" border: none; border-bottom: 1px solid black;width:770px"><br>
                <input type="text" class="lineInput" style=" border: none; border-bottom: 1px solid black;width:770px"><br>
                <input type="text" class="lineInput" style=" border: none; border-bottom: 1px solid black;width:770px"><br>
                <input type="text" class="lineInput" style=" border: none; border-bottom: 1px solid black;width:770px"><br>
            </div>
        </div>

        <div class="mt-5">
            <h5>Equipment/Materials Used: </h5>
            <div class="d-flex mt-4">
                <div>
                    <h6>QTY</h6>
                    <input type="text" style=" border: none; border-bottom: 1px solid black;width:145px"><br>
                    <input type="text" style=" border: none; border-bottom: 1px solid black;width:145px"><br>
                    <input type="text" style=" border: none; border-bottom: 1px solid black;width:145px"><br>
                    <input type="text" style=" border: none; border-bottom: 1px solid black;width:145px"><br>
                    <input type="text" style=" border: none; border-bottom: 1px solid black;width:145px"><br>
                    <input type="text" style=" border: none; border-bottom: 1px solid black;width:145px"><br>
                    <input type="text" style=" border: none; border-bottom: 1px solid black;width:145px"><br>
                </div>
                <div class="mx-5">
                    <h6>Description</h6>
                    <input type="text" style=" border: none; border-bottom: 1px solid black;width:580px"><br>
                    <input type="text" style=" border: none; border-bottom: 1px solid black;width:580px"><br>
                    <input type="text" style=" border: none; border-bottom: 1px solid black;width:580px"><br>
                    <input type="text" style=" border: none; border-bottom: 1px solid black;width:580px"><br>
                    <input type="text" style=" border: none; border-bottom: 1px solid black;width:580px"><br>
                    <input type="text" style=" border: none; border-bottom: 1px solid black;width:580px"><br>
                    <input type="text" style=" border: none; border-bottom: 1px solid black;width:580px"><br>
                </div>
            </div>
        </div>

        <div class="flex-container mt-5">
            <div class="form-section" style="width:250px">
                <p class="form-label">Technician Name (print):</p>
                <input type="text" class="form-input" value="" style="width:250px">
            </div>
            <div class="form-section" style="margin-left:250px">
                <p class="form-label">Signature:</p>

                <input type="text" class="form-input" style="width:250px">
            </div>
        </div>
        <p class="mt-5">The work described above has been completed to my satisfaction:</p>
        <div class="flex-container mt-5">
            <div class="form-section">
                <p class="form-label">Customer Name (please print):</p>
                <input type="text" class="form-input" value="" style="width:250px">
            </div>
            <div class="form-section" style="margin-left:250px">
                <p class="form-label">Customer Signature:</p>
                <input type="text" class="form-input" style="width:250px">
            </div>
        </div>
        @php
        $date = date('d-m-y');
        @endphp
        <p class="form-label mt-1">Date:</p>
        <input type="text" class="form-input" value="{{ $date }}" style="width:250px">
        <h6 class="text-center" style="margin-top:100px"><b>If you are not satisfied with the work performed on
                this order, <br>please contact
                Tech Yeah at (833) 832-4002</b></h6>

        <!-- footer -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top:60px">
            <div>
                <b>Tech Yeah</b>
            </div>
            <div>
                <b>2024</b>
            </div>
        </div>
    </div>

    </section>

    <a href="{{ url('pdf/work/order/download/') }}/{{ $views->id }}" class="download-btn w-25">Download PDF</a>
</body>