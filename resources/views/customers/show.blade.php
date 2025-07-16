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
                                {{ $customers->company_name }}
                            </h3>
                        </a>
                        <span class="svg-icon pt-5" style="float:right;">

                            @if ($current_user->hasAnyAccess(['customers.edit', 'users.superadmin']) && $isCustomerCount <= 0)
                                <a href="{{ route('customers.edit', $customers->id) }}"
                                    class="btn btn-light-primary btn-sm font-weight-bold">
                                    <i class="fas fa-pencil-alt fa-1x"></i> {{ __('common.edit') }}
                                </a>
                            @endif
                            @if ($current_user->hasAnyAccess(['customers.destroy', 'users.superadmin']) && $isCustomerCount <= 0 && $user_status >  0)
                                <a href="{{ route('customers.destroy', $customers['id']) }}"
                                    class="btn btn-light-danger btn-sm font-weight-bold call-modal"
                                    data-target-modal="#commonModalID" data-id="{{ $customers['id'] }}"
                                    data-toggle="modal"
                                    data-url="{{ route('delete-reason', [$customers['id'], 'customers']) }}">
                                    <i class="fas fa-trash-alt fa-1x"></i>
                                    {{ __('common.delete') }}
                                </a>
                            @endif
                            @if ($current_user->hasAnyAccess(['users.info', 'users.superadmin']))
                                <a href="" class="btn btn-light-success btn-sm font-weight-bold show-info"
                                    data-toggle="modal" data-target="#AddModelInfo" data-table="{{ $table_name }}"
                                    data-id="{{ $customers->id }}" data-url="{{ route('get-info') }}">
                                    <span class="navi-icon">
                                        <i class="fas fa-info-circle fa-1x"></i>
                                    </span>
                                    <span class="navi-text">Info</span>
                                </a>
                            @endif
                        </span>
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
                                                {{ trans('common.pan_no') }}</div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style="color:#9d9595;">
                                                {{ trans('GST Type') }}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style="color:#9d9595;">
                                                {{ trans('common.gst_no') }}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold " style="color:#9d9595;">
                                                {{ trans('common.credit') }}
                                            </div>
                                        </th>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #000000;">
                                                {{ $customers->pan_no }}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #000000;">
                                                {{ $customers->gst_type }}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #000000;">
                                                {{ $customers['gst_no'] }}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #000000;">
                                                {{ $customers->credit_days ?? '-' }} days |
                                                {{ $customers->credit_limit ?? '0.00' }} limit
                                            </div>
                                        </th>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('common.company') }}</div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style="color:#9d9595;">
                                                {{ trans('customers.person_name') }}
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
                                                {{ $customers->company_name }}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #000000;">
                                                {{ $customers->person_name }}
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
                                    <a class="nav-link" data-toggle="tab" href="#bank_details">
                                        <span class="nav-text">Bank Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#document">
                                        <span class="nav-text">Document</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#price_list">
                                        <span class="nav-text">Price List</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#shop">
                                        <span class="nav-text">Shop</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#routes">
                                        <span class="nav-text">Routes</span>
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
                                                {{ trans('customers.company_name') }}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold " style="color:#9d9595;">
                                                {{ trans('customers.person_name') }}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('customers.email') }}
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold" style="color:#000000;">
                                                    {{ $customers->company_name ?? ''}}
                                                </div>
                                            </h6>
                                        </th>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->person_name ?? ''}}</div>
                                            </h6>
                                        </th>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->email ?? ''}}
                                                </div>
                                            </h6>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('customers.gst_type') }}</div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('customers.gst_no') }}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('customers.pan_no') }}</div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->gst_type }}</div>
                                            </h6>
                                        </th>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->gst_no }}
                                                </div>
                                            </h6>
                                        </th>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->pan_no }}</div>
                                            </h6>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('Fssai No.') }}
                                            </div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('customers.credit_day') }}</div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('customers.credit_limit') }}
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->fssai_no }}
                                                </div>
                                            </h6>
                                        </th>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->credit_days ?? '' }} days
                                                </div>
                                            </h6>
                                        </th>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->credit_limit ?? '' }} limit
                                                </div>
                                            </h6>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('customers.mobile') }}</div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('price.price_list') }}</div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('Branch') }}</div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->mobile ?? '' }}
                                                </div>
                                            </h6>
                                        </th>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $priceList[$customers->price_list_id] ?? '' }}
                                                </div>
                                            </h6>
                                        </th>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $branchList[$customers->branch_id] ?? '' }}
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
                                                {{ trans('Address Line 1') }}</div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('Address Line 2') }}</div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->customerAddress->address_line1 ?? '' }}
                                                </div>
                                            </h6>
                                        </th>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->customerAddress->address_line2 ?? '' }}
                                                </div>
                                            </h6>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('Mobile 2') }}</div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->customerAddress->mobile2 ?? '' }}
                                                </div>
                                            </h6>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('Phone 1') }}</div>
                                        </th>
                                        <th>
                                            <div class="font-weight-bold" style=" color : #9d9595;">
                                                {{ trans('Phone 2') }}</div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->customerAddress->phone ?? '' }}
                                                </div>
                                            </h6>
                                        </th>
                                        <th>
                                            <h6>
                                                <div class="font-weight-bold " style=" color : #000000;">
                                                    {{ $customers->customerAddress->phone2 ?? '' }}
                                                </div>
                                            </h6>
                                        </th>
                                    </tr>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="bank_details" role="tabpanel"
                                aria-labelledby="kt_tab_pane_4_2">
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th>Account No.</th>
                                            <th>IFSC Code</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $customers->customerBankDetails->account_no ?? '' }}</td>
                                            <td>{{ $customers->customerBankDetails->ifsc_code ?? '' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th>Beneficiary Name</th>
                                            <th>Branch Name</th>
                                            <th>Bank Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $customers->customerBankDetails->beneficiary_name ?? '' }}</td>
                                            <td>{{ $customers->customerBankDetails->branch_name ?? '' }}</td>
                                            <td>{{ $customers->customerBankDetails->bank_name ?? '' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="document" role="tabpanel"
                                aria-labelledby="kt_tab_pane_4_2">
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th>Pan Card</th>
                                            <th>GST Certificate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <img class="img-fluid" src="{{ $customers->pan_card_photo }}" />
                                            </td>
                                            <td>
                                                <img class="img-fluid"
                                                    src="{{ $customers->gst_certificate_photo }}" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="price_list" role="tabpanel" aria-labelledby="kt_tab_pane_4_2">
                                <table class="table table-head-custom table-checkable">
                                    <thead class="thead-light thead">
                                        <tr>
                                            <td>{{ __('common.sr_no') }}</td>
                                            <td>{{ __('common.product_name') }}</td>
                                            <td class="text-right">{{ __('price.mrp') }}</td>
                                            <td class="text-right">{{ __('price.ratail_rate') }}</td>
                                            <td class="text-right">{{ __('price.billing_rate') }}</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @if($priceListData->count() > 0)
                                                @foreach ($priceListData as $key => $row)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $row->product->product_name .' - '. $row->variant->name}}</td>
                                                        <td class="text-right">{{ $row->mrp ?? '' }}</td>
                                                        <td class="text-right">{{ $row->ratail_rate ?? '' }}</td>
                                                        <td class="text-right">{{ $row->rate ?? '' }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center"> {{ __('common.no_records_found') }}</td>    
                                                </tr>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="shop" role="tabpanel" aria-labelledby="kt_tab_pane_4_2">
                                <table class="table table-head-custom table-checkable">
                                    <thead class="thead-light thead">
                                        <tr>
                                            <td>{{ __('common.sr_no') }}</td>
                                            <td>{{ __('shop.name') }}</td>
                                            <td>{{ __('shop.owner_name') }}</td>
                                            <td>{{ __('shop.phone_number') }}</td>
                                            <td>{{ __('shop.route') }}</td>
                                            <td>{{ __('shop.last_visit') }}</td>
                                            <td>{{ __('shop.salesman') }}</td>    
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($shopData->count() > 0)
                                            @foreach ($shopData as $key => $row)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $row->name}}</td>
                                                    <td>{{ $row->owner_name ?? '' }}</td>
                                                    <td>{{ $row->phone_number ?? '' }}</td>
                                                    <td>{{ $row->route_name ?? '' }}</td>
                                                    <td>{{ custom_date_format($row->route_date, 'd-m-Y : H:i:s') ?? '-' }}</td>
                                                    <td>{{ $row->first_name .' '. $row->last_name ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5" class="text-center"> {{ __('common.no_records_found') }}</td>    
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="routes" role="tabpanel" aria-labelledby="kt_tab_pane_4_2">
                                <table class="table table-head-custom table-checkable">
                                    <thead class="thead-light thead">
                                        <tr>
                                            <td>{{ __('common.sr_no') }}</td>
                                            <td>{{ __('common.name') }}</td>
                                            <td>{{ __('shop.shop') }}</td>
                                            <td>{{ __('shop.last_visit') }}</td>
                                            <td>{{ __('shop.salesman') }}</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @if($routeData->count() > 0)
                                                @foreach ($routeData as $key => $row)
                                                    @php
                                                        if($row->shop_visits_latest){
                                                            $salesman = $row->shop_visits_latest->empShopVisit->first_name .' '. $row->shop_visits_latest->empShopVisit->last_name;
                                                            $last_visit = $row->shop_visits_latest ? custom_date_format($row->shop_visits_latest->created_at, 'd-m-Y : H:i:s') : ''; 
                                                        }else{
                                                            $salesman = '';
                                                            $last_visit = ''; 
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $row->name ?? ''}}</td>
                                                        <td>{{ $row->shop_routes->count() ?? ''}}</td>
                                                        <td>{{ $last_visit ?? ''}}</td>
                                                        <td>{{ $salesman ?? ''}}</td>
                                                    </tr>
                                                @endforeach

                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center"> {{ __('common.no_records_found') }}</td>    
                                                </tr>
                                            @endif
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
