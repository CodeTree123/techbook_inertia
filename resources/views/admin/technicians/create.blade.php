@extends('admin.layoutsNew.app')
@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assetsNew/main_css/technician/create.css') }}">
@endsection
@section('content')
    <style>
        .loading {
            --speed-of-animation: 0.9s;
            --gap: 6px;
            --first-color: #4c86f9;
            --second-color: #49a84c;
            --third-color: #f6bb02;
            --fourth-color: #f6bb02;
            --fifth-color: #2196f3;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 110px;
            gap: 6px;
            height: 110px;
        }

        .loading span {
            width: 4px;
            height: 50px;
            background: var(--first-color);
            animation: scale var(--speed-of-animation) ease-in-out infinite;
        }

        .loading span:nth-child(2) {
            background: var(--second-color);
            animation-delay: -0.8s;
        }

        .loading span:nth-child(3) {
            background: var(--third-color);
            animation-delay: -0.7s;
        }

        .loading span:nth-child(4) {
            background: var(--fourth-color);
            animation-delay: -0.6s;
        }

        .loading span:nth-child(5) {
            background: var(--fifth-color);
            animation-delay: -0.5s;
        }

        @keyframes scale {

            0%,
            40%,
            100% {
                transform: scaleY(0.05);
            }

            20% {
                transform: scaleY(1);
            }
        }
    </style>
    <div class="content-wrapper" style="background-color: white;">
        @include('admin.includeNew.breadcrumb')
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-mb-12 d-flex justify-content-between">
                                    <h3 class="text-dark"></h3>
                                    <div class="justify-content-between">
                                        <div class="button btn btn-primary my-2" data-bs-toggle="modal"
                                            data-bs-target="#staticBackdrop"><i class="fas fa-plus-circle"></i> Add Skill
                                        </div>
                                        <a href="{{ route('technician.index') }}" class="btn btn-primary"><i
                                                class="fas fa-list"></i> Technician
                                            List</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body text-dark">
                            <form action="{{ route('technician.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-4">
                                        <label for="company_name">
                                            <h6>Company Name</h6>
                                        </label>
                                        <input type="text" class="form-control" name="company_name"
                                            placeholder="Enter company name" value="{{ old('company_name') }}">
                                        @error('company_name')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="address">
                                            <h6>Address</h6>
                                        </label>
                                        <input type="text" class="form-control" name="address"
                                            placeholder="Enter address" value="{{ old('address') }}">
                                        @error('address')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="country">
                                            <h6>Country</h6>
                                        </label>
                                        <input type="text" class="form-control" name="country"
                                            placeholder="Enter country" value="{{ old('country') }}">
                                        @error('country')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="city">
                                            <h6>City</h6>
                                        </label>
                                        <input type="text" class="form-control" name="city" placeholder="Enter city"
                                            value="{{ old('city') }}">
                                        @error('city')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="state">
                                            <h6>State</h6>
                                        </label>
                                        <input type="text" class="form-control" name="state" placeholder="Enter state"
                                            value="{{ old('state') }}">
                                        @error('state')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="zip_code">
                                            <h6>Zip Code</h6>
                                        </label>
                                        <input type="number" class="form-control" name="zip_code"
                                            placeholder="Enter zip code" value="{{ old('zip_code') }}">
                                        @error('zip_code')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="email">
                                            <h6>Email</h6>
                                        </label>
                                        <input type="text" class="form-control" name="email" placeholder="Enter email"
                                            value="{{ old('email') }}">
                                        @error('email')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-4">
                                        <label for="phone">
                                            <h6>Phone</h6>
                                        </label>
                                        <input type="text" class="form-control" name="phone" placeholder="Enter phone"
                                            value="{{ old('phone') }}">
                                        @error('phone')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="primary_contact">
                                            <h6>Primary Contact Name</h6>
                                        </label>
                                        <input type="text" class="form-control" name="primary_contact"
                                            placeholder="Enter primary contact" value="{{ old('primary_contact') }}">
                                        @error('primary_contact')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="email">
                                            <h6>Primary Contact's Email</h6>
                                        </label>
                                        <input type="text" class="form-control" name="primary_contact_email"
                                            placeholder="Enter primary contact's email"
                                            value="{{ old('primary_contact_email') }}">
                                        @error('primary_contact_email')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="title">
                                            <h6>Title</h6>
                                        </label>
                                        <input type="text" class="form-control" name="title"
                                            placeholder="Enter title" value="{{ old('title') }}">
                                        @error('title')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="cell_phone">
                                            <h6>Cell Phone</h6>
                                        </label>
                                        <input type="text" class="form-control" name="cell_phone"
                                            placeholder="Enter cell phone" value="{{ old('cell_phone') }}">
                                        @error('cell_phone')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="rate">
                                            <h6>Standard Rate</h6>
                                        </label>
                                        <input type="number" class="form-control" name="std_rate"
                                            placeholder="Enter rate" value="{{ old('rate') }}">
                                        @error('rate')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="rate">
                                            <h6>Emergency Rate</h6>
                                        </label>
                                        <input type="text" class="form-control" name="em_rate"
                                            placeholder="Enter emergency rate">
                                        @error('rate')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="rate">
                                            <h6>Overtime Rate</h6>
                                        </label>
                                        <input type="text" class="form-control" name="ot_rate"
                                            placeholder="Enter overtime rate">
                                        @error('rate')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="rate">
                                            <h6>SH Rate</h6>
                                        </label>
                                        <input type="text" class="form-control" name="sh_rate"
                                            placeholder="Enter SH rate">
                                        @error('rate')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="radius">
                                            <h6>Radius</h6>
                                        </label>
                                        <input type="number" class="form-control" name="radius"
                                            placeholder="Enter radius" value="{{ old('radius') }}">
                                        @error('radius')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="travel_fee">
                                            <h6>Travel Fee</h6>
                                        </label>
                                        <input type="number" class="form-control" name="travel_fee"
                                            placeholder="Enter travel fee" value="{{ old('travel_fee') }}">
                                        @error('travel_fee')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="travel_fee">
                                            <h6>C WorkOrder Count</h6>
                                        </label>
                                        <input type="number" class="form-control" name="c_wo_ct"
                                            placeholder="Enter value" value="{{ old('c_wo_ct') }}">
                                        @error('travel_fee')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="status">
                                            <h6>Status</h6>
                                        </label>
                                        <select name="status" class="form-control">
                                            <option value="">Select Status</option>
                                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                            <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>
                                                Pending</option>
                                        </select>
                                        @error('status')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="coi_expire_date">
                                            <h6>COI Expiration Date</h6>
                                        </label>
                                        <input type="date" class="form-control" name="coi_expire_date"
                                            value="{{ old('coi_expire_date') }}">
                                        @error('coi_expire_date')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="coi_file">
                                            <h6>COI Attachment</h6>
                                        </label>
                                        <input type="file" class="form-control" name="coi_file">
                                        @error('coi_file')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="msa_expire_date">
                                            <h6>MSA Expiration Date</h6>
                                        </label>
                                        <input type="date" class="form-control" name="msa_expire_date"
                                            value="{{ old('msa_expire_date') }}">
                                        @error('msa_expire_date')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="msa_file">
                                            <h6>MSA Attachment</h6>
                                        </label>
                                        <input type="file" class="form-control" name="msa_file">
                                        @error('msa_file')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="nda">
                                            <h6>NDA</h6>
                                        </label>
                                        <select name="nda" class="form-control">
                                            <option value="">Select NDA</option>
                                            <option value="Yes" {{ old('nda') == 'Yes' ? 'selected' : '' }}>Yes
                                            </option>
                                            <option value="No" {{ old('nda') == 'No' ? 'selected' : '' }}>No</option>
                                        </select>
                                        @error('nda')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="nda_file">
                                            <h6>NDA Attachment</h6>
                                        </label>
                                        <input type="file" class="form-control" name="nda_file">
                                        @error('nda_file')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="terms">
                                            <h6>Terms</h6>
                                        </label>
                                        <select name="terms" class="form-control">
                                            <option value="">Select Terms</option>
                                            <option value="30" {{ old('terms') == '30' ? 'selected' : '' }}>30
                                            </option>
                                            <option value="45" {{ old('terms') == '45' ? 'selected' : '' }}>45
                                            </option>
                                            <option value="60" {{ old('terms') == '60' ? 'selected' : '' }}>60
                                            </option>
                                            <option value="90" {{ old('terms') == '90' ? 'selected' : '' }}>90
                                            </option>
                                        </select>
                                        @error('terms')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="preference">
                                            <h6>Preferred?</h6>
                                        </label>
                                        <select name="preference" class="form-control">
                                            <option value="Yes" {{ old('preference') == 'Yes' ? 'selected' : '' }}>Yes
                                            </option>
                                            <option selected value="No"
                                                {{ old('preference') == 'No' ? 'selected' : '' }}>No</option>
                                        </select>
                                        @error('preference')
                                            <span style="color:red; font-size:14px">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    {{-- <div class="form-group col-6">
                                    <label for="">Search Address</label>
                                    <input type="text" class="form-control" placeholder="Please enter address to get coordinates" id="address-input">
                                    <span id="responed-address"></span>
                                </div>
                                <div class="form-group col-3">
                                    <button type="button" class="btn btn-secondary" id="coordinate-btn" style="margin-top: 32px">Get Co-ordinate</button>
                                </div>
                                <div class="form-group col-3">
                                    <div class="loading d-none" id="loader">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </div>
                                <div class="form-group col-3">
                                    <label for="Latitude">Latitude</label>
                                    <input type="text" class="form-control" id="latitude" readonly>
                                </div>
                                <div class="form-group col-3">
                                    <label for="Longitude">Longitude</label>
                                    <input type="text" class="form-control" id="longitude" readonly>
                                </div> --}}
                                    {{-- <input type="hidden" id="SearchBox"> --}}
                                    <div class="form-group col-12 custom-form-group">
                                        <label for="skills" class="custom-label">
                                            <h6 class="custom-label-text">Skill Sets</h6>
                                        </label>
                                        <p class="text-danger font-italic custom-description">For multiple select, press
                                            Ctrl and click together</p>
                                        <div class="input-group">
                                            <select multiple name="skill_id[]" id="skills"
                                                class=" form-control  custom-select" style="height: 200px;">
                                            </select>
                                            @error('skill_id')
                                                <span style="color: red; font-size: 14px"
                                                    class="custom-error">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-check-circle"></i> Submit
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--add skill Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content ">
                    <div class="modal-header bg-gray d-flex justify-content-between">
                        <h5 class="modal-title text-white" id="staticBackdropLabel">Add Skills</h5>
                        <button type="button" class="btn-close" id="close-modal" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-dark">
                        <div class="form-row">
                            <label for="">Skill Name</label>
                            <input type="text" class="form-control" name="skill_name"
                                placeholder="Enter skill name...." id="skill_name">
                            <span style="color:red; font-size:14px" id="errors-container"></span>
                            <span style="color:rgb(21, 198, 110); font-size:16px; font-weight:bold"
                                id="success-container"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gray text-white btn-block" id="save">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            loadSkill();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //add skill ajax code start
            $(document).on('click', '#save', function() {
                let errorsContainer = $('#errors-container');
                let skill = $('#skill_name').val();
                let showToast = true;
                $.ajax({
                    type: "POST",
                    url: "{{ route('technician.skills.store') }}",
                    data: {
                        'skill_name': skill,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.message) {
                            $('#errors-container').empty();
                            $('#success-container').text(response.message).fadeIn();
                            setTimeout(function() {
                                $('#success-container').fadeOut();
                            }, 3000);
                        }
                        errorsContainer.empty();
                    },
                    error: function(response) {
                        if (response.status == 422) {
                            errors = response.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                errorsContainer.text(value);
                            });
                        }
                    }
                });
            });

            $(document).on('click', '#close-modal', function() {
                $('#success-container').empty();
                $('#errors-container').empty();
                $('#skill_name').val('');
                loadSkill();
            });

            function loadSkill() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('technician.all.skill') }}",
                    dataType: "JSON",
                    success: function(res) {
                        let html = '';
                        $.each(res.skills, function(key, value) {
                            // Add two icons using <i> elements from Font Awesome
                            html += '<option class="skill-option" value="' + value.id + '"> ' +
                                value.skill_name + '</option>';
                        });
                        $('#skills').html(html);
                    }
                });
            }
            // get latest skill on registration ftech registration form
            $(document).on('click', '#close-modal', function() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('technician.get.skills') }}",
                    dataType: "JSON",
                    success: function(res) {

                        $.each(res.skills, function(key, value) {
                            html += '<option  value="' + value.id + '">' + value
                                .skill_name + '</option>';
                        });

                        $('#skills').html(html);
                    }

                });
            });
        });
    </script>

    {{-- <script>


$('#coordinate-btn').on('click', function () {
    const address = $('#address-input').val();

    if (address.length > 0) {
        $('#loader').removeClass('d-none');
        $.ajax({
            url: "{{ route('technician.coordinate.get') }}",
            method: 'GET',
            data: { address: address },
            success: function (response) {
                console.log(response);
                const firstObject = response[0];
                $('#latitude').val(firstObject.lat);
                $('#longitude').val(firstObject.lon);
                $('#responed-address').text(firstObject.display_name);
                $('#loader').addClass('d-none');
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }
});

</script> --}}
@endsection
