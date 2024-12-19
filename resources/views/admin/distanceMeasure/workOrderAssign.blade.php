@extends('admin.layoutsNew.app')
@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endsection
@section('content')
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
    </symbol>
</svg>
<div class="content-wrapper" style="background-color: white;">
    <!-- Content Header (Page header) -->
    @include('admin.includeNew.breadcrumb')
    <!-- /.content-header -->
    <div class="container-fluid">
        @include('admin.distanceMeasure.modal')
        @include('admin.distanceMeasure.assignModal')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">Get Distance of Technician From The Project Site</h4>
                    </div>
                    <div class="card-body">
                        <input type="hidden" value="{{ $addressString }}" id="destination">
                        <div class="d-none" id="loader">
                            <h6 class="text-dark"><strong>Please wait for the responses from google</strong></h6>
                            <div class="spinner-grow text-danger" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="d-none" id="removable-div">
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
                                        <th>Distance From Address</th>
                                        <th>Duration</th>
                                        <th>Is Within Radius ?</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // destination means site address
        let destination = $('#destination').val();
        // let mode = $('#mode').val();
        $('#loader').removeClass('d-none');
        $.ajax({
            url: "{{ route('distance.get.response') }}",
            type: "POST",
            data: {
                "destination": destination,
                // "mode": mode
            },
            datatype: "JSON",
            success: function(data) {
                console.log(data);
                $('#errors-container').empty();
                $('#removable-div').removeClass('d-none');
                $('#loader').addClass('d-none');
                let html = "";
                $.each(data.technicians, function(key, value) {
                    html += '<tr>' +
                        '<td>' + '<div class="form-check form-switch text-center">' +
                        '<input class="form-check-input close-toggleButton" type="checkbox" id="assignButton">' +
                        '</div>' + '</td>' +
                        '<td hidden>' + value.id + '</td>' +
                        '<td>' + value.technician_id + '</td>' +
                        '<td class="text-nowrap">' + value.company_name + '</td>' +
                        '<td hidden>' + value.email + '</td>' +
                        '<td hidden>' + value.phone + '</td>' +
                        '<td>' + value.status + '</td>' +
                        '<td>' + value.skills + '</td>' +
                        '<td>' + value.rate + '</td>' +
                        '<td>' + value.travel_fee + '</td>' +
                        '<td>' + value.preference + '</td>' +
                        '<td>' + value.distance + '</td>' +
                        '<td>' + value.duration + '</td>' +
                        '<td>' + value.radius + '</td>' +
                        '<td>' + '<button id="contact-btn" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Contact</button>' + '</td>' +
                        '</tr>';
                });
                $('#tbody').html(html);
            },
            error: function(data) {
                if (data.status == 422) {
                    let destination_errors;
                    let mode_errors;
                    destination_errors = data.responseJSON.errors.destination;
                    $.each(destination_errors, function(key, value) {
                        $('#errors-container').text(value);
                    });
                    mode_errors = data.responseJSON.errors.mode;
                    $.each(mode_errors, function(key, value) {
                        $('#errors-container2').text(value);
                    });
                }
            }
        });

        $(document).on("click", "#contact-btn", function() {
            const tr = $(this).closest('tr');
            const email = tr.find('td:eq(4)').text();
            $("#email-input").val(email);
            const company = tr.find('td:eq(3)').text();
            const phone = tr.find('td:eq(5)').text();
            $('.company_name').text(company);
            $("#phone_number").text(phone);
            const phoneNumberLink = `<a href="tel:${phone}">${phone}</a>`;
            $("#phone_number").html(phoneNumberLink);
        });

        $(document).on("click", "#email-btn", function() {
            $('#email-card').removeClass('d-none');
            $("#phone-card").addClass('d-none');
        });

        $(document).on("click", "#phone-btn", function() {
            $("#phone-card").removeClass('d-none');
            $('#email-card').addClass('d-none');
        });

        $(document).on("change", "#assignButton", function() {
            const tr = $(this).closest('tr');
            const tech_id = tr.find('td:eq(1)').text();
            const technician_id = tr.find('td:eq(2)').text();
            const company = tr.find('td:eq(3)').text();
            const status = tr.find('td:eq(6)').text();
            var currentURL = window.location.href;
            var urlSegments = currentURL.split('/');
            var workOrderId = urlSegments[urlSegments.length - 1];

            if ($(this).is(":checked")) {
                $('#staticBackdrop2').modal('show');
                $('#company_name').text(company);
                $('#tech_id').text(technician_id);
                $('#status').text(status);
                $('#ftech_id').val(tech_id);
                $('#workOrderId').val(workOrderId);
            } else {
                $('#staticBackdrop2').modal('hide');
            }
        });

        $(document).on('click', '.btn-close', function() {
            if ($('.close-toggleButton').is(':checked')) {
                $('.close-toggleButton').prop('checked', false);
            }
        });
    });
</script>
@endsection