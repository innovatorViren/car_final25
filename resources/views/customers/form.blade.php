<div class="card card-custom gutter-b">
    <div class="card-body">
        <div class="example mb-10 mt-5">
            <div class="example-preview">
                <div class="row">
                    <div class="col-lg-3 nextPrev">
                        <!--begin::Navigation-->
                        <ul class="navi navi-link-rounded navi-accent navi-hover navi-active nav flex-column mb-8 mb-lg-0"
                            role="tablist">
                            <!--begin::Nav Item-->
                            <li class="navi-item mb-2">
                                <a class="navi-link active" id="basic_detail-tab-5" data-toggle="tab"
                                    href="#basic_detail-5">
                                    <span class="nav-icon mr-3">
                                        <i class="flaticon-user"></i>
                                    </span>
                                    <span class="navi-text">{{ __('customers.form.basic_detail') }}</span>
                                </a>
                            </li>
                            <!--end::Nav Item-->
                            <!--begin::Nav Item-->
                            <li class="navi-item mb-2">
                                <a class="navi-link" id="account-address-tab-6" data-toggle="tab"
                                    href="#account-address-6" aria-controls="account-address">
                                    <span class="nav-icon mr-3">
                                        <i class="flaticon2-shelter"></i>
                                    </span>
                                    <span class="navi-text">{{ __('customers.form.address') }}</span>
                                </a>
                            </li>
                            <!--end::Nav Item-->
                            <!--begin::Nav Item-->
                            <li class="navi-item mb-2">
                                <a class="navi-link" id="document-tab-9" data-toggle="tab" href="#document-9"
                                    aria-controls="document">
                                    <span class="nav-icon mr-3">
                                        <i class="flaticon2-document"></i>
                                    </span>
                                    <span class="navi-text">{{ __('customers.form.documents') }}</span>
                                </a>
                            </li>
                            <!--end::Nav Item-->
                        </ul>
                        <!--end::Navigation-->
                    </div>

                    <div class="col-lg-9">
                        <!--begin::Tab Content-->
                        <div class="tab-content">
                            <!--begin::Accordion-->
                            <div class="tab-pane fade show active first slide active-slide" id="basic_detail-5"
                                role="tabpanel" aria-labelledby="basic_detail-tab-5">
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        {!! Form::label('first_name',trans("customers.first_name"))!!} <i class="text-danger">*</i>
                                        {!! Form::text('first_name', null, ['class' => 'form-control required','placeholder' => 'First Name']) !!}
                                    </div>
                                    <div class="form-group col-lg-4">
                                        {!! Form::label('middle_name',trans("customers.middle_name"))!!}
                                        {!! Form::text('middle_name', null, ['class' => 'form-control','placeholder' => 'Middle Name']) !!}
                                    </div>
                                    <div class="form-group col-lg-4">
                                        {!! Form::label('last_name',trans("customers.last_name"))!!} <i class="text-danger">*</i>
                                        {!! Form::text('last_name', null, ['class' => 'form-control required','placeholder' => 'Last Name']) !!}
                                    </div>
                                    
                                    
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {!! Form::hidden('is_create_user', 1) !!}
                                            {!! Form::checkbox('is_create_user', null, true, ['id' => 'is_create_user', 'disabled']) !!}
                                            {!! Form::label('email', trans('customers.create_user')) !!}
                                            {!! Form::text('email', null, [
                                                'class' => 'form-control',
                                                'data-rule-remote' => route('checkCustomerDuplicateEmail', [$customers->id ?? '']),
                                                'data-msg-remote' => 'Email already exists',
                                                'placeholder' => 'Email',
                                                'id' => 'email',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {!! Form::label('mobile', trans('common.mobile')) !!}<i class="text-danger">*</i>
                                            {!! Form::text('mobile', null, [
                                                'class' => 'form-control mobile jsOptionRequired required',
                                                'data-rule-remote' => route('checkCustomerDuplicateMobileNo', [$customers->id ?? '']),
                                                'data-msg-remote' => 'Mobile No. already exists',
                                                'id' => 'mobile',
                                                'placeholder' => '9xxxxxxxxx',
                                                'title' => 'Please enter a 10-digit mobile number.',
                                                'pattern' => '(^[0-9]{10}$)',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        {!! Form::label('password', trans('users.form.password')) !!}
                                        @if(isset($customers->is_lead_from))
                                            <span class="text-danger">*</span>
                                        @elseif (!isset($customers))
                                            <span class="text-danger">*</span>
                                        @endif

                                        @if(isset($customers->is_lead_from))
                                            {!! Form::password('password', [
                                                'class' => 'form-control required',
                                                'autocomplete' => 'off',
                                                'id' => 'password',
                                                'minlength' => 8,
                                            ]) !!}
                                        @else
                                            {!! Form::password('password', [
                                                'class' => !empty($customers) ? 'form-control' : 'form-control required',
                                                'autocomplete' => 'off',
                                                'id' => 'password',
                                                'minlength' => 8,
                                            ]) !!}
                                        @endif

                                        <small>{{ __('users.form.password_note') }}</small>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        {!! Form::label('password_confirmation', trans('users.form.confirm_password')) !!}
                                        @if(isset($customers->is_lead_from))
                                            <span class="text-danger">*</span>
                                        @elseif (!isset($customers))
                                            <span class="text-danger">*</span>
                                        @endif

                                        @if(isset($customers->is_lead_from))
                                            {!! Form::password('password_confirmation', [
                                                'class' => 'form-control required',
                                                'autocomplete' => 'off',
                                                'equalto' => '#password',
                                                'minlength' => 8,
                                            ]) !!}
                                        @else
                                            {!! Form::password('password_confirmation', [
                                                'class' => !empty($customers) ? 'form-control' : 'form-control required',
                                                'autocomplete' => 'off',
                                                'equalto' => '#password',
                                                'minlength' => 8,
                                            ]) !!}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade slide" id="account-address-6" role="tabpanel"
                                aria-labelledby="account-address-tab-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <h3>{{ __('Address') }}</h3>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    {!! Form::label('address_line', trans('Address Line')) !!}<i class="text-danger">*</i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    {!! Form::textarea('address_line', null, [
                                                        'class' => 'form-control required',
                                                        'id' => 'address_line',
                                                        'rows' => 3,
                                                        'placeholder' => 'Address Line',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    {!! Form::label('country_id ', trans('common.country')) !!}<i class="text-danger">*</i>
                                                    {!! Form::select('country_id', ['' => 'select'] + $countries, null, [
                                                        'class' => 'form-control required',
                                                        'id' => 'country',
                                                        'style' => 'width: 100%;',
                                                        'data-placeholder' => 'Select Country',
                                                    ]) !!}

                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    {!! Form::label('state_id', trans('common.state')) !!}<i class="text-danger">*</i>
                                                    {!! Form::select('state_id', ['' => 'select'] + $states, null, [
                                                        'class' => 'form-control required',
                                                        'id' => 'state',
                                                        'style' => 'width: 100%;',
                                                        'data-placeholder' => 'Select State',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    {!! Form::label('city_id', trans('common.city')) !!}<i class="text-danger">*</i>
                                                    {!! Form::select('city_id', ['' => 'select'] + $cities, null, [
                                                        'class' => 'form-control required',
                                                        'id' => 'city',
                                                        'style' => 'width: 100%;',
                                                        'data-placeholder' => 'Select City',
                                                    ]) !!}
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="form-group">
                                                    {!! Form::label('pincode', trans('common.pincode')) !!}<i class="text-danger">*</i>
                                                    {!! Form::text('pincode', null, [
                                                        'class' => 'form-control required number',
                                                        'maxlength' => '6',
                                                        'minlength' => '5',
                                                        'id' => 'pincode',
                                                        'placeholder' => 'Pincode',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    {!! Form::label('phone', trans('common.phone')) !!}
                                                    {!! Form::text('phone', null, [
                                                        'class' => 'form-control number',
                                                        'minlength' => '10',
                                                        'maxlength' => '10',
                                                        'id' => 'phone',
                                                        'placeholder' => 'Phone (Optional)',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade slide last" id="document-9" role="tabpanel"
                                aria-labelledby="document-tab-9">

                                <div class="row">
                                    <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('aadhar_card_no', trans('employee.aadhar')) !!}<i class="text-danger">*</i>
                                        {!! Form::number('aadhar_card_no', null, [
                                            'class' => 'form-control number aadhar_card_no jsOptionRequired required',
                                            'maxlength' => '12',
                                            'minlength' => '12',
                                            'placeholder' => 'Aadhar Card No (Required)',
                                            'id' => 'aadhar_card_no',
                                            'pattern' => '\d{12}',
                                            'title' => 'Please enter 12 digit number',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group pt-8">
                                        {!! Form::label('aadharcard_img', trans('employee.photo')) !!} :
                                        {!! Form::file('aadharcard_img', ['id' => 'aadharcard_img']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <img alt="Logo"
                                            src="{{ isset($customers->aadharcard_img) && !empty($customers->aadharcard_img) ? asset($customers->aadharcard_img) : asset('default.jpg') }}"
                                            class="h-75 align-self-end" id="aadhar_card_preview"
                                            name="aadhar_card_preview" style="height: 100%;width: 100%;">
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

<div class="card-footer pb-5 pt-5">
    <div class="row">
        <div class="col-8 p-2">
            Alt+A = Add, Alt+S = Save, Alt+B = Back.
        </div>
        <div class="col-4 text-right">
            <a href="" class="mr-2">
                Reset
            </a>
            <button type="submit" class="btn btn-primary jsSaveCustomer saveBtn" id="btn_loader" name="saveBtn">
                {{ __('common.save') }}
            </button>
            <button class="btn btn-hover-bg-primary btn-primary mr-2" id="prevtab" type="button"
                data-toggle="tab" style="display: none;">
                Prev
            </button>
            <button class="btn btn-hover-bg-primary btn-primary mr-2" id="nexttab" type="button"
                data-toggle="tab">
                Next
            </button>
        </div>

    </div>

</div>

@section('styles')
    <style type="text/css">
        .not-valid-tab {
            border: 1px solid !important;
            border-color: red !important;
        }
    </style>
@endsection

@php
    $divArr = [
        ['id' => 'basic_detail-5', 'tab' => 'basic_detail-tab-5'],
        ['id' => 'account-address-6', 'tab' => 'account-address-tab-6'],
        ['id' => 'document-9', 'tab' => 'document-tab-9'],
    ];
@endphp

@section('scripts')
    @include('customers.script')
@endsection
