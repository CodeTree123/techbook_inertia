@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
 <div class="row justify-content-center">
    @if(request()->routeIs('admin.withdraw.log') || request()->routeIs('admin.withdraw.method') || request()->routeIs('admin.users.withdrawals') || request()->routeIs('admin.users.withdrawals.method'))
    
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            <a href="{{ route('admin.withdraw.approved') }}" class="item-link">
                            @lang('Approved Withdrawals')</a></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ __($general->cur_sym) }}{{ showAmount($successful) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-fw fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            <a href="{{ route('admin.withdraw.pending') }}" class="item-link">
                            @lang('Pending Withdrawals')</a></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ __($general->cur_sym) }}{{ showAmount($pending) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-fw fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            <a href="{{ route('admin.withdraw.rejected') }}" class="item-link">
                            @lang('Rejected Withdrawals')</a></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ __($general->cur_sym) }}{{ showAmount($rejected) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-fw fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
<div class="row justify-content-center">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{$pageTitle}}</h6>
        </div>
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>@lang('Gateway | Transaction')</th>
                                <th>@lang('Initiated')</th>
                                <th>@lang('User')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Conversion')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($withdrawals as $withdraw)
                            @php
                            $details = ($withdraw->withdraw_information != null) ? json_encode($withdraw->withdraw_information) : null;
                            @endphp
                            <tr>
                                <td>
                                    <span class="fw-bold"><a href="{{ appendQuery('method',@$withdraw->method->id) }}"> {{ __(@$withdraw->method->name) }}</a></span>
                                    <br>
                                    <small>{{ $withdraw->trx }}</small>
                                </td>
                                <td>
                                    {{ showDateTime($withdraw->created_at) }} <br>  {{ diffForHumans($withdraw->created_at) }}
                                </td>

                                <td>
                                    <span class="fw-bold">{{ $withdraw->user->fullname }}</span>
                                    <br>
                                    <span class="small"> <a href="{{ appendQuery('search',@$withdraw->user->username) }}"><span>@</span>{{ $withdraw->user->username }}</a> </span>
                                </td>


                                <td>
                                   {{ __($general->cur_sym) }}{{ showAmount($withdraw->amount ) }} - <span class="text-danger" title="@lang('charge')">{{ showAmount($withdraw->charge)}} </span>
                                    <br>
                                    <strong title="@lang('Amount after charge')">
                                    {{ showAmount($withdraw->amount-$withdraw->charge) }} {{ __($general->cur_text) }}
                                    </strong>

                                </td>

                                <td>
                                   1 {{ __($general->cur_text) }} =  {{ showAmount($withdraw->rate) }} {{ __($withdraw->currency) }}
                                    <br>
                                    <strong>{{ showAmount($withdraw->final_amount) }} {{ __($withdraw->currency) }}</strong>
                                </td>

                                <td>
                                    @php echo $withdraw->statusBadge @endphp
                                </td>
                                <td>
                                    <a href="{{ route('admin.withdraw.details', $withdraw->id) }}" class="btn btn-sm btn-outline-primary ms-1">
                                        <i class="fa fa-desktop"></i> @lang('Details')
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            @if ($withdrawals->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($withdrawals) }}
            </div>
            @endif
        </div><!-- card end -->
    </div>
 </div>
</div>

@endsection




@push('breadcrumb-plugins')
<x-search-form dateSearch='yes' />
@endpush
