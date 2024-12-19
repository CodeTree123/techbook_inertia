<div class="content-wrapper" style="background-color: white;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
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
                                @foreach($data as $check)
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
                </div>
            </div>
        </div>
    </div>
</div>