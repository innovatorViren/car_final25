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
                                {{ $customers->first_name ?? '' }}
                            </h3>
                        </a>
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
                                        <span class="nav-text">Car</span>
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
                                <table class="table table-border">
                                    <thead>
                                        <tr>
                                            <th>no</th>
                                            <th>Address</th>
                                            <th>Landmark</th>
                                            <th>Pincode</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Country</th>
                                            <th>defult</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customers->customerAddress as $key => $cusAdd)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $cusAdd->address_line1 ?? '' }} {{ $cusAdd->address_line2 ?? '' }}</td>
                                                <td>{{ $cusAdd->landmark ?? ''}}</td>
                                                <td>{{ $cusAdd->pincode ?? ''}}</td>
                                                <td>{{ $cusAdd->city->name ?? ''}}</td>
                                                <td>{{ $cusAdd->state->name ?? ''}}</td>
                                                <td>{{ $cusAdd->country->name ?? ''}}</td>
                                                <td>{{ $cusAdd->is_default ==1 ? 'Yes' : 'No'}}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" align="center">No records found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="document" role="tabpanel"
                                aria-labelledby="kt_tab_pane_4_2">
                                <table class="table table-border">
                                    <thead>
                                        <tr>
                                            <th>no</th>
                                            <th>Vehicle</th>
                                            <th>Car Model</th>
                                            <th>Car Brand</th>
                                            <th>defult</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customers->customerCar as $key => $cusCar)
                                        @php
                                        $carBrand = DB::table('car_brands')->where('id',$cusCar->carModel->car_brand_id)->first();
                                        @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $cusCar->vehicle_name ?? ''}}</td>
                                                <td>{{ $cusCar->carModel->name ?? ''}}</td>
                                                <td>{{ $carBrand->name ?? ''}}</td>
                                                <td>{{ $cusCar->is_default ==1 ? 'Yes' : 'No'}}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" align="center">No records found</td>
                                            </tr>
                                        @endforelse
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
