@extends('admin.layoutsNew.app')
@section('content')
<link rel="stylesheet" href="{{ asset('assetsNew/main_css/customer/edit_customer.css') }}">
<div class="content-wrapper" style="background-color: white;">
    <!-- Content Header (Page header) -->
    @include('admin.includeNew.breadcrumb')
    <!-- /.content-header -->
    <div class="container-fluid text-dark">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-between">
                                <h4><span class="badge bg-success">{{ $edit->company_name }}</span></h4>
                                <a href="{{ route('customer.index') }}" class="btn btn-primary"><i
                                        class="fas fa-list"></i> Customer List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-dark">
                        <form action="{{ url('customer/edit/post') }}/{{ $edit->id }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-row">
                                <div class="form-group col-4">
                                    <label for="company_name">
                                        <h6>Company name</h6>
                                    </label>
                                    <input type="text" class="form-control" name="company_name"
                                        placeholder="Enter company name" value="{{ $edit->company_name }}">
                                    @error('company_name')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="customer_type">
                                        <h6>Customer type</h6>
                                    </label>
                                    <select name="customer_type" class="form-control">
                                        <option value="">Select type</option>
                                        <option value="Customer"
                                            {{ $edit->customer_type == 'Customer' ? 'selected' : '' }}>Customer</option>
                                        <option value="Prospecting"
                                            {{ $edit->customer_type == 'Prospecting' ? 'selected' : '' }}>Prospecting
                                        </option>
                                        <option value="Etc" {{ $edit->customer_type == 'Etc' ? 'selected' : '' }}>
                                            Etc</option>
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
                                        value="{{ $edit->email }}">
                                    @error('email')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="phone">
                                        <h6>Phone</h6>
                                    </label>
                                    <input type="number" class="form-control" name="phone" placeholder="Enter phone"
                                        value="{{ $edit->phone }}">
                                    @error('phone')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="billing_term">
                                        <h6>Billing term</h6>
                                    </label>
                                    <select name="billing_term" class="form-control">
                                        <option value="">Select billing term</option>
                                        <option value="Upon Receipt" {{ $edit->billing_term == 'Upon Receipt' ? 'selected' : '' }}>
                                            Upon Receipt</option>
                                        <option value="NET15" {{ $edit->billing_term == 'NET15' ? 'selected' : '' }}>
                                            NET15</option>
                                        <option value="NET30" {{ $edit->billing_term == 'NET30' ? 'selected' : '' }}>
                                            NET30</option>
                                        <option value="NET45" {{ $edit->billing_term == 'NET45' ? 'selected' : '' }}>
                                            NET45</option>
                                        <option value="Etc" {{ $edit->billing_term == 'Etc' ? 'selected' : '' }}>
                                            Etc</option>
                                    </select>
                                    @error('billing_term')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="team">
                                        <h6>Team</h6>
                                    </label>
                                    <select name="team" class="form-control">
                                        <option value="">Select team</option>
                                        <option value="Blue Team" {{ $edit->team == 'Blue Team' ? 'selected' : '' }}>
                                            Blue Team</option>
                                        <option value="Red Team" {{ $edit->team == 'Red Team' ? 'selected' : '' }}>Red
                                            Team</option>
                                        <option value="Etc" {{ $edit->team == 'Etc' ? 'selected' : '' }}>Etc
                                        </option>
                                    </select>
                                    @error('team')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="sales_person">
                                        <h6>Sales person assigned</h6>
                                    </label>
                                    <input type="text" class="form-control" name="sales_person"
                                        placeholder="Sales person assign" value="{{ $edit->sales_person }}">
                                    @error('sales_person')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="project_manager">
                                        <h6>Project manager assigned</h6>
                                    </label>
                                    <input type="text" class="form-control" name="project_manager"
                                        placeholder="Project Manager assign" value="{{ $edit->project_manager }}">
                                    @error('project_manager')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="shade col-md-12" style="background-color: rgba(175, 225, 175, 0.5); color: black; text-align: center; padding: 10px">
                                   Rates
                                </div>
                                <div class="form-group col-4">
                                    <label for="s_rate_f">
                                        <h6>Standard rate first two hour</h6>
                                    </label>
                                    <input type="number" class="form-control" name="s_rate_f"
                                        placeholder="Standard Rate first two hour" value="{{ $edit->s_rate_f }}">
                                    @error('s_rate_f')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="s_rate_a">
                                        <h6>Standard rate additional hour</h6>
                                    </label>
                                    <input type="number" class="form-control" name="s_rate_a"
                                        placeholder="Standard Rate Additional hour" value="{{ $edit->s_rate_a }}">
                                    @error('s_rate_a')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="e_rate_f">
                                        <h6>Emergency rate first two hour</h6>
                                    </label>
                                    <input type="number" class="form-control" name="e_rate_f"
                                        placeholder="Enter Emergency rate first two hour" value="{{ $edit->e_rate_f }}">
                                    @error('e_rate_f')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="e_rate_a">
                                        <h6>Emergency rate additional hour</h6>
                                    </label>
                                    <input type="number" class="form-control" name="e_rate_a"
                                        placeholder="Enter emergency rate additional hour" value="{{ $edit->e_rate_a }}">
                                    @error('e_rate_a')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="w_rate_f">
                                        <h6>Weekend rates first two hour</h6>
                                    </label>
                                    <input type="number" class="form-control" name="w_rate_f"
                                        placeholder="Enter weekend rate first two hour" value="{{ $edit->w_rate_f }}">
                                    @error('w_rate_f')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="w_rate_a">
                                        <h6>Weekend rates additional hour</h6>
                                    </label>
                                    <input type="number" class="form-control" name="w_rate_a"
                                        placeholder="Enter Weekend rate Additional Hour" value="{{ $edit->w_rate_a }}">
                                    @error('w_rate_a')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="sh_rate_f">
                                        <h6>Sunday & Holiday rates first two hour</h6>
                                    </label>
                                    <input type="number" class="form-control" name="sh_rate_f"
                                        placeholder="Enter Sunday & Holiday rate first two hour" value="{{ $edit->sh_rate_f }}">
                                    @error('sh_rate_f')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="sh_rate_a">
                                        <h6>Sunday & Holiday rates additional hour</h6>
                                    </label>
                                    <input type="number" class="form-control" name="sh_rate_a"
                                        placeholder="Enter Sunday & Holiday rate Additional hour" value="{{ $edit->sh_rate_a }}">
                                    @error('sh_rate_a')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="travel">
                                        <h6>Travel</h6>
                                    </label>
                                    <input type="number" class="form-control" name="travel"
                                        placeholder="Enter travel" value="{{ $edit->travel }}">
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
                                        placeholder="Enter address" value="{{ @$edit->address->address }}">
                                    @error('address')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="city">
                                        <h6>City</h6>
                                    </label>
                                    <input type="text" class="form-control" name="city" placeholder="Enter city"
                                        value="{{ @$edit->address->city }}">
                                    @error('city')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-4">
                                    <label for="state">
                                        <h6>State</h6>
                                    </label>
                                    <input type="text" class="form-control" name="state" placeholder="Enter state"
                                        value="{{ @$edit->address->state }}">
                                    @error('state')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="zip_code">
                                        <h6>Zip Code</h6>
                                    </label>
                                    <input type="text" class="form-control" name="zip_code" placeholder="Enter zip"
                                        value="{{ @$edit->address->zip_code }}">
                                    @error('zip_code')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="country">
                                        <h6>Country</h6>
                                    </label>
                                    <input type="text" class="form-control" name="country"
                                        placeholder="Enter country" value="{{ @$edit->address->country }}">
                                    @error('country')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="shade col-md-12" style="background-color: rgba(175, 225, 175, 0.5); color: black; text-align: center; padding: 10px">
                                   Head Office Address
                                </div>
                                <div class="form-group col-4">
                                    <label for="address">
                                        <h6>Address</h6>
                                    </label>
                                    <input type="text" class="form-control" name="h_address"
                                        placeholder="Enter address" value="{{ @$edit->address->h_address }}">
                                    @error('address')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="city">
                                        <h6>City</h6>
                                    </label>
                                    <input type="text" class="form-control" name="h_city" placeholder="Enter city"
                                        value="{{ @$edit->address->h_city }}">
                                    @error('city')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-4">
                                    <label for="state">
                                        <h6>State</h6>
                                    </label>
                                    <input type="text" class="form-control" name="h_state" placeholder="Enter state"
                                        value="{{ @$edit->address->h_state }}">
                                    @error('state')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="zip_code">
                                        <h6>Zip Code</h6>
                                    </label>
                                    <input type="text" class="form-control" name="h_zip_code" placeholder="Enter zip"
                                        value="{{ @$edit->address->h_zip_code }}">
                                    @error('zip_code')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="country">
                                        <h6>Country</h6>
                                    </label>
                                    <input type="text" class="form-control" name="h_country"
                                        placeholder="Enter country" value="{{ @$edit->address->h_country }}">
                                    @error('country')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="shade col-md-12" style="background-color: rgba(175, 225, 175, 0.5); color: black; text-align: center; padding: 10px">
                                   Type of equipments
                                </div>
                                <div class="form-group col-4">
                                    <label for="type_phone">
                                        <h6>Type of phone</h6>
                                    </label>
                                    <input type="text" class="form-control" name="type_phone"
                                        placeholder="Type Of Phone" value="{{ $edit->type_phone }}">
                                    @error('type_phone')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="type_wireless">
                                        <h6>Type of wireless</h6>
                                    </label>
                                    <input type="text" class="form-control" name="type_wireless"
                                        placeholder="Type Of Wireless" value="{{ $edit->type_wireless }}">
                                    @error('type_wireless')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="type_cctv">
                                        <h6>Type of CCTV</h6>
                                    </label>
                                    <input type="text" class="form-control" name="type_cctv"
                                        placeholder="Type Of CCTV" value="{{ $edit->type_cctv }}">
                                    @error('type_cctv')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label for="type_pos">
                                        <h6>Type of POS</h6>
                                    </label>
                                    <input type="text" class="form-control" name="type_pos"
                                        placeholder="Type Of POS" value="{{ $edit->type_pos }}">
                                    @error('type_pos')
                                    <span style="color:red; font-size:14px">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-12">
                                    <button type="submit" class="btn btn-primary btn-block">Update</button>
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