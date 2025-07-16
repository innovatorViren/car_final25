{{-- Extends layout --}}
@extends($theme)
{{-- Content --}}
@section('title', $title)

@section('content')
    @component('partials._subheader.subheader-v6', [
        'page_title' => __('employee.employee'),
        'back_action' => route('employee.index'),
        'text' => __('common.back'),
        'permission' => true,
    ])
    @endcomponent

    {{-- @include('employee.image_modal') --}}

    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle" id="faq">
                <div class="card">
                    <div class="card-header" id="faqHeading1">
                        <div class="card-header border-0 card-header-right ribbon ribbon-left">
                            <div class="ribbon-target bg-info w-75px" style="">{{ $employee->employee_code }}
                            </div>
                        </div>
                        <div class="d-flex justify-content-between flex-column flex-md-row col-lg-12">
                            <a class="card-title text-dark collapsed" data-toggle="collapse" href="#faq1"
                                aria-expanded="false" aria-controls="faq1" role="button">
                                <h3 class="font-weight-bolder pt-11">
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
                                    {{ $employee->first_name . ' ' . $employee->middle_name . ' ' . $employee->last_name }}
                                </h3>
                            </a>

                            <span class="svg-icon pt-4" style="float:right;">

                                @if (isset($employee->left_date) &&
                                        $employee->left_date != '0000-00-00' &&
                                        $employee->recruit_again == 'Yes' &&
                                        ($employee->rejoin_date == '0000-00-00' || $employee->rejoin_date == null))
                                    <a href="{{ route('employeeRejoin', $employee->id) }}"
                                        class="btn btn-light-primary btn-sm font-weight-bold">
                                        <i class="fas fa-pencil-alt fa-1x"></i> {{ __('employee.rejoin') }}
                                    </a>
                                @endif
                                @if ($current_user->hasAnyAccess(['employee.assign_salesman', 'users.superadmin']) && $department->slug == "sales" && $designation->slug == "area_sales_manager")
                                    <a href="{{ route('employee.assign_salesman', $employee->id) }}" 
                                        class="btn btn-secondary text-dark btn-sm font-weight-bold call-modal"
                                        data-target-modal="#customerModalID"
                                        data-id="{{ $employee->id }}"
                                        data-toggle="modal" 
                                        data-url="{{ route('employee.assign_salesman',$employee->id) }}"
                                        >
                                        <span class="navi-text">
                                            {{ __('employee.assign_salesman') }}
                                        </span>
                                    </a>
                                @endif

                                @if ($current_user->hasAnyAccess(['employee.assign_customer', 'users.superadmin']) && $department->slug == "sales")
                                    <a href="{{ route('employee.assign_customer', $employee->id) }}" 
                                        class="btn btn-secondary text-dark btn-sm font-weight-bold call-modal"
                                        data-target-modal="#customerModalID" 
                                        data-id="{{ $employee->id }}"
                                        data-toggle="modal" 
                                        data-url="{{ route('employee.assign_customer',$employee->id) }}"
                                        >
                                        <span class="navi-text">
                                            {{ __('employee.assign_customer') }}
                                        </span>
                                    </a>
                                @endif

                                @if (
                                    $current_user->hasAnyAccess(['employee.edit', 'users.superadmin']) &&
                                        ($employee->left_date == '0000-00-00' || $employee->left_date == null))
                                    <a href="{{ route('employee.edit', $employee->id) }}"
                                        class="btn btn-light-primary btn-sm font-weight-    bold">
                                        <i class="fas fa-pencil-alt fa-1x"></i> {{ __('common.edit') }}
                                    </a>
                                @endif
                                @if (
                                    $current_user->hasAnyAccess(['employee.destroy']) &&
                                        ($employee->left_date == '0000-00-00' || $employee->left_date == null))
                                    <a href="{{ route('employee.destroy', $employee->id) }}"
                                        data-redirect="{{ route('employee.index') }}"
                                        class="btn btn-light-danger btn-sm font-weight-bold delete-confrim">
                                        <i class="fas fa-trash-alt fa-1x"></i> {{ __('common.delete') }}
                                    </a>
                                @endif
                                @if ($current_user->hasAnyAccess(['users.info', 'users.superadmin']))
                                    <a href="" class="btn btn-light-success btn-sm font-weight-bold show-info"
                                        data-toggle="modal" data-target="#AddModelInfo" data-table="{{ $table_name }}"
                                        data-id="{{ $employee->id }}" data-url="{{ route('get-info') }}">
                                        <span class="navi-icon">
                                            <i class="fas fa-info-circle fa-1x"></i>
                                        </span>
                                        <span class="navi-text">
                                            {{ __('Info') }}
                                        </span>
                                    </a>
                                @endif
                            </span>
                        </div>
                        <div class="col-12 ml-10 mt-n5">
                            <p class="font-weight-bold text-dark-50">
                                {{ isset($employee->DepartmentName) ? $employee->DepartmentName->name : '' }}
                                {{ isset($employee->designationName) ? '| ' . $employee->designationName->name : '' }}
                                {{ $employee->DepartmentName->name == 'Production' && $employee->processName ? '| ' . $employee->processName->name : '' }}
                                {{ $employee->DepartmentName->name == 'CM Production' && $employee->cylinderProcessName ? '| ' . $employee->cylinderProcessName->name : '' }}
                            </p>
                        </div>

                    </div>
                    <div id="faq1" class="collapse" aria-labelledby="faqHeading1" data-parent="#faq">
                        <div class="card-body pl-10 pt-5 pb-2">
                            <div class="row">
                                <div class="col-lg-2">
                                    <table>
                                        <tr>
                                            <th>
                                                <div class="font-weight-bold my-2" style=" color : #9d9595;"></div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                <div class="font-weight-bold my-2  " style=" color : #000000;">
                                                    <div class="symbol symbol-60 symbol-circle symbol-xl-90">
                                                        <img class="symbol-label jsShowImage"
                                                            src="{{ isset($employee->photo_path) && !empty($employee->photo_path) ? asset($employee->photo_path) : asset('/media/svg/avatars/001-boy.svg') }}"
                                                            role="btn">
                                                        </img>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-lg-10">
                                    <table style="width:100%">
                                        <tr>
                                            <th width="30%">
                                                <div class="font-weight-bold my-2" style=" color : #9d9595;">Email</div>
                                            </th>
                                            <th width="30%">
                                                <div class="font-weight-bold my-2" style=" color : #9d9595;">Join Date</div>
                                            </th>
                                            @if (isset($employee->recruit_again) && $employee->left_date != null && $employee->left_date != '0000-00-00')
                                                <th width="30%">
                                                    <div class="font-weight-bold my-2" style=" color : #9d9595;">Recruit
                                                        Again</div>
                                                </th>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold mt-n2" style=" color : #000000;">
                                                        {{ $employee->email }}</div>
                                                </h6>
                                            </th>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold mt-n2" style=" color : #000000;">
                                                        {{ date('d-m-Y', strtotime($employee->join_date)) }}</div>
                                                </h6>
                                            </th>
                                            @if (isset($employee->recruit_again) && $employee->left_date != null && $employee->left_date != '0000-00-00')
                                                <th>
                                                    <h6>
                                                        <div class="font-weight-bold mt-n2" style=" color : #000000;">
                                                            {{ $employee->recruit_again ?? '' }}</div>
                                                    </h6>
                                                </th>
                                            @endif
                                        </tr>


                                        <tr>
                                            <th width="30%">
                                                <div class="font-weight-bold my-2" style=" color : #9d9595;">Mobile</div>
                                            </th>
                                            @if (isset($employee->left_date) && $employee->left_date != null && $employee->left_date != '0000-00-00')
                                                <th width="30%">
                                                    <div class="font-weight-bold my-2" style=" color : #9d9595;">Left Date
                                                    </div>
                                                </th>
                                            @endif

                                            @if (isset($employee->left_reason) && $employee->left_date != null && $employee->left_date != '0000-00-00')
                                                <th width="30%">
                                                    <div class="font-weight-bold my-2" style=" color : #9d9595;">Left
                                                        Reason
                                                    </div>
                                                </th>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold mt-n2" style=" color : #000000;">
                                                        {{ $employee->mobile }} @if ($employee->mobile1 != '')
                                                            ,<br>{{ $employee->mobile1 }}
                                                        @endif
                                                    </div>
                                                </h6>
                                            </th>
                                            @if (isset($employee->left_date) && $employee->left_date != null && $employee->left_date != '0000-00-00')
                                                <th>
                                                    <h6>
                                                        <div class="font-weight-bold mt-n2" style=" color : #000000;">
                                                            {{ date('d-m-Y', strtotime($employee->left_date)) }}</div>
                                                    </h6>

                                                </th>
                                            @endif
                                            @if (isset($employee->left_reason) && $employee->left_date != null && $employee->left_date != '0000-00-00')
                                                <th>
                                                    <h6>
                                                        <div class="font-weight-bold mt-n2" style=" color : #000000;">
                                                            {{ $employee->left_reason ?? '' }}</div>
                                                    </h6>

                                                </th>
                                            @endif
                                        </tr>

                                        <tr>
                                            <th width="30%">
                                                <div class="font-weight-bold my-2" style=" color : #9d9595;"></div>
                                            </th>
                                            @if (isset($employee->rejoin_date) && $employee->rejoin_date != null && $employee->rejoin_date != '0000-00-00')
                                                <th width="30%">
                                                    <div class="font-weight-bold my-2" style=" color : #9d9595;">Re-Join
                                                        Date</div>
                                                </th>
                                            @endif
                                            <th width="30%">
                                                <div class="font-weight-bold my-2" style=" color : #9d9595;"></div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold mt-n2" style=" color : #000000;"></div>
                                                </h6>
                                            </th>
                                            @if (isset($employee->rejoin_date) && $employee->rejoin_date != null && $employee->rejoin_date != '0000-00-00')
                                                <th>
                                                    <h6>
                                                        <div class="font-weight-bold mt-n2" style=" color : #000000;">
                                                            @if ($child_employee_id)
                                                                <a href="{{ route('employee.show', $child_employee_id) }}"
                                                                    target="_blank">{{ date('d-m-Y', strtotime($employee->rejoin_date)) }}</a>
                                                            @else
                                                                {{ date('d-m-Y', strtotime($employee->rejoin_date)) }}
                                                            @endif
                                                        </div>
                                                    </h6>
                                                </th>
                                            @endif
                                            <th>
                                                <div class="font-weight-bold mt-n2" style=" color : #000000;"></div>
                                            </th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
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
                                        <a class="nav-link active" data-toggle="tab" href="#Overview">
                                            <span class="nav-text">Overview</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_4_1">
                                            <span class="nav-text">General</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#job">
                                            <span class="nav-text">Professional</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#document">
                                            <span class="nav-text">Document</span>
                                        </a>
                                    </li>
                                    @if($department->slug == "sales")
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#customers">
                                                <span class="nav-text">Customers</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="card-body pt-1">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="Overview" role="tabpanel"
                                    aria-labelledby="Overview">
                                    <table style="width:100%">
                                        @php
                                            $join_date = $employee->join_date;
                                            $time = now()->diff($join_date);
                                        @endphp
                                        <tr>
                                            <th>
                                                &nbsp;
                                            </th>
                                        </tr>
                                        <tr>
                                            <th width="30%">
                                                <div class="font-weight-bold" style="color:#9d9595;">
                                                    {{ trans('employee.working_since') }}</div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold" style="color:#000000;">
                                                        {{ $time->y . ' Year ' . $time->m . ' Month ' }}</div>
                                                </h6>
                                            </th>
                                        </tr>

                                    </table>

                                </div>
                                <div class="tab-pane fade" id="kt_tab_pane_4_1" role="tabpanel"
                                    aria-labelledby="kt_tab_pane_4_1">
                                    <table style="width:100%">
                                        <tr>
                                            <th>
                                                &nbsp;
                                            </th>
                                        </tr>
                                        <tr>
                                            <th width="30%">
                                                <div class="font-weight-bold" style="color:#9d9595;">
                                                    {{ trans('employee.full_name') }}</div>
                                            </th>
                                            <th width="20%">
                                                <div class="font-weight-bold " style="color:#9d9595;">
                                                    {{ trans('employee.gender') }}</div>
                                            </th>
                                            <th width="25%">
                                                <div class="font-weight-bold" style=" color : #9d9595;">
                                                    {{ trans('employee.date_of_birth') }}</div>
                                            </th>
                                            <th width="25%">
                                                <div class="font-weight-bold " style=" color : #9d9595;">
                                                    {{ trans('employee.age_years') }}</div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold" style="color:#000000;">
                                                        {{ $employee->person_name }}</div>
                                                </h6>
                                            </th>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold " style=" color : #000000;">
                                                        {{ $employee->gender }}</div>
                                                </h6>
                                            </th>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold " style=" color : #000000;">
                                                        {{ date('d-m-Y', strtotime($employee->birth_date)) }}</div>
                                                </h6>
                                            </th>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold" style=" color : #000000;">
                                                        {{ $employee->age }} Year</div>
                                                </h6>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                &nbsp;
                                            </th>
                                        </tr>
                                        <tr></tr>
                                        <tr>
                                            <th width="30%">
                                                <div class="font-weight-bold" style=" color : #9d9595;">
                                                    {{ trans('employee.marital_status') }}</div>
                                            </th>
                                            <th width="20%">
                                                <div class="font-weight-bold" style=" color : #9d9595;">
                                                    {{ trans('employee.hobbies') }}</div>
                                            </th>
                                            <th width="25%">
                                                <div class="font-weight-bold " style=" color : #9d9595;">
                                                    {{ trans('employee.reference') }}</div>
                                            </th>
                                            <th width="25%">
                                                <div class="font-weight-bold " style=" color : #9d9595;">
                                                    {{ trans('employee.reference_no') }}</div>
                                            </th>
                                        </tr>

                                        <tr>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold " style=" color : #000000;">
                                                        {{ ucfirst(trans($employee->marital_status)) }}</div>
                                                </h6>
                                            </th>
                                            <th style="word-break: break-all;">
                                                @php
                                                    $hobbies =
                                                        $employee->hobbies != ''
                                                            ? explode(',', $employee->hobbies)
                                                            : [];
                                                @endphp
                                                <h6>
                                                    <div class="font-weight-bold " style=" color : #000000;">
                                                        {!! implode('<br>', $hobbies) !!}</div>
                                                </h6>
                                            </th>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold" style=" color : #000000;">
                                                        {{ $employee->reference }}</div>
                                                </h6>
                                            </th>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold" style=" color : #000000;">
                                                        {{ $employee->reference_tel_no }}</div>
                                                </h6>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                &nbsp;
                                            </th>
                                        </tr>
                                        <tr>
                                            <th width="30%">
                                                <div class="font-weight-bold" style=" color : #9d9595;">
                                                    {{ trans('Branch') }}</div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold " style=" color : #000000;">
                                                        {{ $branchList[$employee->branch_id] ?? '' }}</div>
                                                </h6>
                                            </th>
                                        </tr>

                                    </table>
                                    <hr>
                                    <table style="width:100%">
                                        <tr>
                                            <th>
                                                <h3>Address</h3>
                                            </th><br>
                                        </tr>
                                        <tr>
                                            <th>
                                                &nbsp;
                                            </th>
                                        </tr>
                                        <tr>
                                            <th width="50%">
                                                <div class="font-weight-bold" style=" color : #9d9595;">
                                                    {{ trans('employee.present_address') }}</div>
                                            </th>
                                            <th width="50%">
                                                <div class="font-weight-bold" style=" color : #9d9595;">
                                                    {{ trans('employee.permanent_address') }}</div>
                                            </th>

                                        </tr>
                                        @php
                                            $presentState = $employee->employeeAddress->presentState->name ?? '';
                                            $permanentState = $employee->employeeAddress->permanentState->name ?? '';
                                            $presentCity = $employee->employeeAddress->presentCity->name ?? '';
                                            $permanentCity = $employee->employeeAddress->permanentCity->name ?? '';
                                        @endphp
                                        <tr>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold " style=" color : #000000;">
                                                        {{ $employee->present_address }}<br>{{ $presentCity }}-{{ $employee->present_pincode }},&nbsp;{{ $presentState ?? '' }}
                                                    </div>
                                                </h6>
                                            </th>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold " style=" color : #000000;">
                                                        {{ $employee->permanent_address }}<br>{{ $permanentCity }}-{{ $employee->permanent_pincode }},&nbsp;{{ $permanentState ?? '' }}
                                                    </div>
                                                </h6>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                &nbsp;
                                            </th>
                                        </tr>
                                    </table>
                                    <hr>
                                    <table style="width:100%">
                                        <tr>
                                            <th>
                                                <h3>{{ __('employee.bank_information') }}</h3>
                                            </th><br>
                                        </tr>
                                        <tr>
                                            <th>
                                                &nbsp;
                                            </th>
                                        </tr>

                                        <tr>
                                            <th>
                                                <div class="font-weight-bold" style=" color : #9d9595;">
                                                    {{ trans('employee.bank_name') }}</div>
                                            </th>
                                            <th>
                                                <div class="font-weight-bold" style=" color : #9d9595;">
                                                    {{ trans('employee.branch_name') }}</div>
                                            </th>
                                            <th>
                                                <div class="font-weight-bold " style=" color : #9d9595;">
                                                    {{ trans('employee.ifsc_code') }}</div>
                                            </th>
                                        </tr>

                                        <tr>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold " style=" color : #000000;">
                                                        {{ $employee->bank_name }}</div>
                                                </h6>
                                            </th>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold " style=" color : #000000;">
                                                        {{ $employee->branch_name }}</div>
                                                </h6>
                                            </th>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold" style=" color : #000000;">
                                                        {{ $employee->ifsc_code }}</div>
                                                </h6>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                &nbsp;
                                            </th>
                                        </tr>
                                        <tr></tr>
                                        <tr>
                                            <th>
                                                <div class="font-weight-bold" style=" color : #9d9595;">
                                                    {{ trans('employee.beneficiary_name') }}</div>
                                            </th>
                                            <th>
                                                <div class="font-weight-bold" style=" color : #9d9595;">
                                                    {{ trans('employee.account_no') }}</div>
                                            </th>

                                        </tr>

                                        <tr>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold " style=" color : #000000;">
                                                        {{ $employee->beneficiary_name }}</div>
                                                </h6>
                                            </th>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold " style=" color : #000000;">
                                                        {{ $employee->account_no }}</div>
                                                </h6>
                                            </th>

                                        </tr>
                                    </table>
                                    <hr>
                                    <table style="width:100%">
                                        <tr>
                                            <th>
                                                <h3>{{ __('employee.medical_information') }}</h3>
                                            </th><br>
                                        </tr>

                                        <tr>
                                            <th>
                                                &nbsp;
                                            </th>
                                        </tr>

                                        <tr>
                                            <th>
                                                <div class="font-weight-bold" style="color:#9d9595;">
                                                    {{ trans('employee.strengths') }}</div>
                                            </th>
                                            <th>
                                                <div class="font-weight-bold " style="color:#9d9595;">
                                                    {{ trans('employee.weakness') }}</div>
                                            </th>
                                            <th>
                                                <div class="font-weight-bold " style="color:#9d9595;">
                                                    {{ trans('employee.blood_group') }}</div>
                                            </th>

                                        </tr>
                                        <tr>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold" style="color:#000000;">
                                                        {{ $employee->strengths }}</div>
                                                </h6>
                                            </th>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold " style=" color : #000000;">
                                                        {{ $employee->weakness }}</div>
                                                </h6>
                                            </th>
                                            <th>
                                                <h6>
                                                    <div class="font-weight-bold " style=" color : #000000;">
                                                        {{ $employee->blood_group }}</div>
                                                </h6>
                                            </th>


                                        </tr>


                                    </table>
                                </div>

                                <div class="tab-pane fade" id="job" role="tabpanel"
                                    aria-labelledby="kt_tab_pane_4_2">
                                    @include('employee.job')
                                </div>
                                <div class="tab-pane fade" id="document" role="tabpanel"
                                    aria-labelledby="kt_tab_pane_4_2">
                                    @include('employee.document')
                                </div>
                                <div class="tab-pane fade" id="customers" role="tabpanel"
                                    aria-labelledby="kt_tab_pane_4_2">
                                    @include('employee.customers')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="load-modal"></div>
    @include('info')
@endsection

@section('scripts')
    @include('employee.script')
@endsection
