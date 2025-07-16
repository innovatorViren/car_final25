{{-- Extends layout --}}
@extends($theme)
{{-- Content --}}

@section('content')
@section('title', __('users.title'))
@component('partials._subheader.subheader-v6', [
    'page_title' => __('users.title'),
    'back_action' => route('users.index'),
    'text' => __('common.back'),
    'permission' => true,
])
    ,
@endcomponent

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom">
                    <div class="card-body p-5">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                @if ($users->emp_type === 'non-employee')
                                    <h4>
                                        <span style="float:left;">
                                            {{ $users->first_name ?? '' }}
                                            {{ $users->last_name ?? '' }}
                                            (Non Employee)
                                        </span>
                                    </h4>
                                @endif

                                @if ($users->emp_type === 'employee')
                                    <h4>
                                        <span style="float:left;">
                                            <a href="{{ route('employee.show', [$users->emp_id]) }}">
                                                {{ $users->first_name ?? '' }}
                                                {{ $users->last_name ?? '' }}
                                                (Employee)
                                            </a>
                                        </span>
                                    </h4>
                                @endif

                                @if ($users->emp_type === 'customer')
                                    <h4>
                                        <span style="float:left;">
                                            <a href="{{ route('customers.show', [$users->customer_id]) }}">
                                                {{ $users->first_name ?? '' }}
                                                {{ $users->last_name ?? '' }}
                                                (Customer)
                                            </a>
                                        </span>
                                    </h4>
                                @endif

                            </div>
                            <div class="col-lg-6">
                                <div class="card-toolbar">
                                    <span class="svg-icon" style="float:right;">
                                        @if ($current_user->hasAnyAccess(['users.edit', 'users.superadmin']))
                                            <a href="{{ route('users.edit', $users->id) }}"
                                                class="btn btn-light-primary btn-sm font-weight-bold">
                                                <i class="fas fa-pencil-alt fa-1x"></i>
                                                Edit
                                            </a>
                                        @endif

                                        @if ($current_user->hasAnyAccess(['users.info', 'users.superadmin']))
                                            <a href=""
                                                class="btn btn-light-success btn-sm font-weight-bold show-info"
                                                data-toggle="modal" data-target="#AddModelInfo"
                                                data-table="{{ $table_name }}" data-id="{{ $users->id }}"
                                                data-url="{{ route('get-info') }}">
                                                <span class="navi-icon">
                                                    <i class="fas fa-info-circle fa-1x"></i>
                                                </span>
                                                <span class="navi-text">Info</span>
                                            </a>
                                        @endif

                                        @if ($current_user->hasAnyAccess(['users.autologin', 'users.superadmin']))
                                            @if ($current_user->hasAnyAccess(['users.superadmin']) || $users->rolesData->slug != 'administrator')
                                                <a href="{{ route('user.auto-login', $users->id) }}"
                                                    class="btn btn-light-dark btn-sm font-weight-bold">
                                                    <i class="fa fa-sign-in-alt fa-1x"></i>
                                                    Login
                                                </a>
                                            @endif
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="card-body pt-1">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th width="33%">
                                        <div class="font-weight-bold text-dark-50">Full Name</div>
                                    </th>
                                    <th width="33%">
                                        <div class="col-lg-5 pl-0">
                                            <div class="font-weight-bold text-dark-50">Email</div>
                                        </div>
                                    </th>
                                    <th width="33%">
                                        <div class="col-lg-5 pl-0">
                                            <div class="font-weight-bold text-dark-50">User Type</div>
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <div class="font-weight-bolder h6">
                                            {{ $users->first_name . ' ' . $users->middle_name . ' ' . $users->last_name }}
                                        </div>
                                    </th>
                                    <th>
                                        <div class="">
                                            <div class="font-weight-bolder h6">{{ $users->email ?? '' }}</div>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="font-weight-bolder h6">
                                            @if ($users->emp_type === 'non-employee')
                                                Non Employee
                                            @endif

                                            @if ($users->emp_type === 'employee')
                                                Employee
                                            @endif

                                            @if ($users->emp_type === 'customer')
                                                Customer
                                            @endif
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th width="33%">
                                        <div class="col-lg-4 pl-0">
                                            <div class="font-weight-bold text-dark-50">Mobile</div>
                                        </div>
                                    </th>
                                    <th width="33%">
                                        <div class="col-lg-5 pl-0 ">
                                            <div class="font-weight-bold text-dark-50">Role</div>
                                        </div>
                                    </th>
                                    <th width="66%" colspan="2">
                                        <div class="font-weight-bold text-dark-50">Allow Multi Login</div>
                                    </th>
                                </tr>

                                <tr>
                                    <th>
                                        <div>
                                            <div class="font-weight-bolder h6">{{ $users->mobile ?? '' }}</div>

                                        </div>
                                    </th>
                                    <th>
                                        <div class="font-weight-bolder h6">{{ $users->rolesData->name ?? '' }}</div>
                                    </th>
                                    <th>
                                        <div class="font-weight-bolder h6">
                                            @if ($users->allow_multi_login == 1)
                                                Yes
                                            @else
                                                No
                                            @endif
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th width="33%">
                                        <div class="font-weight-bold text-dark-50">{{trans("users.form.allow_access_from_other_network")}}</div>
                                    </th>
                                    <th width="33%">
                                        <div class="font-weight-bold text-dark-50"></div>
                                    </th>
                                    <th width="33%">
                                        <div class="font-weight-bold text-dark-50"></div>
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="1">
                                        <div class="font-weight-bolder h6">
                                            @if ($users->allow_access_from_other_network == 'Yes')
                                                Yes
                                            @else
                                                No
                                            @endif
                                        </div>

                                    </th>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include('info')

@endsection
