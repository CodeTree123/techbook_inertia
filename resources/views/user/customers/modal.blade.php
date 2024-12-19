<div class="modal fade" id="newCustomerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabelCustomer">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="d-none" id="newCustomerContainer">
                <form id="customerRegForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-4">
                                <label>Company Name</label>
                                <input type="text" class="form-control" placeholder="Enter Company Name" name="company_name">
                                <span style="color: red; font-size: 14px" id="cus_company_name_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="customer_type">
                                    <h6>Customer Type</h6>
                                </label>
                                <select name="customer_type" class="form-control">
                                    <option value="">Select Customer Type</option>
                                    <option value="Customer">Customer</option>
                                    <option value="Prospecting">Prospecting</option>
                                    <option value="Etc">Etc</option>
                                </select>
                                <span style="color:red; font-size:14px" id="cus_customer_type_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="address">
                                    <h6>Address</h6>
                                </label>
                                <input type="text" class="form-control" name="address" placeholder="Enter Address">
                                <span style="color:red; font-size:14px" id="cus_address_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="address2">
                                    <h6>Address 2</h6>
                                </label>
                                <input type="text" class="form-control" name="address2" placeholder="Enter Address 2">
                                <span style="color:red; font-size:14px" id="cus_address2_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="country">
                                    <h6>Country</h6>
                                </label>
                                <input type="text" class="form-control" name="country" placeholder="Enter Country" value="United States">
                                <span style="color:red; font-size:14px" id="cus_country_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="city">
                                    <h6>City</h6>
                                </label>
                                <input type="text" class="form-control" name="city" placeholder="Enter City">
                                <span style="color:red; font-size:14px" id="cus_city_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="state">
                                    <h6>State</h6>
                                </label>
                                <input id="state" type="text" class="form-control" name="state" placeholder="Enter State">
                                <span style="color:red; font-size:14px" id="cus_state_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="zip_code">
                                    <h6>Zip Code</h6>
                                </label>
                                <input type="text" class="form-control" name="zip_code" placeholder="Enter Zipcode">
                                <span style="color:red; font-size:14px" id="cus_zip_code_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="email">
                                    <h6>Email</h6>
                                </label>
                                <input type="text" class="form-control" name="email" placeholder="Enter Email">
                                <span style="color:red; font-size:14px" id="cus_zip_code_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="phone">
                                    <h6>Phone</h6>
                                </label>
                                <input type="number" class="form-control" name="phone" placeholder="Enter Phone">
                                <span style="color:red; font-size:14px" id="cus_phone_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="s_rate">
                                    <h6>Standard First Hour Rate</h6>
                                </label>
                                <input type="numeric" class="form-control" name="s_rate_f" placeholder="Standard First Hour Rate">
                                <span style="color:red; font-size:14px" id="cus_s_rate_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="s_rate">
                                    <h6>Standard Additional Hour Rate</h6>
                                </label>
                                <input type="numeric" class="form-control" name="s_rate_a" placeholder="Standard Additional Hour Rate">
                                <span style="color:red; font-size:14px" id="cus_s_rate_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="e_rate">
                                    <h6>Emergency First Hour Rate</h6>
                                </label>
                                <input type="numeric" class="form-control" name="e_rate_f" placeholder="Enter Emergency First Hour Rate">
                                <span style="color:red; font-size:14px" id="cus_e_rate_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="e_rate">
                                    <h6>Emergency Additional Hour Rate</h6>
                                </label>
                                <input type="numeric" class="form-control" name="e_rate_a" placeholder="Enter Emergency Additional Hour Rate">
                                <span style="color:red; font-size:14px" id="cus_e_rate_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="e_rate">
                                    <h6>Weekend First Hour Rate</h6>
                                </label>
                                <input type="numeric" class="form-control" name="w_rate_f" placeholder="Enter Weekend First Hour Rate">
                                <span style="color:red; font-size:14px" id="cus_e_rate_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="e_rate">
                                    <h6>Weekend Additional Hour Rate</h6>
                                </label>
                                <input type="numeric" class="form-control" name="w_rate_a" placeholder="Enter Weekend Additional Hour Rate">
                                <span style="color:red; font-size:14px" id="cus_e_rate_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="e_rate">
                                    <h6>Sunday and Holiday First Hour Rate</h6>
                                </label>
                                <input type="numeric" class="form-control" name="sh_rate_f" placeholder="Enter Sunday And Holiday First Hour Rate">
                                <span style="color:red; font-size:14px" id="cus_e_rate_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="e_rate">
                                    <h6>Sunday and Holiday Additional Hour Rate</h6>
                                </label>
                                <input type="numeric" class="form-control" name="sh_rate_a" placeholder="Enter Sunday And Holiday Additional Hour Rate">
                                <span style="color:red; font-size:14px" id="cus_e_rate_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="travel">
                                    <h6>Travel</h6>
                                </label>
                                <input type="number" class="form-control" name="travel" placeholder="Enter Travel">
                                <span style="color:red; font-size:14px" id="cus_travel_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="billing_term">
                                    <h6>Billing Term</h6>
                                </label>
                                <select name="billing_term" class="form-control">
                                    <option value="">Select Billing Term</option>
                                    <option value="NET30">NET30</option>
                                    <option value="NET45">NET45</option>
                                    <option value="Etc">Etc</option>
                                </select>
                                <span style="color:red; font-size:14px"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="type_phone">
                                    <h6>Type Of Phone System</h6>
                                </label>
                                <input type="text" class="form-control" name="type_phone" placeholder="Type Of Phone System">
                                <span style="color:red; font-size:14px" id="cus_type_phone"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="type_wireless">
                                    <h6>Type Of Wireless</h6>
                                </label>
                                <input type="text" class="form-control" name="type_wireless" placeholder="Type Of Wireless">
                                <span style="color:red; font-size:14px" id="cus_type_wireless"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="type_cctv">
                                    <h6>Type Of CCTV</h6>
                                </label>
                                <input type="text" class="form-control" name="type_cctv" placeholder="Type Of CCTV">
                                <span style="color:red; font-size:14px" id="cus_type_cctv"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="type_pos">
                                    <h6>Type Of POS</h6>
                                </label>
                                <input type="text" class="form-control" name="type_pos" placeholder="Type Of POS">
                                <span style="color:red; font-size:14px" id="cus_type_pos"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="team">
                                    <h6>Team</h6>
                                </label>
                                <select name="team" class="form-control">
                                    <option value="">Select Team</option>
                                    <option value="Blue Team">Blue Team</option>
                                    <option value="Red Team">Red Team</option>
                                    <option value="Etc">Etc</option>
                                </select>
                                <span style="color:red; font-size:14px" id="cus_team_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="sales_person">
                                    <h6>Sales Person Assigned</h6>
                                </label>
                                <input type="text" class="form-control" name="sales_person" placeholder="Sales Person Assigned">
                                <span style="color:red; font-size:14px" id="cus_sales_person"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="project_manager">
                                    <h6>Project Manager Assigned</h6>
                                </label>
                                <input type="text" class="form-control" name="project_manager" placeholder="Project Manager Assign">
                                <span style="color:red; font-size:14px" id="project_manager"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <div class="d-none" id="newCusModalSpinner">
                            <button class="btn btn-warning" type="button" disabled>
                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                Please wait!!
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="d-none" id="customerImportContainer">
                <div class="modal-body">
                    <form id="cusImportForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-6">
                                <input type="file" class="form-control" name="customer_csv_file" id="customer_csv_file">
                                <span style="color: red; font-size: 14px" id="cus_import_error_block"></span>
                            </div>
                            <div class="form-group col-6">
                                <div class="spinner-border spinner-border-sm d-none" role="status" id="cus_import_loader" style="margin-top: 6px;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Import</button>
                            </div>
                        </div>
                    </form>
                    <div class="text-center">
                        <label>Click the below button to download sample customer import csv file</label><br>
                        <a href="{{ route('user.customer.download.excel') }}" class="btn btn-primary">Download</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
            <div class="d-none" id="searchCustomerContainer">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-4">
                            <label>Search by customer Id or name</label>
                            <input type="text" class="form-control col-4" id="cusAutoComplete" name="customer_id" placeholder="start typing to search">
                        </div>
                        <div class="form-group col-12">

                        </div>
                        <div class="form-group col-4">
                            <label>Customer Id</label>
                            <input type="text" class="form-control" placeholder="Enter company name" name="customer_id">
                        </div>
                        <div class="form-group col-4">
                            <label>Company Name</label>
                            <input type="text" class="form-control" placeholder="Enter company name" name="company_name">
                            <span style="color: red; font-size: 14px" id="cus_company_name_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="customer_type">
                                <h6>Customer Type</h6>
                            </label>
                            <input type="text" class="form-control" name="customer_type">
                            <span style="color:red; font-size:14px" id="cus_customer_type_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="address">
                                <h6>Address</h6>
                            </label>
                            <input type="text" class="form-control" name="address" placeholder="Enter address">
                            <span style="color:red; font-size:14px" id="cus_address_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="country">
                                <h6>Country</h6>
                            </label>
                            <input type="text" class="form-control" name="country" placeholder="Enter country">
                            <span style="color:red; font-size:14px" id="cus_country_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="city">
                                <h6>City</h6>
                            </label>
                            <input type="text" class="form-control" name="city" placeholder="Enter city">
                            <span style="color:red; font-size:14px" id="cus_city_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="state">
                                <h6>State</h6>
                            </label>
                            <input id="state" type="text" class="form-control" name="state" placeholder="Enter state">
                            <span style="color:red; font-size:14px" id="cus_state_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="zip_code">
                                <h6>Zip Code</h6>
                            </label>
                            <input type="text" class="form-control" name="zip_code" placeholder="Enter zipcode">
                            <span style="color:red; font-size:14px" id="cus_zip_code_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="email">
                                <h6>Email</h6>
                            </label>
                            <input type="text" class="form-control" name="email" placeholder="Enter email">
                            <span style="color:red; font-size:14px" id="cus_zip_code_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="phone">
                                <h6>Phone</h6>
                            </label>
                            <input type="number" class="form-control" name="phone" placeholder="Enter phone">
                            <span style="color:red; font-size:14px" id="cus_phone_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="s_rate">
                                <h6>Standard first hour Rate</h6>
                            </label>
                            <input type="number" class="form-control" name="s_rate_f" placeholder="Standard first hour Rate">
                            <span style="color:red; font-size:14px" id="cus_s_rate_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="s_rate">
                                <h6>Standard additional hour Rate</h6>
                            </label>
                            <input type="number" class="form-control" name="s_rate_a" placeholder="Standard additional hour Rate">
                            <span style="color:red; font-size:14px" id="cus_s_rate_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="e_rate">
                                <h6>Emergency first hour Rate</h6>
                            </label>
                            <input type="number" class="form-control" name="e_rate_f" placeholder="Enter Emergency first hour rate">
                            <span style="color:red; font-size:14px" id="cus_e_rate_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="e_rate">
                                <h6>Emergency Additional hour Rate</h6>
                            </label>
                            <input type="number" class="form-control" name="e_rate_a" placeholder="Enter Emergency additional hour rate">
                            <span style="color:red; font-size:14px" id="cus_e_rate_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="e_rate">
                                <h6>Weekend Additional hour Rate</h6>
                            </label>
                            <input type="number" class="form-control" name="w_rate_a" placeholder="Enter Weekend additional hour rate">
                            <span style="color:red; font-size:14px" id="cus_e_rate_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="e_rate">
                                <h6>Weekend first hour Rate</h6>
                            </label>
                            <input type="number" class="form-control" name="w_rate_f" placeholder="Enter Weekend first hour rate">
                            <span style="color:red; font-size:14px" id="cus_e_rate_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="e_rate">
                                <h6>Sunday and Holiday first hour Rate</h6>
                            </label>
                            <input type="number" class="form-control" name="sh_rate_f" placeholder="Enter Sunday and Holiday first hour rate">
                            <span style="color:red; font-size:14px" id="cus_e_rate_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="e_rate">
                                <h6>Sunday and Holiday Additional hour Rate</h6>
                            </label>
                            <input type="number" class="form-control" name="sh_rate_a" placeholder="Enter Sunday and Holiday additional hour rate">
                            <span style="color:red; font-size:14px" id="cus_e_rate_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="travel">
                                <h6>Travel</h6>
                            </label>
                            <input type="number" class="form-control" name="travel" placeholder="Enter travel">
                            <span style="color:red; font-size:14px" id="cus_travel_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="billing_term">
                                <h6>Billing Term</h6>
                            </label>
                            <input type="text" class="form-control" name="billing_term">
                            <span style="color:red; font-size:14px"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="type_phone">
                                <h6>Type Of Phone System</h6>
                            </label>
                            <input type="text" class="form-control" name="type_phone" placeholder="Type Of Phone">
                            <span style="color:red; font-size:14px" id="cus_type_phone"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="type_wireless">
                                <h6>Type Of Wireless</h6>
                            </label>
                            <input type="text" class="form-control" name="type_wireless" placeholder="Type Of Wireless">
                            <span style="color:red; font-size:14px" id="cus_type_wireless"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="type_cctv">
                                <h6>Type Of CCTV</h6>
                            </label>
                            <input type="text" class="form-control" name="type_cctv" placeholder="Type Of CCTV">
                            <span style="color:red; font-size:14px" id="cus_type_cctv"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="type_pos">
                                <h6>Type Of POS</h6>
                            </label>
                            <input type="text" class="form-control" name="type_pos" placeholder="Type Of POS">
                            <span style="color:red; font-size:14px" id="cus_type_pos"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="team">
                                <h6>Team</h6>
                            </label>
                            <input type="text" class="form-control" name="team">
                            <span style="color:red; font-size:14px" id="cus_team_error"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="sales_person">
                                <h6>Sales Person Assigned</h6>
                            </label>
                            <input type="text" class="form-control" name="sales_person" placeholder="Sales person assign">
                            <span style="color:red; font-size:14px" id="cus_sales_person"></span>
                        </div>
                        <div class="form-group col-4">
                            <label for="project_manager">
                                <h6>Project Manager Assigned</h6>
                            </label>
                            <input type="text" class="form-control" name="project_manager" placeholder="Project Manager assign">
                            <span style="color:red; font-size:14px" id="project_manager"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('customer_modal_script')
