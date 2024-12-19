@extends('admin.layoutsNew.app')
@section('content')
<div class="content-wrapper" style="background-color: white;">
    @include('admin.includeNew.breadcrumb')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('technician.pdf.in.out')}}" method="get">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <input class="form-control" name="date" placeholder="Date">
                                    </div>
                                    <div class="col-md-2">
                                        <input class="form-control" name="company_name" placeholder="Company Name">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-success">Download Pdf</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table class="table">
                            <thead>
                                <th>Date</th>
                                <th>Company Name</th>
                                <th>Technician Name</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Total Hours</th>
                                <th>Time Zone</th>
                            </thead>
                            <tbody>
                                @foreach($checkInOut as $check)
                                <tr>
                                    <td>{{$check->date}}</td>
                                    <td>{{$check->company_name}}</td>
                                    <td>{{$check->tech_name}}</td>
                                    <td>{{$check->check_in}}</td>
                                    <td>{{$check->check_out}}</td>
                                    <td>{{$check->total_hours}}</td>
                                    <td>{{$check->time_zone}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($checkInOut->hasPages())
                    <div class="card-footer py-4">
                        <p class="text-italic">Click below to see next page</p> @php echo paginateLinks($checkInOut) @endphp
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('breadcrumb-plugins')
<p class="font-weight-light p-2 m-2">Do Search by Technician Company Name, Tech Name, Date or Timezone :</p>
<x-search-form dateSearch='yes' />
@endpush