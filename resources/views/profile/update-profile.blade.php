@extends('app')

{{-- Content --}}
@section('title',__('profile.title'))
@section('content')
@component('partials._subheader.subheader-v6',
   [
   'page_title' => __('profile.title'),
   'back_action'=> route('dashboard'),
   'text' => __('common.back'),
   ])
@endcomponent

<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Profile Account Information-->
            <div class="d-flex flex-row">

                <!--begin::Aside-->
                @include('profile._aside', $user)
                <!--end::Aside-->
                <!--begin::Content-->
                <div class="flex-row-fluid ml-lg-8">
                    <!--begin::Card-->
                    <div class="card card-custom">
                        <!--begin::Header-->
                        <div class="card-header py-3">
                            <div class="card-title align-items-start flex-column">
                                <h3 class="card-label font-weight-bolder text-dark">Personal Information</h3>
                                <span class="text-muted font-weight-bold font-size-sm mt-1">Update your personal informaiton</span>
                            </div>
                            <div class="card-toolbar">
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Form-->
                        <form class="form" method="POST" action="{{ route('profile.update') }}" id="profile-update-form" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <!--begin::Heading-->
                                
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Profile Pic</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="image-input image-input-outline" id="kt_profile_avatar" style="background-image: url({{ asset('media/users/blank.png') }})">
                                            <div class="image-input-wrapper" style="background-image: url('{{asset($user->image_path) }}')"></div>
                                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" name="image"/>
                                                <input type="hidden" name="profile_avatar_remove" />
                                            </label>
                                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                            </span>
                                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary" data-action="remove" data-toggle="tooltip" title="Remove avatar">
                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                            </span>
                                        </div>
                                        <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                                        @if ($errors->has('image_path'))
                                            <span class="text-danger">
                                            {{ $errors->first('image_path') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <!--begin::Form Group-->
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Name</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control form-control-lg form-control-solid" type="text" name="first_name" value="{{ $user->first_name }}" required readonly/>
                                        @if ($errors->has('first_name'))
                                            <span class="text-danger">
                                            {{ $errors->first('first_name') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <!--begin::Form Group-->
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Email Address</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="text" class="form-control form-control-lg form-control-solid" value="{{ $user->email }}" name="email" required readonly/>
                                        @if ($errors->has('email'))
                                            <span class="text-danger">
                                            {{ $errors->first('email') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-12 text-right">
                                        <button type="submit" class="btn btn-primary" id="btn_update">{{ __('common.update') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Profile Account Information-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
            
@endsection

@section('scripts')
@include('profile.script')
<!-- <script src="{{ asset('js/pages/custom/profile/profile.js') }}" type="text/javascript"></script> -->
@endsection