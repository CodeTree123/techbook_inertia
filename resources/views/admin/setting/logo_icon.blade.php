@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row mb-none-30">
        <div class="col-md-12 mb-30">
            <div class="card bl--5-primary">
                <div class="card-body">
                    <p class="text--primary">@lang('If the logo and favicon are not changed after you update from this page, please') <span class="text--danger">@lang('clear the cache')</span> @lang('from your browser. As we keep the filename the same after the update, it may show the old image for the cache. usually, it works after clear the cache but if you still see the old logo or favicon, it may be caused by server level or network level caching. Please clear them too.')</p>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row ">
                            <div class="form-group col-xl-6">
                                <div class="image-upload">
                                    <div class="thumb">
                                        
                                        <div class="avatar-edit">
                                            <img src="{{ asset('assets/images/logoIcon/logo.png') }}" width="100px" alt="">
                                            <label for="profilePicUpload1" class="bg-primary" style="color:white;">@lang('Select Logo')</label>
                                            <input type="file" class="profilePicUpload" id="profilePicUpload1" accept=".png, .jpg, .jpeg" name="logo">            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-xl-6">
                                 <div class="image-upload">
                                    <div class="thumb">
                                       <div class="avatar-edit">
                                            <img src="{{ asset('assets/images/logoIcon/favicon.png') }}" width="100px" alt="">
                                            <label for="profilePicUpload2" class="bg-primary" style="color:white;">@lang('Select Favicon')</label>
                                            <input type="file" class="profilePicUpload" id="profilePicUpload2" accept=".png" name="favicon">   
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 h-45">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
