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
                                <th>@lang('Email')</th>
                                <th>@lang('Subscribe At')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($subscribers as $subscriber)
                                <tr>
                                    <td>{{ $subscriber->email }}</td>
                                    <td>{{ showDateTime($subscriber->created_at) }}</td>
                                    <td>
                                        <button href="javascript:void(0)"
                                           class="btn btn-sm btn-outline--danger confirmationBtn"
                                           data-question="@lang('Are you sure to remove this subscriber?')"
                                           data-action="{{ route('admin.subscriber.remove',$subscriber->id) }}"
                                        >
                                            <i class="las la-trash"></i> @lang('Remove')
                                    </button>
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
                @if ($subscribers->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($subscribers) }}
                </div>
                @endif
            </div><!-- card end -->
        </div>


    </div>
</div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.subscriber.send.email') }}" class="btn btn-sm btn-outline-primary" ><i class="fas fa-paper-plane"></i>@lang('Send Email')</a>
@endpush
