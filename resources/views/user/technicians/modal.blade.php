<style>
    label {
        font-weight: bold;
        color: #333;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    #techSkillsForm {
        animation: fadeIn 0.5s ease-in-out;
    }
</style>
<div class="modal fade" id="newTechnicianModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Register New Technician</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="d-none" id="ftechSkillBlock">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Displayed when the button is not clicked -->
                            <button id="addSetsBtn" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> Add Skillsets
                            </button>
                        </div>
                        <div class="col-4">
                            <form id="techSkillsForm" class="d-none">
                                @csrf
                                <label>Add New Skillset</label>
                                <div class="form-group">
                                    <div class="d-flex">
                                        <input type="text" name="skill_name" class="form-control" placeholder="Enter skillsets">
                                        <button type="submit" class="btn btn-primary btn-sm mx-2">Add</button>
                                    </div>
                                    <span style="color: red; font-size: 14px" id="skillset-error"></span>
                                    <span id="success-container" style="font-weight: bold; font-size: 14px; color: #0e0444"></span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.getElementById('addSetsBtn').addEventListener('click', function() {
                    document.getElementById('techSkillsForm').classList.toggle('d-none');
                });
            </script>
            <div class="d-none" id="techRegistration">
                <form id="newTechnicianModalForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-4">
                                <label>Company Name</label>
                                <input type="text" class="form-control" placeholder="Enter company name" name="company_name">
                                <span style="color: red; font-size: 14px" id="company_name_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>Address</label>
                                <input type="text" class="form-control" placeholder="Enter address" name="address">
                                <span style="color: red; font-size: 14px" id="address_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>Country</label>
                                <input type="text" class="form-control" placeholder="Enter country" name="country" value="United States">
                                <span style="color: red; font-size: 14px" id="country_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>City</label>
                                <input type="text" class="form-control" placeholder="Enter city" name="city">
                                <span style="color: red; font-size: 14px" id="city_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>State</label>
                                <input type="text" class="form-control" placeholder="Enter state" name="state">
                                <span style="color: red; font-size: 14px" id="state_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>Zipcode</label>
                                <input type="text" class="form-control" placeholder="Enter zipcode" name="zip_code">
                                <span style="color: red; font-size: 14px" id="zip_code_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>Email</label>
                                <input type="text" class="form-control" placeholder="Enter email" name="email">
                                <span style="color: red; font-size: 14px" id="email_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>Phone</label>
                                <input type="text" class="form-control" placeholder="Enter phone" name="phone">
                                <span style="color: red; font-size: 14px" id="phone_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>Primary Contact Name</label>
                                <input type="text" class="form-control" placeholder="Enter primary contact name" name="primary_contact">
                                <span style="color: red; font-size: 14px" id="primary_contact_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>Primary Contact's Email</label>
                                <input type="text" class="form-control" placeholder="Enter primary contacts email" name="primary_contact_email">
                                <span style="color: red; font-size: 14px" id="primary_contact_email_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>Title</label>
                                <input type="text" class="form-control" placeholder="Enter title" name="title">
                                <span style="color: red; font-size: 14px" id="title_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>Cell Phone</label>
                                <input type="text" class="form-control" placeholder="Enter cell phone" name="cell_phone">
                                <span style="color: red; font-size: 14px" id="cell_phone_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>Standard Rate</label>
                                <input type="numeric" class="form-control" placeholder="Enter rate" name="std_rate">
                                <span style="color: red; font-size: 14px" id="rate_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>EM Rate</label>
                                <input type="numeric" class="form-control" placeholder="Enter rate" name="em_rate">
                                <span style="color: red; font-size: 14px" id="rate_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>OT Rate</label>
                                <input type="numeric" class="form-control" placeholder="Enter rate" name="ot_rate">
                                <span style="color: red; font-size: 14px" id="rate_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>SH Rate</label>
                                <input type="numeric" class="form-control" placeholder="Enter rate" name="sh_rate">
                                <span style="color: red; font-size: 14px" id="rate_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>Radius</label>
                                <input type="numeric" class="form-control" placeholder="Enter radius" name="radius">
                                <span style="color: red; font-size: 14px" id="radius_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>Travel Fee</label>
                                <input type="numeric" class="form-control" placeholder="Enter travel fee" name="travel_fee">
                                <span style="color: red; font-size: 14px" id="travel_fee_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>C Work Order Count</label>
                                <input type="number" class="form-control" placeholder="Enter value" name="c_wo_ct">
                                <span style="color: red; font-size: 14px" id="travel_fee_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label for="status">Select Status</label>
                                <select name="status" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Pending">Pending</option>
                                </select>
                                <span style="color: red; font-size: 14px" id="status_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>COI Expiration Date</label>
                                <input type="date" class="form-control" id="tech_modal_coi_expire_date" placeholder="COI expiration date" autocomplete="off" name="coi_expire_date">
                                <span style="color: red; font-size: 14px" id="coi_expire_date_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>COI Attachment</label>
                                <input type="file" class="form-control" placeholder="COI attachment" name="coi_file">
                                <span style="color: red; font-size: 14px" id="coi_file_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>MSA Expiration Date</label>
                                <input type="date" class="form-control" id="tech_modal_msa_expire_date" placeholder="MSA expiration date" autocomplete="off" name="msa_expire_date">
                                <span style="color: red; font-size: 14px" id="msa_expire_date_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>MSA Attachment</label>
                                <input type="file" class="form-control" placeholder="MSA attachment" name="msa_file">
                                <span style="color: red; font-size: 14px" id="msa_file_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>NDA</label>
                                <select name="nda" class="form-control">
                                    <option value="">Select NDA</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                                <span style="color: red; font-size: 14px" id="nda_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>NDA Attachment</label>
                                <input type="file" class="form-control" placeholder="NDA attachment" name="nda_file">
                                <span style="color: red; font-size: 14px" id="nda_file_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>Terms</label>
                                <select name="terms" class="form-control">
                                    <option value="">Select Terms</option>
                                    <option value="30">30</option>
                                    <option value="45">45</option>
                                    <option value="60">60</option>
                                    <option value="90">90</option>
                                </select>
                                <span style="color: red; font-size: 14px" id="terms_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>Preferred?</label>
                                <select name="preference" class="form-control">
                                    <option value="Yes">Yes</option>
                                    <option selected value="No">No</option>
                                </select>
                                <span style="color: red; font-size: 14px" id="preference_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>Source</label>
                                <input type="text" class="form-control" placeholder="Enter Sourcee" name="source">
                                <span style="color: red; font-size: 14px" id="source_error"></span>
                            </div>
                            <div class="form-group col-4">
                                <label>Notes</label>
                                <textarea class="form-control" name="notes" placeholder="Enter notes here"></textarea>
                                <span style="color: red; font-size: 14px" id="notes_error"></span>
                            </div>
                            <div style="margin-top: 20px;">
                                <label>Skill Sets</label>
                            </div>
                            <div id="skillsContainer" class="row" style="margin-top: 10px;">

                            </div>
                            <span style="color: red; font-size: 14px" id="skill_id_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <div class="d-none" id="newTechModalSpinner">
                            <button class="btn btn-warning" type="button" disabled>
                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                Please wait!!
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="d-none" id="techSearch">
                <div class="modal-body">
                    <div class="container">
                        <label>Search By Id, Name Or Zipcode</label>
                        <div class="col-6">
                            <input type="text" class="form-control" placeholder="please start typing to search......" id="ftechAutoComplete">
                        </div>
                    </div>
                    <div class="d-none" id="techSearchData" style="margin-top: 40px;">
                        <div class="container mt-5">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Technician Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Company:</label>
                                                <span id="company_name_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Address:</label>
                                                <span id="address_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Country:</label>
                                                <span id="country_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>City:</label>
                                                <span id="city_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>State:</label>
                                                <span id="state_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Zipcode:</label>
                                                <span id="zip_code_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Email:</label>
                                                <span id="email_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Primary Contact Email:</label>
                                                <span id="primary_contact_email_span"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Phone:</label>
                                                <span id="phone_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Primary Contact:</label>
                                                <span id="primary_contact_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Title:</label>
                                                <span id="title_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Cell Phone:</label>
                                                <span id="cell_phone_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Rate:</label>
                                                <span id="rate_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Radius:</label>
                                                <span id="radius_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Travel Fee:</label>
                                                <span id="travel_fee_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>C WorkOrder Count:</label>
                                                <span id="c_wo_ct_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>WorkOrder Count:</label>
                                                <span id="wo_ct_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Status:</label>
                                                <span id="status_span"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Preference:</label>
                                                <span id="preference_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>COI Expire Date:</label>
                                                <span id="coi_expire_date_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>MSA Expire Date:</label>
                                                <span id="msa_expire_date_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>NDA:</label>
                                                <span id="nda_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Terms:</label>
                                                <span id="terms_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>COI File:</label>
                                                <span id="coi_file_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>MSA File:</label>
                                                <span id="msa_file_fee_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>NDA File:</label>
                                                <span id="nda_file_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Source:</label>
                                                <span id="source_span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Notes:</label>
                                                <span id="notes_span"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <h5>Skillsets</h5>
                                            <span id="ftech_skills_span"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
            <div class="d-none" id="techZipCode">
                <div class="modal-body">
                    <!-- Zipcode Search -->
                    <div class="col-md-3">
                        <div class="form-group d-flex">
                        <input type="text" style="padding: 4px 8px;" class="form-control h-100" id="hzipcode" placeholder="Enter Zipcode">
                            
                            <button id="searchTechnicians" class="btn btn-sm btn-primary mx-2">Submit</button>
                        </div>
                    </div>

                    <!-- Technician List -->
                    <div class="mt-3" id="technicianList"></div>

                    <!-- Technician Details -->
                    <div class="card-body" id="technicianDetails"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
            <div class="d-none" id="techImport">
                <div class="modal-body">
                    <form id="techImportForm" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12">
                            <div class="col-6">
                                <input type="file" class="form-control" name="ftech_csv_file" id="ftech_csv_file">
                                <div class="spinner-border spinner-border-sm mt-1 d-none" role="status" id="import_spinner">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span style="color: red; font-size: 14px" id="import_error_block"></span>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary btn-sm my-2">Submit</button>
                            </div>
                        </div>
                    </form>
                    <div class="text-center">
                        <label>Click the below button to download sample technician csv file</label><br>
                        <a href="{{ route('user.download.excel') }}" class="btn btn-primary">Download</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
            <div class="d-none" id="techDistance">
                <div class="modal-body">
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
                                            <input type="text" id="siteAddressU" class="form-control" name="site_address"
                                                placeholder="Enter project site address">
                                            <input id="latitude" type="hidden" name="latitude">
                                            <input id="longitude" type="hidden" name="longitude">
                                            <span style="color:red; font-size:15px" id="errors-container"></span>
                                        </div>
                                        <!-- <div class="form-group col-4">
                                            <input type="number" class="form-control"
                                                placeholder="How much tech you want to see in the result?" style="margin-top: 39px;"
                                                id="numberOfTech" name="numberOfTech">
                                            <span style="color:red; font-size:15px" id="errors-container2"></span>
                                        </div> -->
                                        <div class="form-group col-4">
                                            <button type="button" id="submit" class="btn btn-success"
                                                style="margin-top:39px; margin-left:10px;"><i
                                                    class="fa fa-search-plus"></i>&nbsp;Start
                                                Finding</button>
                                        </div>
                                    </div>
                                    <div class="d-none" id="loader0">
                                        <h6 class="text-dark"><strong>Please wait for the response from google</strong></h6>
                                        <div class="spinner-grow text-danger" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                    <div class="d-none" id="removable-divHeader">
                                        <p><b>Showing the results of radius :</p>
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
                                                <tbody id="tbodyH" class="text-nowrap"></tbody>
                                            </table>
                                        </div>
                                        <div class="float-right">
                                            <button id="btn-find-previous" class="btn btn-secondary">Previous</button>
                                            <button type="button" class="btn btn-primary my-2" id="btn-find-more1">Next</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('technician_modal_script')
