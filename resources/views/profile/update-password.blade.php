@extends('app')

{{-- Content --}}
@section('title',__('profile.update_password_title'))
@section('content')
@component('partials._subheader.subheader-v6',
   [
   'page_title' => __('profile.update_password_title'),
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
                                <h3 class="card-label font-weight-bolder text-dark">Change Password</h3>
                                <span class="text-muted font-weight-bold font-size-sm mt-1">Change your account password</span>
                            </div>
                            <div class="card-toolbar">
                            </div>
                        </div>
                        <!--end::Header-->
                        @if(Session::has('message'))
                            @php print_r(Session::get('message')) @endphp
                        @endif
                        <!--begin::Form-->
                        <form class="form" method="POST" action="{{ route('profile.update-password') }}" id="profile-update-password">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-alert">Current Password</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="password" class="form-control form-control-lg form-control-solid mb-2" value="" name="current_password" id="current_password" placeholder="Current password" />
                                        @if ($errors->has('current_password'))
                                            <span class="text-danger">
                                            {{ $errors->first('current_password') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-alert">New Password</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="password" class="form-control form-control-lg form-control-solid" value="" name="password" id="password" placeholder="New password" />
                                        @if ($errors->has('password'))
                                            <span class="text-danger">
                                            {{ $errors->first('password') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-alert">Verify Password</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="password" class="form-control form-control-lg form-control-solid" value="" name="password_confirmation" id="password_confirmation" placeholder="Verify password" />
                                        @if ($errors->has('password_confirmation'))
                                            <span class="text-danger">
                                            {{ $errors->first('password_confirmation') }}
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
<!-- <script src="{{ asset('js/validation/profile.js') }}" type="text/javascript"></script> -->
@endsection