@extends('admin.layoutsNew.app')
@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
@endsection
@section('content')
@php
use App\Models\Customer;
$customers = Customer::all();
@endphp


<link rel="stylesheet" href="{{ asset('assetsNew/main_css/customer/create.css') }}">
<div class="content-wrapper" style="background-color: white;">
    <!-- Content Header (Page header) -->
    @include('admin.includeNew.breadcrumb')
    <div class="container-fluid text-dark">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-mb-12 col-md-12 d-flex justify-content-between">
                                <h3></h3>
                                <div class="justify-content-between">
                                    <a class="btn btn-primary" href="{{ route('customer.index') }}"><i
                                            class="fas fa-list"></i> Customer List</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-4">
                                    <label for="company_name">
                                        <h6>Company name</h6>
                                    </label>
                                    <input type="text" class="form-control" name="company_name"
                                        placeholder="Enter company name" value="{{ old('company_name') }}">
                                    @error('company_name')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="customer_type">
                                        <h6>Customer type</h6>
                                    </label>
                                    <select name="customer_type" class="form-control">
                                        <option value="">Select customer type</option>
                                        <option value="Customer"
                                            {{ old('customer_type') == 'Customer' ? 'selected' : '' }}>Customer</option>
                                        <option value="Prospecting"
                                            {{ old('customer_type') == 'Prospecting' ? 'selected' : '' }}>Prospecting
                                        </option>
                                        <option value="Etc" {{ old('customer_type') == 'Etc' ? 'selected' : '' }}>Etc
                                        </option>
                                    </select>
                                    @error('customer_type')
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
                                    <input type="number" class="form-control" name="phone" placeholder="Enter phone"
                                        value="{{ old('phone') }}">
                                    @error('phone')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="team">
                                        <h6>Team</h6>
                                    </label>
                                    <select name="team" class="form-control" id="">
                                        <option value="">Select Team</option>
                                        <option value="Blue Team" {{ old('team') == 'Blue Team' ? 'selected' : '' }}>
                                            Blue Team</option>
                                        <option value="Red Team" {{ old('team') == 'Red Team' ? 'selected' : '' }}>Red
                                            Team</option>
                                        <option value="Etc" {{ old('team') == 'Etc' ? 'selected' : '' }}>Etc
                                        </option>
                                    </select>
                                    @error('team')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="billing_term">
                                        <h6>Billing term</h6>
                                    </label>
                                    <select name="billing_term" class="form-control">
                                        <option value="">Select billing term</option>

                                        <option value="Upon Receipt" {{ old('billing_term') == 'Upon Receipt' ? 'selected' : '' }}>
                                            Upon Receipt</option>
                                        <option value="NET15" {{ old('billing_term') == 'NET15' ? 'selected' : '' }}>
                                            NET15</option>
                                        <option value="NET30" {{ old('billing_term') == 'NET30' ? 'selected' : '' }}>
                                            NET30</option>
                                        <option value="NET45" {{ old('billing_term') == 'NET45' ? 'selected' : '' }}>
                                            NET45</option>
                                        <option value="Etc" {{ old('billing_term') == 'Etc' ? 'selected' : '' }}>
                                            Etc</option>
                                    </select>
                                    @error('billing_term')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="sales_person">
                                        <h6>Sales person assigned</h6>
                                    </label>
                                    <input type="text" class="form-control" name="sales_person"
                                        placeholder="Enter Sales person assign" value="{{ old('sales_person') }}">
                                    @error('sales_person')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="project_manager">
                                        <h6>Project manager assigned</h6>
                                    </label>
                                    <input type="text" class="form-control" name="project_manager"
                                        placeholder="Enter Project manager assign" value="{{ old('project_manager') }}">
                                    @error('project_manager')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="shade col-md-12" style="background-color: rgba(175, 225, 175, 0.5); color: black; text-align: center; padding: 10px">
                                    Rates
                                </div>

                                <div class="form-group col-4">
                                    <label for="s_rate_f">
                                        <h6>Standard rate first hour</h6>
                                    </label>
                                    <input type="number" class="form-control" name="s_rate_f"
                                        placeholder="Standard rate first hour" value="{{ old('s_rate_f') }}">
                                    @error('s_rate_f')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="s_rate_a">
                                        <h6>Standard rate additional hour</h6>
                                    </label>
                                    <input type="number" class="form-control" name="s_rate_a"
                                        placeholder="Standard rate additional hour" value="{{ old('s_rate_a') }}">
                                    @error('s_rate_a')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="e_rate_f">
                                        <h6>Emergency rate first hour</h6>
                                    </label>
                                    <input type="number" class="form-control" name="e_rate_f"
                                        placeholder="Enter Emergency rate first hour" value="{{ old('e_rate_f') }}">
                                    @error('e_rate_f')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="e_rate_a">
                                        <h6>Emergency rate additional hour</h6>
                                    </label>
                                    <input type="number" class="form-control" name="e_rate_a"
                                        placeholder="Enter Emergency rate additional hour" value="{{ old('e_rate_a') }}">
                                    @error('e_rate_a')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="w_rate_f">
                                        <h6>Weekend rates first hour</h6>
                                    </label>
                                    <input type="number" class="form-control" name="w_rate_f"
                                        placeholder="Enter weekend rate first hour" value="{{ old('w_rate_f') }}">
                                    @error('w_rate_f')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="w_rate_a">
                                        <h6>Weekend rates additional hour</h6>
                                    </label>
                                    <input type="number" class="form-control" name="w_rate_a"
                                        placeholder="Enter weekend rate additional hour" value="{{ old('w_rate_a') }}">
                                    @error('w_rate_a')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="sh_rate_f">
                                        <h6>Sunday & Holiday rates first hour</h6>
                                    </label>
                                    <input type="number" class="form-control" name="sh_rate_f"
                                        placeholder="Enter sunday & holiday rate first hour" value="{{ old('sh_rate_f') }}">
                                    @error('sh_rate_f')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="sh_rate_a">
                                        <h6>Sunday & Holiday rates additional hour</h6>
                                    </label>
                                    <input type="number" class="form-control" name="sh_rate_a"
                                        placeholder="Enter Sunday & Holiday rate additional hour" value="{{ old('sh_rate_a') }}">
                                    @error('sh_rate_a')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="travel">
                                        <h6>Travel</h6>
                                    </label>
                                    <input type="number" class="form-control" name="travel"
                                        placeholder="Enter travel" value="{{ old('travel') }}">
                                    @error('travel')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="shade col-md-12" style="background-color: rgba(175, 225, 175, 0.5); color: black; text-align: center; padding: 10px">
                                    Billing Address
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
                                    <input id="state" type="text" class="form-control" name="state"
                                        placeholder="Enter state" value="{{ old('state') }}">
                                    @error('state')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="zip_code">
                                        <h6>Zip Code</h6>
                                    </label>
                                    <input type="text" class="form-control" name="zip_code" placeholder="Enter zip"
                                        value="{{ old('zip_code') }}">
                                    @error('zip_code')
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

                                <div class="shade col-md-12" style="background-color: rgba(175, 225, 175, 0.5); color: black; text-align: center; padding: 10px">
                                    Head office Address
                                </div>

                                <div class="form-group col-4">
                                    <label for="address">
                                        <h6>Address</h6>
                                    </label>
                                    <input type="text" class="form-control" name="h_address"
                                        placeholder="Enter address" value="{{ old('h_address') }}">
                                    @error('address')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-4">
                                    <label for="city">
                                        <h6>City</h6>
                                    </label>
                                    <input type="text" class="form-control" name="h_city" placeholder="Enter city"
                                        value="{{ old('h_city') }}">
                                    @error('city')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="state">
                                        <h6>State</h6>
                                    </label>
                                    <input id="state" type="text" class="form-control" name="h_state"
                                        placeholder="Enter state" value="{{ old('h_state') }}">
                                    @error('state')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="zip_code">
                                        <h6>Zip Code</h6>
                                    </label>
                                    <input type="text" class="form-control" name="h_zip_code" placeholder="Enter zip"
                                        value="{{ old('h_zip_code') }}">
                                    @error('zip_code')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="country">
                                        <h6>Country</h6>
                                    </label>
                                    <input type="text" class="form-control" name="h_country"
                                        placeholder="Enter country" value="{{ old('h_country') }}">
                                    @error('country')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="shade col-md-12" style="background-color: rgba(175, 225, 175, 0.5); color: black; text-align: center; padding: 10px">
                                    Type of equipments
                                </div>
                                <div class="form-group col-4">
                                    <label for="type_phone">
                                        <h6>Type of phone system</h6>
                                    </label>
                                    <input type="text" class="form-control" name="type_phone"
                                        placeholder="Enter type of phone" value="{{ old('type_phone') }}">
                                    @error('type_phone')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="type_wireless">
                                        <h6>Type of wireless</h6>
                                    </label>
                                    <input type="text" class="form-control" name="type_wireless"
                                        placeholder="Enter type of wireless" value="{{ old('type_wireless') }}">
                                    @error('type_wireless')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="type_cctv">
                                        <h6>Type of CCTV</h6>
                                    </label>
                                    <input type="text" class="form-control" name="type_cctv"
                                        placeholder="Enter type of CCTV" value="{{ old('type_cctv') }}">
                                    @error('type_cctv')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="type_pos">
                                        <h6>Type of POS</h6>
                                    </label>
                                    <input type="text" class="form-control" name="type_pos"
                                        placeholder="Enter type of POS" value="{{ old('type_pos') }}">
                                    @error('type_pos')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-12">
                                    <button type="submit" class="btn btn-primary btn-block"><i
                                            class="fas fa-check"></i> Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection