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
                            <th>@lang('Name')</th>
                            <th>@lang('Subject')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($templates as $template)
                            <tr>
                                <td>{{ __($template->name) }}</td>
                                <td>{{ __($template->subj) }}</td>
                                <td>
                                    <a href="{{ route('admin.setting.notification.template.edit', $template->id) }}"
                                        class="btn btn-sm btn-outline-primary ms-1 editGatewayBtn">
                                        <i class="fa fa-pencil"></i> @lang('Edit')
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
        </div><!-- card end -->
    </div>
 </div>
</div>
@endsection
