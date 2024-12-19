@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jodit/4.2.41/jodit.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <body>
        <style>
            input,
            select,
            textarea {
                border: 0;
                border-bottom: 1px solid #DEE2E6 !important;
                outline: 0;
            }

            .total-bg {
                background-color: rgba(248, 249, 250, 1);
                min-height: calc(100vh - 90px);

            }

            .or-div {
                position: relative;
            }

            .text-divider {
                margin: 2em 0;
                line-height: 0;
                text-align: center;
            }

            .text-divider span {
                background-color: #fff;
                padding: 1em;
            }

            .text-divider:before {
                content: " ";
                display: block;
                border-top: 1px solid #e3e3e3;
                border-bottom: 1px solid #f7f7f7;
            }

            .stage {
                border: 1px solid #AFE1AF;
                font-weight: 700;
            }

            .stage-primary {
                background-color: #AFE1AF;
                border: #AFE1AF;
            }

            .tab {
                border: 1px solid #9BCFF5;
                font-weight: 700;
                transition: 0.2s;
            }

            .tab-primary {
                background-color: #9BCFF5;
            }

            .tab:hover {
                background-color: #9BCFF5;
            }

            .tab-content {
                display: none;
            }

            .tab-content.tab-active {
                display: block;
            }

            .save-btn {
                display: none;
                background-color: #1FE1AF;
            }

            .cancel-btn {
                display: none;
            }

            .nrml-inp {
                display: none;
            }

            .nrml-inp {
                border: none;
                border-bottom: 1px solid black;
                outline: 0 !important;
            }

            .contact-add {
                display: none;
            }

            .tech-add {
                display: none;
            }

            .shipment-add {
                display: none;
            }

            .tech-part-add {
                display: none;
            }

            .closeout-note-add {
                display: none;
            }

            .schedule-add {
                display: none;
            }

            #moreTechCont {
                display: none;
            }

            .file-preview {
                display: inline-block;
                padding: 10px;
                text-align: center;
                border: 1px solid #ddd;
                border-radius: 4px;
                margin-right: 10px;
                width: 120px;
                position: relative;
                /* To position the delete button */
            }

            .preview-img {
                max-width: 100px;
                max-height: 100px;
                margin-bottom: 5px;
            }

            .preview-pdf,
            .preview-file {
                font-size: 50px;
                color: gray;
                margin-bottom: 5px;
            }

            .file-name {
                font-size: 14px;
                color: #333;
                margin-top: 5px;
                word-wrap: break-word;
                text-align: center;
            }

            .delete-btn {
                position: absolute;
                top: 5px;
                right: 5px;
                background: none;
                border: none;
                font-size: 18px;
                color: red;
                cursor: pointer;
            }

            .delete-btn:hover {
                color: darkred;
            }

            .invisible {
                display: none;
            }

            .bg-secondary {
                border: 1px solid #6c757d !important;
            }

            .select2-container {
                display: none;
            }

            .select2-selection {
                border: 0 !important;
                border-radius: 0 !important;
                border-bottom: 1px solid #aaa !important;
            }
        </style>
        <div class="container-fluid total-bg">
            <div class="row">
                <div class="col-2 border-end border-bottom px-3 py-2">
                    <h2 class="fw-bold" style="font-size: 24px;">#{{ $wo->order_id }}</h2>
                    <div class="d-flex align-items-center gap-2">
                        @if ($wo->stage == 7)
                            <span class="fw-bold">Cancelled</span>
                        @elseif($wo->is_hold == 0)
                            @if ($wo->stage == 1)
                                <span class="fw-bold">New</span>
                            @elseif($wo->stage == 2)
                                <span class="fw-bold">Need Dispatch</span>
                            @elseif($wo->stage == 3)
                                <span class="fw-bold">Dispatched</span>
                            @elseif($wo->stage == 4)
                                <span class="fw-bold">Closed</span>
                            @elseif($wo->stage == 5)
                                <span class="fw-bold">Billing</span>
                            @endif
                        @else
                            <span class="fw-bold">On Hold</span>
                        @endif
                        @if ($wo->status == 1)
                            <span class="badge status-badge d-flex align-items-center gap-1"
                                style="color: #148E6F; background-color: #1FE1AF75; height: max-content">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #148E6F; ">
                                </div>
                                Pending
                            </span>
                        @elseif($wo->status == 2)
                            <span class="badge status-badge d-flex align-items-center gap-1"
                                style="color: #148E6F; background-color: #1FE1AF75;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #148E6F; ">
                                </div>
                                Contacted
                            </span>
                        @elseif($wo->status == 3)
                            <span class="badge status-badge d-flex align-items-center gap-1"
                                style="color: #148E6F; background-color: #1FE1AF75;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #148E6F; ">
                                </div>
                                Confirmed
                            </span>
                        @elseif($wo->status == 4)
                            <span class="badge status-badge d-flex align-items-center gap-1"
                                style="color: #148E6F; background-color: #1FE1AF75;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #148E6F; ">
                                </div>
                                At Risk
                            </span>
                        @elseif($wo->status == 5)
                            <span class="badge status-badge d-flex align-items-center gap-1"
                                style="color: #148E6F; background-color: #1FE1AF75;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #148E6F; ">
                                </div>
                                Delayed
                            </span>
                        @elseif($wo->status == 6)
                            <span class="badge status-badge d-flex align-items-center gap-1"
                                style="color: #148E6F; background-color: #1FE1AF75;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #148E6F; ">
                                </div>
                                On Hold
                            </span>
                        @elseif($wo->status == 7)
                            <span class="badge status-badge d-flex align-items-center gap-1"
                                style="color: #148E6F; background-color: #1FE1AF75;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #148E6F; ">
                                </div>
                                En Route
                            </span>
                        @elseif($wo->status == 8)
                            <span class="badge status-badge d-flex align-items-center gap-1"
                                style="color: #148E6F; background-color: #1FE1AF75;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #148E6F; ">
                                </div>
                                Checked-In
                            </span>
                        @elseif($wo->status == 9)
                            <span class="badge status-badge d-flex align-items-center gap-1"
                                style="color: #148E6F; background-color: #1FE1AF75;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #148E6F; ">
                                </div>
                                Checked-Out
                            </span>
                        @elseif($wo->status == 10)
                            <span class="badge status-badge d-flex align-items-center gap-1"
                                style="color: #148E6F; background-color: #1FE1AF75;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #148E6F; ">
                                </div>
                                Needs Approval
                            </span>
                        @elseif($wo->status == 11)
                            <span class="badge status-badge d-flex align-items-center gap-1"
                                style="color: #148E6F; background-color: #1FE1AF75;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #148E6F; ">
                                </div>
                                Issue
                            </span>
                        @elseif($wo->status == 12)
                            <span class="badge status-badge d-flex align-items-center gap-1"
                                style="color: #148E6F; background-color: #1FE1AF75;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #148E6F; ">
                                </div>
                                Approved
                            </span>
                        @elseif($wo->status == 13)
                            <span class="badge status-badge d-flex align-items-center gap-1"
                                style="color: #148E6F; background-color: #1FE1AF75;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #148E6F; ">
                                </div>
                                Invoiced
                            </span>
                        @elseif($wo->status == 14)
                            <span class="badge status-badge d-flex align-items-center gap-1"
                                style="color: #148E6F; background-color: #1FE1AF75;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #148E6F; ">
                                </div>
                                Past Due
                            </span>
                        @elseif($wo->status == 15)
                            <span class="badge status-badge d-flex align-items-center gap-1"
                                style="color: #148E6F; background-color: #1FE1AF75;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #148E6F; ">
                                </div>
                                Paid
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-4 border-end border-bottom px-3 py-2">
                    <h2 class="fw-bold text-center" style="font-size: 24px;">{{ @$wo->customer->company_name }}</h2>
                    <p style="color: #808080;" class="mb-0">Purchase Order : #{{ $wo->p_o }}</p>
                    <p style="color: #808080;" class="mb-0">Problem Code : #595</p>
                    <p style="color: #808080;" class="mb-0">Resolution Code : #59552</p>
                </div>

                <div class="col-3 border-end border-bottom px-3 py-2">
                    <h2 class="fw-bold text-center" style="font-size: 24px;">WO Manager</h2>
                    <p class="fw-bold text-center">{{ @$wo->employee->name }}</p>
                </div>

                <div class="col-3 border-end border-bottom px-3 py-2">
                    <div class="d-flex justify-content-start align-items-center gap-2">
                        <i class="fa-solid fa-circle-user" style="font-size: 25px;"></i>
                        <h2 class="fw-bold" style="font-size: 24px;">Field Tech</h2>
                    </div>
                    <p style="color: #808080;">{{ @$wo->technician->company_name }}; ID :
                        {{ @$wo->technician->technician_id }}</p>
                    @if (@$wo->technician->phone)
                        <a href="callto:{{ @$wo->technician->phone }}"> <i class="fa-solid fa-phone"
                                style="font-size: 14px;"></i> {{ @$wo->technician->phone }}</a><br>
                    @endif
                    @if (@$wo->technician->email)
                        <a href="mailto:{{ @$wo->technician->email }}"> <i class="fa-regular fa-envelope"></i>
                            {{ @$wo->technician->email }}</a>
                    @endif
                </div>

                <div class="col-2 border-end border-bottom px-3 py-2">
                    <p style="font-size: 12px; font-weight: 600;" class="text-center mb-0">Requested By</p>
                    <p class="text-center pb-3">{{ @$wo->requested_by }}</p>
                    @if ($wo->order_type == 1)
                        <p class="text-divider"><span
                                style="font-weight: 600; background-color: rgba(248, 249, 250, 1)">Service</span></p>
                    @elseif($wo->order_type == 2)
                        <p class="text-divider"><span
                                style="font-weight: 600; background-color: rgba(248, 249, 250, 1)">Project</span></p>
                    @elseif($wo->order_type == 3)
                        <p class="text-divider"><span
                                style="font-weight: 600; background-color: rgba(248, 249, 250, 1)">Install</span></p>
                    @endif
                </div>

                <div class="col-4 border-end border-bottom px-3 py-2">
                    <div class="d-flex justify-content-start align-items-center gap-2">
                        <i class="fa-solid fa-location-dot" style="font-size: 16px; color: #00BABA;"></i>
                        <h2 class="fw-bold mb-0" style="font-size: 16px;">Location : {{ @$wo->site->location }}</h2>
                    </div>
                    <p style="color: #808080;">Site: {{ @$wo->site->address_1 }}; {{ @$wo->site->city }},
                        {{ @$wo->site->state }} {{ @$wo->site->zipcode }}</p>
                </div>

                @if (@$wo->checkInOut->first()->check_in)
                    <div class="col-3 border-end border-bottom px-3 py-2">
                        <p class="fw-bold mb-0" style="font-size: 16px;">Time Logged</p>
                        <p style="color: #808080;">{{ @$wo->checkInOut->sum('total_hours') }} Hours</p>
                    </div>
                @else
                    <div class="col-3 border-end border-bottom px-3 py-2">
                        <p class="fw-bold mb-0" style="font-size: 16px;">Scheduled Time</p>
                        <!-- <p style="color: #808080;">{{ @$wo->on_site_by }} at {{ Carbon\Carbon::parse(@$wo->scheduled_time)->format('h:i A') }}</p> -->

                        @if (@$wo->schedules && ($schedule = @$wo->schedules->where('on_site_by', '>=', Carbon\Carbon::now())->first()))
                            <p style="color: #808080;">
                                {{ Carbon\Carbon::parse(@$schedule->on_site_by)->format('m-d-y') }} at
                                {{ Carbon\Carbon::parse(@$schedule->scheduled_time)->format('h:i A') }}
                            </p>
                        @else
                            <p style="color: #808080;">No Schedule Found</p>
                        @endif

                    </div>
                @endif

                <div class="col-3 border-end border-bottom px-3 py-2">
                    <p class="fw-bold mb-0 text-center" style="font-size: 16px;">Support Ticket</p>
                </div>

                <div class="col-2 d-flex gap-1 px-3 py-4">

                    <button type="button" class="btn btn-outline-dark" data-toggle="modal" style="height: max-content;"
                        data-target="#holdModal" @disabled($wo->stage == 7)>
                        {{ $wo->is_hold == 0 ? 'Add Hold' : 'Remove Hold' }}
                    </button>

                    <div class="modal fade" id="holdModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="position-relative">
                                        <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                                        <span class="btn btn-light position-absolute end-0 top-0" data-dismiss="modal"
                                            aria-label="Close">
                                            X
                                        </span>
                                    </div>
                                    <p>You want to hold the work order!</p>
                                </div>
                                <div class="modal-footer">

                                    <form class="w-100" action="{{ route('user.wo.hold', $wo->id) }}" method="post">
                                        @csrf
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" name="holding_note" placeholder="Leave a comment here" id="floatingTextarea2"
                                                style="height: 100px">{{ @$wo->holding_note }}</textarea>
                                            <label for="floatingTextarea2">Holding Note</label>
                                        </div>
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit"
                                            class="btn btn-dark">{{ $wo->is_hold == 0 ? 'Add Hold' : 'Remove Hold' }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-danger" data-toggle="modal" style="height: max-content;"
                        data-target="#exampleModal" @disabled($wo->stage == 7)>
                        {{ $wo->stage != 7 ? 'Cancel' : 'Cancelled' }}
                    </button>


                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="position-relative">
                                        <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                                        <span class="btn btn-light position-absolute end-0 top-0" data-dismiss="modal"
                                            aria-label="Close">
                                            X
                                        </span>
                                    </div>
                                    <p>You want to cancel the work order!</p>
                                </div>
                                <div class="modal-footer">

                                    <form class="w-100" action="{{ route('user.wo.cancel', $wo->id) }}" method="post">
                                        @csrf
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" name="cancelling_note" placeholder="Leave a comment here" id="floatingTextarea2"
                                                style="height: 100px">{{ @$wo->cancelling_note }}</textarea>
                                            <label for="floatingTextarea2">Cancelling Note</label>
                                        </div>
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit"
                                            class="btn btn-danger">{{ $wo->stage != 7 ? 'Cancel' : 'Cancelled' }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-8 px-3 py-4">
                    @if ($wo->stage != 7)
                        <div class="btn-group w-100" role="group" aria-label="Basic example">
                            <button type="button"
                                class="btn stage {{ $wo->is_hold == 1 ? 'bg-secondary' : (in_array($wo->stage, [1, 2, 3, 4, 5]) ? 'stage-primary' : '') }} w-100">NEW</button>
                            <button type="button"
                                class="btn stage {{ $wo->is_hold == 1 ? 'bg-secondary' : (in_array($wo->stage, [2, 3, 4, 5]) ? 'stage-primary' : '') }} w-100">Needs
                                Dispatch</button>
                            <button type="button"
                                class="btn stage {{ $wo->is_hold == 1 ? 'bg-secondary' : (in_array($wo->stage, [3, 4, 5]) ? 'stage-primary' : '') }} w-100">
                                Dispatched</button>
                            <button type="button"
                                class="btn stage {{ $wo->is_hold == 1 ? 'bg-secondary' : (in_array($wo->stage, [4, 5]) ? 'stage-primary' : '') }} w-100">Closed</button>
                            <button type="button"
                                class="btn stage {{ $wo->is_hold == 1 ? 'bg-secondary' : (in_array($wo->stage, [5]) ? 'stage-primary' : '') }} w-100">
                                Billings</button>
                        </div>
                    @else
                        <div class="alert alert-danger mb-0" style="padding-top: 0.375rem; padding-bottom: 0.375rem"
                            role="alert">
                            <i>Note: {{ @$wo->cancelling_note }}</i>
                        </div>
                    @endif
                    @if (@$wo->holding_note && @$wo->is_hold == 1)
                        <div class="alert alert-dark mb-0 mt-2" style="padding-top: 0.375rem; padding-bottom: 0.375rem"
                            role="alert">
                            <i>Note: {{ @$wo->holding_note }}</i>
                        </div>
                    @endif
                </div>

                <div class="col-2 d-flex gap-1 justify-content-end px-3 py-4">
                    <!-- View Button -->
                    <a href="{{ route('work.order.pdf.user.view', $wo->id) }}" class="btn"
                        style="background-color: #AFE1AF; height: max-content" id="woViewButton">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </a>

                    <!-- Revert Form -->
                    <form action="{{ route('user.wo.backStatus', $wo->id) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary"
                            @disabled($wo->stage == 7 || $wo->status == 1)>Revert</button>
                    </form>

                    <!-- Next Form -->
                    <form action="{{ route('user.wo.nextStatus', $wo->id) }}" method="post" onsubmit="showPreloader()">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary" @disabled($wo->stage == 7 || $wo->status == 15)>
                            Next
                        </button>
                    </form>
                </div>


                @if ($wo->status == 4)
                    <div class="col-12">
                        <div class="alert alert-warning mb-0" role="alert">
                            No Technician checked in yet !!!
                            <p>Want to reschedule time?</p>
                            <button type="button" class="btn btn-warning" style="font-weight: 600;"
                                data-bs-toggle="modal" data-bs-target="#rescheduleModal">Rescedule</button>

                            <div class="modal fade" id="rescheduleModal" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Rescedule time</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="rescheduleForm" action="{{ route('user.wo.reSchedule', $wo->id) }}"
                                                method="post">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="exampleFormControlInput1" class="form-label">Schedule Date
                                                        ({{ @$wo->on_site_by }})</label>
                                                    <input type="date" value="{{ @$wo->on_site_by }}"
                                                        name="on_site_by" class="form-control"
                                                        id="exampleFormControlInput1">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlInput1" class="form-label">Schedule
                                                        Time</label>
                                                    <input type="time" value="{{ @$wo->scheduled_time }}"
                                                        name="scheduled_time" class="form-control"
                                                        id="exampleFormControlInput1">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlInput1" class="form-label">Approximate
                                                        hours to complete</label>
                                                    <input type="text" value="{{ @$wo->h_operation }}"
                                                        name="h_operation" class="form-control"
                                                        id="exampleFormControlInput1">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="button"
                                                onclick="document.getElementById('rescheduleForm').submit()"
                                                class="btn btn-warning">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-12 px-3 py-4">
                    <div class="btn-group w-100" role="group" aria-label="Basic example">
                        <button type="button" class="btn tab tab-primary w-100" data-tab="tab1">Details</button>
                        <button type="button" class="btn tab w-100" data-tab="tab2">Field Tech</button>
                        <button type="button" class="btn tab w-100" data-tab="tab3"> Notes</button>
                        <button type="button" class="btn tab w-100" data-tab="tab4">WO Logs</button>
                        <button type="button" class="btn tab w-100" data-tab="tab5">Site History</button>
                    </div>
                </div>

                <div>
                    <div id="tab1" class="tab-content tab-active">
                        <div class="mb-5">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-outline-dark">Copy</button>
                                <button type="button" class="btn btn-outline-dark">Print</button>
                                <button type="button" class="btn btn-outline-dark">Save As Template</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-7">
                                <div class="card action-cards bg-white shadow-lg border-0 mb-4">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h3 style="font-size: 20px; font-weight: 600;">Overview</h3>

                                        <div class="d-flex action-group gap-2">
                                            <button class="btn edit-btn">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button onclick="document.getElementById('overview-form').submit();"
                                                class="btn save-btn fw-bold">
                                                Save
                                            </button>
                                            <button class="btn btn-danger cancel-btn fw-bold">
                                                Cancel
                                            </button>
                                        </div>

                                    </div>
                                    <div class="card-body bg-white">
                                        <form id="overview-form" action="{{ route('user.wo.updateOverview', $wo->id) }}"
                                            method="post">
                                            @csrf
                                            <table>
                                                <tr>
                                                    <td style="font-weight: 600;">Customer Name : </td>
                                                    <td>
                                                        <p class="mb-0 fw-bold nrml-txt">
                                                            {{ @$wo->customer->company_name }}</p>
                                                        <div class="nrml-inp">
                                                            <select name="cus_id"
                                                                class="mb-0 nrml-inp fw-bold p-0 selectpicker"
                                                                id="my-select"
                                                                placeholder="Search with Customer Name / Customer Id / Zipcode"
                                                                autocomplete="off"
                                                                value="{{ @$wo->customer->company_name }}"
                                                                data-live-search="true" data-width="100%" data-size="5">
                                                                <option value="">-- Select an Option --</option>
                                                                @foreach ($customers as $customer)
                                                                    <option value="{{ $customer->id }}"
                                                                        @if ($wo->slug == $customer->id) selected @endif>
                                                                        {{ $customer->customer_id }}
                                                                        {{ $customer->company_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        @if ($errors->has('cus_id'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('cus_id') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: 600;">Priority : </td>
                                                    <td>
                                                        @if ($wo->priority == 1)
                                                            <p class="mb-0 fw-bold nrml-txt">P1</p>
                                                        @elseif($wo->priority == 2)
                                                            <p class="mb-0 fw-bold nrml-txt">P2</p>
                                                        @elseif($wo->priority == 3)
                                                            <p class="mb-0 fw-bold nrml-txt">P3</p>
                                                        @elseif($wo->priority == 4)
                                                            <p class="mb-0 fw-bold nrml-txt">P4</p>
                                                        @elseif($wo->priority == 5)
                                                            <p class="mb-0 fw-bold nrml-txt">P5</p>
                                                        @endif
                                                        <!-- <input class="mb-0 nrml-inp fw-bold p-0" type="text"
                                                        value="P5"> -->
                                                        <select class="mb-0 nrml-inp fw-bold w-100 p-0" name="priority">
                                                            <option value="1"
                                                                @if ($wo->priority == 1) selected @endif>P1
                                                            </option>
                                                            <option value="2"
                                                                @if ($wo->priority == 2) selected @endif>P2
                                                            </option>
                                                            <option value="3"
                                                                @if ($wo->priority == 3) selected @endif>P3
                                                            </option>
                                                            <option value="4"
                                                                @if ($wo->priority == 4) selected @endif>P4
                                                            </option>
                                                            <option value="5"
                                                                @if ($wo->priority == 5) selected @endif>P5
                                                            </option>
                                                        </select>
                                                        @if ($errors->has('priority'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('priority') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: 600;">Requested By : </td>
                                                    <td>
                                                        <p class="mb-0 fw-bold nrml-txt">{{ @$wo->requested_by }}</p>
                                                        <input class="mb-0 nrml-inp fw-bold p-0" name="requested_by"
                                                            type="text" value="{{ @$wo->requested_by }}">
                                                        @if ($errors->has('requested_by'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('requested_by') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: 600;">Team : </td>
                                                    <td>
                                                        <p class="mb-0 fw-bold nrml-txt">Dilshan Ahmed</p>
                                                        <input class="mb-0 nrml-inp fw-bold p-0" type="text"
                                                            value="Dilshan Ahmed" name="team">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: 600;">WO Manager : </td>
                                                    <td>
                                                        <p class="mb-0 fw-bold nrml-txt">{{ @$wo->employee->name }}</p>
                                                        <!-- <input class="mb-0 nrml-inp fw-bold p-0" type="text"
                                                            value="{{ @$wo->employee->name }}" name="wo_manager"> -->
                                                        <select name="wo_manager" class="mb-0 nrml-inp fw-bold p-0 "
                                                            id="my-select"
                                                            placeholder="Search with Customer Name / Customer Id / Zipcode"
                                                            autocomplete="off"
                                                            value="{{ @$wo->customer->company_name }}">
                                                            <option value="">-- Select an Option --</option>
                                                            @foreach ($employees as $employee)
                                                                <option value="{{ $employee->id }}"
                                                                    @if ($wo->em_id == $employee->id) selected @endif>
                                                                    {{ $employee->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('wo_manager'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('wo_manager') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </form>


                                    </div>
                                </div>

                                <div class="card action-cards bg-white shadow-lg border-0 mb-4">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h3 style="font-size: 20px; font-weight: 600;">Scope Of Work</h3>

                                        <div class="d-flex action-group gap-2">
                                            <button class="btn edit-btn">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button onclick="document.getElementById('scopeForm').submit();"
                                                class="btn save-btn fw-bold">
                                                Save
                                            </button>
                                            <button class="btn btn-danger cancel-btn fw-bold">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body bg-white">
                                        <form id="scopeForm" action="{{ route('user.wo.updateScopeOfWork', $wo->id) }}"
                                            method="post">
                                            @csrf
                                            <textarea name="scope_work" class="mb-0 fw-bold p-0 w-100 textEditor" rows="10" id="editor">{!! $wo->scope_work !!}</textarea>
                                            @if ($errors->has('scope_work'))
                                                <span class="text-danger">{{ $errors->first('scope_work') }}</span>
                                            @endif
                                        </form>
                                        <div class="mb-0 fw-bold nrml-txt">{!! $wo->scope_work !!}</div>
                                    </div>
                                </div>

                                <div class="card action-cards bg-white shadow-lg border-0 mb-4">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h3 style="font-size: 20px; font-weight: 600;">Tools Required</h3>

                                        <div class="d-flex action-group gap-2">
                                            <button class="btn edit-btn">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button onclick="document.getElementById('toolForm').submit();"
                                                class="btn save-btn fw-bold">
                                                Save
                                            </button>
                                            <button class="btn btn-danger cancel-btn fw-bold">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body bg-white">
                                        <div class="mb-0 fw-bold nrml-txt">{!! $wo->r_tools !!}</div>
                                        <form id="toolForm" action="{{ route('user.wo.updateTools', $wo->id) }}"
                                            method="post">
                                            @csrf
                                            <textarea name="r_tools" class="mb-0 fw-bold p-0 w-100 textEditor" rows="10" id="editor1">{!! $wo->r_tools !!}</textarea>
                                            @if ($errors->has('r_tools'))
                                                <span class="text-danger">{{ $errors->first('r_tools') }}</span>
                                            @endif
                                        </form>
                                    </div>
                                </div>

                                <div class="card bg-white shadow-lg border-0 mb-4">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h3 style="font-size: 20px; font-weight: 600;">Technician Provided Parts </h3>
                                    </div>
                                    <div class="card-body bg-white">

                                        <div class="row border-top border-bottom">
                                            <div class="col-5 border-end">
                                                <h6>Parts Description</h6>
                                            </div>
                                            <div class="col-2 border-end">
                                                <h6>Parts Number</h6>
                                            </div>
                                            <div class="col-1 border-end">
                                                <h6>Price</h6>
                                            </div>
                                            <div class="col-1 border-end">
                                                <h6>Quantity</h6>
                                            </div>
                                            <div class="col-1 border-end">
                                                <h6>Amount</h6>
                                            </div>
                                            <div class="col-2">
                                                Actions
                                            </div>
                                        </div>
                                        @foreach ($wo->techProvidedParts as $part)
                                            <form class="row border-bottom action-cards"
                                                id="techProvidedPart-{{ $part->id }}"
                                                action="{{ route('user.wo.updateTechPart', $part->id) }}" method="post">
                                                @csrf
                                                <div class="col-5 border-end">
                                                    <p class="mb-0 fw-bold nrml-txt">{{ $part->part_name }}</p>
                                                    <input class="mb-0 nrml-inp fw-bold p-0 w-100" name="part_name"
                                                        type="text" value="{{ $part->part_name }}">
                                                </div>
                                                <div class="col-2 border-end">
                                                    <p class="mb-0 fw-bold nrml-txt">{{ $part->parts_number }}</p>
                                                    <input class="mb-0 nrml-inp fw-bold p-0 w-100" name="parts_number"
                                                        type="text" value="{{ $part->parts_number }}">
                                                </div>
                                                <div class="col-1 border-end">
                                                    <p class="mb-0 fw-bold nrml-txt">${{ $part->price }}</p>
                                                    <input class="mb-0 nrml-inp fw-bold p-0 w-100" name="price"
                                                        type="text" value="{{ $part->price }}">
                                                </div>
                                                <div class="col-1 border-end">
                                                    <p class="mb-0 fw-bold nrml-txt">{{ $part->quantity }}</p>
                                                    <input class="mb-0 nrml-inp fw-bold p-0 w-100" name="quantity"
                                                        type="text" value="{{ $part->quantity }}">
                                                </div>
                                                <div class="col-1 border-end">
                                                    <p class="mb-0 fw-bold">${{ $part->amount }}</p>
                                                </div>
                                                <div class="col-2 d-flex action-group gap-2">
                                                    <button type="button" class="btn edit-btn">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>

                                                    <button type="submit" class="btn save-btn fw-bold">
                                                        <i class="fa-regular fa-floppy-disk"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger cancel-btn fw-bold">
                                                        <i class="fa-solid fa-ban"></i>
                                                    </button>
                                                    <button
                                                        onclick="document.getElementById('deleteTechPart-{{ $part->id }}').submit()"
                                                        type="button" class="btn" style="height: max-content;">
                                                        <i class="fa-solid fa-trash text-danger" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            </form>
                                            <form id="deleteTechPart-{{ $part->id }}"
                                                action="{{ route('user.wo.deleteTechPart', $part->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                            </form>
                                        @endforeach

                                        <form class="row border-bottom tech-part-add"
                                            action="{{ route('user.wo.storeTechPart', $wo->id) }}" method="post">
                                            @csrf
                                            <div class="col-5 border-end">
                                                <input class="mb-0 fw-bold p-0 w-100 border-bottom-0" name="part_name"
                                                    placeholder="Parts Name" type="text" value="">
                                            </div>
                                            <div class="col-2 border-end">
                                                <input class="mb-0 fw-bold p-0 w-100 border-bottom-0" name="parts_number"
                                                    type="text" placeholder="Parts Number">
                                            </div>
                                            <div class="col-1 border-end">
                                                <input class="mb-0 fw-bold p-0 w-100 border-bottom-0" placeholder="Price"
                                                    name="price" type="text" id="priceInput" value="">
                                            </div>
                                            <div class="col-1 border-end">
                                                <input class="mb-0 fw-bold p-0 w-100 border-bottom-0" name="quantity"
                                                    placeholder="Quantity" type="text" id="quantityInput"
                                                    value="">
                                            </div>
                                            <div class="col-1 border-end" id="totalDisplay">
                                                $00.00
                                            </div>

                                            <div class="col-2 d-flex action-group gap-2">

                                                <button style="background-color: #1FE1AF;" type="submit"
                                                    class="btn fw-bold">
                                                    <i class="fa-regular fa-floppy-disk"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger cnclTechPart fw-bold">
                                                    <i class="fa-solid fa-ban"></i>
                                                </button>
                                            </div>
                                        </form>


                                        <div class="mt-3">
                                            <button class="btn btn-outline-dark addTechPart">+ Add Item</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card action-cards bg-white shadow-lg border-0 mb-4">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h3 style="font-size: 20px; font-weight: 600;">Parts Provided By Tech Yeah/Client
                                        </h3>

                                        <div class="d-flex action-group gap-2">
                                            <button class="btn edit-btn">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button class="btn save-btn fw-bold">
                                                Save
                                            </button>
                                            <button class="btn btn-danger cancel-btn fw-bold">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body d-flex bg-white">
                                        <div class="w-100 py-5 border-end">
                                            <p>Tech Yeah</p>
                                            <button type="button" class="btn btn-outline-dark">+ Add Item</button>
                                        </div>
                                        <div class="w-100 p-5">
                                            <p>Client</p>
                                            <button type="button" class="btn btn-outline-dark">+ Add Item</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card bg-white shadow-lg border-0 mb-4">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h3 style="font-size: 20px; font-weight: 600;">Shipments</h3>
                                    </div>
                                    <div class="card-body bg-white">
                                        @foreach ($wo->shipments as $shipment)
                                            <div class="p-3 mb-3 action-cards d-flex justify-content-between"
                                                style="background-color: #E3F2FD;">
                                                <div>
                                                    <h5 class="nrml-txt">
                                                        @if ($shipment->associate == 'fedex')
                                                            <a target="_blank"
                                                                href="https://www.fedex.com/fedextrack/?trknbr={{ $shipment->tracking_number }}">{{ $shipment->tracking_number }}</a>
                                                        @elseif($shipment->associate == 'ups')
                                                            <a target="_blank"
                                                                href="https://www.ups.com/track?loc=en_US&tracknum={{ $shipment->tracking_number }}">{{ $shipment->tracking_number }}</a>
                                                        @endif

                                                    </h5>

                                                    <i class="nrml-txt" style="font-size: 12px;">
                                                        from: <span>{{ @$shipment->shipment_from }}</span> --- <span><i
                                                                class="fa-solid fa-truck-fast"></i></span>--- to:
                                                        <span>{{ @$shipment->shipment_to }}</span>
                                                    </i>
                                                    <br>

                                                    <i class="nrml-txt" style="font-size: 12px;">by <span
                                                            class="text-uppercase">{{ $shipment->associate == 'fedex' ? 'Fedex' : 'UPS' }}</span>
                                                        <span style="font-size: 12px;">
                                                            ({{ $shipment->created_at->format('m/d/Y') }})</span></i>

                                                    <form id="updateShipmentForm-{{ $shipment->id }}"
                                                        action="{{ route('user.wo.updateShipment', $shipment->id) }}"
                                                        method="post">
                                                        @csrf
                                                        <select name="associate" class="mt-3 nrml-inp fw-bold p-0 w-100">
                                                            <option value="fedex" @selected($shipment->associate == 'fedex')>Fedex
                                                            </option>
                                                            <option value="ups" @selected($shipment->associate == 'ups')>UPS
                                                            </option>
                                                        </select>
                                                        <input type="text" name="tracking_number"
                                                            value="{{ $shipment->tracking_number }}"
                                                            placeholder="Enter Tracking Number"
                                                            class="mt-3 border-bottom w-100 nrml-inp">

                                                        <input type="text" name="from"
                                                            value="{{ $shipment->shipment_from }}"
                                                            placeholder="Enter Shippment From"
                                                            class="mt-3 border-bottom w-100 nrml-inp">

                                                        <input type="text" name="to"
                                                            value="{{ $shipment->shipment_to }}"
                                                            placeholder="Enter Shippment To"
                                                            class="mt-3 border-bottom w-100 nrml-inp">

                                                        <input type="date" name="date"
                                                            value="{{ $shipment->created_at ? $shipment->created_at->format('Y-m-d') : '' }}"
                                                            placeholder="Enter Shipment Date"
                                                            class="mt-3 border-bottom w-100 nrml-inp">

                                                    </form>

                                                </div>
                                                <div class="d-flex action-group gap-2">
                                                    <button class="btn edit-btn" style="height: max-content;">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <button
                                                        onclick="document.getElementById('updateShipmentForm-{{ $shipment->id }}').submit()"
                                                        class="btn save-btn fw-bold" style="height: max-content;">
                                                        Save
                                                    </button>
                                                    <button class="btn btn-danger cancel-btn fw-bold"
                                                        style="height: max-content;">
                                                        Cancel
                                                    </button>
                                                    <form action="{{ route('user.wo.deleteShipment', $shipment->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn"
                                                            style="height: max-content;">
                                                            <i class="fa-solid fa-trash text-danger"></i>
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="py-3 border-bottom shipment-add">
                                            <div>
                                                <form id="createShipmentForm"
                                                    action="{{ route('user.wo.createShipment', $wo->id) }}"
                                                    method="post">
                                                    @csrf
                                                    <label for="" class="mt-2">Select Method</label>
                                                    <select name="associate" class="mb-0 nrml-inp fw-bold p-0 w-100"
                                                        style="display: block;">
                                                        <option value="fedex" selected="">Fedex</option>
                                                        <option value="ups">UPS</option>
                                                    </select>
                                                    <label for="" class="mt-2">Tracking Number</label>
                                                    <input type="text" name="tracking_number"
                                                        placeholder="Enter Tracking Number"
                                                        class="mb-2 border-bottom w-100">

                                                    <label for="" class="mt-2">From</label>
                                                    <input type="text" name="from"
                                                        placeholder="Enter Shipment From"
                                                        class="mb-2 border-bottom w-100">

                                                    <label for="" class="mt-2">To</label>
                                                    <input type="text" name="to" placeholder="Enter Shipment To"
                                                        class="mb-2 border-bottom w-100">

                                                    <label for="" class="mt-2">Date</label>
                                                    <input type="date" name="date" placeholder="Shipment Date"
                                                        class="mb-2 border-bottom w-100">
                                                </form>

                                            </div>
                                            <div class="d-flex action-group gap-2">
                                                <button onclick="document.getElementById('createShipmentForm').submit()"
                                                    class="btn btn-success fw-bold" style="height: max-content;">
                                                    Save
                                                </button>
                                                <button class="btn btn-danger cnclShipment fw-bold"
                                                    style="height: max-content;">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                        <div class="w-100 py-3">
                                            <button type="button" class="btn btn-outline-dark addShipment">+ Add
                                                Shipment</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card action-cards bg-white shadow-lg border-0 mb-4">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h3 style="font-size: 20px; font-weight: 600;">Tech Yeah Documents For Technicians
                                        </h3>

                                        <div class="d-flex action-group gap-2">
                                            <button class="btn edit-btn">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button class="btn save-btn fw-bold">
                                                Save
                                            </button>
                                            <button class="btn btn-danger cancel-btn fw-bold">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body bg-white">
                                        <div id="technicianDocCont" class="d-flex gap-2">
                                            @foreach ($wo->docsForTech as $doc)
                                                @php
                                                    $fileExtension = pathinfo($doc->name, PATHINFO_EXTENSION);
                                                    $imageExtensions = ['jpg', 'jpeg', 'png'];
                                                    $pdf = ['pdf'];
                                                @endphp
                                                <div class="file-preview">
                                                    <div class="file-content">
                                                        @if (in_array(strtolower($fileExtension), $imageExtensions))
                                                            <img src="{{ asset($doc->file) }}" alt="Preview"
                                                                class="preview-image img-fluid">
                                                        @elseif(in_array(strtolower($fileExtension), $pdf))
                                                            <i class="fa fa-file-pdf preview-pdf text-danger"
                                                                aria-hidden="true"></i>
                                                        @else
                                                            <i class="fa fa-file preview-file" aria-hidden="true"></i>
                                                        @endif
                                                        <a href="{{ asset($doc->file) }}"
                                                            download="{{ $doc->name }}">
                                                            <p class="file-name">{{ $doc->name }}</p>
                                                        </a>
                                                    </div>
                                                    <form action="{{ route('user.wo.deleteDocForTech', $doc->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="delete-btn">×</button>
                                                    </form>

                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="w-100 py-3">
                                            <form id="uploadTechForm"
                                                action="{{ route('user.wo.uploadDocForTech', $wo->id) }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <label for="technicianDoc" class="btn btn-outline-dark">Add File</label>
                                                <input id="technicianDoc" name="file[]" class="invisible"
                                                    type="file"
                                                    onchange="document.getElementById('uploadTechForm').submit()" multiple>
                                            </form>

                                        </div>
                                    </div>
                                </div>

                                <div class="card action-cards bg-white shadow-lg border-0 mb-4">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h3 style="font-size: 20px; font-weight: 600;">Dispatch Instructions</h3>

                                        <div class="d-flex action-group gap-2">
                                            <button class="btn edit-btn">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button onclick="document.getElementById('instructionForm').submit()"
                                                class="btn save-btn fw-bold">
                                                Save
                                            </button>
                                            <button class="btn btn-danger cancel-btn fw-bold">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body bg-white">
                                        <div class="mb-0 fw-bold nrml-txt">{!! $wo->instruction !!}</div>
                                        <form id="instructionForm"
                                            action="{{ route('user.wo.updateDispatchedInstruction', $wo->id) }}"
                                            method="post">
                                            @csrf
                                            <textarea name="instruction" class="mb-0 fw-bold p-0 w-100 textEditor" rows="10" id="editor2">{!! $wo->instruction !!}</textarea>
                                            @if ($errors->has('instruction'))
                                                <span class="text-danger">{{ $errors->first('instruction') }}</span>
                                            @endif
                                        </form>
                                    </div>
                                </div>

                                <div class="card bg-white shadow-lg border-0 mb-4">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h3 style="font-size: 20px; font-weight: 600;">Tasks</h3>
                                        <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal"
                                            data-bs-target="#addTask">+ Add Task</button>

                                        <div class="modal fade" id="addTask" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Add Task</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="taskForm"
                                                            action="{{ route('user.wo.addTask', ['id' => $wo->id, 'tech_id' => null]) }}"
                                                            method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <select name="type" class="taskSelect form-select mb-2"
                                                                aria-label="Default select example">
                                                                <option value="" selected>Select Option</option>
                                                                <option value="call">Call</option>
                                                                <option value="collect_signature">Collect Signature
                                                                </option>
                                                                <option value="custom_task">Completed Custom Task</option>
                                                                <option value="shipping_details">Shipping Details</option>
                                                                <option value="send_email">Send Email</option>
                                                                <option value="upload_file">Upload File</option>
                                                                <option value="upload_photo">Upload/Take Photo</option>
                                                                <option value="closeout_note">Closeout Note</option>
                                                            </select>

                                                            <style>
                                                                .email,
                                                                .phone,
                                                                .from,
                                                                .item,
                                                                .file,
                                                                .reason,
                                                                .desc {
                                                                    display: none;
                                                                }
                                                            </style>


                                                            <!-- Eamil -->
                                                            <div id="" class="mb-3 email">
                                                                <input name="email" type="email" class="form-control"
                                                                    id="exampleFormControlInput1"
                                                                    placeholder="Enter Email">
                                                            </div>

                                                            <!-- Phone -->
                                                            <div id="" class="mb-3 phone">
                                                                <input name="phone" type="text" class="form-control"
                                                                    id="exampleFormControlInput1"
                                                                    placeholder="Enter Phone">
                                                            </div>

                                                            <!-- From -->
                                                            <div id="" class="mb-3 from">
                                                                <input name="from" type="text" class="form-control"
                                                                    id="exampleFormControlInput1"
                                                                    placeholder="Enter Signee's Name">
                                                            </div>

                                                            <!-- Item -->
                                                            <div id="" class="mb-3 item">
                                                                <input name="item" type="text" class="form-control"
                                                                    id="exampleFormControlInput1"
                                                                    placeholder="Enter Item Name">
                                                            </div>

                                                            <!-- Item -->
                                                            <div id="" class="mb-3 file">
                                                                <input name="file" type="file" class="form-control"
                                                                    id="exampleFormControlInput1"
                                                                    placeholder="Enter Item Name">
                                                            </div>
                                                            <!-- Reason -->
                                                            <div id="" class="form-floating mb-2 reason">
                                                                <textarea name="reason" class="form-control" placeholder="Enter Reason" id="floatingTextarea2"
                                                                    style="height: 100px"></textarea>
                                                                <label for="floatingTextarea2">Reason</label>
                                                            </div>

                                                            <!-- Description -->
                                                            <div id="" class="form-floating mb-2 desc">
                                                                <textarea name="desc" class="form-control" placeholder="Enter Description" id="floatingTextarea2"
                                                                    style="height: 100px"></textarea>
                                                                <label for="floatingTextarea2">Description</label>
                                                            </div>

                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button onclick="document.getElementById('taskForm').submit()"
                                                            type="button" class="btn btn-dark">Add Task</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body bg-white">
                                        @foreach ($wo->tasks as $task)
                                            @if ($task->type != 'closeout_note' && $task->tech_id == null)
                                                <div class="px-4 py-3 mb-4 d-flex justify-content-between action-cards"
                                                    style="background-color: #E3F2FD; cursor: pointer">
                                                    <div class="d-flex">
                                                        <form id="completed-{{ $task->id }}"
                                                            action="{{ route('user.wo.completeTask', $task->id) }}"
                                                            method="post">
                                                            @csrf
                                                            <input onclick="" class="form-check-input ms-0 me-2"
                                                                type="checkbox" value="1"
                                                                onchange="document.getElementById('completed-{{ $task->id }}').submit()"
                                                                @checked($task->is_completed)
                                                                id="task{{ $task->id }}">
                                                        </form>
                                                        <label class="form-check-label" for="task{{ $task->id }}">

                                                            <form id="editTaskForm-{{ $task->id }}"
                                                                action="{{ route('user.wo.editTask', $task->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @if ($task->type == 'call')
                                                                    Call at <a
                                                                        href="callto:{{ $task->phone }}">{{ @$task->phone }}</a>
                                                                    <p class="mb-2 nrml-txt"
                                                                        style="font-weight: 300; font-size: 14px; color: #808080">
                                                                        Reason: {{ @$task->reason }}</p>

                                                                    <p class="mb-0 nrml-txt"
                                                                        style="font-weight: 300; font-size: 12px; color: #808080">
                                                                        Task Assigned at
                                                                        {{ $task->created_at->format('h:i A') }}
                                                                        ({{ $task->created_at->format('m-d-y') }})
                                                                    </p>

                                                                    <input class="mb-0 nrml-inp fw-bold p-0 mt-2"
                                                                        type="text" value="{{ @$task->phone }}"
                                                                        name="phone" placeholder="Phone Number">
                                                                    <textarea class="mb-0 nrml-inp fw-bold p-0 w-100 mt-2" name="reason" id="">{{ @$task->reason }}</textarea>
                                                                @elseif($task->type == 'collect_signature')
                                                                    Collect Signature from {{ @$task->from }}
                                                                    <p class="mb-0 nrml-txt"
                                                                        style="font-weight: 300; font-size: 12px; color: #808080">
                                                                        Task Assigned at
                                                                        {{ $task->created_at->format('h:i A') }}
                                                                        ({{ $task->created_at->format('m-d-y') }})
                                                                    </p>
                                                                    <input class="mb-0 nrml-inp fw-bold p-0 mt-2"
                                                                        type="text" value="{{ @$task->from }}"
                                                                        name="from" placeholder="Signature From">
                                                                @elseif($task->type == 'custom_task')
                                                                    Custom Task
                                                                    <p class="mb-2 nrml-txt"
                                                                        style="font-weight: 300; font-size: 14px; color: #808080">
                                                                        Description: {{ @$task->description }}</p>
                                                                    <p class="mb-0 nrml-txt"
                                                                        style="font-weight: 300; font-size: 12px; color: #808080">
                                                                        Task Assigned at
                                                                        {{ $task->created_at->format('h:i A') }}
                                                                        ({{ $task->created_at->format('m-d-y') }})
                                                                    </p>
                                                                    <textarea class="mb-0 nrml-inp fw-bold p-0 w-100 mt-2" name="desc" id="">{{ @$task->description }}</textarea>
                                                                @elseif($task->type == 'shipping_details')
                                                                    Shipping Details ({{ @$task->item }})
                                                                    <p class="mb-0 nrml-txt"
                                                                        style="font-weight: 300; font-size: 12px; color: #808080">
                                                                        Task Assigned at
                                                                        {{ $task->created_at->format('h:i A') }}
                                                                        ({{ $task->created_at->format('m-d-y') }})
                                                                    </p>
                                                                    <input class="mb-0 nrml-inp fw-bold p-0 mt-2"
                                                                        type="text" value="{{ @$task->item }}"
                                                                        name="item" placeholder="Shipping Item">
                                                                @elseif($task->type == 'send_email')
                                                                    Send Email at <a
                                                                        href="mailto:{{ @$task->email }}">{{ @$task->email }}</a>
                                                                    <p class="mb-2 nrml-txt"
                                                                        style="font-weight: 300; font-size: 14px; color: #808080">
                                                                        Reason: {{ @$task->reason }}</p>
                                                                    <p class="mb-0 nrml-txt"
                                                                        style="font-weight: 300; font-size: 12px; color: #808080">
                                                                        Task Assigned at
                                                                        {{ $task->created_at->format('h:i A') }}
                                                                        ({{ $task->created_at->format('m-d-y') }})
                                                                    </p>
                                                                    <input class="mb-0 nrml-inp fw-bold p-0 mt-2"
                                                                        type="email" value="{{ @$task->email }}"
                                                                        name="email" placeholder="Email">
                                                                    <textarea class="mb-0 nrml-inp fw-bold p-0 w-100 mt-2" name="reason" id="">{{ @$task->reason }}</textarea>
                                                                @elseif($task->type == 'upload_file')
                                                                    Upload File
                                                                    @php
                                                                        $fileName = str_replace(
                                                                            'docs/tasks/',
                                                                            '',
                                                                            $task->file,
                                                                        );

                                                                        $filePath = asset($task->file);
                                                                    @endphp
                                                                    <a href="{{ $filePath }}" target="_blank">
                                                                        {{ $fileName }}
                                                                    </a>
                                                                    <p class="mb-2 nrml-txt"
                                                                        style="font-weight: 300; font-size: 14px; color: #808080">
                                                                        Description: {{ @$task->description }}</p>

                                                                    <p class="mb-0 nrml-txt"
                                                                        style="font-weight: 300; font-size: 12px; color: #808080">
                                                                        Task Assigned at
                                                                        {{ $task->created_at->format('h:i A') }}
                                                                        ({{ $task->created_at->format('m-d-y') }})
                                                                    </p>
                                                                    <textarea class="mb-0 nrml-inp fw-bold p-0 w-100 mt-2" name="desc" id="">{{ @$task->description }}</textarea>
                                                                @elseif($task->type == 'upload_photo')
                                                                    Upload/Take Photo
                                                                    @php
                                                                        $fileName = str_replace(
                                                                            'docs/tasks/',
                                                                            '',
                                                                            $task->file,
                                                                        );

                                                                        $filePath = asset($task->file);
                                                                    @endphp
                                                                    <a href="{{ $filePath }}" target="_blank">
                                                                        {{ $fileName }}
                                                                    </a>
                                                                    <p class="mb-2 nrml-txt"
                                                                        style="font-weight: 300; font-size: 14px; color: #808080">
                                                                        Description: {{ @$task->description }}</p>

                                                                    <p class="mb-0 nrml-txt"
                                                                        style="font-weight: 300; font-size: 12px; color: #808080">
                                                                        Task Assigned at
                                                                        {{ $task->created_at->format('h:i A') }}
                                                                        ({{ $task->created_at->format('m-d-y') }})
                                                                    </p>
                                                                    <textarea class="mb-0 nrml-inp fw-bold p-0 w-100 mt-2" name="desc" id="">{{ @$task->description }}</textarea>
                                                                @elseif($task->type == 'closeout_note')
                                                                    Closeout Note
                                                                    <p class="mb-2 nrml-txt"
                                                                        style="font-weight: 300; font-size: 14px; color: #808080">
                                                                        Description: {{ @$task->description }}</p>
                                                                    <p class="mb-0 nrml-txt"
                                                                        style="font-weight: 300; font-size: 12px; color: #808080">
                                                                        Task Assigned at
                                                                        {{ $task->created_at->format('h:i A') }}
                                                                        ({{ $task->created_at->format('m-d-y') }})
                                                                    </p>
                                                                    <textarea class="mb-0 nrml-inp fw-bold p-0 w-100 mt-2" name="reason" id="">{{ @$task->description }}</textarea>
                                                                @endif
                                                            </form>

                                                            <button type="button" class="rounded-circle mt-3 d-flex justify-content-center align-items-center"
                                                                style="width: 30px; height: 30px; border: 1px dashed #000" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Assign the task">
                                                                <i class="fa-solid fa-user-plus fa-fade"></i>
                                                            </button >
                                                             
                                                        </label>

                                                    </div>

                                                    <div class="d-flex action-group gap-2">
                                                        <button class="btn edit-btn" style="height: max-content;">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </button>
                                                        <button
                                                            onclick="document.getElementById('editTaskForm-{{ $task->id }}').submit()"
                                                            class="btn save-btn fw-bold" style="height: max-content;">
                                                            Save
                                                        </button>
                                                        <button class="btn btn-danger cancel-btn fw-bold"
                                                            style="height: max-content;">
                                                            Cancel
                                                        </button>
                                                        <form action="{{ route('user.wo.deleteTask', $task->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn"
                                                                style="height: max-content;">
                                                                <i class="fa-solid fa-trash text-danger"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                @foreach (@$wo->assignedTech as $tech)
                                    <div class="card bg-white shadow-lg border-0 mb-4">
                                        <div
                                            class="card-header bg-white d-flex justify-content-between align-items-center">
                                            <h3 style="font-size: 20px; font-weight: 600;">Tasks for
                                                {{ $tech->engineer->name }}</h3>
                                            <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal"
                                                data-bs-target="#addTask-{{ $tech->tech_id }}">+ Add Task</button>

                                            <div class="modal fade" id="addTask-{{ $tech->tech_id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Add Task
                                                                {{ $tech->id }}</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="taskForm-{{ $tech->id }}"
                                                                action="{{ route('user.wo.addTask', ['id' => $wo->id, 'tech_id' => $tech->tech_id]) }}"
                                                                method="post" enctype="multipart/form-data">
                                                                @csrf
                                                                <select name="type" class="taskSelect form-select mb-2"
                                                                    aria-label="Default select example">
                                                                    <option value="" selected>Select Option</option>
                                                                    <option value="call">Call</option>
                                                                    <option value="collect_signature">Collect Signature
                                                                    </option>
                                                                    <option value="custom_task">Completed Custom Task
                                                                    </option>
                                                                    <option value="shipping_details">Shipping Details
                                                                    </option>
                                                                    <option value="send_email">Send Email</option>
                                                                    <option value="upload_file">Upload File</option>
                                                                    <option value="upload_photo">Upload/Take Photo</option>
                                                                    <option value="closeout_note">Closeout Note</option>
                                                                </select>

                                                                <style>
                                                                    .email,
                                                                    .phone,
                                                                    .from,
                                                                    .item,
                                                                    .file,
                                                                    .reason,
                                                                    .desc {
                                                                        display: none;
                                                                    }
                                                                </style>


                                                                <!-- Eamil -->
                                                                <div id="" class="mb-3 email">
                                                                    <input name="email" type="email"
                                                                        class="form-control" id="exampleFormControlInput1"
                                                                        placeholder="Enter Email">
                                                                </div>

                                                                <!-- Phone -->
                                                                <div id="" class="mb-3 phone">
                                                                    <input name="phone" type="text"
                                                                        class="form-control" id="exampleFormControlInput1"
                                                                        placeholder="Enter Phone">
                                                                </div>

                                                                <!-- From -->
                                                                <div id="" class="mb-3 from">
                                                                    <input name="from" type="text"
                                                                        class="form-control" id="exampleFormControlInput1"
                                                                        placeholder="Enter Signee's Name">
                                                                </div>

                                                                <!-- Item -->
                                                                <div id="" class="mb-3 item">
                                                                    <input name="item" type="text"
                                                                        class="form-control" id="exampleFormControlInput1"
                                                                        placeholder="Enter Item Name">
                                                                </div>

                                                                <!-- Item -->
                                                                <div id="" class="mb-3 file">
                                                                    <input name="file" type="file"
                                                                        class="form-control" id="exampleFormControlInput1"
                                                                        placeholder="Enter Item Name">
                                                                </div>
                                                                <!-- Reason -->
                                                                <div id="" class="form-floating mb-2 reason">
                                                                    <textarea name="reason" class="form-control" placeholder="Enter Reason" id="floatingTextarea2"
                                                                        style="height: 100px"></textarea>
                                                                    <label for="floatingTextarea2">Reason</label>
                                                                </div>

                                                                <!-- Description -->
                                                                <div id="" class="form-floating mb-2 desc">
                                                                    <textarea name="desc" class="form-control" placeholder="Enter Description" id="floatingTextarea2"
                                                                        style="height: 100px"></textarea>
                                                                    <label for="floatingTextarea2">Description</label>
                                                                </div>





                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button
                                                                onclick="document.getElementById('taskForm-{{ $tech->id }}').submit()"
                                                                type="button" class="btn btn-dark">Add Task</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body bg-white">
                                            <div class="form-check px-4 py-3 mb-4 d-flex justify-content-between"
                                                style="background-color: #E3F2FD; cursor: pointer">
                                                <div class="w-100">
                                                    <form id="checkinForm-{{ $tech->tech_id }}"
                                                        action="{{ route('user.wo.checkin', ['id' => $wo->id, 'tech_id' => $tech->tech_id]) }}"
                                                        method="post" class="row">
                                                        @csrf
                                                        <label class="form-check-label col-10"
                                                            for="checkin-{{ $tech->tech_id }}">
                                                            <input
                                                                onclick="if(this.checked) document.getElementById('checkinForm-{{ $tech->tech_id }}').submit()"
                                                                class="form-check-input ms-0 me-2" type="checkbox"
                                                                value="1" id="checkin-{{ $tech->tech_id }}"
                                                                @disabled($wo->status <= 6) @checked(
                                                                    @$wo->checkInOut->where('tech_id', $tech->tech_id)->last()->check_in &&
                                                                        !$wo->checkInOut->where('tech_id', $tech->tech_id)->last()->check_out)>
                                                            <div>
                                                                @if (
                                                                    @$wo->checkInOut->where('tech_id', $tech->tech_id)->last() &&
                                                                        @$wo->checkInOut->where('tech_id', $tech->tech_id)->last()->check_in &&
                                                                        !@$wo->checkInOut->where('tech_id', $tech->tech_id)->last()->check_out)
                                                                    Checked in
                                                                @elseif(
                                                                    @$wo->checkInOut->where('tech_id', $tech->tech_id)->last() &&
                                                                        @$wo->checkInOut->where('tech_id', $tech->tech_id)->last()->check_out)
                                                                    Check in again
                                                                @elseif(!@$wo->checkInOut->where('tech_id', $tech->tech_id)->last())
                                                                    Check in
                                                                @endif

                                                                <p class="mb-0 nrml-txt"
                                                                    style="font-weight: 300; font-size: 12px; color: #808080">
                                                                    Check in at
                                                                    <?php
                                                                    $currentTime = date('h:i A');
                                                                    $timezone = date_default_timezone_get();
                                                                    echo $currentTime . ' ' . $timezone;
                                                                    ?>
                                                                </p>
                                                            </div>

                                                        </label>

                                                        @if ($wo->checkInOut->count() > 0 && ($wo->checkInOut->last()->check_out || !$wo->checkInOut->last()->check_in))
                                                            <button type="button"
                                                                data-id="reason-{{ $tech->id }}"
                                                                class="btn btn-dark col-2 addReasonButton">+ Add
                                                                reason</button>
                                                            <textarea name="reason" id="reason-{{ $tech->id }}" class="col-12 mt-3 reasonTextarea"
                                                                placeholder="Enter Reason" style="display: none;"></textarea>
                                                        @endif

                                                        <!-- Move the script outside the loop -->
                                                        <script>
                                                            // Select all buttons with the class 'addReasonButton'
                                                            document.querySelectorAll('.addReasonButton').forEach((button, index) => {
                                                                console.log(`Button ${index + 1} initialized`); // Log each button during initialization

                                                                button.addEventListener('click', function() {
                                                                    // Get the associated textarea using the data-id attribute
                                                                    const textareaId = this.getAttribute('data-id');
                                                                    console.log(
                                                                    `Button clicked: ${textareaId}`); // Log the button click and its associated textarea ID

                                                                    const textarea = document.getElementById(textareaId);

                                                                    if (textarea) {
                                                                        // Log the current display state of the textarea
                                                                        console.log(`Current display state of ${textareaId}: ${textarea.style.display}`);

                                                                        // Toggle the display of the textarea
                                                                        textarea.style.display = textarea.style.display === 'none' || textarea.style.display ===
                                                                            '' ? 'block' : 'none';

                                                                        // Log the new display state of the textarea
                                                                        console.log(`New display state of ${textareaId}: ${textarea.style.display}`);
                                                                    } else {
                                                                        console.error(`Textarea with ID ${textareaId} not found`);
                                                                    }
                                                                });
                                                            });
                                                        </script>


                                                    </form>

                                                </div>

                                            </div>

                                            @foreach ($wo->tasks as $task)
                                                @if ($task->type != 'closeout_note' && $task->tech_id == $tech->tech_id)
                                                    <div class="px-4 py-3 mb-4 d-flex justify-content-between action-cards"
                                                        style="background-color: #E3F2FD; cursor: pointer">
                                                        <div class="d-flex">
                                                            <form id="completed-{{ $task->id }}"
                                                                action="{{ route('user.wo.completeTask', $task->id) }}"
                                                                method="post">
                                                                @csrf
                                                                <input onclick="" class="form-check-input ms-0 me-2"
                                                                    type="checkbox" value="1"
                                                                    onchange="document.getElementById('completed-{{ $task->id }}').submit()"
                                                                    @checked($task->is_completed)
                                                                    id="task{{ $task->id }}">
                                                            </form>
                                                            <label class="form-check-label"
                                                                for="task{{ $task->id }}">

                                                                <form id="editTaskForm-{{ $task->id }}"
                                                                    action="{{ route('user.wo.editTask', $task->id) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @if ($task->type == 'call')
                                                                        Call at <a
                                                                            href="callto:{{ $task->phone }}">{{ @$task->phone }}</a>
                                                                        <p class="mb-2 nrml-txt"
                                                                            style="font-weight: 300; font-size: 14px; color: #808080">
                                                                            Reason: {{ @$task->reason }}</p>

                                                                        <p class="mb-0 nrml-txt"
                                                                            style="font-weight: 300; font-size: 12px; color: #808080">
                                                                            Task Assigned at
                                                                            {{ $task->created_at->format('h:i A') }}
                                                                            ({{ $task->created_at->format('m-d-y') }})
                                                                        </p>

                                                                        <input class="mb-0 nrml-inp fw-bold p-0 mt-2"
                                                                            type="text" value="{{ @$task->phone }}"
                                                                            name="phone" placeholder="Phone Number">
                                                                        <textarea class="mb-0 nrml-inp fw-bold p-0 w-100 mt-2" name="reason" id="">{{ @$task->reason }}</textarea>
                                                                    @elseif($task->type == 'collect_signature')
                                                                        Collect Signature from {{ @$task->from }}
                                                                        <p class="mb-0 nrml-txt"
                                                                            style="font-weight: 300; font-size: 12px; color: #808080">
                                                                            Task Assigned at
                                                                            {{ $task->created_at->format('h:i A') }}
                                                                            ({{ $task->created_at->format('m-d-y') }})
                                                                        </p>
                                                                        <input class="mb-0 nrml-inp fw-bold p-0 mt-2"
                                                                            type="text" value="{{ @$task->from }}"
                                                                            name="from" placeholder="Signature From">
                                                                    @elseif($task->type == 'custom_task')
                                                                        Custom Task
                                                                        <p class="mb-2 nrml-txt"
                                                                            style="font-weight: 300; font-size: 14px; color: #808080">
                                                                            Description: {{ @$task->description }}</p>
                                                                        <p class="mb-0 nrml-txt"
                                                                            style="font-weight: 300; font-size: 12px; color: #808080">
                                                                            Task Assigned at
                                                                            {{ $task->created_at->format('h:i A') }}
                                                                            ({{ $task->created_at->format('m-d-y') }})
                                                                        </p>
                                                                        <textarea class="mb-0 nrml-inp fw-bold p-0 w-100 mt-2" name="desc" id="">{{ @$task->description }}</textarea>
                                                                    @elseif($task->type == 'shipping_details')
                                                                        Shipping Details ({{ @$task->item }})
                                                                        <p class="mb-0 nrml-txt"
                                                                            style="font-weight: 300; font-size: 12px; color: #808080">
                                                                            Task Assigned at
                                                                            {{ $task->created_at->format('h:i A') }}
                                                                            ({{ $task->created_at->format('m-d-y') }})
                                                                        </p>
                                                                        <input class="mb-0 nrml-inp fw-bold p-0 mt-2"
                                                                            type="text" value="{{ @$task->item }}"
                                                                            name="item" placeholder="Shipping Item">
                                                                    @elseif($task->type == 'send_email')
                                                                        Send Email at <a
                                                                            href="mailto:{{ @$task->email }}">{{ @$task->email }}</a>
                                                                        <p class="mb-2 nrml-txt"
                                                                            style="font-weight: 300; font-size: 14px; color: #808080">
                                                                            Reason: {{ @$task->reason }}</p>
                                                                        <p class="mb-0 nrml-txt"
                                                                            style="font-weight: 300; font-size: 12px; color: #808080">
                                                                            Task Assigned at
                                                                            {{ $task->created_at->format('h:i A') }}
                                                                            ({{ $task->created_at->format('m-d-y') }})
                                                                        </p>
                                                                        <input class="mb-0 nrml-inp fw-bold p-0 mt-2"
                                                                            type="email" value="{{ @$task->email }}"
                                                                            name="email" placeholder="Email">
                                                                        <textarea class="mb-0 nrml-inp fw-bold p-0 w-100 mt-2" name="reason" id="">{{ @$task->reason }}</textarea>
                                                                    @elseif($task->type == 'upload_file')
                                                                        Upload File
                                                                        @php
                                                                            $fileName = str_replace(
                                                                                'docs/tasks/',
                                                                                '',
                                                                                $task->file,
                                                                            );

                                                                            $filePath = asset($task->file);
                                                                        @endphp
                                                                        <a href="{{ $filePath }}" target="_blank">
                                                                            {{ $fileName }}
                                                                        </a>
                                                                        <p class="mb-2 nrml-txt"
                                                                            style="font-weight: 300; font-size: 14px; color: #808080">
                                                                            Description: {{ @$task->description }}</p>

                                                                        <p class="mb-0 nrml-txt"
                                                                            style="font-weight: 300; font-size: 12px; color: #808080">
                                                                            Task Assigned at
                                                                            {{ $task->created_at->format('h:i A') }}
                                                                            ({{ $task->created_at->format('m-d-y') }})
                                                                        </p>
                                                                        <textarea class="mb-0 nrml-inp fw-bold p-0 w-100 mt-2" name="desc" id="">{{ @$task->description }}</textarea>
                                                                    @elseif($task->type == 'upload_photo')
                                                                        Upload/Take Photo
                                                                        @php
                                                                            $fileName = str_replace(
                                                                                'docs/tasks/',
                                                                                '',
                                                                                $task->file,
                                                                            );

                                                                            $filePath = asset($task->file);
                                                                        @endphp
                                                                        <a href="{{ $filePath }}" target="_blank">
                                                                            {{ $fileName }}
                                                                        </a>
                                                                        <p class="mb-2 nrml-txt"
                                                                            style="font-weight: 300; font-size: 14px; color: #808080">
                                                                            Description: {{ @$task->description }}</p>

                                                                        <p class="mb-0 nrml-txt"
                                                                            style="font-weight: 300; font-size: 12px; color: #808080">
                                                                            Task Assigned at
                                                                            {{ $task->created_at->format('h:i A') }}
                                                                            ({{ $task->created_at->format('m-d-y') }})
                                                                        </p>
                                                                        <textarea class="mb-0 nrml-inp fw-bold p-0 w-100 mt-2" name="desc" id="">{{ @$task->description }}</textarea>
                                                                    @elseif($task->type == 'closeout_note')
                                                                        Closeout Note
                                                                        <p class="mb-2 nrml-txt"
                                                                            style="font-weight: 300; font-size: 14px; color: #808080">
                                                                            Description: {{ @$task->description }}</p>
                                                                        <p class="mb-0 nrml-txt"
                                                                            style="font-weight: 300; font-size: 12px; color: #808080">
                                                                            Task Assigned at
                                                                            {{ $task->created_at->format('h:i A') }}
                                                                            ({{ $task->created_at->format('m-d-y') }})
                                                                        </p>
                                                                        <textarea class="mb-0 nrml-inp fw-bold p-0 w-100 mt-2" name="reason" id="">{{ @$task->description }}</textarea>
                                                                    @endif
                                                                </form>

                                                            </label>

                                                        </div>

                                                        <div class="d-flex action-group gap-2">
                                                            <button class="btn edit-btn" style="height: max-content;">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </button>
                                                            <button
                                                                onclick="document.getElementById('editTaskForm-{{ $task->id }}').submit()"
                                                                class="btn save-btn fw-bold"
                                                                style="height: max-content;">
                                                                Save
                                                            </button>
                                                            <button class="btn btn-danger cancel-btn fw-bold"
                                                                style="height: max-content;">
                                                                Cancel
                                                            </button>
                                                            <form action="{{ route('user.wo.deleteTask', $task->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn"
                                                                    style="height: max-content;">
                                                                    <i class="fa-solid fa-trash text-danger"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach

                                            <div class="form-check px-4 py-3 mb-4 d-flex justify-content-between"
                                                style="background-color: #E3F2FD; cursor: pointer">
                                                <div class="w-100">
                                                    <form id="checkoutForm-{{ $tech->tech_id }}"
                                                        action="{{ route('user.wo.checkout', ['id' => $wo->id, 'tech_id' => $tech->tech_id]) }}"
                                                        method="post" class="row">
                                                        @csrf

                                                        <label class="form-check-label col-10"
                                                            for="checkout-{{ $tech->tech_id }}">
                                                            <input
                                                                onchange="document.getElementById('checkoutForm-{{ $tech->tech_id }}').submit()"
                                                                class="form-check-input ms-0 me-2" type="checkbox"
                                                                value="" id="checkout-{{ $tech->tech_id }}"
                                                                @disabled($wo->status <= 7) @checked(@$wo->checkInOut->where('tech_id', $tech->tech_id)->last()->check_out)>
                                                            <div>
                                                                @if (
                                                                    @$wo->checkInOut->where('tech_id', $tech->tech_id)->last() &&
                                                                        @$wo->checkInOut->where('tech_id', $tech->tech_id)->last()->check_out)
                                                                    Checked out
                                                                @else
                                                                    Check out
                                                                @endif
                                                                <p class="mb-0 nrml-txt"
                                                                    style="font-weight: 300; font-size: 12px; color: #808080">
                                                                    Check out at <?php
                                                                    $currentTime = date('h:i A');
                                                                    $timezone = date_default_timezone_get();
                                                                    echo $currentTime . ' ' . $timezone;
                                                                    ?>
                                                                </p>
                                                            </div>

                                                        </label>

                                                    </form>

                                                </div>
                                            </div>

                                            @foreach ($wo->tasks as $task)
                                                @if ($task->type == 'closeout_note')
                                                    <div class="px-4 py-3 mb-4 d-flex justify-content-between action-cards"
                                                        style="background-color: #E3F2FD; cursor: pointer">
                                                        <div class="d-flex">
                                                            <label class="form-check-label"
                                                                for="task{{ $task->id }}">

                                                                <form id="editCloseout-{{ $task->id }}"
                                                                    action="{{ route('user.wo.editCloseoutNote', $task->id) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    Closeout Note
                                                                    <p class="mb-2 nrml-txt"
                                                                        style="font-weight: 500; font-size: 16px; color: #808080">
                                                                        Note: {{ @$task->description }}</p>
                                                                    <textarea class="mb-0 nrml-inp fw-bold p-0 w-100 mt-2" name="desc" id="">{{ @$task->description }}</textarea>
                                                                </form>

                                                            </label>
                                                        </div>

                                                        <div class="d-flex action-group gap-2">
                                                            <button class="btn edit-btn" style="height: max-content;">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </button>
                                                            <button
                                                                onclick="document.getElementById('editCloseout-{{ $task->id }}').submit()"
                                                                class="btn save-btn fw-bold"
                                                                style="height: max-content;">
                                                                Save
                                                            </button>
                                                            <button class="btn btn-danger cancel-btn fw-bold"
                                                                style="height: max-content;">
                                                                Cancel
                                                            </button>
                                                            <form action="{{ route('user.wo.deleteTask', $task->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn"
                                                                    style="height: max-content;">
                                                                    <i class="fa-solid fa-trash text-danger"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                            <div class="py-3 border-bottom closeout-note-add">
                                                <div>
                                                    <form id="createCloseoutNoteForm"
                                                        action="{{ route('user.wo.addCloseoutNote', $wo->id) }}"
                                                        method="post">
                                                        @csrf
                                                        <label for="">Closeout Note</label>
                                                        <textarea name="desc" placeholder="Enter Closeout Note" class="mb-2 border-bottom w-100"></textarea>
                                                    </form>

                                                </div>
                                                <div class="d-flex action-group gap-2">
                                                    <button
                                                        onclick="document.getElementById('createCloseoutNoteForm').submit()"
                                                        class="btn btn-success fw-bold" style="height: max-content;">
                                                        Save
                                                    </button>
                                                    <button class="btn btn-danger cnclCloseoutNote fw-bold"
                                                        style="height: max-content;">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <button class="btn btn-outline-dark addCloseoutNote">+ Add Closeout
                                                    Note</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach


                                <div class="card bg-white shadow-lg border-0 mb-4">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h3 style="font-size: 20px; font-weight: 600;">Deliverables </h3>
                                    </div>
                                    <div class="card-body bg-white">
                                        <!-- Pre Photo Section -->
                                        <h6>Pre Photo</h6>
                                        <div id="preDeliverCont" class="file-preview-container"></div>
                                        <div class="w-100 px-3 pb-2 mb-2 border-bottom">
                                            <div class="d-flex gap-2">
                                                @foreach ($wo->tasks as $task)
                                                    @if ($task->type == 'download_file' || $task->type == 'upload_file' || $task->type == 'upload_photo')
                                                        <div class="file-preview">
                                                            @php
                                                                $fileInfo = $task->file ? pathinfo($task->file) : [];
                                                                $extension = isset($fileInfo['extension'])
                                                                    ? strtolower($fileInfo['extension'])
                                                                    : null;
                                                            @endphp

                                                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                                <!-- If the file is an image -->
                                                                <img src="{{ asset($task->file) }}"
                                                                    class="preview-img" alt="Image Preview">
                                                            @elseif($extension == 'pdf')
                                                                <i class="fa fa-file-pdf preview-pdf"
                                                                    aria-hidden="true"></i>
                                                                <p>PDF File</p>
                                                            @else
                                                                <i class="fa fa-file preview-file"
                                                                    aria-hidden="true"></i>
                                                                <p>File: {{ str_replace('docs/tasks/', '', $task->file) }}
                                                                </p>
                                                            @endif

                                                            <p class="file-name">
                                                                {{ str_replace('docs/tasks/', '', $task->file) }}</p>
                                                            <button class="delete-btn">X</button>
                                                        </div>
                                                    @endif
                                                @endforeach

                                            </div>

                                            <label for="preTechnicianDoc" class="btn">+ Add File</label>
                                            <input id="preTechnicianDoc" class="invisible file-input" type="file"
                                                multiple>
                                        </div>

                                        <!-- Post Photo Section -->
                                        <h6>Post Photo</h6>
                                        <div id="postDeliverCont" class="file-preview-container"></div>
                                        <div class="w-100 px-3 pb-2 mb-2 border-bottom">
                                            <label for="postTechnicianDoc" class="btn">+ Add File</label>
                                            <input id="postTechnicianDoc" class="invisible file-input" type="file"
                                                multiple>
                                        </div>

                                        <!-- Misc Photo Section -->
                                        <h6>Misc Photo</h6>
                                        <div id="miscDeliverCont" class="file-preview-container"></div>
                                        <div class="w-100 px-3 pb-2 mb-2 border-bottom">
                                            <label for="miscTechnicianDoc" class="btn">+ Add File</label>
                                            <input id="miscTechnicianDoc" class="invisible file-input" type="file"
                                                multiple>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-5">
                                <div class="card bg-white shadow-lg border-0 mb-4">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h3 style="font-size: 20px; font-weight: 600;">Contacts</h3>
                                    </div>
                                    <div class="card-body bg-white">
                                        <a href="tel:{{ @$wo->technician->phone }}">Technician Phone:
                                            {{ @$wo->technician->phone }}</a>
                                        @foreach ($wo->contacts as $contact)
                                            <div class="py-3 d-flex justify-content-between border-bottom action-cards">
                                                <div>
                                                    <h6 class="nrml-txt">{{ $contact->title }}</h6>
                                                    <p class="nrml-txt">{{ $contact->name }}</p>
                                                    <a class="nrml-txt"
                                                        href="callto:{{ $contact->phone }}">{{ $contact->phone }}</a>
                                                    <form id="updateContactForm{{ $contact->id }}"
                                                        action="{{ route('user.wo.updateContact', $contact->id) }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="text" name="title"
                                                            value="{{ $contact->title }}"
                                                            class="mb-0 nrml-inp fw-bold p-0">
                                                        <input type="text" name="name"
                                                            value="{{ $contact->name }}" class="mb-0 nrml-inp p-0">
                                                        <input type="text" name="phone"
                                                            value="{{ $contact->phone }}"
                                                            class="mb-0 nrml-inp text-primary p-0">
                                                    </form>

                                                </div>
                                                <div class="d-flex action-group gap-2">
                                                    <button class="btn edit-btn">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <button
                                                        onclick="document.getElementById('updateContactForm{{ $contact->id }}').submit()"
                                                        class="btn save-btn fw-bold" style="height: max-content;">
                                                        Save
                                                    </button>
                                                    <button class="btn btn-danger cancel-btn fw-bold"
                                                        style="height: max-content;">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach


                                        <div class="py-3 border-bottom contact-add">
                                            <div>
                                                <form id="createContactForm"
                                                    action="{{ route('user.wo.createContact', $wo->id) }}"
                                                    method="post">
                                                    @csrf
                                                    <label for="">Title</label>
                                                    <input type="text" name="title" placeholder="Enter Title"
                                                        class="mb-2 border-bottom w-100" style="font-weight: 600;">
                                                    <label for="">Name</label>
                                                    <input type="text" name="name" placeholder="Enter Name"
                                                        class="mb-2 border-bottom w-100">
                                                    <label for="">Number</label>
                                                    <input type="text" name="phone" placeholder="Enter Number"
                                                        class="mb-2 border-bottom w-100 text-primary">
                                                </form>

                                            </div>
                                            <div class="d-flex action-group gap-2">
                                                <button onclick="document.getElementById('createContactForm').submit()"
                                                    class="btn btn-success fw-bold" style="height: max-content;">
                                                    Save
                                                </button>
                                                <button class="btn btn-danger cnclContact fw-bold"
                                                    style="height: max-content;">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <button class="btn btn-outline-dark addContact">Add Contact</button>
                                        </div>
                                    </div>

                                </div>

                                <div class="card bg-white shadow-lg border-0 mb-4">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h3 style="font-size: 20px; font-weight: 600;">Schedule</h3>

                                    </div>
                                    <div class="card-body bg-white">
                                        <!-- <div class="position-relative p-3 mb-3" style="background-color: #E3F2FD;">
                                            <p>Start at a specific date and time</p>
                                            @php
                                                use Carbon\Carbon;
                                                $day = null;
                                                if ($wo->on_site_by) {
                                                    try {
                                                        $day = Carbon::parse($wo->on_site_by)->format('l');
                                                    } catch (\Exception $e) {
                                                        $day = null;
                                                    }
                                                }
                                            @endphp

                                            <b>
                                                {{ @$day }},
                                                <form id="scheduleForm" action="{{ route('user.wo.updateSchedule', $wo->id) }}" method="post">
                                                    @csrf

                                                    <span class="nrml-txt">{{ @$wo->on_site_by }}</span>
                                                    <span>
                                                        <input type="date" name="on_site_by" class="mb-2 border-bottom nrml-inp fw-bold"
                                                            value="{{ $wo->on_site_by ? Carbon::createFromFormat('m-d-y', $wo->on_site_by)->format('Y-m-d') : '' }}">

                                                    </span> at
                                                    <span class="nrml-txt">{{ Carbon::parse(@$wo->scheduled_time)->format('h:i A') }}</span>
                                                    <span>
                                                        <input type="time" name="scheduled_time" class="mb-2 border-bottom nrml-inp fw-bold" value="{{ @$wo->scheduled_time }}">
                                                    </span>
                                                    <span class="nrml-txt">({{ @$wo->site->time_zone }})</span>
                                                    <span>
                                                        @php
                                                            $timezoneMap = [
                                                                'PT' => 'America/Los_Angeles',
                                                                'MT' => 'America/Denver',
                                                                'CT' => 'America/Chicago',
                                                                'ET' => 'America/New_York',
                                                                'AKT' => 'America/Anchorage',
                                                                'HST' => 'Pacific/Honolulu',

                                                                'PT/MT' => 'America/Los_Angeles',
                                                                'CT/MT' => 'America/Chicago',
                                                                'CT/ET' => 'America/New_York',
                                                            ];
                                                        @endphp
                                                        <select name="time_zone" class="mb-2 border-bottom nrml-inp fw-bold">
                                                            <option value="">Select Timezone</option>
                                                            @foreach ($timezoneMap as $abbr => $zone)
    <option value="{{ $abbr }}" @if (@$wo->site->time_zone == $abbr) selected @endif>
                                                                {{ $zone }} ({{ $abbr }})
                                                            </option>
    @endforeach
                                                        </select>
                                                    </span>
                                            </b>
                                            <p>Approximate hours to complete</p>
                                            <b class="nrml-txt">{{ @$wo->h_operation }}</b>
                                            <input type="text" name="h_operation" class="mb-2 border-bottom nrml-inp fw-bold" value="{{ @$wo->h_operation }}">
                                            <p>updated by {{ @$wo->employee->name }} on {{ @$wo->updated_at->format('m/d/Y') }} at {{ @$wo->updated_at->format('h:i A') }}</p>
                                            </form>

                                            <div class="d-flex action-group gap-2 position-absolute end-0 top-0 p-3">
                                                <button class="btn edit-btn">
                                                    <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                                </button>
                                                <button onclick="document.getElementById('scheduleForm').submit();" class="btn save-btn fw-bold">
                                                    Save
                                                </button>
                                                <button class="btn btn-danger cancel-btn fw-bold">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div> -->

                                        @foreach ($wo->schedules as $schedule)
                                            <div class="position-relative p-3 mb-3 action-cards"
                                                style="background-color: #E3F2FD;">
                                                <p>Start at a specific date and time</p>


                                                <b>
                                                    {{ Carbon::parse($schedule->on_site_by)->format('l') }},
                                                    <form id="scheduleForm"
                                                        action="{{ route('user.wo.updateSchedule', $wo->id) }}"
                                                        method="post">
                                                        @csrf
                                                        <span class="nrml-txt">
                                                            {{ Carbon::parse($schedule->on_site_by)->format('m-d-y') }}

                                                            at
                                                            {{ Carbon::parse(@$schedule->scheduled_time)->format('h:i A') }}
                                                            ({{ @$wo->site->time_zone }})
                                                        </span>


                                                        <span class="nrml-inp">
                                                            <input type="date" name="on_site_by"
                                                                class="mb-2 border-bottom nrml-inp fw-bold"
                                                                value="{{ $schedule->on_site_by }}">
                                                            at
                                                            <input type="time" name="scheduled_time"
                                                                class="mb-2 border-bottom nrml-inp fw-bold"
                                                                value="{{ @$wo->scheduled_time }}">

                                                            @php
                                                                $timezoneMap = [
                                                                    'PT' => 'America/Los_Angeles',
                                                                    'MT' => 'America/Denver',
                                                                    'CT' => 'America/Chicago',
                                                                    'ET' => 'America/New_York',
                                                                    'AKT' => 'America/Anchorage',
                                                                    'HST' => 'Pacific/Honolulu',

                                                                    'PT/MT' => 'America/Los_Angeles',
                                                                    'CT/MT' => 'America/Chicago',
                                                                    'CT/ET' => 'America/New_York',
                                                                ];
                                                            @endphp
                                                            <select name="time_zone"
                                                                class="mb-2 border-bottom nrml-inp fw-bold">
                                                                <option value="">Select Timezone</option>
                                                                @foreach ($timezoneMap as $abbr => $zone)
                                                                    <option value="{{ $abbr }}"
                                                                        @if (@$wo->site->time_zone == $abbr) selected @endif>
                                                                        {{ $zone }} ({{ $abbr }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </span>

                                                        <!-- <span class="nrml-txt">{{ Carbon::parse($schedule->on_site_by)->format('m-d-y') }}</span>
                                                    <span>
                                                        <input type="date" name="on_site_by" class="mb-2 border-bottom nrml-inp fw-bold"
                                                            value="{{ $schedule->on_site_by }}">

                                                    </span> at
                                                    <span class="nrml-txt">{{ Carbon::parse(@$schedule->scheduled_time)->format('h:i A') }}</span>
                                                    <span>
                                                        <input type="time" name="scheduled_time" class="mb-2 border-bottom nrml-inp fw-bold" value="{{ @$wo->scheduled_time }}">
                                                    </span>
                                                    <span class="nrml-txt">({{ @$wo->site->time_zone }})</span>
                                                    <span>
                                                        @php
                                                            $timezoneMap = [
                                                                'PT' => 'America/Los_Angeles',
                                                                'MT' => 'America/Denver',
                                                                'CT' => 'America/Chicago',
                                                                'ET' => 'America/New_York',
                                                                'AKT' => 'America/Anchorage',
                                                                'HST' => 'Pacific/Honolulu',

                                                                'PT/MT' => 'America/Los_Angeles',
                                                                'CT/MT' => 'America/Chicago',
                                                                'CT/ET' => 'America/New_York',
                                                            ];
                                                        @endphp
                                                        <select name="time_zone" class="mb-2 border-bottom nrml-inp fw-bold">
                                                            <option value="">Select Timezone</option>
                                                            @foreach ($timezoneMap as $abbr => $zone)
    <option value="{{ $abbr }}" @if (@$wo->site->time_zone == $abbr) selected @endif>
                                                                {{ $zone }} ({{ $abbr }})
                                                            </option>
    @endforeach
                                                        </select>
                                                    </span> -->
                                                </b>
                                                <p>Approximate hours to complete</p>
                                                <b class="nrml-txt">{{ @$schedule->h_operation }}</b>
                                                <input type="text" name="h_operation"
                                                    class="mb-2 border-bottom nrml-inp fw-bold"
                                                    value="{{ @$wo->h_operation }}">
                                                <p>updated by {{ @$wo->employee->name }} on
                                                    {{ @$wo->updated_at->format('m/d/Y') }} at
                                                    {{ @$wo->updated_at->format('h:i A') }}</p>

                                                @if ($schedule->schedule_note)
                                                    <i style="color: #6a6a6a" class="nrml-txt">Note:
                                                        {{ @$schedule->schedule_note }}</i>
                                                @endif
                                                <textarea name="schedule_note" class="mb-2 border-bottom nrml-inp fw-bold">{{ @$schedule->schedule_note }}</textarea>
                                                </form>

                                                <div class="d-flex action-group gap-2 position-absolute end-0 top-0 p-3">
                                                    <button class="btn edit-btn">
                                                        <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                                    </button>
                                                    <button onclick="document.getElementById('scheduleForm').submit();"
                                                        class="btn save-btn fw-bold">
                                                        Save
                                                    </button>
                                                    <button class="btn btn-danger cancel-btn fw-bold">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="py-3 border-bottom schedule-add">
                                            <div>
                                                <form id="createScheduleForm"
                                                    action="{{ route('user.wo.createSchedule', $wo->id) }}"
                                                    method="post">
                                                    @csrf
                                                    <label for="">Schedule Date</label>
                                                    <input type="date" name="on_site_by" placeholder="Enter Date"
                                                        class="mb-2 border-bottom w-100" style="font-weight: 600;">
                                                    <label for="">Schedule Time</label>
                                                    <input type="time" name="scheduled_time"
                                                        placeholder="Enter Name" class="mb-2 border-bottom w-100">
                                                    <label for="">Hours Of Operation</label>
                                                    <input type="text" name="h_operation"
                                                        placeholder="Enter Hours Of Operation"
                                                        class="mb-2 border-bottom w-100 text-primary">

                                                    <label for="">Schedule Note</label>
                                                    <textarea type="text" name="schedule_note" placeholder="Enter Hours Of Operation"
                                                        class="mb-2 border-bottom w-100"></textarea>
                                                </form>

                                            </div>
                                            <div class="d-flex action-group gap-2">
                                                <button onclick="document.getElementById('createScheduleForm').submit()"
                                                    class="btn btn-success fw-bold" style="height: max-content;">
                                                    Save
                                                </button>
                                                <button class="btn btn-danger cnclSchedule fw-bold"
                                                    style="height: max-content;">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                        <button class="btn btn-outline-dark addSchedule">+ Add Schedule</button>
                                    </div>
                                </div>

                                <div class="card action-cards bg-white shadow-lg border-0 mb-4">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h3 style="font-size: 20px; font-weight: 600;">Location</h3>

                                        <div class="d-flex action-group gap-2">
                                            <button class="btn edit-btn">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button onclick="document.getElementById('siteForm').submit()"
                                                class="btn save-btn fw-bold">
                                                Save
                                            </button>
                                            <button class="btn btn-danger cancel-btn fw-bold">
                                                Cancel
                                            </button>
                                        </div>

                                    </div>
                                    <div class="card-body bg-white">
                                        <div class="nrml-inp">
                                            <h6 for="" style="border-bottom: 0 !important;">Site location</h6>

                                            <form id="siteForm"
                                                action="{{ route('user.wo.updateSiteInfo', $wo->id) }}" method="post"
                                                class="mb-4">
                                                @csrf
                                                <select name="site_id" class="mb-0 nrml-inp fw-bold p-0 selectpicker"
                                                    id="my-select" autocomplete="off" data-live-search="true"
                                                    value="{{ @$wo->customer->company_name }}" data-width="100%"
                                                    data-size="5">
                                                    <option value="">-- Select an Option --</option>
                                                    @foreach ($customerSites as $site)
                                                        <option value="{{ $site->id }}"
                                                            @if ($wo->site_id == $site->id) selected @endif>
                                                            {{ $site->site_id }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('cus_id'))
                                                    <span class="text-danger">{{ $errors->first('cus_id') }}</span>
                                                @endif
                                            </form>

                                        </div>
                                        @if ($wo->site)
                                            <p class="mb-0">{{ @$wo->site->location }} &
                                                {{ @$wo->site->address_1 }},</p>
                                            <p class="mb-0">{{ @$wo->site->city }}, {{ @$wo->site->state }}, </p>
                                            <p class="mb-0">{{ @$wo->site->zipcode }}</p>

                                            <!-- @php
                                                // Assuming the coordinates are stored as POINT(lat lng) and retrieved dynamically.
                                                $point = $wo->site->co_ordinates; // Assuming $wo->site->co_ordinates holds the POINT value.
                                                if ($point) {
                                                    $coordinates = explode(
                                                        ' ',
                                                        str_replace(['POINT(', ')'], '', $point),
                                                    );
                                                    $latitude = $coordinates[0] ?? 34.9776679; // Default latitude
                                                    $longitude = $coordinates[1] ?? -120.4379281; // Default longitude
                                                } else {
                                                    $latitude = 34.9776679; // Default latitude
                                                    $longitude = -120.4379281; // Default longitude
                                                }
                                            @endphp

                                        <iframe
                                            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCZQq1GlPJb8PrwOkCiihS-tAq0qS-O1j8&q={{ $latitude }},{{ $longitude }}"
                                            width="100%"
                                            height="450"
                                            style="border:0;"
                                            allowfullscreen=""
                                            loading="lazy">
                                        </iframe> -->
                                        @endif
                                    </div>
                                </div>

                                <div class="card bg-white shadow-lg border-0 mb-4 action-cards">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h3 style="font-size: 20px; font-weight: 600;">Pay Sheet</h3>
                                        <div class="d-flex action-group gap-2">
                                            <button class="btn edit-btn">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button onclick="document.getElementById('paysheetForm').submit()"
                                                class="btn save-btn fw-bold">
                                                Save
                                            </button>
                                            <button class="btn btn-danger cancel-btn fw-bold">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body bg-white">
                                        <p class="mb-0">Payment Terms</p>
                                        <p class="mb-0 mx-5"><b>NET-{{ @$wo->technician->terms ?? ' ' }}-day terms</b>
                                        </p>

                                        <form id="paysheetForm" action="{{ route('user.wo.updatePaySheet', $wo->id) }}"
                                            method="post">
                                            @csrf
                                            <div class="d-flex mt-4 justify-content-between"
                                                style="font-size: 20px; font-weight: 400 !important">
                                                <p>Labor</p>
                                            </div>
                                            <div class="d-flex mt-2 justify-content-between ps-5"
                                                style="font-size: 20px; font-weight: 400 !important">
                                                <p>Rate</p>
                                                <hr class="w-75">
                                                <p>${{ number_format(is_numeric(@$wo->technician->rate['STD']) ? @$wo->technician->rate['STD'] : 0, 2) }}
                                                </p>

                                            </div>
                                            <div class="d-flex mt-2 justify-content-between ps-5"
                                                style="font-size: 20px; font-weight: 400 !important">
                                                <p>Hour</p>
                                                <hr class="w-75">
                                                <p id="totalHours">{{ @$wo->checkInOut->sum('total_hours') }} hours</p>
                                            </div>
                                            <hr>

                                            <div class="d-flex mt-2 justify-content-between ps-5"
                                                style="font-size: 20px; font-weight: 400 !important">
                                                <p>Total Labor Cost</p>
                                                <hr class="w-50">
                                                <p>${{ is_numeric(@$wo->checkInOut->sum('total_hours')) && is_numeric(@$wo->technician->rate['STD'])
                                                    ? @$wo->checkInOut->sum('total_hours') * @$wo->technician->rate['STD']
                                                    : 0.0 }}
                                                </p>
                                            </div>

                                            <div class="d-flex mt-2 justify-content-between ps-5"
                                                style="font-size: 20px; font-weight: 400 !important">
                                                <p>Travel</p>
                                                <hr class="w-50">
                                                <p class="nrml-txt">$0.00</p>
                                                <input id="travelRate" name="travel" type="text"
                                                    class="nrml-inp text-end" style="width: 100px;" value="0.00"
                                                    oninput="updateTotal()">
                                            </div>

                                            <div class="d-flex mt-2 justify-content-between"
                                                style="font-size: 20px; font-weight: 400 !important">
                                                <p>Expenses</p>
                                            </div>
                                            @foreach ($wo->techProvidedParts as $part)
                                                <div class="d-flex mt-2 justify-content-between ps-5"
                                                    style="font-size: 20px; font-weight: 400 !important">
                                                    <p>{{ $part->part_name }} x {{ $part->quantity }}</p>
                                                    <hr class="w-50">
                                                    <p>${{ $part->amount }}</p>
                                                </div>
                                            @endforeach
                                        </form>

                                        <button class="btn btn-outline-dark my-3">+ Add Items</button>
                                        <div class="d-flex mt-2 justify-content-between" style="font-size: 20px;">
                                            <p class="fw-bold">Total Pay</p>
                                            <p id="totalPay" class="fw-bold">
                                                ${{ is_numeric(@$wo->checkInOut->sum('total_hours')) && is_numeric(@$wo->technician->rate['STD'])
                                                    ? @$wo->checkInOut->sum('total_hours') * @$wo->technician->rate['STD'] + $wo->techProvidedParts->sum('amount')
                                                    : 0 }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="card action-cards bg-white shadow-lg border-0 mb-4">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h3 style="font-size: 20px; font-weight: 600;">Profit Sheet</h3>

                                        <div class="d-flex action-group gap-2">
                                            <button class="btn edit-btn">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button class="btn save-btn fw-bold">
                                                Save
                                            </button>
                                            <button class="btn btn-danger cancel-btn fw-bold">
                                                Cancel
                                            </button>
                                        </div>

                                    </div>
                                    <div class="card-body bg-white">

                                    </div>
                                </div>

                                <div class="card bg-white shadow-lg border-0 mb-4">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h3 style="font-size: 20px; font-weight: 600;">Time log</h3>
                                    </div>
                                    <div class="card-body bg-white">

                                        @foreach ($wo->checkInOut as $check)
                                            <form id="deleteLog-{{ $check->id }}"
                                                action="{{ route('user.wo.deleteLog', $check->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')

                                            </form>
                                            <form action="{{ route('user.wo.logCheckinout', $check->id) }}"
                                                method="post" class="py-3 mb-3 mx-1 row flex-row card action-cards"
                                                style="background-color: #E3F2FD;">
                                                @csrf
                                                <div
                                                    class="col-12 pb-3 mb-3 border-bottom d-flex justify-content-between align-items-center">
                                                    <span>Total Hours:
                                                        @if ($check->total_hours)
                                                            @php
                                                                $checkIn = \Carbon\Carbon::parse($check->check_in);
                                                                $checkOut = \Carbon\Carbon::parse($check->check_out);

                                                                // Handle the case where check-out time is on the next day
                                                                if ($checkOut->lt($checkIn)) {
                                                                    $checkOut->addDay();
                                                                }

                                                                $diff = $checkIn->diff($checkOut);

                                                                $hours = $diff->h;
                                                                $minutes = $diff->i;

                                                                // Format the time as "X hour(s) Y minute(s)"
                                                                $formattedTime = '';
                                                                if ($hours > 0) {
                                                                    $formattedTime .=
                                                                        "{$hours} hour" . ($hours > 1 ? 's' : '') . ' ';
                                                                }
                                                                if ($minutes > 0) {
                                                                    $formattedTime .=
                                                                        "{$minutes} minute" . ($minutes > 1 ? 's' : '');
                                                                }

                                                                echo trim($formattedTime);
                                                            @endphp


                                                            ≈

                                                            {{ $check->total_hours }} hour
                                                        @else
                                                            @php
                                                                $timezoneMap = [
                                                                    'PT' => 'America/Los_Angeles',
                                                                    'MT' => 'America/Denver',
                                                                    'CT' => 'America/Chicago',
                                                                    'ET' => 'America/New_York',
                                                                    'AKT' => 'America/Anchorage',
                                                                    'HST' => 'Pacific/Honolulu',

                                                                    'PT/MT' => [
                                                                        'America/Los_Angeles',
                                                                        'America/Denver',
                                                                    ],
                                                                    'CT/MT' => ['America/Denver', 'America/Chicago'],
                                                                    'CT/ET' => ['America/Chicago', 'America/New_York'],
                                                                ];

                                                                $shortTimezone = $wo->site->time_zone ?? 'UTC';
                                                                $mappedTimezone = $timezoneMap[$shortTimezone] ?? 'UTC';
                                                                $minutes = \Carbon\Carbon::parse(
                                                                    $check->check_in,
                                                                    $mappedTimezone,
                                                                )->diffInMinutes();
                                                            @endphp
                                                            @if ($minutes < 60)
                                                                {{ $minutes }} minutes
                                                            @else
                                                                {{ round($minutes / 60, 2) }} hour
                                                            @endif
                                                        @endif
                                                    </span>



                                                    <div class="d-flex action-group gap-2">
                                                        <button type="button" class="btn edit-btn">
                                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                                        </button>
                                                        <button type="submit"
                                                            onclick="document.getElementById('logCheckinForm').submit()"
                                                            class="btn save-btn fw-bold" style="height: max-content;">
                                                            Save
                                                        </button>
                                                        <button type="button" class="btn btn-danger cancel-btn fw-bold"
                                                            style="height: max-content;">
                                                            Cancel
                                                        </button>

                                                        <button
                                                            onclick="document.getElementById('deleteLog-{{ $check->id }}').submit()"
                                                            type="button" class="btn"
                                                            style="height: max-content;">
                                                            <i class="fa-solid fa-trash text-danger"
                                                                aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="col-6 border-end">
                                                    <p>Checked in at <span
                                                            class="nrml-txt">{{ \Carbon\Carbon::parse($check->check_in)->format('h:i A') }}</span>
                                                    </p>
                                                    @if ($check->checkin_note)
                                                        <p style="font-weight: 300; font-size: 14px">
                                                            {{ $check->checkin_note }}</p>
                                                    @endif
                                                    <input type="time" value="{{ $check->check_in }}"
                                                        class="nrml-inp mb-2" name="check_in" id="">

                                                    <p>Date <span class="nrml-txt">{{ $check->date }}</span></p>
                                                    @php
                                                        $formattedDate = $check->date
                                                            ? \Carbon\Carbon::parse($check->date)->format('Y-m-d')
                                                            : '';
                                                    @endphp

                                                    <input type="date" value="{{ $formattedDate }}"
                                                        class="nrml-inp mb-2" name="date" id="">


                                                </div>
                                                <div class="col-6 ps-3">
                                                    <p>
                                                        @if ($check->check_out)
                                                            Checked out at <span
                                                                class="nrml-txt">{{ \Carbon\Carbon::parse($check->check_out)->format('h:i A') }}</span>
                                                        @else
                                                            Not checked out yet
                                                        @endif
                                                    </p>
                                                    @if ($check->checkout_note)
                                                        <p style="font-weight: 300; font-size: 14px">
                                                            {{ $check->checkout_note }}</p>
                                                    @endif
                                                    <input type="time" value="{{ $check->check_out }}"
                                                        class="nrml-inp" name="check_out" id="">
                                                </div>
                                                <div class="col-12 border-top pt-2 d-flex gap-2">
                                                    by
                                                    @if (@$check->engineer->avatar)
                                                        <img src="{{ asset($check->engineer->avatar) }}"
                                                            style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover"
                                                            alt="" srcset="">
                                                    @else
                                                        <div class="bg-primary d-flex justify-content-center align-items-center text-white"
                                                            style="width: 30px; height: 30px; border-radius: 50%;">
                                                            {{ substr(@$check->engineer->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                    {{ @$check->engineer->name }}
                                                </div>
                                            </form>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div id="tab2" class="tab-content">
                        @if ($wo->ftech_id == null)
                            <div class="card" style="border-top:none; border-radius:0px">
                                <div class="card-header">
                                    <h3>Field Technician</h3>
                                    <input type="hidden" id="workOrderId" value="{{ $wo->id }}">
                                    <span style="float:right">
                                        <button type="button" class="btn btn-success" id="findClosestTechBtn1">
                                            <i class="fa fa-magnifying-glass" style="font-size: 13px;"></i>&nbsp;Find
                                            Tech
                                        </button>
                                    </span>

                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <h6 id="assignedTechMessage"></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid d-none" id="tech_distance_view1" style="margin-top:-60px">
                                @include('user.distanceMeasureModal.assign')
                                @include('user.distanceMeasureModal.contact')
                                <div class="card" style="border-top: none; border-radius:0px; margin-top: 40px;">
                                    <div class="card-body">
                                        <div class="d-none" id="loader1">
                                            <h6 class="text-dark"><strong>Please wait for the responses from
                                                    google</strong></h6>
                                            <div class="spinner-grow text-danger" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <div class="d-none" id="removable-div1">
                                            <div class="table-responsive">
                                                <table class="table table-bordered text-dark" id="data-table">
                                                    <thead class="text-nowrap">
                                                        <tr>
                                                            <th>Assign</th>
                                                            <th>Technician ID</th>
                                                            <th>Company Name</th>
                                                            <th>Status</th>
                                                            <th>Skill Sets</th>
                                                            <th>Rate</th>
                                                            <th>Travel Fee</th>
                                                            <th>Preferred?</th>
                                                            <th>Distance</th>
                                                            <th>Duration</th>
                                                            <th>Within Radius ?</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbody" class="text-center"></tbody>
                                                </table>
                                            </div>
                                            <div style="text-align:center;">
                                                <button type="button" class="btn btn-secondary my-1"
                                                    id="btn-previous0">Previous</button>
                                                <button type="button" class="btn btn-success my-1"
                                                    id="btn-find-more0">Next</button>
                                            </div>
                                        </div>
                                        <div class="d-none" id="confirmation-div">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p class="text-bold" id="message"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            @if (@$wo->technician->tech_type == 'company')
                                <div class="col-12 mx-auto mb-5">
                                    <h5>Company Details</h5>
                                    <table class="table table-bordered text-left">
                                        <thead>
                                            <tr>
                                                <th>Company Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ @$wo->technician->company_name }}</td>
                                                <td>{{ @$wo->technician->email }}</td>
                                                <td>{{ @$wo->technician->phone }}</td>
                                                <td>{{ @$wo->technician->address_data->address }},
                                                    {{ @$wo->technician->address_data->city }},
                                                    {{ @$wo->technician->address_data->state }},
                                                    {{ @$wo->technician->address_data->zip_code }},
                                                    {{ @$wo->technician->address_data->country }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            <div class="col-12 mx-auto">
                                <h5>
                                    @if (@$wo->technician->tech_type == 'company')
                                        Admin Details
                                    @else
                                        Assigned Technician Details
                                    @endif
                                </h5>
                                <table class="table table-bordered text-left">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Technician Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <form id="editTech" action="{{ route('user.wo.editTech', $wo->ftech_id) }}"
                                            method="post">
                                            @csrf
                                            <tr class="action-cards">
                                                <td id="ftech_company">
                                                    @if (@$wo->technician->tech_type == 'company')
                                                        <span class="nrml-txt">{{ @$wo->technician->admin_name }}
                                                            ({{ @$wo->technician->technician_id }})</span>
                                                        <input type="text" name="admin_name"
                                                            value="{{ @$wo->technician->admin_name }}"
                                                            class="form-control nrml-inp">
                                                    @else
                                                        <span class="nrml-txt">{{ @$wo->technician->company_name }}
                                                            ({{ @$wo->technician->technician_id }})</span>
                                                        <input type="text" name="company_name"
                                                            value="{{ @$wo->technician->company_name }}"
                                                            class="form-control nrml-inp">
                                                    @endif
                                                </td>
                                                <td id="ftech_email">{{ @$wo->technician->email }}</td>
                                                <td id="ftech_email">{{ @$wo->technician->phone }}</td>
                                                <td id="ftech_address">{{ @$wo->technician->address_data->address }},
                                                    {{ @$wo->technician->address_data->city }},
                                                    {{ @$wo->technician->address_data->state }},
                                                    {{ @$wo->technician->address_data->zip_code }},
                                                    {{ @$wo->technician->address_data->country }}
                                                </td>
                                                <td>
                                                    <span class="nrml-txt">{{ @$wo->technician->tech_type }}</span>

                                                    <select name="tech_type" id=""
                                                        class="form-select nrml-inp">
                                                        <option value="individual"
                                                            @if (@$wo->technician->tech_type == 'individual') selected @endif>Individual
                                                        </option>
                                                        <option value="company"
                                                            @if (@$wo->technician->tech_type == 'company') selected @endif>Company
                                                        </option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="d-flex action-group gap-2">
                                                        <button type="button" class="btn edit-btn">
                                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                                        </button>
                                                        <button type="submit"
                                                            onclick="document.getElementById('editTech').submit()"
                                                            class="btn save-btn fw-bold" style="height: max-content;">
                                                            Save
                                                        </button>
                                                        <button type="button" class="btn btn-danger cancel-btn fw-bold"
                                                            style="height: max-content;">
                                                            Cancel
                                                        </button>

                                                        <button onclick="document.getElementById('deleteTech').submit()"
                                                            type="button" class="btn"
                                                            style="height: max-content;">
                                                            <i class="fa-solid fa-trash text-danger"
                                                                aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </form>
                                        <form id="deleteTech" action="{{ route('user.wo.deleteTech', $wo->id) }}"
                                            method="post">
                                            @csrf
                                        </form>
                                    </tbody>
                                </table>
                            </div>
                            @if (@$wo->technician->tech_type == 'company')
                                <div class="col-12 mx-auto">
                                    <h5>Assigned Technician Details</h5>
                                    <table class="table table-bordered text-left">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Role</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (@$wo->assignedTech as $eng)
                                                <form id="editTech"
                                                    action="{{ route('user.wo.editAssignees', $eng->tech_id) }}"
                                                    method="post">
                                                    @csrf
                                                    <tr class="action-cards">
                                                        <td id="ftech_company">
                                                            <span class="nrml-txt">{{ @$eng->engineer->name }}</span>
                                                            <input type="text" name="eng_name"
                                                                value="{{ @$eng->engineer->name }}"
                                                                class="form-control nrml-inp">
                                                        </td>
                                                        <td id="ftech_company">
                                                            <span class="nrml-txt">{{ @$eng->engineer->role }}</span>
                                                            <input type="text" name="role"
                                                                value="{{ @$eng->engineer->role }}"
                                                                class="form-control nrml-inp">
                                                        </td>
                                                        <td id="ftech_email">
                                                            <span class="nrml-txt">
                                                                {{ @$eng->engineer->email }}
                                                            </span>
                                                            <input type="email" name="email"
                                                                value="{{ @$eng->engineer->email }}"
                                                                class="form-control nrml-inp">
                                                        </td>
                                                        <td id="ftech_email">
                                                            <span class="nrml-txt">
                                                                {{ @$eng->engineer->phone }}
                                                            </span>
                                                            <input type="text" name="phone"
                                                                value="{{ @$eng->engineer->phone }}"
                                                                class="form-control nrml-inp">
                                                        </td>
                                                        <td>
                                                            <div class="d-flex action-group gap-2">
                                                                <button type="button" class="btn edit-btn">
                                                                    <i class="fa-solid fa-pen-to-square"
                                                                        aria-hidden="true"></i>
                                                                </button>
                                                                <button type="submit"
                                                                    onclick="document.getElementById('editTech').submit()"
                                                                    class="btn save-btn fw-bold"
                                                                    style="height: max-content;">
                                                                    Save
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-danger cancel-btn fw-bold"
                                                                    style="height: max-content;">
                                                                    Cancel
                                                                </button>

                                                                <button
                                                                    onclick="document.getElementById('deleteAssignees-{{ $eng->id }}').submit()"
                                                                    type="button" class="btn"
                                                                    style="height: max-content;">
                                                                    <i class="fa-solid fa-trash text-danger"
                                                                        aria-hidden="true"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </form>
                                                <form id="deleteAssignees-{{ $eng->id }}"
                                                    action="{{ route('user.wo.deleteAssignees', $eng->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endif

                        @if (@$wo->ftech_id && @$wo->technician->tech_type == 'company')
                            <button type="button" class="btn btn-outline-dark addTech my-3"
                                @if (isset($wo->assignedTech) && $wo->assignedTech->count() >= 2) disabled @endif>
                                + Add Technician
                            </button>
                            @if ($wo->assignedTech->count() >= 2)
                                <p class="text-danger">You have reached a maximum of 2 technicians</p>
                            @endif

                            <div class="tech-add">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Technician</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('technician.engineer.assign', $wo->id) }}"
                                            method="post" enctype="multipart/form-data">
                                            @csrf
                                            <select class="selectpicker" multiple data-width="100%"
                                                title="Choose Technicians..." name="techs[]">
                                                @foreach (@$wo->technician->engineers as $engineer)
                                                    @php
                                                        // Check if the engineer's ID exists in the assigned techs
$isAssigned = @$wo->assignedTech->contains(
    'tech_id',
                                                            $engineer->id,
                                                        );
                                                    @endphp

                                                    @if (!$isAssigned)
                                                        <option value="{{ $engineer->id }}">{{ $engineer->name }}
                                                        </option>
                                                    @endif
                                                @endforeach

                                            </select>

                                            <button type="button" class="my-3 btn btn-outline-dark"
                                                id="toggleButton">+ Add More Technician</button>

                                            <div id="moreTechCont">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text" class="form-control" id="name"
                                                        name="name" placeholder="Enter Name">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="role" class="form-label">Role</label>
                                                    <input type="text" class="form-control" id="role"
                                                        name="role" placeholder="Enter Role">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="emailTech" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="emailTech"
                                                        name="email" placeholder="Enter Email">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="phoneTech" class="form-label">Phone</label>
                                                    <input type="text" class="form-control" id="phoneTech"
                                                        name="phone" placeholder="Enter Phone">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="avatar" class="form-label">Avatar</label>
                                                    <input type="file" class="form-control" id="avatar"
                                                        name="avatar" placeholder="Enter Phone">
                                                </div>
                                            </div>


                                            <div class="d-flex gap-2 mt-4">
                                                <button type="button" class="btn btn-secondary cnclTech">Close</button>
                                                <button type="submit" class="btn btn-dark">Assign</button>
                                            </div>
                                        </form>
                                        <script>
                                            document.getElementById('toggleButton').addEventListener('click', function() {
                                                const container = document.getElementById('moreTechCont');
                                                if (container.style.display === 'none' || container.style.display === '') {
                                                    container.style.display = 'block'; // Show the container
                                                } else {
                                                    container.style.display = 'none'; // Hide the container
                                                }
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div id="tab3" class="tab-content">
                        Notes
                    </div>
                    <div id="tab4" class="tab-content">
                        WO Logs
                    </div>
                    <div id="tab5" class="tab-content">
                        Site History
                    </div>
                </div>

            </div>
        </div>
        <script>
            document.querySelectorAll('.taskSelect').forEach((select) => {
                select.addEventListener('change', function() {
                    const form = select.closest('form'); // Get the parent form
                    const email = form.querySelector('.email');
                    const phone = form.querySelector('.phone');
                    const from = form.querySelector('.from');
                    const item = form.querySelector('.item');
                    const file = form.querySelector('.file');
                    const reason = form.querySelector('.reason');
                    const desc = form.querySelector('.desc');

                    // Hide all fields initially
                    [email, phone, from, item, file, reason, desc].forEach((el) => {
                        el.style.display = 'none';
                    });

                    // Show specific fields based on the selected value
                    switch (select.value) {
                        case 'call':
                            phone.style.display = 'block';
                            reason.style.display = 'block';
                            break;
                        case 'collect_signature':
                            from.style.display = 'block';
                            break;
                        case 'custom_task':
                            desc.style.display = 'block';
                            break;
                        case 'shipping_details':
                            item.style.display = 'block';
                            break;
                        case 'send_email':
                            email.style.display = 'block';
                            reason.style.display = 'block';
                            break;
                        case 'upload_file':
                        case 'upload_photo':
                            file.style.display = 'block';
                            desc.style.display = 'block';
                            break;
                        case 'closeout_note':
                            desc.style.display = 'block';
                            break;
                    }
                });
            });
        </script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
        </script>
        @push('custom_script')
            @include('user.script.wo_view')
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script>
                function handleFilePreview(inputId, containerId) {
                    const fileInput = document.getElementById(inputId);
                    const fileContainer = document.getElementById(containerId);

                    fileInput.addEventListener('change', function(event) {
                        const files = event.target.files;
                        fileContainer.innerHTML = ''; // Clear previous previews

                        // Loop through all selected files
                        for (let i = 0; i < files.length; i++) {
                            const file = files[i];
                            const filePreview = document.createElement('div');
                            filePreview.classList.add('file-preview');

                            // Display image for image files, PDF for PDF files, etc.
                            const fileType = file.type.split('/')[0];

                            // Preview image for image files
                            if (fileType === 'image') {
                                const img = document.createElement('img');
                                img.src = URL.createObjectURL(file);
                                img.classList.add('preview-img');
                                filePreview.appendChild(img);
                            } else if (fileType === 'application' && file.type.includes('pdf')) {
                                const pdfIcon = document.createElement('i');
                                pdfIcon.classList.add('fa', 'fa-file-pdf', 'preview-pdf');
                                filePreview.appendChild(pdfIcon);
                            } else {
                                const fileIcon = document.createElement('i');
                                fileIcon.classList.add('fa', 'fa-file', 'preview-file');
                                filePreview.appendChild(fileIcon);
                            }

                            // Display the file name under the preview
                            const fileName = document.createElement('p');
                            fileName.classList.add('file-name');
                            fileName.innerText = file.name;
                            filePreview.appendChild(fileName);

                            // Add a delete button for each file preview
                            const deleteBtn = document.createElement('button');
                            deleteBtn.classList.add('delete-btn');
                            deleteBtn.innerHTML = 'X';
                            deleteBtn.addEventListener('click', function() {
                                fileContainer.removeChild(filePreview);
                            });

                            filePreview.appendChild(deleteBtn);
                            fileContainer.appendChild(filePreview);
                        }
                    });
                }

                // Initialize file preview for each input
                handleFilePreview('preTechnicianDoc', 'preDeliverCont');
                handleFilePreview('postTechnicianDoc', 'postDeliverCont');
                handleFilePreview('miscTechnicianDoc', 'miscDeliverCont');
            </script>


            <script>
                const fileInput = document.getElementById('technicianDoc');
                const docContainer = document.getElementById('technicianDocCont');

                // Listen for file selection
                fileInput.addEventListener('change', function(event) {
                    const files = event.target.files; // Get the selected files

                    // Loop through the selected files
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        const filePreview = document.createElement('div');
                        filePreview.classList.add('file-preview');
                        const fileType = file.type.split('/')[0];

                        // Create a container for the image and filename
                        const fileContent = document.createElement('div');
                        fileContent.classList.add('file-content');

                        // Preview image for image files
                        if (fileType === 'image') {
                            const img = document.createElement('img');
                            img.src = URL.createObjectURL(file);
                            img.classList.add('preview-img');
                            fileContent.appendChild(img);
                        } else if (fileType === 'application' && file.type.includes('pdf')) {
                            // Preview for PDF files
                            const pdfIcon = document.createElement('i');
                            pdfIcon.classList.add('fa', 'fa-file-pdf', 'preview-pdf');
                            fileContent.appendChild(pdfIcon);
                        } else {
                            // Display a generic file icon for other file types
                            const fileIcon = document.createElement('i');
                            fileIcon.classList.add('fa', 'fa-file', 'preview-file');
                            fileContent.appendChild(fileIcon);
                        }

                        // Create file name label under the image
                        const fileName = document.createElement('p');
                        fileName.classList.add('file-name');
                        fileName.innerText = file.name; // Display the file name
                        fileContent.appendChild(fileName);

                        // Append file content (image + filename) to the file preview container
                        filePreview.appendChild(fileContent);

                        // Create the delete button (cross)
                        const deleteBtn = document.createElement('button');
                        deleteBtn.classList.add('delete-btn');
                        deleteBtn.innerHTML = '&times;'; // Cross sign
                        deleteBtn.addEventListener('click', () => {
                            filePreview.remove(); // Remove the file preview on click
                        });
                        filePreview.appendChild(deleteBtn); // Add delete button to the preview

                        // Append the file preview to the container
                        docContainer.appendChild(filePreview);
                    }
                });
            </script>


            <script>
                const tabButtons = document.querySelectorAll('.tab');
                const tabContents = document.querySelectorAll('.tab-content');

                tabButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        tabButtons.forEach((btn) => btn.classList.remove('tab-primary'));
                        tabContents.forEach((content) => content.classList.remove('tab-active'));

                        button.classList.add('tab-primary');
                        const targetTab = button.getAttribute('data-tab');
                        document.getElementById(targetTab).classList.add('tab-active');
                    });
                });
            </script>

            <script>
                $(document).ready(function() {
                    // Initialize all Select2 elements with class "select2"
                    $('.select2').select2({
                        placeholder: 'Select an option',
                        allowClear: true
                    });

                    // Select all button-pair containers
                    const buttonPairs = document.querySelectorAll('.action-cards');

                    buttonPairs.forEach((pair) => {
                        const editBtn = pair.querySelector('.edit-btn');
                        const saveBtn = pair.querySelector('.save-btn');
                        const cnclBtn = pair.querySelector('.cancel-btn');
                        const text = pair.querySelectorAll('.nrml-txt');
                        const inp = pair.querySelectorAll('.nrml-inp');
                        const selectTwo = pair.querySelectorAll('.select2');
                        const jodit = pair.querySelectorAll('.jodit-container');
                        // Toggle visibility and functionality on edit/cancel
                        editBtn.addEventListener('click', () => {
                            editBtn.style.display = 'none';
                            cnclBtn.style.display = 'block';
                            saveBtn.style.display = 'block';

                            text.forEach((txt) => {
                                txt.style.display = 'none';
                            });
                            inp.forEach((input) => {
                                input.style.display = 'block';
                            });

                            jodit.forEach((bigText) => {
                                bigText.style.display = 'block';
                            });

                        });

                        cnclBtn.addEventListener('click', () => {
                            cnclBtn.style.display = 'none';
                            saveBtn.style.display = 'none';
                            editBtn.style.display = 'block';

                            text.forEach((txt) => {
                                txt.style.display = 'block';
                            });
                            inp.forEach((input) => {
                                input.style.display = 'none';
                            });

                            jodit.forEach((bigText) => {
                                bigText.style.display = 'none';
                            });
                        });
                    });
                });
            </script>

            <script>
                function checkForElements() {
                    var elements = $('.jodit-status-bar-link');
                    if (elements.length > 0) {
                        elements.attr('href', '');
                        elements.attr('target', '');
                        elements.text('Powered By Techbook.');
                        elements.css('text-decoration', 'none');
                        elements.on('click', function(e) {
                            e.preventDefault();
                            console.log('Link click prevented');
                        });
                        console.log('Elements found and updated');
                    } else {
                        setTimeout(checkForElements, 500);
                    }
                }
                $(document).ready(checkForElements);
            </script>

            <script>
                const addContact = document.querySelector('.addContact');
                const cnclContact = document.querySelector('.cnclContact');
                const contactCont = document.querySelector('.contact-add');

                addContact.addEventListener('click', () => {
                    contactCont.style.display = 'block';
                    addContact.style.display = 'none';
                });
                cnclContact.addEventListener('click', () => {
                    contactCont.style.display = 'none';
                    addContact.style.display = 'block';
                });
            </script>
            <script>
                const addShipment = document.querySelector('.addShipment');
                const cnclShipment = document.querySelector('.cnclShipment');
                const shipmentCont = document.querySelector('.shipment-add');

                addShipment.addEventListener('click', () => {
                    shipmentCont.style.display = 'block';
                    addShipment.style.display = 'none';
                });
                cnclShipment.addEventListener('click', () => {
                    shipmentCont.style.display = 'none';
                    addShipment.style.display = 'block';
                });
            </script>
            <script>
                const addTechPart = document.querySelector('.addTechPart');
                const cnclTechPart = document.querySelector('.cnclTechPart');
                const techPartCont = document.querySelector('.tech-part-add');

                addTechPart.addEventListener('click', () => {
                    techPartCont.style.display = 'flex';
                    addTechPart.style.display = 'none';
                });
                cnclTechPart.addEventListener('click', () => {
                    techPartCont.style.display = 'none';
                    addTechPart.style.display = 'block';
                });
            </script>

            <script>
                const addSchedule = document.querySelector('.addSchedule');
                const cnclSchedule = document.querySelector('.cnclSchedule');
                const scheduleCont = document.querySelector('.schedule-add');

                addSchedule.addEventListener('click', () => {
                    scheduleCont.style.display = 'block';
                    addSchedule.style.display = 'none';
                });
                cnclSchedule.addEventListener('click', () => {
                    scheduleCont.style.display = 'none';
                    addSchedule.style.display = 'block';
                });
            </script>

            <script>
                const addTech = document.querySelector('.addTech');
                const cnclTech = document.querySelector('.cnclTech');
                const techCont = document.querySelector('.tech-add');

                addTech.addEventListener('click', () => {
                    techCont.style.display = 'block';
                    addTech.style.display = 'none';
                });
                cnclTech.addEventListener('click', () => {
                    techCont.style.display = 'none';
                    addTech.style.display = 'block';
                });
            </script>

            <script>
                document.querySelectorAll('.addCloseoutNote').forEach((addCloseNote, index) => {
                    const cnclCloseNote = document.querySelectorAll('.cnclCloseoutNote')[index];
                    const closeNoteCont = document.querySelectorAll('.closeout-note-add')[index];

                    addCloseNote.addEventListener('click', () => {
                        closeNoteCont.style.display = 'block';
                        addCloseNote.style.display = 'none';
                    });

                    cnclCloseNote.addEventListener('click', () => {
                        closeNoteCont.style.display = 'none';
                        addCloseNote.style.display = 'block';
                    });
                });
            </script>

            <script src="//cdnjs.cloudflare.com/ajax/libs/jodit/4.2.43/jodit.min.js"></script>
            <script>
                const editor = Jodit.make('#editor');
                const editorOne = Jodit.make('#editor1');
                const editorTwo = Jodit.make('#editor2');
                const editorThree = Jodit.make('#editor3');
            </script>
        @endpush
        <script>
            $(document).ready(function() {
                // Initialize Select2
                $('.select2').select2({
                    placeholder: 'Select an option', // Placeholder text
                    allowClear: true, // Adds a clear button
                });

            });
        </script>

        <script>
            // Get the input elements and total display
            const totalDisplay = document.getElementById('totalPay');
            const travelInput = document.getElementById('travelRate');



            function updateTotal() {
                // Strip '$' and non-numeric characters from the total display value
                const totalPrice = {
                    {
                        is_numeric(@$wo - > checkInOut - > sum('total_hours')) && is_numeric(@$wo - > technician - > rate[
                                'STD']) ?
                            (@$wo - > checkInOut - > sum('total_hours') * @$wo - > technician - > rate['STD']) + $wo - >
                            techProvidedParts - > sum('amount') :
                            0
                    }
                };
                const travel = parseFloat(travelInput.value) || 0;


                const total = (totalPrice + travel).toFixed(2);

                console.log(total);
                totalDisplay.textContent = `$${total}`;
            }

            // Attach event listener for real-time updates
            // travelInput.addEventListener('input', updateTotal);
        </script>

        <script>
            function downloadFile(event, filePath, fileName) {
                event.preventDefault(); // Prevent the default link behavior
                const a = document.createElement('a');
                a.href = filePath;
                a.download = fileName;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

        </script>

        <script>
            $(document).ready(function() {
                $(function() {
                    $('.selectpicker').selectpicker();
                });
            });
        </script>
    </body>
@endsection
