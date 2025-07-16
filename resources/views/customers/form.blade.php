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
                                <a class="navi-link" id="bank_detail-tab-7" data-toggle="tab" href="#bank_detail-7"
                                    aria-controls="bank_detail">
                                    <span class="nav-icon mr-3">
                                        <i class="flaticon-notes"></i>
                                    </span>
                                    <span class="navi-text">{{ __('customers.form.bank_detail') }}</span>
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
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            {!! Form::label('pan_no', trans('common.pan_no')) !!}
                                            {!! Form::text('pan_no', null, [
                                                'class' => 'form-control pan_no jsOptionRequired',
                                                'data-rule-remote' => route('checkCustomerDuplicatePanNo', [$customers->id ?? '']),
                                                'data-msg-remote' => 'PAN No. already exists',
                                                'title' => 'PAN No. should be in the format ABxxxxxx4F',
                                                'pattern' => '(^([a-zA-Z]{5})([0-9]{4})([a-zA-Z]{1})$)',
                                                'placeholder' => 'ABCDE1234F',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            {!! Form::label('gst_type', trans('customers.gst_type')) !!}<i class="text-danger">*</i>
                                            {!! Form::select('gst_type', ['' => 'select'] + $gst_type, null, [
                                                'class' => 'form-control required',
                                                'id' => 'gst_type',
                                                'style' => 'width: 100%;',
                                                'data-placeholder' => 'Select GST Type',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group gstData">
                                            {!! Form::label('gst_no', trans('common.gst_no')) !!}<i class="text-danger">*</i>
                                            {!! Form::text('gst_no', null, [
                                                'class' => 'form-control gst_no jsOptionRequired required',
                                                'data-rule-remote' => route('checkCustomerDuplicateGstNo', [$customers->id ?? '']),
                                                'data-msg-remote' => 'GST No. already exists',
                                                'pattern' => '(^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$)',
                                                'placeholder' => '24EWHTQ3432S7Z6',
                                                'id' => 'gst_no',
                                                'title' => 'GST No. should be in the format 24xxxxxxxxxxxZ6',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {!! Form::label('company_name', trans('customers.company_name')) !!} <i class="text-danger">*</i>
                                            {!! Form::text('company_name', null, [
                                                'class' => 'form-control company_name jsOptionRequired required',
                                                'data-rule-remote' => route('checkCustomerDuplicateCompanyName', [$customers->id ?? '']),
                                                'data-msg-remote' => 'Company Name already exists',
                                                'placeholder' => 'Company Name',
                                                'id' => 'company_name',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            {!! Form::label('person_name', trans('customers.person_name')) !!} <i class="text-danger">*</i>
                                            {!! Form::text('person_name', null, [
                                                'class' => 'form-control required',
                                                'placeholder' => 'Person Name',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
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
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            {!! Form::label('price_list_id', trans('price.price_list')) !!} <i class="text-danger">*</i>
                                            {{ Form::select('price_list_id', ['' => 'Select Price List'] + $priceList, null, [
                                                'class' => 'form-control required',
                                                'id' => 'price_list_id',
                                                'data-placeholder' => 'Select Price List',
                                            ]) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            {!! Form::label('branch_id', trans('Branch')) !!} <i class="text-danger">*</i>
                                            {{ Form::select('branch_id', ['' => 'Select Branch'] + $branchList, null, [
                                                'class' => 'form-control required',
                                                'id' => 'branch_list_id',
                                                'data-placeholder' => 'Select Branch',
                                            ]) }}
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

                                <div class="row">
                                    <div class="col-lg-4 col-4">
                                        <div class="form-group">
                                            {!! Form::label('credit_days', trans('customers.credit_day')) !!}
                                            {!! Form::text('credit_days', null, [
                                                'class' => 'form-control number',
                                                'id' => 'credit_days',
                                                'placeholder' => '0-99 (Optional)',
                                                'min' => 0,
                                                'max' => 99,
                                                'title' => 'Credit Days should be in the range of 0 to 99',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-4">
                                        <div class="form-group">
                                            {!! Form::label('credit_limit', trans('customers.credit_limit')) !!}
                                            {!! Form::text('credit_limit', null, [
                                                'class' => 'form-control number',
                                                'id' => 'credit_limit',
                                                'placeholder' => '0-10000000 (Optional)',
                                                'min' => 0,
                                                'max' => 10000000,
                                                'title' => 'Credit Limit should be in the range of 0 to 10000000',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-4 fssai_no">
                                        <div class="form-group">
                                            {!! Form::label('fssai_no', trans('FSSAI No.')) !!}
                                            {!! Form::text('fssai_no', null, [
                                                'class' => 'form-control fssai_no ',
                                                'data-rule-remote' => route('checkCustomerDuplicateFssaiNo', [$customers->id ?? '']),
                                                'data-msg-remote' => 'FSSAI No. already exists',
                                                'id' => 'fssai_no',
                                                'placeholder' => 'ex 208xxxxxxxxx11  (Optional)',
                                                'title' => 'FSSAI No. should be 14 characters long and should be in the format 208xxxxxxxxx11',
                                                'pattern' => '(^[0-9]{14}$)',
                                            ]) !!}
                                        </div>
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
                                                    {!! Form::label('address_line1', trans('Address Line 1')) !!}<i class="text-danger">*</i>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    {!! Form::label('address_line2', trans('Address Line 2')) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    {!! Form::textarea('address_line1', null, [
                                                        'class' => 'form-control required',
                                                        'id' => 'address_line1',
                                                        'rows' => 3,
                                                        'placeholder' => 'Address Line 1',
                                                    ]) !!}
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    {!! Form::textarea('address_line2', null, [
                                                        'class' => 'form-control',
                                                        'id' => 'address_line2',
                                                        'rows' => 3,
                                                        'placeholder' => 'Address Line 2 (Optional)',
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
                                                    {!! Form::label('mobile2', trans('customers.mobile2')) !!}
                                                    {!! Form::text('mobile2', null, [
                                                        'class' => 'form-control mobile2 jsOptionRequired',
                                                        // 'data-rule-remote' => route('checkCustomerDuplicateMobileNo', [$customers->id ?? '']),
                                                        // 'data-msg-remote' => 'Mobile No. already exists',
                                                        'id' => 'mobile2',
                                                        'placeholder' => '9xxxxxxxxx (Optional)',
                                                        'title' => 'Please enter a 10-digit mobile number.',
                                                        'pattern' => '(^[0-9]{10}$)',
                                                    ]) !!}
                                                </div>
                                            </div>
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
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    {!! Form::label('phone2', trans('Phone 2')) !!}
                                                    {!! Form::text('phone2', null, [
                                                        'class' => 'form-control number',
                                                        'minlength' => '10',
                                                        'maxlength' => '10',
                                                        'id' => 'phone2',
                                                        'placeholder' => 'phone 2 (Optional)',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade slide" id="bank_detail-7" role="tabpanel"
                                aria-labelledby="bank_detail-tab-7">

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {!! Form::label('account_no', trans('customers.form.account_no')) !!}
                                            {!! Form::text('account_no', null, [
                                                'class' => 'form-control number',
                                                'placeholder' => 'Account No',
                                                'id' => 'account_no',
                                                'pattern' => '(^[0-9]{9,18}$)',
                                                'title' => 'Account No should be 9 to 18 digits long',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {!! Form::label('ifsc_code', trans('customers.form.ifsc_code')) !!}
                                            {!! Form::text('ifsc_code', null, [
                                                'class' => 'form-control',
                                                'placeholder' => 'IFSC Code',
                                                'id' => 'ifsc_code',
                                                'pattern' => '(^[A-Za-z]{4}0[A-Z0-9a-z]{6}$)',
                                                'title' => 'IFSC Code should be in the format ABCD0123456',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {!! Form::label('beneficiary_name', trans('employee.beneficiary_name')) !!}
                                            {!! Form::text('beneficiary_name', null, [
                                                'class' => 'form-control',
                                                'placeholder' => 'Beneficiary Name',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            {!! Form::label('branch_name', trans('customers.form.branch_name')) !!}
                                            {!! Form::text('branch_name', null, [
                                                'class' => 'form-control',
                                                'placeholder' => 'Branch Name',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            {!! Form::label('bank_name', trans('customers.form.bank_name')) !!}
                                            {!! Form::text('bank_name', null, [
                                                'class' => 'form-control',
                                                'placeholder' => 'Bank Name',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane fade slide last" id="document-9" role="tabpanel"
                                aria-labelledby="document-tab-9">

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="form-group">
                                                {!! Form::label('pan_card_photo', trans('customers.form.pan_card')) !!} <br>
                                                {!! Form::file('pan_card_photo', [
                                                    'id' => 'pan_card_photo',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="form-group">
                                                <img alt="Logo"
                                                    src="{{ isset($customers->pan_card_photo) && !empty($customers->pan_card_photo) ? asset($customers->pan_card_photo) : asset('default.jpg') }}"
                                                    class="h-75 align-self-end" id="pan_card_preview"
                                                    name="pan_card_preview" style="height: 30%;width: 30%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="form-group">
                                                {!! Form::label('gst_certificate_photo', trans('customers.form.gst_certificate')) !!}<br>
                                                {!! Form::file('gst_certificate_photo', [
                                                    'id' => 'gst_certificate_photo',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="form-group">
                                                <img alt="Logo"
                                                    src="{{ isset($customers->gst_certificate_photo) && !empty($customers->gst_certificate_photo) ? asset($customers->gst_certificate_photo) : asset('default.jpg') }}"
                                                    class="h-75 align-self-end" id="gst_certificate_preview"
                                                    name="gst_certificate_preview" style="height: 30%;width: 30%;">
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
        ['id' => 'bank_detail-7', 'tab' => 'bank_detail-tab-7'],
        ['id' => 'document-9', 'tab' => 'document-tab-9'],
    ];
@endphp

@section('scripts')
    @include('customers.script')
@endsection
