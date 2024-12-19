@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
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
                                <th>@lang('Gateway')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($gateways as $gateway)
                                <tr>
                                    <td>{{__($gateway->name)}}</td>

                                    <td>
                                        @php
                                            echo $gateway->statusBadge
                                        @endphp
                                    </td>
                                    <td>
                                        <div class="button--group">
                                            <a href="{{ route('admin.gateway.manual.edit', $gateway->alias) }}" class="btn btn-sm btn-outline-primary editGatewayBtn">
                                                <i class="fa fa-pencil"></i> @lang('Edit')
                                            </a>

                                            @if($gateway->status == Status::DISABLE)
                                                <button class="btn btn-sm btn-outline-success confirmationBtn" data-question="@lang('Are you sure to enable this gateway?')" data-action="{{ route('admin.gateway.manual.status',$gateway->id) }}">
                                                    <i class="fa fa-eye"></i> @lang('Enable')
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline-danger confirmationBtn" data-question="@lang('Are you sure to disable this gateway?')" data-action="{{ route('admin.gateway.manual.status',$gateway->id) }}">
                                                    <i class="fa fa-eye-slash"></i> @lang('Disable')
                                                </button>
                                            @endif
                                        </div>
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
            </div><!-- card end -->
        </div>
    </div>
</div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <div class="input-group w-auto search-form">
        <input type="text" name="search_table" class="form-control bg-white" placeholder="@lang('Search')...">
        <button class="btn btn-primary input-group-text"><i class="fa fa-search"></i></button>
    </div>
    <a class="btn btn-outline-primary" href="{{ route('admin.gateway.manual.create') }}"><i class="fa fa-plus"></i>@lang('Add New')</a>
@endpush
