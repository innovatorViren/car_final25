{{-- Extends layout --}}
@extends('app')
{{-- Content --}}
@section('content')
@section('title', $master_title)
@component('partials._subheader.subheader-v6', [
    'page_title' => __('master.masters'),
])
@endcomponent
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-3">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label font-weight-bolder text-dark">Other</span>
                        </h3>
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-40 symbol-light-primary mr-5" id="country"
                                name="country">
                                <i class="flaticon2-arrow text-primary"></i>
                            </div>
                            <div class="d-flex flex-column font-weight-bold">
                                <a href="{{ url('country') }}"
                                    class="text-dark text-hover-primary mb-1 font-size-lg">{{ __('common.country') }}</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-40 symbol-light-primary mr-5" id="state"
                                name="state">
                                <i class="flaticon2-arrow text-primary"></i>
                            </div>
                            <div class="d-flex flex-column font-weight-bold">
                                <a href="{{ url('state') }}"
                                    class="text-dark text-hover-primary mb-1 font-size-lg">{{ __('common.state') }}</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-40 symbol-light-primary mr-5" id="city"
                                name="city">
                                <i class="flaticon2-arrow text-primary"></i>
                            </div>
                            <div class="d-flex flex-column font-weight-bold">
                                <a href="{{ url('city') }}"
                                    class="text-dark text-hover-primary mb-1 font-size-lg">{{ __('common.city') }}</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-40 symbol-light-primary mr-5" id="year"
                                name="year">
                                <i class="flaticon2-arrow text-primary"></i>
                            </div>
                            <div class="d-flex flex-column font-weight-bold">
                                <a href="{{ url('year') }}"
                                    class="text-dark text-hover-primary mb-1 font-size-lg">{{ __('common.year') }}</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-40 symbol-light-primary mr-5">
                                <i class="flaticon2-arrow text-primary"></i>
                            </div>

                            <div class="d-flex flex-column font-weight-bold">
                                <a href="{{ route('banner.index') }}"
                                    class="text-dark text-hover-primary mb-1 font-size-lg">
                                    {{ __('banner.title') }}
                                </a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-40 symbol-light-primary mr-5" id="car_brand"
                                name="car_brand">
                                <i class="flaticon2-arrow text-primary"></i>
                            </div>
                            <div class="d-flex flex-column font-weight-bold">
                                <a href="{{ url('car-brand') }}"
                                    class="text-dark text-hover-primary mb-1 font-size-lg">{{ __('common.car_brand') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
