<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.11.14/jquery.timepicker.min.js"></script>
<script>
    var success = false;
    var clickCount = 0;
    var respondedTechnicians = [];
    var siteAddress = "";

    $(document).ready(function() {
        $('#findClosestTechBtn').click(function() {
            if (window.confirm("Are you sure want to find closest technician?")) {
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
                success: function(data) {
                    siteAddress = data;
                    closestTech(data);
                    $("#tech_distance_view").removeClass('d-none');
                },
                error: function(xhr, status, error) {
                    $("#tech_distance_view").addClass('d-none');
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
            $('#loader').removeClass('d-none');
            $.ajax({
                url: "{{ route('user.findTech.withDistance') }}",
                type: "POST",
                data: {
                    "destination": destination
                },
                success: function(data) {
                    $('#loader').addClass('d-none');
                    $('#removable-div').removeClass('d-none');
                    $("#btn-find-more").off('click').on('click', function() {
                        findMoreTech();
                    });
                    updateTechTable(data);
                },
                error: function(xhr, status, error) {
                    $('#loader').addClass('d-none');
                    $('#tech_distance_view').addClass('d-none');
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
            $('#loader').removeClass('d-none');
            clickCount++;
            let radiusElevator = clickCount * 50;
            $.ajax({
                url: "{{ route('user.findTech.withDistance') }}",
                type: "POST",
                data: {
                    "destination": siteAddress,
                    "radiusValue": radiusElevator,
                    "respondedTechnicians": respondedTechnicians,
                },
                success: function(data) {
                    $('#loader').addClass('d-none');
                    $('#removable-div').removeClass('d-none');
                    updateTechTable(data);
                },
                error: function(data) {
                    success = false;
                    $('#loader').addClass('d-none');
                    $('#tech_distance_view').addClass('d-none');
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

        function updateTechTable(data) {
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
                    '<td>' +
                    '<button id="contact-btn" class="btn btn-success btn-smaller" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Contact</button>' +
                    '</td>' +
                    '</tr>';
                respondedTechnicians.push(value.id);
            });
            $('#tbody').html(html);
        }
        console.log(siteAddress);
    });
</script>
<script>
    var customerId = "";
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var route = "";
        $('#workOrderCreateReqDate').datepicker({
            language: "en"
        });

        $('#completedByCreateForm').datepicker({
            language: "en",
            dateFormat: 'mm-dd-yy'
        });

        $('#dashboardReqDate').datepicker({
            language: "en",
            dateFormat: 'mm-dd-yy'
        });

        $('#check_in_edit').timepicker({
            timeFormat: 'H:i:s'
        });

        $('#check_out_edit').timepicker({
            timeFormat: 'H:i:s'
        });
        $('#dashboardCompletedBy').datepicker({
            language: "en",
            dateFormat: 'mm-dd-yy'
        });

        $('#dashboardOnsiteBy').datepicker({
            language: "en",
            dateFormat: 'mm-dd-yy'
        });
        $('#woCreateOnsiteBy').datepicker({
            language: "en",
            dateFormat: 'mm-dd-yy'
        });

        $('#workOrderSearchForm').removeClass('d-none');

        //all modal draggable code
        $('.modal-dialog').draggable({
            handle: ".modal-header"
        });

        //create sub ticket start
        $('#subTicketButton').on('click', function() {
            fetchSubTicket('onsite');
        });

        function fetchSubTicket(category) {
            if ($.fn.DataTable.isDataTable('#ticket_type')) {
                $('#ticket_type').DataTable().destroy();
            }

            $('#ticket_type').DataTable({
                processing: true,
                serverSide: true,
                ajax: 'work/order/' + category,
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'order_id'
                    },
                    {
                        render: function(data, type, row) {
                            return '<i data-id="' + row.id +
                                '" class="fas fa-edit ticketID"></i>';
                        }
                    }
                ],
                columnDefs: [{
                    targets: '_all',
                    "orderable": false
                }]
            });
            $('#subTicketModal').modal('show');
        }

        $(document).on('click', '.ticketID', function() {
            let id = $(this).data('id');
            let rowData = $(this).closest('tr');
            let table = $('#ticket_type').DataTable();
            let selectedRowData = table.row(rowData).data();
            let ticketId = selectedRowData.order_id;
            $('#postSubTicketModal').find('#subticket_ticketID').val(id);
            $('#postSubTicketModal').find('#subticket_ticket_id').val(ticketId);
            $('#postSubTicketModal').modal('show');
        });

        $('#workOrderSearchInput').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('user.get.work.order.search') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        "query": request.term,
                    },
                    success: function(data) {
                        response($.map(data.results, function(item) {

                            if (item.company_name == null) {
                                return {
                                    label: item.order_id,
                                    value: item.order_id,
                                    workOrderId: item.id,
                                }
                            } else {
                                return {
                                    label: item.order_id + "-" + item
                                        .company_name,
                                    value: item.order_id,
                                    workOrderId: item.id,
                                }
                            }
                        }));
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                var workOrderId = ui.item.workOrderId;
                $('#workOrderId').val(workOrderId);
                workOrderData(workOrderId);
            }
        });

        function ifNullWorkOrder(orderId) {
            $('#work-order-tab').addClass('active');
            $('#notes-tab, #site-history-tab, #parts, #ticket, #fieldTech, #check_out').removeClass('active');

            $.ajax({
                url: "{{ route('user.workOrder.check') }}",
                type: "POST",
                data: {
                    "id": orderId
                },
                success: function(data) {},
                error: function(xhr, status, error) {
                    if (xhr.status === 404) {
                        iziToast.warning({
                            message: xhr.responseJSON.message,
                            position: "center"
                        });
                    }
                }
            });
        }

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

        $(document).on("click", "#contact-btn", function() {
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
                    $('#removable-div').addClass('d-none');
                    $('#confirmation-div').removeClass('d-none');
                    siteHistory(data.id);
                }
            });
        });

        function customerParts(id) {
            $.ajax({
                url: "{{ route('user.customer.parts.details') }}",
                type: "GET",
                data: {
                    "work_order_id": id
                },
                success: function(data) {
                    $('#partsCusIdTd').text(data.customerinventory.customer.customer_id);
                    $('#parts-customer-id').val(data.customerinventory.customer.id);
                    $('#partsCusNameTd').text(data.customerinventory.customer.company_name);
                    $('#partsCusAvailPartsTd').text(data.inventoryItem);
                    $('#partsCusTotalPartsTd').text(data.inventoryItemTotal);
                }
            });
        }

        //site history script
        function siteHistory(id) {
            $.ajax({
                url: '/user/get/site/history/' + id,
                method: 'GET',
                success: function(data) {
                    $('#fTechTabDiv').removeClass('d-none');
                    $('#siteHistoryTabDiv').removeClass('d-none');
                    $('#assignedTechMessage').text(data.tech_message);
                    $('#siteHistoryMessage').text(data.site_message);
                    setSiteData(data.result);
                },
                error: function(xhr, status, error) {
                    if (xhr.status == 404) {
                        $('#fTechTabDiv').addClass('d-none');
                        $('#siteHistoryTabDiv').addClass('d-none');
                        $('#assignedTechMessage').text(xhr.responseJSON.tech_error);
                        $('#siteHistoryMessage').text(xhr.responseJSON.site_error);
                        $('#ftech_id').text("");
                        $('#ftech_email').text("");
                        $('#ftech_address').text("");
                        $('#ftech_country').text("");
                        $('#ftech_city').text("");
                        $('#ftech_state').text("");
                        $('#ftech_zipcode').text("");
                        $('#ftech_company').text("");
                    }
                }
            });
        }

        function setSiteData(data) {
            $('#siteHCompany').html('<strong><span style="font-size: 16px;">Site Company :</span></strong> ' + (
                data.company_name || ''));
            $('#siteHLocation').html('<strong><span style="font-size: 16px;">Site Location :</span></strong> ' +
                (data.location || ''));
            $('#siteHAddress').html('<strong><span style="font-size: 16px;">Site Address :</span></strong> ' + (
                data.address_1 || ''));
            $('#siteHZipcode').html('<strong><span style="font-size: 16px;">Site Zipcode :</span></strong> ' + (
                data.zipcode || ''));
            $('#siteHCity').html('<strong><span style="font-size: 16px;">Site City :</span></strong> ' + (data
                .city || ''));
            $('#siteHState').html('<strong><span style="font-size: 16px;">Site State :</span></strong> ' + (data
                .state || ''));
            $('#siteHtech').html(
                '<strong><span style="font-size: 16px;">Technician Required :</span></strong> ' + (data
                    .num_tech_required || ''));
            $('#siteHname').html('<strong><span style="font-size: 16px;">Site Contact Name :</span></strong> ' +
                (data.site_contact_name || ''));
            $('#siteHphone').html(
                '<strong><span style="font-size: 16px;">Site Contact Phone :</span></strong> ' + (data
                    .site_contact_phone || ''));
            $('#siteHwork').html('<strong><span style="font-size: 16px;">Total Work Order :</span></strong> ' +
                (data.wT || ''));
            $('#siteHwcomplete').html(
                '<strong><span style="font-size: 16px;">Total Work Order Complete :</span></strong> ' + (
                    data.wC || ''));
            $('#r_tools').html('<strong><span style="font-size: 16px;">' + (data.r_tools || '') +
                '</span></strong>');
            $('#ftech_company').html('<strong><span style="font-size: 16px;">Company Name :</span></strong> ' +
                (data.fcompany_name || ''));
            $('#Check_in_ftech_company').val((data.fcompany_name || ''));
            $('#Header_time_zone').html('<strong><span style="font-size: 16px;">Time Zone :</span></strong> ' +
                (data.time_zone || ''));
            $('#time_zone').val((data.time_zone || ''));
            $('#ftech_id').html(
                '<strong><span style="font-size: 16px;">Feild Technician ID :</span></strong> ' + (data
                    .technician_id || ''));
            $('#ftech_email').html('<strong><span style="font-size: 16px;">Email :</span></strong> ' + (data
                .ftech_email || ''));
            $('#ftech_address').html('<strong><span style="font-size: 16px;">Address :</span></strong> ' + (data
                .ftech_address || ''));
            $('#ftech_country').html('<strong><span style="font-size: 16px;">Country :</span></strong> ' + (data
                .ftech_country || ''));
            $('#ftech_city').html('<strong><span style="font-size: 16px;">City :</span></strong> ' + (data
                .ftech_city || ''));
            $('#ftech_state').html('<strong><span style="font-size: 16px;">State :</span></strong> ' + (data
                .ftech_state || ''));
            $('#ftech_zipcode').html('<strong><span style="font-size: 16px;">Zipcode :</span></strong> ' + (data
                .ftech_zipcode || ''));
            $('#w_id').val(data.w_id);
            $('#check_in_w_id').val(data.w_id);
        }
        //end site history script

        function checkInOutTable(id) {
            if ($.fn.DataTable.isDataTable('#checkInOutTable')) {
                $('#checkInOutTable').DataTable().destroy();
            }

            $('#checkInOutTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('user.table.checkInOut', ['id' => ':id']) }}".replace(':id', id),
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'company_name'
                    },
                    {
                        data: 'tech_name'
                    },
                    {
                        data: 'check_in'
                    },
                    {
                        data: 'check_out'
                    },
                    {
                        data: 'total_hours'
                    },
                    {
                        data: 'time_zone'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<button class="btn btn-success btn-sm checkout-btn m-1">Check-out</button>' +
                                '<button class="btn btn-primary btn-sm edit-btn m-1">Edit</button>';
                        }
                    }
                ],
                columnDefs: [{
                    targets: '_all',
                    orderable: false
                }]
            });
        }

        function subTicketTable(id) {
            if ($.fn.DataTable.isDataTable('#sub_ticket_table')) {
                $('#sub_ticket_table').DataTable().destroy();
            }

            $('#sub_ticket_table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('user.table.sub.ticket', ['id' => ':id']) }}".replace(':id', id),
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tech_support_note'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return data.user_data.firstname + ' ' + data.user_data.lastname;
                        }
                    },
                    {
                        data: 'formatted_created_at'
                    },
                    {
                        data: 'formatted_updated_at'
                    },
                    {
                        render: function(data, type, row) {
                            return '<i data-id="" class="fas fa-edit"></i>';
                        }
                    }
                ],
                columnDefs: [{
                    targets: '_all',
                    "orderable": false
                }]
            });
        }

        function closeoutNotes(id) {
            if ($.fn.DataTable.isDataTable('#closeout-notes-table')) {
                $('#closeout-notes-table').DataTable().destroy();
            }

            $('#closeout-notes-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('user.workOrder.closeoutNotes', ['id' => ':id']) }}".replace(':id',
                    id),
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'close_out_notes'
                    },

                    {
                        data: null,
                        render: function(data, type, row) {
                            return data.user_data.firstname + ' ' + data.user_data.lastname;
                        }
                    },
                    {
                        data: 'formatted_created_at'
                    },
                    {
                        data: 'formatted_updated_at'
                    },

                    {
                        render: function(data, type, row) {
                            return '<i data-id="" class="fas fa-edit"></i>';
                        }
                    }
                ],
                columnDefs: [{
                    targets: '_all',
                    "orderable": false
                }]
            });
        }
        //checking/out general notes method
        function generalNotes(id) {
            if ($.fn.DataTable.isDataTable('#general-notes-table')) {
                $('#general-notes-table').DataTable().destroy();
            }

            $('#general-notes-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('user.workOrder.generalNotes', ['id' => ':id']) }}".replace(':id', id),
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'general_notes'
                    },

                    {
                        data: null,
                        render: function(data, type, row) {
                            return data.user_data.firstname + ' ' + data.user_data.lastname;
                        }
                    },
                    {
                        data: 'formatted_created_at'
                    },
                    {
                        data: 'formatted_updated_at'
                    },

                    {
                        render: function(data, type, row) {
                            return '<i data-id="" class="fas fa-edit"></i>';
                        }
                    }
                ],
                columnDefs: [{
                    targets: '_all',
                    "orderable": false
                }]
            });
        }

        function dispatchNotes(id) {
            if ($.fn.DataTable.isDataTable('#dispatch-notes-table')) {
                $('#dispatch-notes-table').DataTable().destroy();
            }

            $('#dispatch-notes-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('user.workOrder.dispatchNotes', ['id' => ':id']) }}".replace(':id',
                    id),
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'dispatch_notes'
                    },

                    {
                        data: null,
                        render: function(data, type, row) {
                            return data.user_data.firstname + ' ' + data.user_data.lastname;
                        }
                    },
                    {
                        data: 'formatted_created_at'
                    },
                    {
                        data: 'formatted_updated_at'
                    },

                    {
                        render: function(data, type, row) {
                            return '<i data-id="" class="fas fa-edit"></i>';
                        }
                    }
                ],
                columnDefs: [{
                    targets: '_all',
                    "orderable": false
                }]
            });
        }

        function billingNotes(id) {
            if ($.fn.DataTable.isDataTable('#billing-notes-table')) {
                $('#billing-notes-table').DataTable().destroy();
            }

            $('#billing-notes-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('user.workOrder.billingNotes', ['id' => ':id']) }}".replace(':id', id),
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'billing_notes'
                    },

                    {
                        data: null,
                        render: function(data, type, row) {
                            return data.user_data.firstname + ' ' + data.user_data.lastname;
                        }
                    },
                    {
                        data: 'formatted_created_at'
                    },
                    {
                        data: 'formatted_updated_at'
                    },

                    {
                        render: function(data, type, row) {
                            return '<i data-id="" class="fas fa-edit"></i>';
                        }
                    }
                ],
                columnDefs: [{
                    targets: '_all',
                    "orderable": false
                }]
            });
        }

        function techSupportNotes(id) {
            if ($.fn.DataTable.isDataTable('#techSupport-notes-table')) {
                $('#techSupport-notes-table').DataTable().destroy();
            }

            $('#techSupport-notes-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('user.workOrder.techSupportNotes', ['id' => ':id']) }}".replace(':id',
                    id),
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tech_support_notes'
                    },

                    {
                        data: null,
                        render: function(data, type, row) {
                            return data.user_data.firstname + ' ' + data.user_data.lastname;
                        }
                    },
                    {
                        data: 'formatted_created_at'
                    },
                    {
                        data: 'formatted_updated_at'
                    },

                    {
                        render: function(data, type, row) {
                            return '<i data-id="" class="fas fa-edit"></i>';
                        }
                    }
                ],
                columnDefs: [{
                    targets: '_all',
                    "orderable": false
                }]
            });
        }

        //get work order  data from this function
        function workOrderData(id) {
            $.ajax({
                url: "{{ route('user.get.work.order') }}",
                type: "GET",
                data: {
                    "id": id
                },
                success: function(data) {
                    setField(data);
                }
            });
        }
        //function for populate all the dashboard field
        function setField(data) {
            const stage = data.result.stage;
            const STAGE_NEW = 1;
            const STAGE_NEED_DISPATCH = 2;
            const STAGE_DISPATCH = 3;
            const STAGE_CLOSED = 4;
            const STAGE_BILLING = 5;
            const values = {
                dashboardScopeOfWork: data.result.scope_work,
                dashboardDeliverables: data.result.deliverables,
                dashboardToolsRequired: data.result.tools_required,
                dashboardDispatchIns: data.result.instruction,
            };
            // Set content in Jodit editors
            window.editorsForm2.forEach(function(item) {
                const editorId = item.id;
                console.log("ids" + item.id);
                const editorInstance = item.editor;
                if (values[editorId] !== undefined) {
                    editorInstance.value = values[editorId];
                }
            });
           // Set the href dynamically
            let woViewUrl = '{{ route("work.order.pdf.user.view", ":wo_id") }}'.replace(':wo_id', data.result.wo_id);
            $('#woViewButton').data('href', woViewUrl);  // Store the URL in the button's data attribute

            // Add a click event to navigate to the URL
            $('#woViewButton').on('click', function() {
                var url = $(this).data('href');
                var width = 1000;
                var height = 600;
                var left = (screen.width / 2) - (width / 2);
                var top = (screen.height / 2) - (height / 2);

                window.open(
                    url, 
                    'popupWindow', 
                    'width=' + width + ', height=' + height + ', top=' + top + ', left=' + left + ', toolbar=no, menubar=no, scrollbars=yes, resizable=yes'
                );
            });

            $('#dashboardCustomerAddress').val(data.result.customer_address);
            $('#dashboardCustomerCity').val(data.result.customer_city);
            $('#dashboardCustomerState').val(data.result.customer_state);
            $('#dashboardCustomerZipcode').val(data.result.customer_zipcode);
            $('#dashboardCustomerPhone').val(data.result.customer_phone);
            $('#dashboardCustomerId').val(data.result.customer_id);
            $('#dashboardSiteId').val(data.result.customer_site_id);
            $('#dashboardSiteAddress').val(data.result.customer_site_address);
            $('#dashboardSiteCity').val(data.result.customer_site_city);
            $('#dashboardSiteZipcode').val(data.result.customer_site_zipcode);
            $('#dashboardSiteState').val(data.result.customer_site_state);
            $('#dashboardSiteContact').val(data.result.site_contact);
            $('#dashboardSiteContactPhone').val(data.result.site_contact_phone);
            $('#dashboardSiteHoursOp').val(data.result.site_hours_operation);
            $('#dashboardOnsiteBy').val(data.result.onsite_by);
            $('#dashboardscheduledTime').val(data.result.scheduled_time);
            $('#dashboardNumOfTech').val(data.result.number_of_tech);
            $('#dashboardReqDate').val(data.result.requested_date);
            $('#dashboardReqBy').val(data.result.requested_by);
            $('#dashboardCompletedBy').val(data.result.completed_by);
            $('#dashboardpO').val(data.result.p_o);
            $('#dashboardEmployeeId').val(data.result.project_manager);
            $('#dashboardEmployeeIdInput').val(data.result.employee_id);
            $('#dashboardSp').val(data.result.sales_person);
            $('#dashboardCustomerIdInput').val(data.result.customerId);
            $('#dashboardSiteIdInput').val(data.result.siteId);
            $('#dashboardEmailPhoneSelect option').each(function() {
                if ($(this).val() === data.result.request_type) {
                    $(this).prop('selected', true);
                    return false;
                }
            });
        // Mapping of allowed status options for each stage
        const stageStatusMapping = {
            1: ["1"],                  // New: Pending
            2: ["2"],                  // Needs Dispatch: Contacted
            3: ["3", "4", "5", "6", "7", "8", "9"], // Dispatched: Multiple statuses
            4: ["10", "11"],           // Closed: Checked Out, Needs Approval
            5: ["12", "13", "14", "15"] // Billing: Approved, Invoiced, Past Due, Paid
        };

        // If you want to set initial values, use them here
        const initialStage = data.result.stage;
        const initialStatus = data.result.status;

        // Set initial values for stage and clear initial status
        $('#dashboardWorkOrderStage').val(initialStage);
        $('#dashboardWorkOrderStatus').val(''); // Clear any selection in status

        // Function to update status options based on the selected stage
        function updateStatusOptions(selectedStage) {
            const allowedStatuses = stageStatusMapping[selectedStage] || [];

            console.log('Selected Stage:', selectedStage);
            console.log('Allowed Statuses:', allowedStatuses);

            // Reset all status options to visible
            $('#dashboardWorkOrderStatus option').prop('hidden', false);

            // Hide options that are not in the allowed list
            $('#dashboardWorkOrderStatus option').each(function() {
                if (!allowedStatuses.includes($(this).val())) {
                    $(this).prop('hidden', true);
                }
            });

            // Set the status dropdown to the initial status or the first allowed option
            if (allowedStatuses.includes(initialStatus)) {
                $('#dashboardWorkOrderStatus').val(initialStatus);
            } else {
                $('#dashboardWorkOrderStatus').val(allowedStatuses[0] || '');
            }
        }

        // Run the update on initial load
        updateStatusOptions(initialStage);

        // Listen for changes on the stage dropdown and update the status options
        $('#dashboardWorkOrderStage').on('change', function() {
            const selectedStage = $(this).val();
            updateStatusOptions(selectedStage);
        });

        // Select the status option based on the initial data
        $('#dashboardWorkOrderStatus option').each(function() {
            if ($(this).val() == initialStatus) {
                $(this).prop('selected', true);
                return false; // Exit the loop once the matching option is found
            }
        });

            $('#dashboardPrioritySelect option').each(function() {
                if ($(this).val() == data.result.priority) {
                    $(this).prop('selected', true);
                    return false;
                }
            });
            switch (stage) {
            case STAGE_NEW:
                $('#newTab').addClass('active');
                $('#needDispatchTab').removeClass('active');
                $('#dispatchTab').removeClass('active');
                $('#completeTab').removeClass('active');
                $('#billingTab').removeClass('active');
                break;
            case STAGE_NEED_DISPATCH:
                $('#needDispatchTab').addClass('active');
                $('#newTab').addClass('active');
                $('#dispatchTab').removeClass('active');
                $('#completeTab').removeClass('active');
                $('#billingTab').removeClass('active');
                break;
            case STAGE_DISPATCH:
                $('#dispatchTab').addClass('active');
                $('#newTab').addClass('active');
                $('#needDispatchTab').addClass('active');
                $('#completeTab').removeClass('active');
                $('#billingTab').removeClass('active');
                break;
            case STAGE_CLOSED:
                $('#completeTab').addClass('active');
                $('#newTab').addClass('active');
                $('#needDispatchTab').addClass('active');
                $('#dispatchTab').addClass('active');
                $('#billingTab').removeClass('active');
                break;
            case STAGE_BILLING:
                $('#billingTab').addClass('active');
                $('#newTab').addClass('active');
                $('#needDispatchTab').addClass('active');
                $('#dispatchTab').addClass('active');
                $('#completeTab').addClass('active');
                break;
            default:
                console.warn("Unknown stage:", stage);
                break;
            }
        }

        $(document).on('click', '#notes-tab', function() {
            const orderId = $('#workOrderId').val();
            if (orderId == "") {
                ifNullWorkOrder(orderId);
                $('#notes-container').addClass('d-none');
            } else {
                refresher(orderId);
                $('#workOrderSearchForm').addClass('d-none');
                $('#site_history_view').addClass('d-none');
                $('#notes-container').removeClass('d-none');
                $("#parts_view").addClass('d-none');
                $("#fieldTech_view").addClass('d-none');
                $("#ticket_view").addClass('d-none');
                $("#check_out_view").addClass('d-none');
                $("#tech_distance_view").addClass('d-none');
            }
        });

        $(document).on('click', '#work-order-tab', function() {
            $('#workOrderSearchForm').removeClass('d-none');
            $('#notes-container').addClass('d-none');
            $('#site_history_view').addClass('d-none');
            $("#parts_view").addClass('d-none');
            $("#fieldTech_view").addClass('d-none');
            $("#ticket_view").addClass('d-none');
            $("#check_out_view").addClass('d-none');
            $("#tech_distance_view").addClass('d-none');
        });

        $('#site-history-tab').click(function() {
            const orderId = $('#workOrderId').val();
            if (orderId == "") {
                ifNullWorkOrder(orderId);
                $('#site_history_view').addClass('d-none');
            } else {
                siteHistory(orderId);
                $('#workOrderSearchForm').addClass('d-none');
                $('#notes-container').addClass('d-none');
                $('#site_history_view').removeClass('d-none');
                $("#parts_view").addClass('d-none');
                $("#fieldTech_view").addClass('d-none');
                $("#ticket_view").addClass('d-none');
                $("#check_out_view").addClass('d-none');
                $("#tech_distance_view").addClass('d-none');
            }
        });

        $("#parts").click(function() {
            const orderId = $('#workOrderId').val();
            if (orderId == "") {
                ifNullWorkOrder(orderId);
                $("#parts_view").addClass('d-none');
            } else {
                customerParts(orderId);
                siteHistory(orderId);
                $('#workOrderSearchForm').addClass('d-none');
                $('#notes-container').addClass('d-none');
                $('#site_history_view').addClass('d-none');
                $("#parts_view").removeClass('d-none');
                $("#fieldTech_view").addClass('d-none');
                $("#ticket_view").addClass('d-none');
                $("#check_out_view").addClass('d-none');
                $("#tech_distance_view").addClass('d-none');
            }
        });

        $("#fieldTech").click(function() {
            const orderId = $('#workOrderId').val();
            if (orderId == "") {
                ifNullWorkOrder(orderId);
                $("#fieldTech_view").addClass('d-none');
            } else {
                siteHistory(orderId);
                $("#fieldTech_view").removeClass('d-none');
                $('#workOrderSearchForm').addClass('d-none');
                $('#notes-container').addClass('d-none');
                $('#site_history_view').addClass('d-none');
                $("#parts_view").addClass('d-none');
                $("#ticket_view").addClass('d-none');
                $("#check_out_view").addClass('d-none');
                $("#tech_distance_view").addClass('d-none');
            }
        });

        $("#ticket").click(function() {
            const orderId = $('#workOrderId').val();
            $('#w_id').val(orderId);
            if (orderId == "") {
                ifNullWorkOrder(orderId);
                $("#ticket_view").addClass('d-none');
            } else {
                subTicketTable(orderId);
                $('#workOrderSearchForm').addClass('d-none');
                $('#notes-container').addClass('d-none');
                $('#site_history_view').addClass('d-none');
                $("#parts_view").addClass('d-none');
                $("#fieldTech_view").addClass('d-none');
                $("#ticket_view").removeClass('d-none');
                $("#check_out_view").addClass('d-none');
                $("#tech_distance_view").addClass('d-none');
            }
        });

        $("#check_out").click(function() {
            const orderId = $('#workOrderId').val();
            getTechCompany(orderId);
            if (orderId == "") {
                ifNullWorkOrder(orderId);
            } else {
                checkInOutTable(orderId);
                $("#check_out_view").removeClass('d-none');
                $('#workOrderSearchForm').addClass('d-none');
                $('#notes-container').addClass('d-none');
                $('#site_history_view').addClass('d-none');
                $("#parts_view").addClass('d-none');
                $("#fieldTech_view").addClass('d-none');
                $("#ticket_view").addClass('d-none');
                $("#tech_distance_view").addClass('d-none');
            }
        });

        function getTechCompany(id) {
            $.ajax({
                url: "{{ route('user.order.site.history', ['id' => ':id']) }}".replace(':id', id),
                type: "GET",
                success: function(response) {
                    $('#Check_in_ftech_company').val(response.result.fcompany_name);
                    $('#time_zone').val(response.result.time_zone);
                    $('#check_in_w_id').val(response.result.w_id);
                }
            });
        }
        //search work Order start
        $("#Wsearch").click(function() {
            $("#defualtWorkOrder").removeClass('d-none');
            $("#workOrderCreateForm").addClass('d-none');
            $("#allRecord").addClass('d-none');
            $('#notes-container').addClass('d-none');
            $("#workOrderSearchForm").removeClass('d-none');
            $('#site_history_view').addClass('d-none');
            $("#parts_view").addClass('d-none');
            $("#ticket_view").addClass('d-none');
        });
        //search work order end 

        //create work order with type

        let currentRoute = null; // To store the current route
        let currentAction = null; // To store the current action

        // Attach click event to all buttons with the class `actionButton`
        $('.actionButton').click(function () {
            // Get the action type from the button's data attribute
            currentAction = $(this).data('action');

            // Dynamically set the route based on the action type
            switch (currentAction) {
                case 'service':
                    currentRoute = '{{ route('user.work.order.service') }}';
                    break;
                case 'install':
                    currentRoute = '{{ route('user.work.order.install') }}';
                    break;
                case 'project':
                    currentRoute = '{{ route('user.work.order.project') }}';
                    break;
                default:
                    iziToast.error({
                        message: "Invalid action type selected.",
                        position: "center",
                    });
                    return; // Exit if no valid action is found
            }

            // Show the work order form
            toggleViews();
        });

        // Attach a single click event to the submit button
        $('#orderSubmitButtonN').click(function () {
            if (currentRoute && currentAction) {
                // Call the function to create the work order
                createWorkOrder(currentRoute, currentAction);
            } else {
                iziToast.error({
                    message: "Please select an action before submitting.",
                    position: "center",
                });
            }
        });

        // Function to handle work order creation
        function createWorkOrder(route, action) {
            // Collect form data
            const formData = new FormData(document.getElementById('WOFORM'));

            // Append additional data
            formData.append('action', action);

            // Perform AJAX request to create a work order
            $.ajax({
                url: route,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    iziToast.success({
                        message: response.message || "Work order created successfully!",
                        position: "center",
                    });
                    if (response.id) {
                        window.location.href = `{{ route('user.work.order.view.layout', ':id') }}`.replace(':id', response.id);
                    }
                },
                error: function (xhr, status, error) {
                    iziToast.error({
                        message: "Failed to create work order.",
                        position: "center",
                    });
                    console.error("Error:", error);
                },
            });
        }


        // Function to toggle views during work order creation
        function toggleViews() {
            let viewsToHide = [
                "#fieldTech_view",
                "#allRecord",
                "#site_history_view",
                "#tech_distance_view",
                "#defualtWorkOrder",
                "#notes-container",
                "#check_out_view",
            ];

            viewsToHide.forEach(function (view) {
                $(view).addClass('d-none'); // Add the hidden class
            });

            $("#workOrderCreateForm").removeClass("d-none"); // Show the form
        }

        //fill data when new service,project,install is created
        function fieldPopulator(id) {
            $.ajax({
                url: "{{ route('user.order.data') }}",
                type: "GET",
                data: {
                    "id": id,
                },
                success: function(response) {
                    // customerAutoComplete();
                    // siteAutoComplete();
                    $('#workOrderCreateInput').val(response.order_id);
                    $('#workOrderCreateReqDate').val(response.open_date);
                    $('#workOrderCreateStatus option').each(function() {
                        if ($(this).val() == response.status) {
                            $(this).prop('selected', true);
                            return false;
                        }
                    });
                }
            });
        }

        $('#dashboardEmployeeId').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('employee.search') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        "query": request.term,
                    },
                    success: function(data) {
                        response($.map(data.results, function(item) {
                            return {
                                label: item.name + "-" + item
                                    .email + "-" + item.mobile,
                                value: item.name,
                                emId: item.id,
                            }
                        }));
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                var selectedEmId = ui.item.emId;
                $('#dashboardEmployeeIdInput').val(selectedEmId);
                createDynamicInput(selectedEmId, "#dashboardEmployeeId");
            }
        });

        $('#createFormEmployeeId').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('employee.search') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        "query": request.term,
                    },
                    success: function(data) {
                        response($.map(data.results, function(item) {
                            return {
                                label: item.name + "-" + item
                                    .email + "-" + item.mobile,
                                value: item.name,
                                emId: item.id,
                            }
                        }));
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                var selectedEmId = ui.item.emId;
                $('#createFormEmployeeIdInput').val(selectedEmId);
                createDynamicInput(selectedEmId, "#createFormEmployeeId");
            }
        });

        $('#dashboardCustomerId').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('user.customer.autocomplete.wosearch') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        "query": request.term,
                    },
                    success: function(data) {
                        response($.map(data.results, function(item) {
                            return {
                                label: item.customer_id + "-" + item
                                    .company_name + "-" + item.address
                                    .zip_code,
                                value: item.customer_id,
                                cusId: item.id,
                            }
                        }));
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                var selectedCusId = ui.item.cusId;
                $('#dashboardCustomerIdInput').val(selectedCusId);
                loadCustomer(selectedCusId, 1);
                createDynamicInput(selectedCusId, "wo-search-cusId", "#dashboardCustomerId");
            }
        });


        //site autocomplete for search workorder form
        $('#dashboardSiteId').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('user.site.autocomplete') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        "query": request.term,
                        "id": $('#wo-search-cusId').val(),
                    },
                    success: function(data) {
                        $('#dashboardSiteIdErrors').text("");
                        response($.map(data.results, function(item) {
                            return {
                                label: item.site_id + "-" + item.location +
                                    "-" + item.zipcode,
                                value: item.site_id,
                                siteID: item.id,
                            }
                        }));
                    },
                    error: function(xhr, status, errors) {
                        if (xhr.status == 422) {
                            $('#dashboardSiteIdErrors').text(xhr.responseJSON.errors);
                        }
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                var selectedSiteId = ui.item.siteID;
                loadSite(selectedSiteId, 1);
                $('#dashboardSiteIdInput').val(selectedSiteId);
            }
        });


        //customer autocomplete for work order create form
        $('#CustomerIdCreateForm').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('user.customer.autocomplete.wosearch') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        "query": request.term,
                    },
                    success: function(data) {
                        response($.map(data.results, function(item) {
                            return {
                                label: item.customer_id + "-" + item
                                    .company_name + "-" + item.address
                                    .zip_code,
                                value: item.customer_id,
                                cusId: item.id,
                            }
                        }));
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                var selectedCusId = ui.item.cusId;
                $('#customer_idCreateForm').val(selectedCusId);
                loadCustomer(selectedCusId, 2);
                createDynamicInput(selectedCusId, "createFormCusId", "#CustomerIdCreateForm");
            }
        });

        //site autocomplete for create workorder form
        $('#siteIdCreateForm').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('user.site.autocomplete') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        "query": request.term,
                        "id": $('#createFormCusId').val(),
                    },
                    success: function(data) {
                        $('#siteIdCreateFormErrors').text("");
                        response($.map(data.results, function(item) {
                            return {
                                label: item.site_id + "-" + item.location +
                                    "-" + item.zipcode,
                                value: item.site_id,
                                siteID: item.id,
                            }
                        }));
                    },
                    error: function(xhr, status, errors) {
                        if (xhr.status == 422) {
                            $('#siteIdCreateFormErrors').text(xhr.responseJSON.errors);
                        }
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                var selectedSiteId = ui.item.siteID;
                $('#site_idCreateForm').val(selectedSiteId);
                loadSite(selectedSiteId, 2);
            }
        });

        function loadSite(id, type) {
            $.ajax({
                url: "{{ route('user.get.site') }}",
                type: "GET",
                data: {
                    "id": id
                },
                success: function(data) {
                    if (type === 1) {
                        $('#dashboardSiteAddress').val(data.result.address);
                        $('#dashboardSiteCity').val(data.result.city);
                        $('#dashboardSiteState').val(data.result.state);
                        $('#dashboardSiteZipcode').val(data.result.zipcode);
                    } else {
                        $('#siteAddressCreateForm').val(data.result.address);
                        $('#siteCityCreateForm').val(data.result.city);
                        $('#siteStateCreateForm').val(data.result.state);
                        $('#siteZipcodeCreateForm').val(data.result.zipcode);
                    }
                }
            });
        }

        //load customer for both workorder search panel and create wo panel when user search for a customer
        function loadCustomer(id, type) {
            $.ajax({
                url: "{{ route('user.fetch.customer') }}",
                type: "GET",
                data: {
                    "id": id
                },
                success: function(data) {
                    if (type === 1) {
                        $('#dashboardCustomerPhone').val(data.phone);
                        $('#dashboardCustomerState').val(data.address.state);
                        $('#dashboardCustomerCity').val(data.address.city);
                        $('#dashboardCustomerAddress').val(data.address.address);
                        $('#dashboardCustomerZipcode').val(data.address.zip_code);
                        $('#dashboardPm').val(data.project_manager);
                        $('#dashboardSp').val(data.sales_person);
                    } else {
                        $('#customerPhoneCreateForm').val(data.phone);
                        $('#customerStateCreateForm').val(data.address.state);
                        $('#customerCityCreateForm').val(data.address.city);
                        $('#customerAddressCreateForm').val(data.address.address);
                        $('#customerZipcodeCreateForm').val(data.address.zip_code);
                        $('#customerPmCreateForm').val(data.project_manager);
                        $('#customerSpCreateForm').val(data.sales_person);
                    }
                }
            });
        }


        //note button script
        $("#general").click(function() {
            $("#generalNote").removeClass('d-none');
            $("#closeOut").addClass('d-none');
            $("#dNote").addClass('d-none');
            $("#tNote").addClass('d-none');
            $("#bNote").addClass('d-none');
        });
        $("#close").click(function() {
            $("#closeOut").removeClass('d-none');
            $("#dNote").addClass('d-none');
            $("#tNote").addClass('d-none');
            $("#bNote").addClass('d-none');
            $("#generalNote").addClass('d-none');
        });
        $("#dispatch").click(function() {
            $("#dNote").removeClass('d-none');
            $("#tNote").addClass('d-none');
            $("#bNote").addClass('d-none');
            $("#generalNote").addClass('d-none');
            $("#closeOut").addClass('d-none');
        });
        $("#tech").click(function() {
            $("#tNote").removeClass('d-none');
            $("#bNote").addClass('d-none');
            $("#generalNote").addClass('d-none');
            $("#closeOut").addClass('d-none');
            $("#dNote").addClass('d-none');
        });
        $("#bill").click(function() {
            $("#bNote").removeClass('d-none');
            $("#generalNote").addClass('d-none');
            $("#closeOut").addClass('d-none');
            $("#dNote").addClass('d-none');
            $("#tNote").addClass('d-none');
        });
        //end note button script

        const destroyEditors = (editorsArray) => {
            editorsArray.forEach(item => {
                item.editor.destruct();
            });
            editorsArray.length = 0;
        };
        const initializeEditors = (selector, editorsArray) => {
            $(selector).each(function(index, element) {
                const editorId = $(element).attr('id');
                if (!editorId) {
                    console.error(`Element at index ${index} does not have an ID attribute.`);
                    return;
                }
                const editor = new Jodit(element, {
                    toolbarAdaptive: false,
                    toolbarSticky: true,
                    height: 300,
                });

                editorsArray.push({
                    id: editorId,
                    editor: editor
                });
                console.log(`Initialized editor: ${editorId}`);
            });
        };

        const editorsForm1 = [];
        initializeEditors('#WOFORM .textEditor', editorsForm1);
        window.editorsForm1 = editorsForm1;

        const editorsForm2 = [];
        initializeEditors('#defaultWO .textEditor', editorsForm2);
        window.editorsForm2 = editorsForm2;

        //create work order update script start
        $('#WOFORM').on('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            if (window.editorsForm1) {
                window.editorsForm1.forEach(function(item) {
                    var editorName = item.name;
                    var editorInstance = item.editor;
                    formData.set(editorName, editorInstance.value);
                });
            }
            var button = $('#orderSubmitButton2');
            var icon = button.find('i');
            icon.removeClass('d-none');
            button.find('.button-text2').text('Please Wait !');

            $.ajax({
                url: "{{ route('user.work.order.update') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    icon.addClass('d-none');
                    button.find('.button-text2').text('Submit');
                    $('#createFormWoIdErrors').text("");
                    $('#siteIdCreateFormErrors').text("");
                    $('#createFormEmployeeIdInputErrors').text("");
                    $('#createFormCusIdErrors').text("");

                    refresher(response.id);
                    iziToast.success({
                        message: response.message,
                        position: "center"
                    });
                },
                error: function(xhr) {
                    icon.addClass('d-none');
                    button.find('.button-text2').text('Submit');
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        Object.values(xhr.responseJSON.errors).forEach((errorMessages) => {
                            errorMessages.forEach((message) => {
                                iziToast.error({
                                    message: message,
                                    position: 'center'
                                });
                            });
                        });

                        $('#createFormWoIdErrors').text(xhr.responseJSON.errors
                            .workOrderId);
                        $('#siteIdCreateFormErrors').text(xhr.responseJSON.errors.site_id);
                        $('#createFormCusIdErrors').text(xhr.responseJSON.errors
                            .customer_id);
                    }
                }
            });
        });

        $('#defaultWO').on('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            if (window.editorsForm2) {
                window.editorsForm2.forEach(function(item) {
                    var editorName = item.name;
                    var editorInstance = item.editor;
                    formData.set(editorName, editorInstance.value);
                });
            }

            var button = $('#orderSubmitButton');
            var icon = button.find('i');
            icon.removeClass('d-none');
            button.find('.button-text').text('Please Wait !');
            $.ajax({
                url: "{{ route('user.work.order.update') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    icon.addClass('d-none');
                    button.find('.button-text').text('Submit');

                    $("#generalNote").addClass('d-none');
                    $("#closeOut").addClass('d-none');
                    $("#dNote").addClass('d-none');
                    $("#tNote").addClass('d-none');
                    $("#bNote").addClass('d-none');

                    iziToast.success({
                        message: response.message,
                        position: "center"
                    });
                    $('textarea[name="general_notes"]').val("");
                    $('textarea[name="close_out_notes"]').val("");
                    $('textarea[name="dispatch_notes"]').val("");
                    $('textarea[name="billing_notes"]').val("");
                    $('textarea[name="tech_support_notes"]').val("");
                    $('input[type="file"]').val('');
                    $('#imagePreview').empty();
                },
                error: function(xhr) {
                    icon.addClass('d-none');
                    button.find('.button-text').text('Submit');
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        Object.values(xhr.responseJSON.errors).forEach((errorMessages) => {
                            errorMessages.forEach((message) => {
                                iziToast.error({
                                    message: message,
                                    position: 'center'
                                });
                            });
                        });

                        $('#dashboardOrderIdErrors').text(xhr.responseJSON.errors
                            .workOrderId);
                        $('#dashboardSiteIdErrors').text(xhr.responseJSON.errors.site_id);
                        $('#dashboardEmployeeIdInputErrors').text(xhr.responseJSON.errors.em_id);
                        $('#dashboardCustomerIdErrors').text(xhr.responseJSON.errors
                            .customer_id);
                    }
                },
            });
        });

        function refresher(id) {
            generalNotes(id);
            dispatchNotes(id);
            billingNotes(id);
            techSupportNotes(id);
            closeoutNotes(id);
        }

        //create sub ticket script
        $('#create_sub_ticket').on('submit', function(event) {
            event.preventDefault();

            var formData = $(this).serialize();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ route('user.sub.ticket') }}",
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    subTicketTable(response.id);
                    iziToast.success({
                        message: response.message,
                        position: "center"
                    });
                },
                error: function(xhr, status, error) {
                    let errorMessage = "";
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    iziToast.error({
                        title: 'Error',
                        message: errorMessage,
                        position: 'center'
                    });
                }
            });
        });
        //end sub ticket script

        //create checkin script
        $('#create_check_in').on('submit', function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ route('user.checkin') }}",
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    checkInOutTable(response.id);
                    iziToast.success({
                        message: response.message,
                        position: "center"
                    });
                    $('#check-in-out-tech_name-error').text("");
                },
                error: function(xhr, status, error) {
                    if (xhr.status == 400) {
                        iziToast.warning({
                            message: xhr.responseJSON.errors,
                            position: 'center'
                        });
                    }
                    if (xhr.status == 422) {
                        $('#check-in-out-tech_name-error').text(xhr.responseJSON.errors
                            .tech_name);
                    }
                    if (xhr.status == 404) {
                        iziToast.warning({
                            message: xhr.responseJSON.techNotFound,
                            position: 'center'
                        });
                    }
                }
            });
        });
        //end checkin script

        //checkout script
        $('#checkInOutTable').on('click', '.checkout-btn', function(event) {
            event.preventDefault();
            var rowData = $('#checkInOutTable').DataTable().row($(this).closest('tr')).data();
            $('#roundTripModal').modal('show');
            $("#c_check_out").data('id', rowData.id);
            $("#r_trip").data('id', rowData.id);
        });

        $("#c_check_out").click(function() {
            var id = $(this).data('id');
            initiateCheckOut(id, 'complete');
        });

        $("#r_trip").click(function() {
            var id = $(this).data('id');
            initiateCheckOut(id, 'round_trip');
        });

        function initiateCheckOut(id, type) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ route('user.checkout', ['id' => ':id']) }}".replace(':id', id),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    type: type
                },
                success: function(response) {
                    checkInOutTable(response.id);
                    iziToast.success({
                        message: response.message,
                        position: "center"
                    });
                },
                error: function(xhr, status, error) {
                    let errorMessage = "";
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    iziToast.error({
                        title: 'Error',
                        message: errorMessage,
                        position: 'center'
                    });
                }
            });
        }
        //end checkout script

        //check-in-out edit script
        $('#checkInOutTable').on('click', '.edit-btn', function(event) {
            event.preventDefault();
            var rowData = $('#checkInOutTable').DataTable().row($(this).closest('tr')).data();
            if (rowData) {
                openEditModal(rowData);
            } else {
                console.error('Row data not found.');
            }
        });

        function openEditModal(rowData) {
            $('#editModal').modal('show');
            $('#edit_w_id').val(rowData.work_order_id);
            $('#edit_id').val(rowData.id);
            $('#tech_name').val(rowData.tech_name);
            $('#date_edit').val(rowData.date);
            $('#check_in_edit').val(rowData.check_in);
            $('#check_out_edit').val(rowData.check_out);
            console.log('Editing row:', rowData);
        }
        $('#check_in_out_edit_form').on('submit', function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            var r_id = $("#edit_id").val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ route('user.checkout.edit', ['id' => ':id']) }}".replace(':id', r_id),
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    checkInOutTable($("#edit_w_id").val());
                    iziToast.success({
                        message: response.message,
                        position: "center"
                    });
                },
                error: function(xhr, status, error) {
                    let errorMessage = "";
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    iziToast.error({
                        title: 'Error',
                        message: errorMessage,
                        position: 'center'
                    });
                }
            });
        });

        $("#delete_check_in_out").click(function() {
            var r_id = $("#edit_id").val();
            $.ajax({
                url: "{{ route('user.checkout.delete', ['id' => ':id']) }}".replace(':id', r_id),
                type: 'GET',
                success: function(response) {
                    $('#editModal').modal('hide');
                    checkInOutTable($("#edit_w_id").val());
                    iziToast.success({
                        message: response.message,
                        position: "center"
                    });
                },
                error: function(xhr, status, error) {
                    let errorMessage = "";
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    iziToast.error({
                        title: 'Error',
                        message: errorMessage,
                        position: 'center'
                    });
                }
            });
        });
        //end check-in-out edit script
        $('#parts-search-parts').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('user.inventory.autocomplete') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        "query": request.term,
                        "customer_id": $('#parts-customer-id').val()
                    },
                    success: function(data) {
                        response($.map(data.results, function(item) {
                            return {
                                label: item.part_number,
                                value: item.part_number,
                                partID: item.id,
                            }
                        }));
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                var selectedPartId = ui.item.partID;
                inventoryItem(selectedPartId);
                getResponse(selectedPartId);
            }
        });

        function inventoryItem(id) {
            $.ajax({
                url: "{{ route('user.inventory.item') }}",
                type: "GET",
                data: {
                    "id": id
                },
                success: function(data) {
                    $('#parts-item-name').val(data.item_name);
                    $('#parts-quantity').val(data.quantity);
                    $('#parts-unit-price').val(data.unit_cost);
                }
            });
        }

        function getResponse(id) {
            $('#parts-quantity-need').on('keyup', function() {
                var typedNumber = $(this).val();

                $.ajax({
                    url: "{{ route('user.inventory.calculation') }}",
                    type: "GET",
                    data: {
                        "item_id": id,
                        "item_value": typedNumber
                    },
                    success: function(data) {
                        $('#parts-total-price').val(data);
                    }
                });
            });
        }

        // generate dynamic input element
        function createDynamicInput(value, inputId, placement) {
            let dynamicInput = $("<input>").attr({
                type: "hidden",
                value: value,
                id: inputId
            });
            dynamicInput.insertAfter(placement);
        }

        $('.filerPageOrderId').click(function() {
            $("#defualtWorkOrder").removeClass("d-none");
            $("#tab-stage").removeClass("d-none");
            let orderId = $(this).closest('tr').attr('data-id');
            let order_id = $(this).text();
            $('#workOrderSearchInput').val(order_id);
            $('#workOrderId').val(orderId);
            workOrderData(orderId);
            $('#allRecord').hide();
        });
        $('#filerPageOrderIdOut').click(function() {
            $("#defualtWorkOrder").addClass("d-none");
            $("#tab-stage").addClass("d-none");
            $("#notes-container").addClass("d-none");
            $("#site_history_view").addClass("d-none");
            $("#parts_view").addClass("d-none");
            $("#fieldTech_view").addClass("d-none");
            $("#ticket_view").addClass("d-none");
            $("#check_out_view").addClass("d-none");
            $('#allRecord').show();

        });
    });
