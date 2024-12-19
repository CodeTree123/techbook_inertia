@extends('admin.layoutsNew.app')
@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection

@section('content')
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path
                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
        </symbol>
        <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
            <path
                d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
        </symbol>
        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path
                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </symbol>
    </svg>
    <div class="content-wrapper" style="background-color: white;">
        <input type="hidden" id="techResData">
        @include('admin.includeNew.breadcrumb')
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-dark">Get Distance of Technician From The Project Site</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-4">
                                    <label><strong>
                                            <h6 class="text-dark"><strong>Provide your project site address below :</strong>
                                            </h6>
                                        </strong></label>
                                    <input type="text" id="siteAddress" class="form-control" name="site_address"
                                        placeholder="Enter project site address">
                                    <input id="latitude" type="hidden" name="latitude">
                                    <input id="longitude" type="hidden" name="longitude">
                                    <span style="color:red; font-size:15px" id="errors-container"></span>
                                </div>
                                <div class="form-group col-4">
                                    <input type="number" class="form-control"
                                        placeholder="How much tech you want to see in the result?" style="margin-top: 39px;"
                                        id="numberOfTech" name="numberOfTech">
                                    <span style="color:red; font-size:15px" id="errors-container2"></span>
                                </div>
                                <div class="form-group col-4">
                                    <button type="button" id="submit" class="btn btn-success"
                                        style="margin-top:39px; margin-left:10px;"><i
                                            class="fa fa-search-plus"></i>&nbsp;Start
                                        Finding</button>
                                </div>
                            </div>
                            <div class="d-none" id="loader">
                                <h6 class="text-dark"><strong>Please wait for the response from google</strong></h6>
                                <div class="spinner-grow text-danger" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div class="d-none" id="removable-div">
                                <p><b>Showing the results of radius : <span id="radiusValue"></span> mi</b></p>
                                <div class="table-responsive">
                                    <table class="table table-bordered text-dark table-hover">
                                        <thead class="text-nowrap">
                                            <tr>
                                                <th>#</th>
                                                <th>Technician ID</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Company Name</th>
                                                <th>Status</th>
                                                <th>Skill Sets</th>
                                                <th>Rate</th>
                                                <th>Travel Fee</th>
                                                <th>Preferred?</th>
                                                <th>Distance From Address</th>
                                                <th>Duration</th>
                                                <th>Is Within Radius ?</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody" class="text-nowrap"></tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    <button type="button" class="btn btn-primary my-2" id="btn-find-more">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var success = false;
        var clickCount = 0;
        var respondedTechnicians = [];

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '#submit', function() {
                let destination = $('#siteAddress').val();
                let lat = $('#latitude').val();
                let lon = $('#longitude').val();
                let numberOfTech = $('#numberOfTech').val();
                $('#loader').removeClass('d-none');
                $.ajax({
                    url: "{{ route('distance.get.response') }}",
                    type: "POST",
                    data: {
                        "destination": destination,
                        "latitude": lat,
                        "longitude": lon,
                        "respondedTechnicians": respondedTechnicians,
                        "numberOfTech": numberOfTech,
                    },
                    datatype: "JSON",
                    success: function(data) {
                        let html = "";
                        success = true;
                        $('#errors-container').empty();
                        $('#errors-container2').empty();
                        $('#removable-div').removeClass('d-none');
                        $('#loader').addClass('d-none');
                        $("#btn-find-more").off('click').on('click', function() {
                            findMoreTech();
                        });
                        updateTechTable(data);
                    },
                    error: function(data) {
                        success = false;
                        $('#loader').addClass('d-none');
                        if (data.status == 422) {
                            $('#errors-container').text(data.responseJSON.errors.destination);
                            $('#errors-container2').text(data.responseJSON.errors.numberOfTech);
                        }
                        if (data.status == 404) {
                            iziToast.warning({
                                message: data.responseJSON.errors,
                                position: "topRight"
                            });
                        }
                    }
                });
            });

            function findMoreTech() {
                clickCount++;
                let radiusElevator = clickCount * 50;
                let numberOfTech = $('#numberOfTech').val();
                $.ajax({
                    url: "{{ route('distance.get.response') }}",
                    type: "POST",
                    data: {
                        "latitude": $('#latitude').val(),
                        "longitude": $('#longitude').val(),
                        "destination": $('#siteAddress').val(),
                        "radiusValue": radiusElevator,
                        "respondedTechnicians": respondedTechnicians,
                        "numberOfTech": numberOfTech,
                    },
                    success: function(data) {
                        $('#errors-container').empty();
                        $('#errors-container2').empty();
                        $('#removable-div').removeClass('d-none');
                        $('#loader').addClass('d-none');
                        updateTechTable(data);
                    },
                    error: function(data) {
                        success = false;
                        $('#loader').addClass('d-none');
                        if (data.status == 422) {
                            $('#errors-container').text(data.responseJSON.errors.destination);
                            $('#errors-container2').text(data.responseJSON.errors.numberOfTech);
                        }
                        if (data.status == 404) {
                            iziToast.warning({
                                message: data.responseJSON.errors,
                                position: "topRight"
                            });
                        }
                    }
                });
            }

            //tech table populator
            function updateTechTable(data) {
                let html = "";
                $.each(data.technicians, function(key, value) {
                    let distanceValue = parseFloat(value.distance.split(' ')[0]);
                    let radiusValue = parseFloat(value.radius_value);
                    const isGreaterThanRadius = distanceValue > radiusValue;
                    let rowClass = distanceValue > radiusValue ? 'd-none' : '';
                    html += '<tr>' +
                        '<td class="text-center align-middle mt-auto">' + (key + 1) +
                        '</td>' +
                        '<td class="text-center align-middle mt-auto">' + value
                        .technician_id + '</td>' +
                        '<td class="text-center align-middle mt-auto">' + value
                        .email + '</td>' +
                        '<td class="text-center align-middle mt-auto">' + value
                        .phone + '</td>' +
                        '<td class="text-center align-middle mt-auto">' + value
                        .company_name + '</td>' +
                        '<td class="text-center align-middle mt-auto">' + value
                        .status + '</td>' +
                        '<td class="text-center align-middle mt-auto">' +
                        value
                        .skills + '</td>' +
                        '<td class="text-center align-middle mt-auto">' + value
                        .rate + '</td>' +
                        '<td class="text-center align-middle mt-auto">' + value
                        .travel_fee + '</td>' +
                        '<td class="text-center align-middle mt-auto">' + value
                        .preference + '</td>' +
                        '<td class="text-center align-middle mt-auto">' + value
                        .distance + '</td>' +
                        '<td class="text-center align-middle mt-auto">' + value
                        .duration + '</td>' +
                        '<td class="text-center align-middle mt-auto">' + value
                        .radius + '</td>' +
                        '</tr>';
                    $('#radiusValue').text(value.radius_value);
                    respondedTechnicians.push(value.id);
                });
                $('#tbody').html(html);
            }

            //address autocomplete
            $('#siteAddress').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('distance.geocode.autocomplete') }}",
                        method: "POST",
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            $("#errors-container").text("");
                            if (data) {
                                if (data.full_name && data.latitude !== undefined && data
                                    .longitude !== undefined) {
                                    var address = data.full_name;
                                    var latitude = data.latitude;
                                    var longitude = data.longitude;
                                    var label = address;
                                    var value = address;
                                    response([{
                                        label: label,
                                        value: value,
                                        lat: latitude,
                                        lng: longitude
                                    }]);
                                } else {
                                    $("#errors-container").text(
                                        "Incomplete data received from server.");
                                }
                            } else {
                                $("#errors-container").text(
                                    "No data received from server.");
                            }
                        },
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    $("#latitude").val(ui.item.lat);
                    $("#longitude").val(ui.item.lng);
                }
            });
        });
    </script>
@endsection
