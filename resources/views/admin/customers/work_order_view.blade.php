@extends('admin.layoutsNew.app')
@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endsection
@section('content')
    <link rel="stylesheet" href="{{ asset('assetsNew/main_css/customer/work_order_view.css') }}">
    <div class="content-wrapper" style="background-color: white;">
        @include('admin.includeNew.breadcrumb')
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between align-items-center">
                        <h3><span class="badge badge-success">{{ @$views->customer->company_name }} Work Order</span></h3>
                        @if (auth()->guard('admin')->user()->role_id != Status::DISPATCH_TEAM)
                            <div class="d-flex">
                                @if ($btnWorkOperation)
                                    <button class="button btn btn-warning m-2" id="#" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop">Add Work Performed</button>
                                @else
                                @endif
                                @if ($btnInvoice)
                                    <a href="{{ url('/customer/invoice') }}/{{ $views->id }}"
                                        class="button btn btn-outline-success m-2">Invoice</a>
                                    <a href="{{ route('customer.work.order.pdf', ['id' => $views->id]) }}"
                                        class="button btn btn-outline-success m-2">Download Pdf</a>
                                @else
                                @endif
                                @if ($btnDispatch || $btnOpen)
                                    <a href="{{ url('/customer/order/edit') }}/{{ $views->id }}"
                                        class="button btn btn-outline-danger m-2">Break Order</a>
                                @else
                                @endif
                                @if ($btnClosed)
                                    <a href="{{ route('customer.work.order.pdf', ['id' => $views->id]) }}"
                                        class="button btn btn-outline-success m-2">Download Pdf</a>
                                    <a href="{{ url('/customer/invoice') }}/{{ $views->id }}"
                                        class="button btn btn-outline-success m-2">Invoice</a>
                                @else
                                @endif
                                <a href="{{ url('/customer/customer/zone') }}/{{ @$views->customer->id }}"
                                    class="button btn btn-outline-primary m-2">Go Back</a>
                            @elseif(auth()->guard('admin')->user()->role_id == Status::DISPATCH_TEAM ||
                                    auth()->guard('admin')->user()->role_id == Status::SALES_TEAM)
                                <div class="d-flex">
                                    @if (auth()->guard('admin')->user()->role_id == Status::DISPATCH_TEAM)
                                        <a href="{{ route('customer.work.order.pdf', ['id' => $views->id]) }}"
                                            class="button btn btn-outline-success m-2">Pdf</a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <section class="container">
                        <!-- header section -->
                        <nav class="navbar navbar-expand-lg" style="margin-top:-30px">
                            <div class="container-fluid">
                                <a class="navbar-brand" href="#"><img
                                        src="https://techyeahinc.com/assets/media/logo/tech_yeah_logo.png" alt=""
                                        style="width:170px;margin-left: -17px;"></a>
                                <div class="collapse navbar-collapse" id="navbarScroll">
                                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll"
                                        style="--bs-scroll-height: 100px;">
                                    </ul>
                                    <form class="d-flex" role="search">
                                        <div>
                                            <h5>1905 Marketview Dr. Yorkville, IL 60560</h5>
                                            <h5 style="float: right" href="http://www.techyeahinc.com">www.techyeahinc.com
                                            </h5>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </nav>
                        <!-- Table Tamplate -->
                        <table class="table table-bordered table-hover mt-5 ">
                            <tbody>
                                <tr class="text-center">
                                    <th class="header-table bg-primary">Tech Yeah Work Order #: {{ $views->order_id }}</th>
                                </tr>
                                <tr>
                                    <th>Date: {{ $views->open_date }}</th>
                                </tr>
                                <tr>
                                    <th>Location Name & Site ID: {{ @$views->site->location }} (
                                        {{ @$views->site->site_id }} )</th>
                                </tr>
                                <tr>
                                    <th>Address :
                                        {{ implode(
                                            ', ',
                                            array_filter([
                                                @$views->site->address_1,
                                                @$views->site->address_2,
                                                @$views->site->city,
                                                @$views->site->state,
                                                @$views->site->zipcode,
                                            ]),
                                        ) }}
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
                        <section class="mt-3 others-section">
                            <h3><span class="badge-warning p-2 rounded">Scheduled for {{ $views->date_schedule }}</span>
                            </h3>
                            <div class="contact-info mt-5">
                                <h5 style="font-weight: bold; color: black;">Project Manager:
                                    {{ @$views->customer->project_manager }}</h5>
                                <h5 style="font-weight: bold; color: black;">Phone: {{ @$views->customer->phone }}</h5>
                                <h5 style="font-weight: bold; color: black;">Email: {{ @$views->customer->email }}</h5>
                            </div>
                            <div class="mt-3">
                                <p>Contact Tech Yeah at {{ @$views->customer->phone }} upon arrival at the job site to log
                                    in and upon work completion to checkout
                                    or you may not receive payment.</p>
                                <h4 class="mt-5">{{ $views->instruction }}</h4>
                                <h5 class="mt-4">{{ $views->a_instruction }}</h5>
                                <div class="mt-5">
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
                                <span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span>
                                <span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span>
                                <span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span>
                                <span>&#43;</span><span>&#43;</span>
                                <span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span>
                                <span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span>
                                <span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span>
                                <span>&#43;</span><span>&#43;</span>
                                <span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span>
                                <span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span>
                                <span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span>
                                <span>&#43;</span><span>&#43;</span>
                                <span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span>
                                <span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span>
                                <span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span>
                                <span>&#43;</span><span>&#43;</span>
                                <span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;</span><span>&#43;

                                    <div class="required-tools d-flex">
                                        <h5>REQUIRED TOOLS:</h5>
                                        <h5><u><span class="bg-warning p-2 rounded mx-4">{{ $views->r_tools }}</span></u>
                                        </h5>

                                    </div>
                            </div>
                            <div class="flex-container mt-5">
                                <p>Tech Yeah</p>
                                <p class="right-align">2023</p>
                            </div>
                        </section>
                    </section>
                    <section class="container">
                        <nav class="navbar navbar-expand-lg " style="margin-top:-30px">
                            <div class="container-fluid">
                                <a class="navbar-brand" href="#"><img
                                        src="https://techyeahinc.com/assets/media/logo/tech_yeah_logo.png" alt=""
                                        style="width:170px;margin-left: -17px;"></a>
                                <div class="collapse navbar-collapse" id="navbarScroll">
                                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll"
                                        style="--bs-scroll-height: 100px;">
                                    </ul>
                                    <form class="d-flex" role="search">
                                        <div>
                                            <h5>1905 Marketview Dr. Yorkville, IL 60560</h5>
                                            <h5 style="float: right" href="http://www.techyeahinc.com">www.techyeahinc.com
                                            </h5>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </nav>
                        <div class="scope-of-work">
                            <h3>Scope Of Work:</h3>

                            {!! $views->scope_work !!}


                            <h3>Upon Arrival on Site:</h3>
                            <ol>
                                <li>Check in with PM {{ @$views->customer->project_manager }} at
                                    {{ @$views->customer->phone }} upon arrival.</li>
                                <li>Complete the scope as listed above.</li>
                                <li>Send all pictures to <a href="">{{ @$views->customer->email }}</a>.</li>
                                <li>Check out with the onsite contact.</li>
                                <li>Check out with {{ @$views->customer->project_manager }} at
                                    {{ @$views->customer->phone }}.</li>
                            </ol>
                        </div>
                        <div class="del">
                            <h3>Deliverables:</h3>
                            {!! $views->deliverables !!}
                        </div>
                        <div class="form-group col-md-12">
                            <h3>Random Picture: </h3>
                            @if ($imageFileNames != null)
                                @foreach ($imageFileNames as $imageName)
                                    <img src="{{ asset('imgs/' . $imageName) }}" alt="Random Image"
                                        style="border: 2px solid #555; width: 350px; height: 250px; margin: 2px;">
                                @endforeach
                            @else
                                <h3>No Image Found</h3>
                            @endif
                        </div>
                        <div class="flex-container mt-5">
                            <p>Tech Yeah</p>
                            <p class="right-align">2023</p>
                        </div>
                    </section>

                    <section class="container">
                        <nav class="navbar navbar-expand-lg  " style="margin-top:-30px">
                            <div class="container-fluid">
                                <a class="navbar-brand" href="#"><img
                                        src="https://techyeahinc.com/assets/media/logo/tech_yeah_logo.png" alt=""
                                        style="width:170px;margin-left: -17px;"></a>
                                <div class="collapse navbar-collapse" id="navbarScroll">
                                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll"
                                        style="--bs-scroll-height: 100px;">
                                    </ul>
                                    <form class="d-flex" role="search">
                                        <div>
                                            <h5>1905 Marketview Dr. Yorkville, IL 60560</h5>
                                            <h5 style="float: right" href="http://www.techyeahinc.com">www.techyeahinc.com
                                            </h5>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </nav>
                        <!-- form section -->
                        <h3 class="mt-5">Description of Work Performed (Please list by date):</h3>
                        @foreach ($wps as $wp)
                            <input type="text" class="form-input"
                                value="({{ $wp->date }})-{{ $wp->work_perform }}">
                        @endforeach
                        <h4>Equipment/Materials Used:</h4>
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <th style="font-size:16px">Date</th>
                                <th style="font-size:16px">Qty</th>
                                <th style="font-size:16px">Description</th>
                            </tr>
                            <colgroup>
                                <col style="width: 15%;">
                                <col style="width: 15%;">
                                <col style="width: 85%;">
                            </colgroup>
                            @foreach ($wps as $wp)
                                <tr>
                                    <td><input type="text" class="form-input" style="width: 100%;"
                                            value="({{ $wp->date }})"></td>
                                    <td><input type="text" class="form-input" style="width: 100%;"
                                            value="{{ $wp->quantity }}"></td>
                                    <td><input type="text" class="form-input" style="width: 100%;"
                                            value="{{ $wp->description }}"></td>
                                </tr>
                            @endforeach
                        </table>

                        <div class="flex-container mt-5">
                            <div class="form-section">
                                <p class="form-label">Technician Name (print):</p>
                                <input type="text" class="form-input"
                                    value="{{ @$views->technician->company_name }}">
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
                                <input type="text" class="form-input" value="{{ @$views->customer->company_name }}">
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
                        <div class="flex-container">
                            <p>Tech Yeah</p>
                            <p class="right-align">2023</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!--add Work Performed Modal -->
        <div class="modal fade text-dark " id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-gray d-flex justify-content-between">
                        <h5 class="modal-title" id="staticBackdropLabel">Add Work Performed</h5>
                        <button type="button" class="btn-close" id="close-modal" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="myForm">
                            <div class="row">
                                <input type="hidden" name="work_order_id" value="{{ $views->id }}"
                                    id="work_order_id" readonly>
                                <div class="form-group col-md-6">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" name="quantity"
                                        value="{{ old('quantity') }}" id="quantity">
                                    <span style="color:red; font-size:16px" id="quantity-error"></span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" class="form-control" name="price"
                                        value="{{ old('price') }}" id="price">
                                    <span style="color:red; font-size:16px" id="price-error"></span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" class="form-control" cols="30" rows="5" value="{{ old('description') }}"
                                        id="description"></textarea>
                                    <span style="color:red; font-size:16px" id="description-error"></span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="work_request" class="form-label">Work request</label>
                                    <textarea name="work_request" class="form-control" cols="30" rows="5" value="{{ old('work_request') }}"
                                        id="work_request"></textarea>
                                    <span style="color:red; font-size:16px" id="work_request-error"></span>

                                </div>
                                <div class="form-group col-md-12">
                                    <label for="work_perform" class="form-label">Work Perform</label>
                                    <textarea name="work_perform" class="form-control" cols="30" rows="5" value="{{ old('work_perform') }}"
                                        id="work_perform"></textarea>
                                    <span style="color:red; font-size:16px" id="work_perform-error"></span>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gray btn-block" id="save">Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            $(document).on('click', '#save', function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                let id = $('#work_order_id').val();
                let quantity = $('#quantity').val();
                let price = $('#price').val();
                let description = $('#description').val();
                let work_request = $('#work_request').val();
                let work_perform = $('#work_perform').val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('customer.work.perform') }}",
                    data: {
                        'work_order_id': id,
                        'quantity': quantity,
                        'price': price,
                        'description': description,
                        'work_request': work_request,
                        'work_perform': work_perform,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.message) {
                            // Request was successful
                            $("#quantity-error,#price-error,#description-error,#work_request-error,#work_perform-error")
                                .empty();
                            iziToast.success({
                                message: response.message,
                                position: "topRight"
                            });
                        }
                    },
                    error: function(response) {
                        if (response.status == 422) {
                            // Validation error
                            let errors = response.responseJSON.errors;
                            $("#quantity-error").text(errors.quantity);
                            $("#price-error").text(errors.price);
                            $("#description-error").text(errors.description);
                            $("#work_request-error").text(errors.work_request);
                            $("#work_perform-error").text(errors.work_perform);
                        } else {
                            // Handle other errors (e.g., network errors, server errors)
                            iziToast.error({
                                message: "An error occurred. Please try again later.",
                                position: "topRight"
                            });
                        }
                    }
                });
            });
            document.getElementById('close-modal').addEventListener('click', function() {
                window.location.reload();
            });
        </script>
    @endsection
