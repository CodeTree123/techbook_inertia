{{-- site modal --}}
<style>
    .ui-autocomplete {
        z-index: 9999;
    }
</style>

<div class="modal fade" id="newSiteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="site-modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height:400px;overflow: auto;">
                {{-- search site div --}}
                <div class="row d-none" id="site-search-container">
                    <div class="form-group col-md-6">
                        <label for="">Site</label>
                        <input type="text" class="form-control" id="searchSite"
                            placeholder="Search with Site Id / Location / Zipcode">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Company/Customer</label>
                        <input type="text" class="form-control" id="siteCompanyName" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Address</label>
                        <input type="text" class="form-control" id="siteAddress" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">City</label>
                        <input type="text" class="form-control" id="siteCity" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">State</label>
                        <input type="text" class="form-control" id="siteState" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Zipcode</label>
                        <input type="text" class="form-control" id="siteZipcode" readonly>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="">Timezone</label>
                        <input type="text" class="form-control" id="siteTimezone" readonly>
                    </div>
                    <div class="form-group col-12">
                        <label for="">Descriptions</label>
                        <textarea id="description_data" cols="15" rows="3" class="form-control"></textarea readonly>
                    </div>
                    <div class="form-group col-6">
                        <span id="created_time"></span>
                    </div>
                </div>
                {{-- import site div --}}
                <form id="site-import-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row d-none" id="site-import-container">
                        <div class="form-group col-5">
                            <label for="">Select Customer</label>
                            <input type="text" id="cusIdSearchInput" placeholder="Start typing to search....."
                                class="form-control col-4 searchInput">
                            <input type="hidden" id="siteImportCustomerId" name="customer_id">
                            <span style="color:red; font-size:16px" id="siteExcelCustomerError"></span>
                        </div>
                        <div class="form-group col-5">
                            <label for="">Select a excel file</label>
                            <input type="file" id="siteExcelFileInput" class="form-control" name="site_excel_file">
                            <span style="color:red; font-size:16px" id="siteExcelError"></span>
                        </div>
                        <div class="col-2">
                            <button type="submit" class="btn btn-primary btn-sm"
                                style="margin-top: 23px;">Import</button>
                        </div>
                        <div class="col-12 mt-5" style="text-align: center;">
                            <label>Click the below button to download sample site import csv file</label><br>
                            <button type="button" id="sampleSiteExel" class="btn btn-primary">Download</button>
                        </div>
                    </div>
                </form>
                <form id="site-reg-form">
                    @csrf
                    {{-- site registration div --}}
                    <div class="row d-none" id="site-reg-container">
                        <div class="form-group col-4">
                            <label for="">Select Customer</label>
                            <input type="text" placeholder="Start typing to search....."
                                class="form-control searchInput" id="customer_id">
                            <input type="hidden" name="customer_id" id="siteRegCusId">
                            <span style="color:red; font-size:16px" id="customer_id-error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="">Site Id</label>
                            <input type="text" class="form-control" placeholder="Enter the site id"
                                name="site_id" id="site_id">
                            <span style="color:red; font-size:16px" id="site_id-error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="">Location Name</label>
                            <input type="text" class="form-control" placeholder="Enter location name"
                                name="location" id="location">
                            <span style="color:red; font-size:16px" id="location-error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="">State</label>
                            <input type="text" class="form-control" placeholder="Enter state" name="state"
                                id="state">
                            <span style="color:red; font-size:16px" id="state-error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="">Address 1</label>
                            <input type="text" class="form-control" placeholder="Enter Address1" name="address_1"
                                id="address_1">
                            <span style="color:red; font-size:16px" id="address_1-error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="">Address 2</label>
                            <input type="text" class="form-control" placeholder="Enter Address2" name="address_2"
                                id="address_2">
                            <span style="color:red; font-size:16px" id="address_2-error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="">City</label>
                            <input type="text" class="form-control" placeholder="Enter city" name="city"
                                id="city">
                            <span style="color:red; font-size:16px" id="city-error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="">Zipcode</label>
                            <input type="text" class="form-control" placeholder="Enter zipcode" name="zipcode"
                                id="zipcode">
                            <span style="color:red; font-size:16px" id="zipcode-error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="">Timezone</label>
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
                            <select name="time_zone" id="time_zone" placeholder="Enter timezone" class="form-control">
                                <option value="">Select Timezone</option>
                                @foreach($timezoneMap as $abbr => $zone)
                                    <option value="{{ $abbr }}">{{ $zone }} ({{ $abbr }})</option>
                                @endforeach
                            </select>
                            <span style="color:red; font-size:16px" id="time_zone-error"></span>
                        </div>
                        <div class="form-group col-12">
                            <label for="">Property Description</label>
                            <textarea id="description" cols="15" rows="5" class="form-control"
                                placeholder="Enter property descriptions" name="description"></textarea>
                        <span style="color:red; font-size:16px" id="description-error"></span>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary my-2 w-100" type="submit">Submit</button>
                    </div>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <div class="d-none" id="newSitesModalSpinner">
                    <button class="btn btn-warning" type="button" disabled>
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        Please wait!!
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('site_modal_script')
<script>
    $(document).ready(function() {
        $(document).on('click', '#sampleSiteExel', function() {
            window.location.href = "{{ route('user.sample.site.import.excel') }}";
        });
        //site import code
        $(document).on('submit', '#site-import-form', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "{{ route('user.site.import') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#siteExcelCustomerError').text('');
                    $('#siteExcelError').text('');
                    $('#siteExcelFileInput').val('');
                    $('#cusIdSearchInput').val('');
                    iziToast.success({
                        message: data.success,
                        position: "topRight"
                    });
                },
                error: function(data) {
                    if (data.status === 422) {
                        errors = data.responseJSON.errors;
                        $('#siteExcelCustomerError').text('');
                        $('#siteExcelError').text('');
                        $('#siteExcelCustomerError').text(errors.customer_id);
                        $('#siteExcelError').text(errors.site_excel_file);
                    }
                }
            });
        });

        //site registration script
        $(document).on('submit', '#site-reg-form', function(e) {
            e.preventDefault();
            $('#newSitesModalSpinner').removeClass('d-none');
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('user.store.site') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    $('#newSitesModalSpinner').addClass('d-none');
                    if (data.message) {
                        $('#customer_id-error,#company_name-error,#site_id-error,#location-error,#state-error,#address_1-error,#address_2-error,#city-error,#zipcode-error,#description-error,#time_zone-error')
                            .empty();

                        $('#customer_id,#company_name,#site_id,#location,#state,#address_1,#address_2,#city,#zipcode,#description,#time_zone')
                            .val("");
                        iziToast.success({
                            message: data.message,
                            position: "topRight"
                        });
                    }
                },
                error: function(data) {
                    if (data.status === 422) {
                        $('#newSitesModalSpinner').addClass('d-none');
                        errors = data.responseJSON.errors;
                        $('#customer_id-error,#company_name-error,#site_id-error,#location-error,#state-error,#address_1-error,#address_2-error,#city-error,#zipcode-error,#description-error,#time_zone-error')
                            .empty();

                        const fieldsToHandle = ['customer_id', 'company_name', 'site_id',
                            'location', 'state', 'address_1', 'address_2', 'city',
                            'zipcode', 'description', 'time_zone'
                        ];

                        fieldsToHandle.forEach(field => {
                            if (errors[field]) {
                                $('#' + field + '-error').text(errors[field]);
                            }
                        });
                    } else {
                        $('#newSitesModalSpinner').addClass('d-none');
                        iziToast.error({
                            message: data.responseJSON.errors.database,
                            position: "topCenter"
                        });
                    }
                }
            });
        });

        //site import module open
        $(document).on('click', '#siteImportButton', function() {
            $('#site-modal-title').text('Import Site');
            $('#site-reg-container').addClass('d-none');
            $('#site-search-container').addClass('d-none');
            $('#site-import-container').removeClass('d-none');
            $('#newSiteModal').modal('show');
        });
        //new site module open
        $(document).on('click', '#siteNewButton', function() {
            $('#site-modal-title').text('Register Site');
            $('#site-import-container').addClass('d-none');
            $('#site-search-container').addClass('d-none');
            $('#site-reg-container').removeClass('d-none');
            $('#newSiteModal').modal('show');
        });
        //site search button open
        $(document).on('click', '#siteSearchButton', function() {
            $('#site-modal-title').text('Search Site');
            $('#site-import-container').addClass('d-none');
            $('#site-reg-container').addClass('d-none');
            $('#site-search-container').removeClass('d-none');
            $('#newSiteModal').modal('show');
        });

        //clear the site modal when close
        $(document).on('click', '.btn-close', function() {
            $('#customer_id-error,#company_name-error,#site_id-error,#location-error,#state-error,#address_1-error,#address_2-error,#city-error,#zipcode-error,#description-error,#time_zone-error')
                .empty();
            $('#customer_id,#company_name,#site_id,#location,#state,#address_1,#address_2,#city,#zipcode,#description,#time_zone')
                .val("");
        });

        //select customer autocomplete
        $('.searchInput').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('user.customer.autocomplete') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        "query": request.term,
                        "customer": "1"
                    },
                    success: function(data) {
                        response($.map(data.results, function(item) {
                            return {
                                label: item.company_name + "-" + item
                                    .customer_id,
                                value: item.company_name + "-" + item
                                    .customer_id,
                                customerID: item.id,
                            }
                        }));
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                var selectedCustomerId = ui.item.customerID;
                $('#siteRegCusId').val(selectedCustomerId);
                $('#siteImportCustomerId').val(selectedCustomerId);
            }
        });

        //get specific site information
        function loadSite(id) {
            $.ajax({
                url: "{{ route('user.get.site') }}",
                type: "GET",
                data: {
                    "id": id
                },
                success: function(data) {
                    $('#siteCompanyName').val(data.result.customer);
                    $('#siteAddress').val(data.result.address);
                    $('#siteCity').val(data.result.city);
                    $('#siteState').val(data.result.state);
                    $('#siteZipcode').val(data.result.zipcode);
                    $('#siteTimezone').val(data.result.time_zone);
                    $('#description_data').val(data.result.description);
                    $('#created_time').text(data.result.created_at);
                }
            });
        }

        //site autocomplete search
        $('#searchSite').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('user.modal.site.search') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        "query": request.term,
                    },
                    success: function(data) {
                        response($.map(data.results, function(item) {
                            return {
                                label: item.site_id + "-" + item.location +
                                    "-" + item.zipcode,
                                value: item.site_id,
                                siteID: item.id,
                            }
                        }));
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                var selectedSiteId = ui.item.siteID;
                loadSite(selectedSiteId);
            }
        });
    });
</script>
@endpush