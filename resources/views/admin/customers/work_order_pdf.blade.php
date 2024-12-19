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

    .custom-table tr:nth-child(odd) {
        background-color: #3498db;
        color: #fff;
    }

    #work-order {
        padding-left: 20px;
    }

    .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-image {
        width: 200px;
        margin-top: 20px;
    }

    .header-text {
        text-align: right;
        margin-top: -100px;
    }

    .header-cell {
        font-size: 30px;
        text-align: center;
    }
</style>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <section class="container">
                <!-- header section -->
                <nav class="navbar navbar-expand-lg" style="margin-top: -30px">
                    <div class="container custom-navbar justify-content-center">
                        <!-- Added 'justify-content-center' class -->
                        <a class="navbar-brand" href="#">
                            <div class="header-flex">
                                <div class="container">
                                    <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents('assetsNew/dist/img/mainlogo.png')) }}" alt="" class="header-image">
                                    <h5 class="header-text">1905 Marketview Dr. Yorkville, IL 60560</h5>
                                </div>
                            </div>
                        </a>
                        <div class="collapse navbar-collapse" id="navbarScroll">
                            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                            </ul>
                            <form class="marketview">
                                <h5 style="float: right" href="http://www.techyeahinc.com" class="header-text">
                                    www.techyeahinc.com</h5>
                            </form>
                        </div>
                    </div>
                </nav>







                <!-- Table Tamplate -->
                <table class="custom-table">
                    <tbody>
                        <tr class="headerline">
                            <th class="header-cell" id="work-order">Tech Yeah Work Order #: {{ $views->order_id }}</th>
                        </tr>
                        <tr>
                            <th>Date: {{ $views->open_date }}</th>
                        </tr>
                        <tr>
                            <th>Location Name & Site ID: {{ $views->site->location }} ({{ $views->site->site_id }})</th>
                        </tr>
                        <tr>
                            <th>Address, City, State & Zip: {{ $views->site->address_1 }},
                                {{ $views->site->address_2 }}, {{ $views->site->city }}, {{ $views->site->state }} -
                                {{ $views->site->zipcode }}
                            </th>
                        </tr>
                        <tr>
                            <th>Site Contact: {{ $views->site_contact_name }}</th>
                        </tr>
                        <tr>
                            <th>Main Telephone #: {{ $views->main_tel }}</th>
                        </tr>
                        <tr>
                            <th>Hours: {{ $views->h_operation }}</th>
                        </tr>
                    </tbody>
                </table>


                <!-- Scheduled -->
                <section class=" others-section">
                    <h3><span class="badge-warning p-2 rounded">Scheduled for {{ $views->date_schedule }}</span></h3>
                    <div class="contact-info mt-1">
                        <h5 style="font-weight: bold; color: black;">Project Manager:
                            {{ $views->customer->project_manager }}
                        </h5>
                        <h5 style="font-weight: bold; color: black;">Phone: {{ $views->customer->phone }}</h5>
                        <h5 style="font-weight: bold; color: black;">Email: {{ $views->customer->email }}</h5>
                    </div>
                    <div class="mt-1">
                        <p>Contact Tech Yeah at {{ $views->customer->phone }} upon arrival at the job site to log in
                            and upon work completion to checkout
                            or you may not receive payment.</p>
                        <h4 class="mt-5">{{ $views->instruction }}</h4>
                        <!-- <p class="mt-5">Failure to obtain authorization from Tech Yeah for additional work WILL result in NON-Payment
                            </p> -->

                        <h5>{{ $views->a_instruction }}</h5>
                        <div>
                            @if ($views->e_checkin == null)
                            <h4>Earliest Check-in: N/A</h4>
                            @else
                            <h4>Earliest Check-in: {{ $views->e_checkin }}</h4>
                            @endif
                            @if ($views->l_checkin == null)
                            <h4>Latest Check-in: N/A</h4>
                            @else
                            <h4>Latest Check-in: {{ $views->l_checkin }}</h4>
                            @endif

                        </div>
                        <div class="required-tools d-flex">
                            <h5>REQUIRED TOOLS:</h5>
                            <h5><u><span class="bg-warning p-2 rounded mx-4">{{ $views->r_tools }}</span></u></h5>
                        </div>
                    </div>
                    <div class="container" style="text-align: left; ">
                        <p style="display: inline;">Tech Yeah</p>
                        <p style="display: inline; float: right; margin-top:-13px;">2023</p>
                    </div>
                </section>
            </section>
            <section class="container">
                <nav class="navbar navbar-expand-lg" style="margin-top: -30px">
                    <div class="container custom-navbar justify-content-center">
                        <!-- Added 'justify-content-center' class -->
                        <a class="navbar-brand" href="#">
                            <div class="header-flex">
                                <div class="container">
                                    <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents('assetsNew/dist/img/mainlogo.png')) }}" alt="" class="header-image">
                                    <h5 class="header-text">1905 Marketview Dr. Yorkville, IL 60560</h5>
                                </div>
                            </div>
                        </a>
                        <div class="collapse navbar-collapse" id="navbarScroll">
                            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                            </ul>
                            <form class="marketview">
                                <h5 style="float: right" href="http://www.techyeahinc.com" class="header-text">
                                    www.techyeahinc.com</h5>
                            </form>
                        </div>
                    </div>
                </nav>

                <div class="scope-of-work">
                    <h3>Scope Of Work:</h3>

                    {!! $views->scope_work !!}

                    <h3>Upon Arrival on Site:</h3>
                    <ol>
                        <li>Check in with PM {{ $views->customer->project_manager }} at {{ $views->customer->phone }}
                            upon arrival.</li>
                        <li>Complete the scope as listed above.</li>
                        <li>Send all pictures to <a href="">{{ $views->customer->email }}</a>.</li>
                        <li>Check out with the onsite contact.</li>
                        <li>Check out with {{ $views->customer->project_manager }} at {{ $views->customer->phone }}.
                        </li>
                    </ol>
                </div>
                <div class="scope-of-work">
                    <h3>Deliverables:</h3>

                    {!! $views->deliverables !!}

                </div>
                <div class="form-group col-md-12">
                    <h3>Random Picture: </h3>
                    @if ($imageFileNames != null)
                    @foreach ($imageFileNames as $imageName)
                    <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents('imgs/' . $imageName)) }}" alt="Random Image" style="border: 2px solid #555; width: 350px; height: 250px; margin: 2px;">
                    @endforeach
                    @else
                    <h3>No Image Found</h3>
                    @endif
                </div>
                <div class="container" style="text-align: left; margin-top:200px;">
                    <p style="display: inline;">Tech Yeah</p>
                    <p style="display: inline; float: right; margin-top:-1px;">2023</p>
                </div>
            </section>

            <section class="container">
                <nav class="navbar navbar-expand-lg" style="margin-top:100px;">
                    <div class="container custom-navbar justify-content-center">
                        <!-- Added 'justify-content-center' class -->
                        <a class="navbar-brand" href="#">
                            <div class="header-flex">
                                <div class="container">
                                    <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents('assetsNew/dist/img/mainlogo.png')) }}" alt="" class="header-image">
                                    <h5 class="header-text">1905 Marketview Dr. Yorkville, IL 60560</h5>
                                </div>
                            </div>
                        </a>
                        <div class="collapse navbar-collapse" id="navbarScroll">
                            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                            </ul>
                            <form class="marketview">
                                <h5 style="float: right" href="http://www.techyeahinc.com" class="header-text">
                                    www.techyeahinc.com</h5>
                            </form>
                        </div>
                    </div>
                </nav>

                <!-- form section -->
                <h3 class="mt-5">Description of Work Performed (Please list by date):</h3>
                @foreach ($wps as $wp)
                <li type="square">({{ $wp->date }})-{{ $wp->work_perform }}</li>
                @endforeach
                <h4>Equipment/Materials Used:</h4>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <th>
                            <h4>Date</h4>
                        </th>
                        <th>
                            <h4>Qty</h4>
                        </th>
                        <th>
                            <h4>Description</h4>
                        </th>
                    </tr>
                    <colgroup>
                        <col style="width: 15%;">
                        <col style="width: 15%;">
                        <col style="width: 85%;">
                    </colgroup>

                    @foreach ($wps as $wp)
                    <tr>
                        <td>
                            <li type="circle" style="text-align: center">({{ $wp->date }})</li>
                        </td>
                        <td>
                            <li type="circle" style="text-align: center">{{ $wp->quantity }}</li>
                        </td>
                        <td>
                            <li type="circle" style="text-align: center">{{ $wp->description }}</li>
                        </td>
                    </tr>
                    @endforeach
                </table>

                <div class="flex-container mt-5">
                    <div class="form-section">
                        <p class="form-label">Technician Name (print):</p>
                        <input type="text" class="form-input" value="{{ @$views->technician->company_name }}">
                    </div>
                    <div class="form-section">
                        <p class="form-label">Signature:</p>
                        <input type="text" class="form-input">
                    </div>
                </div>
                <p class="mt-5">The work described above has been completed to my satisfaction:</p>
                <div class="flex-container mt-3">
                    <div class="form-section">
                        <p class="form-label">Customer Name (please print):</p>
                        <input type="text" class="form-input" value="{{ $views->customer->company_name }}">
                    </div>
                    <div class="form-section">
                        <p class="form-label">Customer Signature:</p>
                        <input type="text" class="form-input">
                    </div>
                </div>
                @php
                $date = date('d-m-y');
                @endphp
                <p class="form-label mt-4">Date:</p>
                <input type="text" class="form-input w-25" value="{{ $date }}">
                <h5 class="text-center mt-5"><b>If you are not satisfied with the work performed on this order,
                        <br>please contact
                        Tech Yeah at 630.474.5234</b></h5>
                <!-- footer -->
                <div class="container" style="text-align: left; margin-top:50px;">
                    <p style="display: inline;">Tech Yeah</p>
                    <p style="display: inline; float: right; margin-top:-1px;">2023</p>
                </div>


            </section>
        </div>
    </div>
</div>
</div>