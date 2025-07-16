{{-- Extends layout --}}
@extends('app')
{{-- Content --}}
@section('content')
@section('title', __('settings.general_settings'))

@component('partials._subheader.subheader-v6', [
    'page_title' => __('settings.general_settings'),
])
@endcomponent

@php
    $user = Sentinel::getUser();
@endphp

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            @include('components.error')

            <div class="card-body">

                <div class="example">
                    <div class="example-preview">
                        <div class="row">
                            <div class="col-lg-3">
                                <!--begin::Navigation-->
                                <ul class="navi navi-link-rounded navi-accent navi-hover navi-active nav flex-column mb-8 mb-lg-0"
                                    role="tablist">
                                    <!--begin::Nav Item-->
                                    <li class="navi-item mb-2">
                                        <a class="navi-link active" id="home-tab-1" data-toggle="tab" href="#home-5">
                                            <span class="nav-icon mr-3">
                                                <i class="flaticon-user"></i>
                                            </span>
                                            <span class="navi-text">{{ __('settings.company') }}</span>
                                        </a>
                                    </li>

                                    <li class="navi-item mb-2">
                                        <a class="navi-link" id="app-version-tab-2" data-toggle="tab"
                                            href="#app_version_tab" aria-controls="contact">
                                            <span class="nav-icon mr-3">
                                                <i class="flaticon-app"></i>
                                            </span>
                                            <span class="navi-text">{{ __('settings.app_version') }}</span>
                                        </a>
                                    </li>
                                </ul>
                                <!--end::Navigation-->
                            </div>
                            <div class="col-lg-9">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="home-5" role="tabpanel"
                                        aria-labelledby="home-tab-1">
                                        @include('settings.company_form')
                                    </div>

                                    <div class="tab-pane fade" id="app_version_tab" role="tabpanel"
                                        aria-labelledby="tax-version-tab-2">
                                        @include('settings.app_version_form')
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@include('settings.script')