<script>
    var success = false;
    var clickCount = 0;
    var respondedTechnicians = [];
    let responseStack = []; // Stack to store responses

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '#submit', function() {
            let destination = $('#siteAddressU').val();
            let lat = $('#latitude').val();
            let lon = $('#longitude').val();
            let numberOfTech = 10;
            $('#loader0').removeClass('d-none');
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
                    $('#removable-divHeader').removeClass('d-none');
                    $('#loader0').addClass('d-none');
                    responseStack.push(data);
                    $("#btn-find-more1").off('click').on('click', function() {
                        findMoreTech(1); // Increment radius
                    });

                    $("#btn-find-previous").off('click').on('click', function() {
                        findMoreTech(-1); // Decrement radius
                    });
                    updateTechTable(data);
                },
                error: function(data) {
                    success = false;
                    $('#loader0').addClass('d-none');
                    if (data.status == 422) {
                        $('#errors-container').text(data.responseJSON.errors.destination);
                        // $('#errors-container2').text(data.responseJSON.errors.numberOfTech);
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

        let clickCount = 0; // Tracks the number of clicks
        let minClickCount = 0; // Tracks the minimum click count (no negative radius)

        function findMoreTech(direction) {
            if (direction === 1) {
                clickCount++;

                let radiusElevator = clickCount * 50; // Adjust radius
                let numberOfTech = 10;

                // AJAX request to fetch data
                $.ajax({
                    url: "{{ route('distance.get.response') }}",
                    type: "POST",
                    data: {
                        latitude: $('#latitude').val(),
                        longitude: $('#longitude').val(),
                        destination: $('#siteAddressU').val(),
                        radiusValue: radiusElevator,
                        respondedTechnicians: respondedTechnicians,
                        numberOfTech: numberOfTech,
                    },
                    success: function(data) {
                        $('#errors-container').empty();
                        $('#errors-container2').empty();
                        $('#removable-divHeader').removeClass('d-none');
                        $('#loader0').addClass('d-none');

                        // Push the response onto the stack
                        responseStack.push(data);

                        // Display the response
                        console.log(responseStack);
                        updateTechTable(data);
                    },
                    error: function(data) {
                        $('#loader0').addClass('d-none');
                        if (data.status == 422) {
                            $('#errors-container').text(data.responseJSON.errors.destination);
                            // $('#errors-container2').text(data.responseJSON.errors.numberOfTech);
                        }
                        if (data.status == 404) {
                            iziToast.warning({
                                message: data.responseJSON.errors,
                                position: "topRight"
                            });
                        }
                    }
                });
            } else if (direction === -1) {
                if (clickCount > minClickCount) {
                    // Pop the top response
                    responseStack.pop();
                    clickCount--;

                    if (responseStack.length > 0) {
                        // Display the next response under the top
                        const previousResponse = responseStack[responseStack.length - 1];
                        console.log(previousResponse);
                        updateTechTable(previousResponse);
                    } else {
                        iziToast.warning({
                            message: "No previous data available in the stack.",
                            position: "topRight"
                        });
                    }
                } else {
                    iziToast.warning({
                        message: "You are already at the minimum search radius.",
                        position: "topRight"
                    });
                }
            }
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
            $('#tbodyH').html(html);
        }

        //address autocomplete
        $('#siteAddressU').autocomplete({
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
<script>
    $(document).ready(function() {
        $('#techImportForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $('#import_spinner').removeClass('d-none');

            $.ajax({
                url: "{{ route('user.ftech.import') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    iziToast.success({
                        message: data.success,
                        position: "topRight"
                    });
                    $('#import_error_block').text("");
                    $('#import_spinner').addClass('d-none');
                    $('#ftech_csv_file').val("");
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        $('#import_spinner').addClass('d-none');
                        $('#import_error_block').text(xhr.responseJSON.errors
                            .ftech_csv_file);
                    }
                }
            });
        });

        //skillsets table from db for ftech reg
        function skillSets() {
            $.ajax({
                url: "{{ route('user.ftech.skills') }}",
                type: "GET",
                success: function(data) {
                    var skillsContainer = $('#skillsContainer');
                    var checkboxHtml = '';

                    data.forEach(function(skill, index) {
                        if (index % 3 === 0) {
                            checkboxHtml +=
                                '<div class="form-group col-3 text-nowrap overflow-hidden">';
                        }
                        checkboxHtml += '<div class="form-check">' +
                            '<input class="form-check-input" type="checkbox" name="skill_id[]" id="skill_' +
                            skill.id + '" value="' + skill.id + '">' +
                            '<label class="form-check-label" for="skill_' + skill.id +
                            '">' + skill.skill_name + '</label>' +
                            '</div>';

                        if ((index + 1) % 3 === 0 || (index + 1) === data.length) {
                            checkboxHtml += '</div>';
                        }
                    });
                    skillsContainer.html(checkboxHtml);
                }
            });
        }

        function technician(id) {
            $.ajax({
                url: "{{ route('user.ftech.data') }}",
                type: "GET",
                data: {
                    "id": id
                },
                success: function(data) {
                    if (data) {
                        $('#techSearchData').removeClass('d-none');
                    }

                    $('#company_name_span').text(data.tech.company_name);
                    $('#address_span').text(data.tech.address_data.address);
                    $('#country_span').text(data.tech.address_data.country);
                    $('#city_span').text(data.tech.address_data.city);
                    $('#state_span').text(data.tech.address_data.state);
                    $('#zip_code_span').text(data.tech.address_data.zip_code);
                    $('#email_span').text(data.tech.email);
                    $('#phone_span').text(data.tech.phone);
                    $('#primary_contact_span').text(data.tech.primary_contact);
                    $('#title_span').text(data.tech.title);
                    $('#cell_phone_span').text(data.tech.cell_phone);
                    $('#rate_span').text(data.tech.rate);
                    $('#radius_span').text(data.tech.radius);
                    $('#travel_fee_span').text(data.tech.travel_fee);
                    $('#c_wo_ct_span').text(data.tech.c_wo_ct);
                    $('#wo_ct_span').text(data.tech.wo_ct);
                    $('#status_span').text(data.tech.status);
                    $('#source_span').text(data.tech.source);
                    $('#notes_span').text(data.tech.notes);
                    $('#preference_span').text(data.tech.preference);
                    $('#coi_expire_date_span').text(data.tech.coi_expire_date);
                    $('#msa_expire_date_span').text(data.tech.msa_expire_date);
                    $('#nda_span').text(data.tech.nda);
                    $('#terms_span').text(data.tech.terms);
                    $('#ftech_skills_span').text(data.skills);
                },
            });
        }

        $('#ftechAutoComplete').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('user.technician.autocomplete') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        "query": request.term,
                    },
                    success: function(data) {
                        response($.map(data.results, function(item) {
                            return {
                                label: item.company_name + "-" + item
                                    .technician_id,
                                value: item.company_name + "-" + item
                                    .technician_id,
                                techID: item.id,
                            }
                        }));
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                var selectedTechId = ui.item.techID;
                technician(selectedTechId);
            }
        });

        $('#techNewButton').on('click', function() {
            $('#staticBackdropLabel').text("Register New Technician");
            $('#techRegistration').removeClass('d-none');
            $('#ftechSkillBlock').removeClass('d-none');
            $('#techSearch').addClass('d-none');
            $('#techImport').addClass('d-none');
            skillSets();
            $('#techDistance').addClass('d-none');
            $('#newTechnicianModal').modal('show');
            $('#techZipCode').addClass('d-none');
        });

        $('#techSearchButton').on('click', function() {
            $('#staticBackdropLabel').text("Find Technician");
            $('#techSearch').removeClass('d-none');
            $('#techRegistration').addClass('d-none');
            $('#ftechSkillBlock').addClass('d-none');
            $('#techImport').addClass('d-none');
            $('#techDistance').addClass('d-none');
            $('#newTechnicianModal').modal('show');
            $('#techZipCode').addClass('d-none');
        });

        $('#techZipCodeButton').on('click', function() {
            $('#staticBackdropLabel').text("Find Technician");
            $('#techZipCode').removeClass('d-none');
            $('#techSearch').addClass('d-none');
            $('#techRegistration').addClass('d-none');
            $('#ftechSkillBlock').addClass('d-none');
            $('#techImport').addClass('d-none');
            $('#techDistance').addClass('d-none');
            $('#newTechnicianModal').modal('show');
        });

        $('#techImportButton').on('click', function() {
            $('#staticBackdropLabel').text("Bulk Import Technician");
            $('#techImport').removeClass('d-none');
            $('#techRegistration').addClass('d-none');
            $('#ftechSkillBlock').addClass('d-none');
            $('#techSearch').addClass('d-none');
            $('#techDistance').addClass('d-none');
            $('#newTechnicianModal').modal('show');
        });
        $('#techDistanceButton').on('click', function() {
            $('#staticBackdropLabel').text("Measure Distance Technician");
            $('#techImport').addClass('d-none');
            $('#techRegistration').addClass('d-none');
            $('#ftechSkillBlock').addClass('d-none');
            $('#techSearch').addClass('d-none');
            $('#techDistance').removeClass('d-none');
            $('#newTechnicianModal').modal('show');
            $('#techZipCode').addClass('d-none');
        });
        $('.technician-link').on('click', function(){
            $('#technicianList').addClass('d-none');
        });

        $('#newTechnicianModalForm').on('submit', function(e) {
            e.preventDefault();
            $('#newTechModalSpinner').removeClass('d-none');
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('user.ftech.new') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#newTechModalSpinner').addClass('d-none');
                    iziToast.success({
                        message: data.success,
                        position: "topRight"
                    });
                    $('#newTechnicianModalForm')[0].reset();
                },
                error: function(data) {
                    if (data.status == 422) {
                        $('#newTechModalSpinner').addClass('d-none');
                        errors = data.responseJSON.errors;
                        $("#company_name_error,#address_error,#country_error,#city_error,#state_error,#zip_code_error,#email_error,#phone_error,#primary_contact_error,#primary_contact_email_error,#title_error,#cell_phone_error,#rate_error,#radius_error,#travel_fee_error,#status_error,#coi_expire_date_error,#coi_file_error,#msa_expire_date_error,#msa_file_error,#nda_error,#nda_file_error,#terms_error,#preference_error,#skill_id_error")
                            .empty();

                        const fields = ["company_name", "address", "country", "city",
                            "state", "zip_code", "email", "phone", "primary_contact",
                            "primary_contact_email", "title", "cell_phone", "rate",
                            "radius", "travel_fee", "status", "coi_expire_date",
                            "coi_file", "msa_expire_date", "msa_file", "nda",
                            "nda_file", "terms", "preference", "skill_id"
                        ];

                        fields.forEach(field => {
                            if (errors[field]) {
                                $('#' + field + '_error').text(errors[field]);
                            }
                        });
                    }
                }
            });
        });

        $('#techSkillsForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('user.skillsets.new') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#skillset-error').text("");
                    $("input[name='skill_name']").val("");
                    skillSets();
                    $('#success-container').text(data.message).fadeIn();
                    setTimeout(function() {
                        $('#success-container').fadeOut();
                    }, 3000);
                },
                error: function(data) {
                    $('#skillset-error').text(data.responseJSON.errors.skill_name);
                }
            });
        });
    });
