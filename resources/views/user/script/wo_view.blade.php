<script>
var success = false;
var clickCount = 0;
var respondedTechnicians = [];
var siteAddress = "";
var currentPage = 1; // Current page tracker
var previousResults = {}; // Store previous results

$(document).ready(function () {
    $('#findClosestTechBtn1').click(function () {
        if (window.confirm("Are you sure want to find the closest technician?")) {
            findWorkOrder();
        }
    });

    function findWorkOrder() {
        $.ajax({
            url: "{{ route('user.workOrder.get') }}",
            type: "POST",
            data: {
                "id": $('#workOrderId').val()
            },
            success: function (data) {
                siteAddress = data;
                closestTech(data);
                $("#tech_distance_view1").removeClass('d-none');
            },
            error: function (xhr, status, error) {
                $("#tech_distance_view1").addClass('d-none');
                if (xhr.status === 404) {
                    iziToast.error({
                        message: xhr.responseJSON.message,
                        position: "center"
                    });
                }
                if (xhr.status === 400) {
                    iziToast.error({
                        message: xhr.responseJSON.message,
                        position: "center"
                    });
                }
            }
        });
    }

    function closestTech(destination) {
        $('#loader1').removeClass('d-none');
        $.ajax({
            url: "{{ route('user.findTech.withDistance') }}",
            type: "POST",
            data: {
                "destination": destination,
                "page": currentPage // Pass current page to the server
            },
            success: function (data) {
                $('#loader1').addClass('d-none');
                $('#removable-div1').removeClass('d-none');
                $("#btn-find-more0").off('click').on('click', function () {
                    currentPage++;
                    findMoreTech();
                });
                $("#btn-previous0").off('click').on('click', function () {
                    if (currentPage > 1) {
                        currentPage--;
                        loadPreviousTech();
                    }
                });
                updateTechTable(data);
                previousResults[currentPage] = data; // Store results by page
            },
            error: function (xhr, status, error) {
                $('#loader1').addClass('d-none');
                $('#tech_distance_view1').addClass('d-none');
                if (xhr.status === 404) {
                    iziToast.warning({
                        message: xhr.responseJSON.errors,
                        position: "center"
                    });
                }
                if (xhr.status === 400) {
                    iziToast.error({
                        message: xhr.responseJSON.error,
                        position: "center"
                    });
                }
                if (xhr.status === 503) {
                    iziToast.warning({
                        message: xhr.responseJSON.geocodeError,
                        position: "center"
                    });
                }
            }
        });
    }

    function findMoreTech() {
        $('#loader1').removeClass('d-none');
        clickCount++;
        let radiusElevator = clickCount * 50;
        $.ajax({
            url: "{{ route('user.findTech.withDistance') }}",
            type: "POST",
            data: {
                "destination": siteAddress,
                "radiusValue": radiusElevator,
                "respondedTechnicians": respondedTechnicians,
                "page": currentPage // Increment page
            },
            success: function (data) {
                $('#loader1').addClass('d-none');
                $('#removable-div1').removeClass('d-none');
                updateTechTable(data);
                previousResults[currentPage] = data; // Save result for current page
            },
            error: function (xhr, status, error) {
                success = false;
                $('#loader1').addClass('d-none');
                $('#tech_distance_view1').addClass('d-none');
                if (xhr.status === 404) {
                    iziToast.warning({
                        message: xhr.responseJSON.errors,
                        position: "center"
                    });
                }
                if (xhr.status === 503) {
                    iziToast.warning({
                        message: xhr.responseJSON.geocodeError,
                        position: "center"
                    });
                }
            }
        });
    }

    function loadPreviousTech() {
        if (previousResults[currentPage]) {
            updateTechTable(previousResults[currentPage]);
        }
    }

    function updateTechTable(data) {
        let html = "";
        $.each(data.technicians, function (key, value) {
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
                '<td>' +
                '<button id="contact-btn1" class="btn btn-success btn-smaller" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Contact</button>' +
                '</td>' +
                '</tr>';
            respondedTechnicians.push(value.id);
        });
        $('#tbody').html(html);
    }
});
</script>

<script>
    $(document).on("change", "#assignButton", function() {
        const tr = $(this).closest('tr');
        const tech_id = tr.find('td:eq(1)').text();
        const technician_id = tr.find('td:eq(2)').text();
        const company = tr.find('td:eq(3)').text();
        const status = tr.find('td:eq(6)').text();
        var workOrderId = $('#workOrderId').val();

        if ($(this).is(":checked")) {
            $('#staticBackdrop2').modal('show');
            $('#assign_modal_company_name').text(company);
            $('#assign_modal_tech_id').text(technician_id);
            $('#assign_modal_status').text(status);
            $('#assign_modal_ftech_id').val(tech_id);
            $('#assign_modal_workOrderId').val(workOrderId);
        } else {
            $('#staticBackdrop2').modal('hide');
        }
    });
    $(document).on("click", "#contact-btn1", function() {
            const tr = $(this).closest('tr');
            const email = tr.find('td:eq(4)').text();
            $("#contact_modal_email_input").val(email);
            const company = tr.find('td:eq(3)').text();
            const phone = tr.find('td:eq(5)').text();
            $('.contact_modal_company').text(company);
            $("#contact_modal_phone_number").text(phone);
            const phoneNumberLink = `<a href="tel:${phone}">${phone}</a>`;
            $("#contact_modal_phone_number").html(phoneNumberLink);
        });
    $(document).on("click", "#email-btn", function() {
        $('#email-card').removeClass('d-none');
        $("#phone-card").addClass('d-none');
    });

    $(document).on("click", "#phone-btn", function() {
        $("#phone-card").removeClass('d-none');
        $('#email-card').addClass('d-none');
    });

    $(document).on('click', '.btn-close', function() {
        if ($('.close-toggleButton').is(':checked')) {
            $('.close-toggleButton').prop('checked', false);
        }
    });

    $('#send_mail_form').on('submit', function(e) {
        e.preventDefault();
        $('#email-sending-loader').removeClass('d-none');
        let formData = new FormData(this);
        $.ajax({
            url: "{{ route('user.sendmail.tech') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#email-sending-loader').addClass('d-none');
                iziToast.success({
                    message: data.message,
                    position: "center"
                });
            }
        });
    });
    $('#user_dispatch_form').on('submit', function(e) {
        e.preventDefault();
        let isSendingMail = $('#defaultCheck1').is(':checked') ? 1 : 0;
        $('#assignTechLoader').removeClass('d-none');
        let formData = new FormData(this);
        formData.append('isSendingMail', isSendingMail);

        $.ajax({
            url: "{{ route('user.dispatch.order') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#assignTechLoader').addClass("d-none");
                iziToast.success({
                    message: data.message,
                    position: "center"
                });
                $('#message').text('Technician Assigned!!');
                $('#removable-div1').addClass('d-none');
                $('#confirmation-div').removeClass('d-none');
                siteHistory(data.id);
            }
        });
    });
</script>