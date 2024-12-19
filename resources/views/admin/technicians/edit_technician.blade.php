@extends('admin.layoutsNew.app')
@section('content')
@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endsection
<link rel="stylesheet" href="{{ asset('assetsNew/main_css/technician/edit_technician.css') }}">


<div class="content-wrapper" style="background-color: white;">
    <!-- Content Header (Page header) -->
    @include('admin.includeNew.breadcrumb')
    <!-- /.content-header -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-dark">
                        <form action="{{ url('technician/tech/edit/post') }}/{{ $edit->id }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-row">
                                <div class="form-group col-4">
                                    <label for="company_name">
                                        <h6>Company Name</h6>
                                    </label>
                                    <input type="text" class="form-control" name="company_name"
                                        value="{{ $edit->company_name }}" placeholder="Enter company name">
                                    @error('company_name')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="address">
                                        <h6>Address</h6>
                                    </label>
                                    <input type="text" class="form-control" name="address"
                                        value="{{ $edit->address_data->address }}" placeholder="Enter address">
                                    @error('address')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="country">
                                        <h6>Country</h6>
                                    </label>
                                    <input type="text" class="form-control" name="country"
                                        value="{{ $edit->address_data->country }}" placeholder="Enter country">
                                    @error('country')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-4">
                                    <label for="city">
                                        <h6>City</h6>
                                    </label>
                                    <input type="text" class="form-control" name="city"
                                        value="{{ $edit->address_data->city }}" placeholder="Enter city">
                                    @error('city')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="state">
                                        <h6>State</h6>
                                    </label>
                                    <input type="text" class="form-control" name="state"
                                        value="{{ $edit->address_data->state }}" placeholder="Enter state">
                                    @error('state')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="zip_code">
                                        <h6>Zip Code</h6>
                                    </label>
                                    <input type="text" class="form-control" name="zip_code"
                                        value="{{ $edit->address_data->zip_code }}" placeholder="Enter zip code">
                                    @error('zip_code')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="email">
                                        <h6>Email</h6>
                                    </label>
                                    <input type="text" class="form-control" name="email"
                                        value="{{ $edit->email }}" placeholder="Enter email">
                                    @error('email')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-4">
                                    <label for="phone">
                                        <h6>Phone</h6>
                                    </label>
                                    <input type="text" class="form-control" name="phone"
                                        value="{{ $edit->phone }}" placeholder="Enter phone">
                                    @error('phone')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="primary_contact">
                                        <h6>Primary Contact Name</h6>
                                    </label>
                                    <input type="text" class="form-control" name="primary_contact"
                                        value="{{ $edit->primary_contact }}" placeholder="Enter primary contact name">
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
                                        value="{{ $edit->primary_contact_email }}">
                                    @error('primary_contact_email')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="title">
                                        <h6>Title</h6>
                                    </label>
                                    <input type="text" class="form-control" name="title"
                                        value="{{ $edit->title }}" placeholder="Enter title">
                                    @error('title')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="cell_phone">
                                        <h6>Cell Phone</h6>
                                    </label>
                                    <input type="text" class="form-control" name="cell_phone"
                                        value="{{ $edit->cell_phone }}" placeholder="Enter cell phone">
                                    @error('cell_phone')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="rate">
                                        <h6>Standard Rate</h6>
                                    </label>
                                    <input type="text" class="form-control" name="std_rate"
                                        value="{{ $edit->rate['STD'] ?? '' }}"
                                        placeholder="Enter standard rate">
                                    @error('rate')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="rate">
                                        <h6>Emergency Rate</h6>
                                    </label>
                                    <input type="text" class="form-control" name="em_rate"
                                        value="{{ $edit->rate['EM'] ?? '' }}"
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
                                        value="{{ $edit->rate['OT'] ?? '' }}"
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
                                        value="{{ $edit->rate['SH'] ?? '' }}" placeholder="Enter SH rate">
                                    @error('rate')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="radius">
                                        <h6>Radius</h6>
                                    </label>
                                    <input type="text" class="form-control" name="radius"
                                        value="{{ $edit->radius }}" placeholder="Enter radius">
                                    @error('radius')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="travel_fee">
                                        <h6>Travel Fee</h6>
                                    </label>
                                    <input type="text" class="form-control" name="travel_fee"
                                        value="{{ $edit->travel_fee }}" placeholder="Enter travel fee">
                                    @error('travel_fee')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="travel_fee">
                                        <h6>C WorkOrder Count</h6>
                                    </label>
                                    <input type="text" class="form-control" name="c_wo_ct"
                                        value="{{ $edit->c_wo_ct }}" placeholder="Enter Value">
                                    @error('c_wo_ct')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="travel_fee">
                                        <h6>WorkOrder Count</h6>
                                    </label>
                                    <input type="text" class="form-control" name="wo_ct"
                                        value="{{ $edit->wo_ct }}" placeholder="Enter Value">
                                    @error('wo_ct')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="status">
                                        <h6>Status</h6>
                                    </label>
                                    <select name="status" class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="Active" {{ $edit->status == 'Active' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="Inactive" {{ $edit->status == 'Inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                        <option value="Pending" {{ $edit->status == 'Pending' ? 'selected' : '' }}>
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
                                        value="{{ $edit->coi_expire_date }}">
                                    @error('coi_expire_date')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="nda">
                                        <h6>COI Pdf</h6>
                                    </label>
                                    <input type="file" class="form-control" name="coi_file"
                                        value="{{ $edit->coi_file }}" placeholder="Enter NDA">
                                    @error('coi_file')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                    <a href="{{ route('technician.viewPdf_coi', $edit->id) }}"
                                        target="_blank">{{ $edit->coi_file }}</a>
                                </div>
                                <div class="form-group col-4">
                                    <label for="msa_expire_date">
                                        <h6>MSA Expiration Date</h6>
                                    </label>
                                    <input type="date" class="form-control" name="msa_expire_date"
                                        value="{{ $edit->msa_expire_date }}">
                                    @error('msa_expire_date')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="nda">
                                        <h6>MSA Pdf</h6>
                                    </label>
                                    <input type="file" class="form-control" name="msa_file"
                                        value="{{ $edit->msa_file }}" placeholder="Enter NDA">
                                    @error('msa_file')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                    <a href="{{ route('technician.viewPdf_msa', $edit->id) }}"
                                        target="_blank">{{ $edit->msa_file }}</a>
                                </div>
                                <div class="form-group col-4">
                                    <label for="nda">
                                        <h6>NDA</h6>
                                    </label>
                                    <input type="text" class="form-control" name="nda"
                                        value="{{ $edit->nda }}" placeholder="Enter NDA">
                                    @error('nda')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="nda">
                                        <h6>NDA Pdf</h6>
                                    </label>
                                    <input type="file" class="form-control" name="nda_file"
                                        value="{{ $edit->nda_file }}" placeholder="Enter NDA">
                                    @error('nda_file')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                    <a href="{{ route('technician.viewPdf_nda', $edit->id) }}"
                                        target="_blank">{{ $edit->nda_file }}</a>
                                </div>
                                <div class="form-group col-4">
                                    <label for="terms">
                                        <h6>Terms</h6>
                                    </label>
                                    <select name="terms" class="form-control">
                                        <option value="">Select Terms</option>
                                        <option value="30" {{ $edit->terms == '30' ? 'selected' : '' }}>30
                                        </option>
                                        <option value="45" {{ $edit->terms == '45' ? 'selected' : '' }}>45
                                        </option>
                                        <option value="60" {{ $edit->terms == '60' ? 'selected' : '' }}>60
                                        </option>
                                        <option value="90" {{ $edit->terms == '90' ? 'selected' : '' }}>90
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
                                        <option value="Yes" {{ $edit->preference == 'Yes' ? 'selected' : '' }}>Yes
                                        </option>
                                        <option value="No" {{ $edit->preference == 'No' ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                    @error('preference')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-12">
                                    <label for="skills">
                                        <h6>Skill Sets</h6>
                                    </label>
                                    <p class="font-weight-light text-success font-italic">{{ $skill_sets_string }}</p>
                                    <select multiple name="skill_id[]" id="skills" class="form-control"
                                        style="height:200px;">
                                    </select>
                                    @error('skill_id')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-sync-alt"></i>
                                    Update
                                </button>
                            </div>
                        </form>
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


        $.ajax({
            type: "GET",
            url: "{{ route('technician.all.skill') }}",
            dataType: "JSON",
            success: function(res) {
                let html = '';
                $.each(res.skills, function(key, value) {
                    html += '<option class="skill-option" value="' + value.id + '"> ' +
                        value.skill_name + '</option>';
                });
                $('#skills').html(html);
            }
        });
    });
</script>
@endsection