</script>
<script>
$(document).ready(function () {
    // Search technicians by zipcode
    $('#searchTechnicians').on('click', function () {
        let zipcode = $('#hzipcode').val();
        //alert(zipcode);

        $.ajax({
            url: '{{ route('technician.zipCode.search') }}',
            method: 'GET',
            data: { zipcode: zipcode },
            success: function (response) {
                let technicianList = response.technicians;

                if (technicianList.length > 0) {
                    // Display the total number of technicians found
                    let totalHtml = `<p class="fw-bold">Total Technicians Found: ${technicianList.length}</p>`;
                    
                    // Create the technician list
                    let listHtml = '<ul>';
                    technicianList.forEach(function (tech) {
                        listHtml += `<li>
                            <a href="#" class="technician-link" data-id="${tech.id}">${tech.company_name}</a>
                        </li>`;
                    });
                    listHtml += '</ul>';
                    
                    // Combine the total and list
                    $('#technicianList').html(totalHtml + listHtml).show(); // Ensure the list is visible
                } else {
                    $('#technicianList').html('<p>No technicians found for this zipcode.</p>').show();
                }

                $('#technicianDetails').empty();
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                $('#technicianList').html('<p>An error occurred while fetching technicians.</p>').show();
            }
        });
    });

    // Fetch and display technician details
    $(document).on('click', '.technician-link', function (e) {
        e.preventDefault();
        let technicianId = $(this).data('id');

        // Dynamically construct the URL for technician details
        const technicianDetailsRoute = '{{ route('technician.zipCode.details', ['id' => ':id']) }}';
        const url = technicianDetailsRoute.replace(':id', technicianId);

        $.ajax({
            url: url,
            method: 'GET',
            success: function (response) {
                let technician = response.technician;
                if (technician) {
                    let detailsHtml = `
                        <h3 class="mt-2">${technician.company_name}</h3>
                        <p class="fw-bold">Email: ${technician.email}</p>
                        <p class="fw-bold">Phone: ${technician.phone}</p>
                        <p class="fw-bold">Standard Rate: ${technician.rate?.STD || 'N/A'}</p>
                        <p class="fw-bold">Emergency Rate: ${technician.rate?.EM || 'N/A'}</p>
                        <p class="fw-bold">Overtime Rate: ${technician.rate?.OT || 'N/A'}</p>
                        <p class="fw-bold">Special Hour Rate: ${technician.rate?.SH || 'N/A'}</p>
                        <p class="fw-bold">Address: ${technician.address_data?.address || 'N/A'}</p>
                        <p class="fw-bold">City: ${technician.address_data?.city || 'N/A'}</p>
                        <p class="fw-bold">State: ${technician.rate?.state || 'N/A'}</p>
                        <p class="fw-bold">Zip Code: ${technician.rate?.zip_code || 'N/A'}</p>
                        <button id="closeDetails" class="btn btn-secondary mt-3">Close</button>
                        `;
                    $('#technicianDetails').html(detailsHtml);

                    // Hide the technician list when showing details
                    $('#technicianList').hide();
                } else {
                    $('#technicianDetails').html('<p>Technician details not found.</p>');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                $('#technicianDetails').html('<p>An error occurred while fetching technician details.</p>');
            }
        });
    });
});

$(document).on('click', '#closeDetails', function () {
    $('#technicianDetails').empty(); // Clear the details
    $('#technicianList').show(); // Show the technician list
});

</script>

@endpush