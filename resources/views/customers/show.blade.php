{{-- Extends layout --}}
@extends('app')
{{-- Content --}}
@section('content')
@section('title', __('customers.title'))

@component('partials._subheader.subheader-v6', [
    'page_title' => __('customers.title'),
    'back_action' => route('customers.index'),
    'text' => __('common.back'),
    'permission' => true,
])
@endcomponent

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">

        <div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle" id="faq">
            <div class="card">
                <div class="card-header" id="faqHeading1">
                    <div class="d-flex justify-content-between flex-column flex-md-row col-lg-12">
                        <a class="card-title text-dark collapsed" data-toggle="collapse" href="#faq1"
                            aria-expanded="false" aria-controls="faq1" role="button">
                            <h3 class="font-weight-bolder pt-3">
                                <span class="svg-icon svg-icon-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                            <path
                                                d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                                fill="#000000" fill-rule="nonzero"></path>
                                            <path
                                                d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                                fill="#000000" fill-rule="nonzero" opacity="0.3"
                                                transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999)">
                                            </path>
                                        </g>
                                    </svg>
                                </span>&nbsp;
                                {{ $customers->first_name ?? '' }}
                            </h3>
                        </a>
                    </div>
                </div>
                <div id="faq1" class="collapse" aria-labelledby="faqHeading1" data-parent="#faq">
                    <div class="card-body pt-5 pl-10 pb-2">
                        @if ($current_user->hasAnyAccess(['customers.view', 'users.superadmin']))
                            <table style="width:100%" class="table table-borderless">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('Aadhar Card') }}</div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style="color:#9d9595;">
                                                
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style="color:#9d9595;">
                                               
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold " style="color:#9d9595;">
                                                
                                            </div>
                                        </th>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #000000;">
                                                {{ $customers->aadhar_card_no }}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #000000;">
                                               
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #000000;">
                                               
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #000000;">
                                                
                                            </div>
                                        </th>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('customers.first_name') }}</div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style="color:#9d9595;">
                                                {{ trans('customers.middle_name') }}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style="color:#9d9595;">
                                                {{ trans('common.email') }}
                                            </div>
                                        </th>
                                       
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #000000;">
                                                {{ $customers->first_name }}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #000000;">
                                                {{ $customers->middle_name }}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #000000;">
                                                {{ $customers->email }}
                                            </div>
                                        </th>
                                        
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="card-title font-weight-bolder text-dark">
                            <ul class="nav nav-light-success nav-bold nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#general">
                                        <span class="nav-text">General</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#address">
                                        <span class="nav-text">Address</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#document">
                                        <span class="nav-text">Document</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body pt-1">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="general" role="tabpanel"
                                aria-labelledby="general">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>
                                            <div class="font-weight-bold" style="color:#9d9595;">
                                                {{ trans('customers.first_name') }}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold " style="color:#9d9595;">
                                                {{ trans('customers.middle_name') }}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('customers.last_name') }}
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold" style="color:#000000;">
                                                    {{ $customers->first_name ?? ''}}
                                                </div>
                                            </h6>
                                        </th>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->middle_name ?? ''}}</div>
                                            </h6>
                                        </th>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->last_name ?? ''}}
                                                </div>
                                            </h6>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('customers.email') }}</div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('Aadhar Card') }}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('customers.mobile') }}</div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->email }}</div>
                                            </h6>
                                        </th>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->aadhar_card_no }}
                                                </div>
                                            </h6>
                                        </th>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->mobile ?? '' }}
                                                </div>

                                            </h6>
                                        </th>
                                    </tr>
                                </table>
                            </div>
                            <div class="tab-pane fade show" id="address" role="tabpanel"
                                aria-labelledby="address">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('Address Line') }}</div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('Phone') }}</div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->address_line ?? '' }}
                                                </div>
                                            </h6>
                                        </th>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->phone ?? '' }}
                                                </div>
                                            </h6>
                                        </th>
                                    </tr>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="document" role="tabpanel"
                                aria-labelledby="kt_tab_pane_4_2">
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th>Aadhar card</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <img class="img-fluid" src="{{ $customers->aadharcard_img }}" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="load-modal"></div>
    @include('info')
    <style>
        section {
            display: grid;
            grid-template-columns: repeat(1);
            grid-gap: 100px;
        }

        section div {
            height: 30px;
        }
    </style>
@endsection