</script>

<!-- dashboard script -->
<script>
    (function($) {
        $.fn.menumaker = function(options) {
            var cssmenu = $(this),
                settings = $.extend({
                    format: "dropdown",
                    sticky: false
                }, options);
            return this.each(function() {
                $(this).find(".button").on('click', function() {
                    $(this).toggleClass('menu-opened');
                    var mainmenu = $(this).next('ul');
                    if (mainmenu.hasClass('open')) {
                        mainmenu.slideToggle().removeClass('open');
                    } else {
                        mainmenu.slideToggle().addClass('open');
                        if (settings.format === "dropdown") {
                            mainmenu.find('ul').show();
                        }
                    }
                });
                cssmenu.find('li ul').parent().addClass('has-sub');
                multiTg = function() {
                    cssmenu.find(".has-sub").prepend('<span class="submenu-button"></span>');
                    cssmenu.find('.submenu-button').on('click', function() {
                        $(this).toggleClass('submenu-opened');
                        if ($(this).siblings('ul').hasClass('open')) {
                            $(this).siblings('ul').removeClass('open').slideToggle();
                        } else {
                            $(this).siblings('ul').addClass('open').slideToggle();
                        }
                    });
                };
                if (settings.format === 'multitoggle') multiTg();
                else cssmenu.addClass('dropdown');
                if (settings.sticky === true) cssmenu.css('position', 'fixed');
                resizeFix = function() {
                    var mediasize = 1000;
                    if ($(window).width() > mediasize) {
                        cssmenu.find('ul').show();
                    }
                    if ($(window).width() <= mediasize) {
                        cssmenu.find('ul').hide().removeClass('open');
                    }
                };
                resizeFix();
                return $(window).on('resize', resizeFix);
            });
        };
    })(jQuery);

    (function($) {
        $(document).ready(function() {
            $("#cssmenu").menumaker({
                format: "multitoggle"
            });
        });
    })(jQuery);
</script>