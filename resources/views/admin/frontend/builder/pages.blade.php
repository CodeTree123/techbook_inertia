@extends('admin.layouts.app')
@section('content')
<div class= "container-fluid">
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
                                <th>@lang('Slug')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($pdata as $k => $data)
                                <tr>
                                    <td>{{ __($data->name) }}</td>
                                    <td>{{ __($data->slug) }}</td>
                                    <td>
                                        <div class="button--group">
                                            <a href="{{ route('admin.frontend.manage.section', $data->id) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-pen"></i> @lang('Edit')</a>
                                            @if($data->is_default == Status::NO)
                                                <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                                data-action="{{ route('admin.frontend.manage.pages.delete',$data->id) }}"
                                                data-question="@lang('Are you sure to remove this page?')">
                                                    <i class="fas fa-trash"></i> @lang('Delete')
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

    {{-- Add METHOD MODAL --}}
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white"> @lang('Add New Page')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.frontend.manage.pages.save')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label> @lang('Page Name')</label>
                            <input type="text" class="form-control" name="name" value="{{old('name')}}" required>
                        </div>
                        <div class="form-group">
                            <label> @lang('Slug')</label>
                            <input type="text" class="form-control" name="slug" value="{{old('slug')}}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn btn-sm btn-outline-primary addBtn"><i class="fas fa-plus"></i>@lang('Add New')</button>
@endpush

@push('script')

    <script>
        (function ($) {
            "use strict";

            $('.addBtn').on('click', function () {
                var modal = $('#addModal');
                modal.find('input[name=id]').val($(this).data('id'))
                modal.modal('show');
            });

        })(jQuery);

    </script>

@endpush
