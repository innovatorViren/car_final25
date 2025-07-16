{{-- Extends layout --}}
@extends('app')
{{-- Content --}}
@section('content') @section('title', $title)
@component('partials._subheader.subheader-v6', [
    'page_title' => __('employee.employee'),
    'back_text' => __('common.back'),
    'model_back_action' => route('employee.index'),
])
@endcomponent

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <h5>{{ __('department.department') }}</h5>
                        <div class="row">
                            @if (isset($employeeData) && count($employeeData) > 0)
                                @foreach ($employeeData as $dept_key => $empData)
                                    @php $dept_key_slug = Str::slug($dept_key); @endphp
                                    <div class="col-lg-12">
                                        <div class="tab-content">
                                            <div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle"
                                                id="Liability">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <a class="card-title text-dark collapsed" data-toggle="collapse"
                                                            data-target="#{{ $dept_key_slug }}" aria-expanded="true"
                                                            role="button">
                                                            <span class="svg-icon svg-icon-primary">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="24px" height="24px" viewBox="0 0 24 24"
                                                                    version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
                                                                        <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                                        <path
                                                                            d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                                                            fill="#000000" fill-rule="nonzero"></path>
                                                                        <path
                                                                            d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                                                            fill="#000000" fill-rule="nonzero"
                                                                            opacity="0.3"
                                                                            transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999)">
                                                                        </path>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                            @php
                                                                $deptCount = 0;
                                                                if (count($empData) > 0) {
                                                                    foreach ($empData as $key => $processValue) {
                                                                        foreach ($processValue as $value) {
                                                                            $deptCount += count($value);
                                                                        }
                                                                    }
                                                                }
                                                            @endphp
                                                            <div class="card-label text-dark pl-4">
                                                                {{ $dept_key ?? '' }}
                                                                -
                                                                {{ $deptCount ?? 0 }}
                                                                Employee
                                                            </div>
                                                        </a>
                                                    </div>

                                                    <div id="{{ $dept_key_slug }}" class="collapse">
                                                        <div class="card-body text-dark-50 font-size-lg pl-12">
                                                            <div class="tab-content">
                                                                <div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle"
                                                                    id="LiabilityDetail">
                                                                    @if (isset($empData) && count($empData) > 0)
                                                                        @foreach ($empData as $key => $processValue)
                                                                            @if ($key == '')
                                                                                @if (isset($processValue) && count($processValue) > 0)
                                                                                    @foreach ($processValue as $desig_key => $value)
                                                                                        <div class="card">
                                                                                            <div class="card-header">
                                                                                                <a class="card-title text-dark collapsed"
                                                                                                    data-toggle="collapse"
                                                                                                    data-target="#{{ $dept_key_slug }}_{{ Str::slug($desig_key) }}"
                                                                                                    aria-expanded="true"
                                                                                                    role="button">
                                                                                                    <span
                                                                                                        class="svg-icon svg-icon-primary">
                                                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                                                            width="24px"
                                                                                                            height="24px"
                                                                                                            viewBox="0 0 24 24"
                                                                                                            version="1.1">
                                                                                                            <g stroke="none"
                                                                                                                stroke-width="1"
                                                                                                                fill="none"
                                                                                                                fill-rule="evenodd">
                                                                                                                <polygon
                                                                                                                    points="0 0 24 0 24 24 0 24">
                                                                                                                </polygon>
                                                                                                                <path
                                                                                                                    d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                                                                                                    fill="#000000"
                                                                                                                    fill-rule="nonzero">
                                                                                                                </path>
                                                                                                                <path
                                                                                                                    d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                                                                                                    fill="#000000"
                                                                                                                    fill-rule="nonzero"
                                                                                                                    opacity="0.3"
                                                                                                                    transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999)">
                                                                                                                </path>
                                                                                                            </g>
                                                                                                        </svg>
                                                                                                    </span>
                                                                                                    <div
                                                                                                        class="card-label text-dark pl-4">
                                                                                                        {{ $desig_key ?? '' }}
                                                                                                        -
                                                                                                        {{ count($value) }}
                                                                                                        Employee
                                                                                                    </div>
                                                                                                </a>
                                                                                            </div>
                                                                                            <div id="{{ $dept_key_slug }}_{{ Str::slug($desig_key) }}"
                                                                                                class="collapse">
                                                                                                <div
                                                                                                    class="card-body text-dark-50 font-size-lg pl-12">
                                                                                                    <div
                                                                                                        class="d-flex flex-column">
                                                                                                        <div
                                                                                                            class="d-flex flex-column">
                                                                                                            @foreach ($value as $emp_name)
                                                                                                                @php
                                                                                                                    $name = explode(
                                                                                                                        '-',
                                                                                                                        $emp_name[
                                                                                                                            'employee_name'
                                                                                                                        ],
                                                                                                                    );
                                                                                                                @endphp
                                                                                                                <div
                                                                                                                    class="d-flex justify-content-between font-size-lg mb-3">
                                                                                                                    <div
                                                                                                                        class="col-lg-4">
                                                                                                                        <span
                                                                                                                            class="font-weight-bold mr-15">{{ $name[0] ?? '' }}</span>
                                                                                                                    </div>
                                                                                                                    <div
                                                                                                                        class="col-lg-4">
                                                                                                                        <span
                                                                                                                            class="text-right">{{ $name[1] ?? '' }}</span>
                                                                                                                    </div>
                                                                                                                    <div
                                                                                                                        class="col-lg-4">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            @endforeach
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                @endif
                                                                            @else
                                                                                @php
                                                                                    $processCount = 0;
                                                                                    if (count($empData) > 0) {
                                                                                        foreach (
                                                                                            $processValue
                                                                                            as $value
                                                                                        ) {
                                                                                            $processCount += count(
                                                                                                $value,
                                                                                            );
                                                                                        }
                                                                                    }
                                                                                @endphp
                                                                                <div class="card">
                                                                                    <div class="card-header">
                                                                                        <a class="card-title text-dark collapsed"
                                                                                            data-toggle="collapse"
                                                                                            data-target="#{{ $dept_key_slug }}_{{ Str::slug($key) }}"
                                                                                            aria-expanded="true"
                                                                                            role="button">
                                                                                            <span
                                                                                                class="svg-icon svg-icon-primary">
                                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                                                    width="24px"
                                                                                                    height="24px"
                                                                                                    viewBox="0 0 24 24"
                                                                                                    version="1.1">
                                                                                                    <g stroke="none"
                                                                                                        stroke-width="1"
                                                                                                        fill="none"
                                                                                                        fill-rule="evenodd">
                                                                                                        <polygon
                                                                                                            points="0 0 24 0 24 24 0 24">
                                                                                                        </polygon>
                                                                                                        <path
                                                                                                            d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                                                                                            fill="#000000"
                                                                                                            fill-rule="nonzero">
                                                                                                        </path>
                                                                                                        <path
                                                                                                            d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                                                                                            fill="#000000"
                                                                                                            fill-rule="nonzero"
                                                                                                            opacity="0.3"
                                                                                                            transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999)">
                                                                                                        </path>
                                                                                                    </g>
                                                                                                </svg>
                                                                                            </span>

                                                                                            <div
                                                                                                class="card-label text-dark pl-4">
                                                                                                {{ $key ?? '' }}
                                                                                                -
                                                                                                {{ $processCount ?? 0 }}
                                                                                                Employee
                                                                                            </div>
                                                                                        </a>
                                                                                    </div>
                                                                                    <div id="{{ $dept_key_slug }}_{{ Str::slug($key) }}"
                                                                                        class="collapse">
                                                                                        <div
                                                                                            class="card-body text-dark-50 font-size-lg pl-12">
                                                                                            <div class="tab-content">
                                                                                                <div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle"
                                                                                                    id="LiabilityDetail">
                                                                                                    @if (isset($processValue) && count($processValue) > 0)
                                                                                                        @foreach ($processValue as $desig_key => $value)
                                                                                                            <div
                                                                                                                class="card">
                                                                                                                <div
                                                                                                                    class="card-header">
                                                                                                                    <a class="card-title text-dark collapsed"
                                                                                                                        data-toggle="collapse"
                                                                                                                        data-target="#{{ $dept_key_slug }}_{{ Str::slug($key) }}_{{ Str::slug($desig_key) }}"
                                                                                                                        aria-expanded="true"
                                                                                                                        role="button">
                                                                                                                        <span
                                                                                                                            class="svg-icon svg-icon-primary">
                                                                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                                                                                width="24px"
                                                                                                                                height="24px"
                                                                                                                                viewBox="0 0 24 24"
                                                                                                                                version="1.1">
                                                                                                                                <g stroke="none"
                                                                                                                                    stroke-width="1"
                                                                                                                                    fill="none"
                                                                                                                                    fill-rule="evenodd">
                                                                                                                                    <polygon
                                                                                                                                        points="0 0 24 0 24 24 0 24">
                                                                                                                                    </polygon>
                                                                                                                                    <path
                                                                                                                                        d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                                                                                                                        fill="#000000"
                                                                                                                                        fill-rule="nonzero">
                                                                                                                                    </path>
                                                                                                                                    <path
                                                                                                                                        d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                                                                                                                        fill="#000000"
                                                                                                                                        fill-rule="nonzero"
                                                                                                                                        opacity="0.3"
                                                                                                                                        transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999)">
                                                                                                                                    </path>
                                                                                                                                </g>
                                                                                                                            </svg>
                                                                                                                        </span>
                                                                                                                        <div
                                                                                                                            class="card-label text-dark pl-4">
                                                                                                                            {{ $desig_key ?? '' }}
                                                                                                                            -
                                                                                                                            {{ count($value) }}
                                                                                                                            Employee
                                                                                                                        </div>
                                                                                                                    </a>
                                                                                                                </div>
                                                                                                                <div id="{{ $dept_key_slug }}_{{ Str::slug($key) }}_{{ Str::slug($desig_key) }}"
                                                                                                                    class="collapse">
                                                                                                                    <div
                                                                                                                        class="card-body text-dark-50 font-size-lg pl-12">
                                                                                                                        <div
                                                                                                                            class="d-flex flex-column">
                                                                                                                            @foreach ($value as $emp_name)
                                                                                                                                @php
                                                                                                                                    $name = explode(
                                                                                                                                        '-',
                                                                                                                                        $emp_name[
                                                                                                                                            'employee_name'
                                                                                                                                        ],
                                                                                                                                    );
                                                                                                                                @endphp
                                                                                                                                <div
                                                                                                                                    class="d-flex justify-content-between font-size-lg mb-3">
                                                                                                                                    <div
                                                                                                                                        class="col-lg-4">
                                                                                                                                        <span
                                                                                                                                            class="font-weight-bold mr-15">{{ $name[0] ?? '' }}</span>
                                                                                                                                    </div>
                                                                                                                                    <div
                                                                                                                                        class="col-lg-4">
                                                                                                                                        <span
                                                                                                                                            class="text-right">{{ $name[1] ?? '' }}</span>
                                                                                                                                    </div>
                                                                                                                                    <div
                                                                                                                                        class="col-lg-4">
                                                                                                                                        <span
                                                                                                                                            class="text-right">
                                                                                                                                            <span
                                                                                                                                                class="fas fa-rupee-sign">
                                                                                                                                            </span>
                                                                                                                                            {{ $emp_name['income_total'] ? number_format($emp_name['income_total']) : '0' }}</span>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            @endforeach
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        @endforeach
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