<script>
    $(document).ready(function() {

        $('#customerRegForm').on('submit', function(e) {
            e.preventDefault();
            $('#newCusModalSpinner').removeClass('d-none');
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('user.customer.reg') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#newCusModalSpinner').addClass('d-none');
                    iziToast.success({
                        message: data.success,
                        position: "topRight"
                    });

                    $('#customerRegForm')[0].reset();
                    $('#cus_company_name_error').text('');
                    $('#cus_address_error').text('');
                    $('#cus_customer_type_error').text('');
                    $('#cus_phone_error').text('');
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        $('#newCusModalSpinner').addClass('d-none');
                        errors = xhr.responseJSON.errors;
                        $('#cus_company_name_error').text(errors.company_name);
                        $('#cus_address_error').text(errors.address);
                        $('#cus_customer_type_error').text(errors.customer_type);
                        $('#cus_phone_error').text(errors.phone);
                    }
                }
            });
        });

        $('#cusAutoComplete').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('user.customer.autocomplete') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        "query": request.term,
                    },
                    success: function(data) {
                        response($.map(data.results, function(item) {
                            return {
                                label: item.company_name + "-" + item.customer_id,
                                value: item.company_name + "-" + item.customer_id,
                                customerID: item.id,
                            }
                        }));
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                var selectedCusId = ui.item.customerID;
                customer(selectedCusId);
            }
        });

        function customer(id) {
            $.ajax({
                url: "{{ route('user.fetch.customer') }}",
                type: "GET",
                data: {
                    "id": id
                },
                success: function(data) {
                    console.log(data);
                    const fields = ["customer_id", "company_name", "address", "country", "city", "state",
                     "zip_code", "email", "customer_type", "phone",
                     "s_rate_f",
                     "s_rate_a",
                      "e_rate_f",
                      "e_rate_a",
                      "w_rate_a",
                      "w_rate_f",
                      "sh_rate_f",
                      "sh_rate_a",
                       "travel", "billing_term", "type_phone", "type_pos", 
                     "type_wireless", "type_cctv", "team", "sales_person",
                      "project_manager"];

                    fields.forEach(field => {
                        if (field === "address" && typeof data[field] === "object") {
                            Object.keys(data[field]).forEach(subfield => {
                                var inputSelector = "[name='" + subfield + "']";
                                $(inputSelector).val(data[field][subfield]);
                            });
                        } else if (data[field]) {
                            $("[name='" + field + "']").val(data[field]);
                        }
                    });
                }
            });
        }

        $('#cusImportForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            $('#cus_import_loader').removeClass('d-none');

            $.ajax({
                url: "{{ route('user.customer.import') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    iziToast.success({
                        message: data.success,
                        position: "topRight"
                    });
                    $('#cus_import_error_block').text("");
                    $('#cus_import_loader').addClass('d-none');
                    $('#customer_csv_file').val("");
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        $('#cus_import_loader').addClass('d-none');
                        $('#cus_import_error_block').text(xhr.responseJSON.errors.customer_csv_file);
                    }
                }
            });
        });

        $('#cusNewButton').on('click', function() {
            $('#staticBackdropLabelCustomer').text('Add New Customer');
            $('#newCustomerContainer').removeClass('d-none');
            $('#customerImportContainer').addClass('d-none');
            $('#searchCustomerContainer').addClass('d-none');
            $('#newCustomerModal').modal('show');
        });

        $('#cusSearchButton').on('click', function() {
            $('#staticBackdropLabelCustomer').text('Search Customer');
            $('#newCustomerContainer').addClass('d-none');
            $('#customerImportContainer').addClass('d-none');
            $('#searchCustomerContainer').removeClass('d-none');
            $('#newCustomerModal').modal('show');
        });

        $('#cusImportButton').on('click', function() {
            $('#staticBackdropLabelCustomer').text('Import Customer');
            $('#newCustomerContainer').addClass('d-none');
            $('#searchCustomerContainer').addClass('d-none');
            $('#customerImportContainer').removeClass('d-none');
            $('#newCustomerModal').modal('show');
        });
    });
</script>
@endpush