<div class="card card-custom gutter-b">
    <div class="card-body">
        <div class="row col-lg-12">
            <h3>{{ $generateCode ?? $employee['employee_code'] }}</h3>
        </div>
        <div class="example mb-10 mt-5">

            <div class="row">
                <div class="col-lg-3 nextPrev">
                    <!--begin::Navigation-->
                    <ul class="navi navi-link-rounded navi-accent navi-hover navi-active nav flex-column mb-8 mb-lg-0"
                        role="tablist">
                        <!--begin::Nav Item-->
                        <li class="navi-item mb-2">
                            <a class="navi-link active" id="employee_information-tab-5" data-toggle="tab"
                                href="#employee_information-5">
                                <span class="nav-icon mr-3">
                                    <i class="flaticon2-rocket-1"></i>
                                </span>
                                <span class="navi-text">{{ __('employee.employee_information') }}</span>
                            </a>
                        </li>
                        <!--end::Nav Item-->
                        <!--begin::Nav Item-->
                        <li class="navi-item mb-2">
                            <a class="navi-link" id="contact_information-tab-5" data-toggle="tab"
                                href="#contact_information-5" aria-controls="contact_information">
                                <span class="nav-icon mr-3">
                                    <i class="flaticon2-rocket-1"></i>
                                </span>
                                <span class="navi-text">{{ __('employee.contact_information') }}</span>
                            </a>
                        </li>
                        <!--end::Nav Item-->
                        <!--begin::Nav Item-->
                        <li class="navi-item mb-2">
                            <a class="navi-link" id="document_information-tab-5" data-toggle="tab"
                                href="#document_information-5" aria-controls="document_information">
                                <span class="nav-icon mr-3">
                                    <i class="flaticon2-rocket-1"></i>
                                </span>
                                <span class="navi-text">{{ __('employee.document_information') }}</span>
                            </a>
                        </li>
                        <!--end::Nav Item-->
                        <!--begin::Nav Item-->
                        <li class="navi-item mb-2">
                            <a class="navi-link" id="medical_information-tab-6" data-toggle="tab"
                                href="#medical_information-6" aria-controls="medical_information">
                                <span class="nav-icon mr-3">
                                    <i class="flaticon2-rocket-1"></i>
                                </span>
                                <span class="navi-text">{{ __('employee.medical_information') }}</span>
                            </a>
                        </li>
                        <!--end::Nav Item-->

                        <!--begin::Nav Item-->
                        <li class="navi-item mb-2">
                            <a class="navi-link" id="bank_information-tab-8" data-toggle="tab"
                                href="#bank_information-8" aria-controls="bank_information">
                                <span class="nav-icon mr-3">
                                    <i class="flaticon2-rocket-1"></i>
                                </span>
                                <span class="navi-text">{{ __('employee.bank_information') }}</span>
                            </a>
                        </li>
                        <!--end::Nav Item-->
                        <!--begin::Nav Item-->
                        <li class="navi-item mb-2">
                            <a class="navi-link" id="job_information-tab-10" data-toggle="tab"
                                href="#job_information-10" aria-controls="job_information">
                                <span class="nav-icon mr-3">
                                    <i class="flaticon2-rocket-1"></i>
                                </span>
                                <span class="navi-text">{{ __('employee.job_information') }}</span>
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
                        <div class="tab-pane fade show active first slide active-slide" id="employee_information-5"
                            role="tabpanel" aria-labelledby="employee_information-tab-5">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('first_name', trans('employee.first_name')) !!} <i class="text-danger">*</i>
                                        {!! Form::text('first_name', null, ['class' => 'form-control required', 'placeholder' => 'First Name']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('first_name', trans('employee.middle_name')) !!}
                                        {!! Form::text('middle_name', null, ['class' => 'form-control', 'placeholder' => 'Middle Name']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('first_name', trans('employee.last_name')) !!} <i class="text-danger">*</i>
                                        {!! Form::text('last_name', null, ['class' => 'form-control required', 'placeholder' => 'Last Name']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {!! Form::label('person_name', trans('employee.full_name')) !!} <i class="text-danger">*</i>
                                        {!! Form::text('person_name', null, [
                                            'class' => 'form-control required',
                                            'placeholder' => trans('employee.full_name'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! Form::checkbox('is_create_user', null, true, ['id' => 'is_create_user', 'disabled']) !!}
                                        {!! Form::label('email', trans('employee.create_user')) !!}
                                        <span class="text-danger showEmailAsterix">*</span>
                                        {!! Form::text('email', null, [
                                            'class' => 'form-control email jsOptionRequired required',
                                            'data-rule-remote' => route('checkEmployeeDuplicateEmail', [$employee->id ?? '']),
                                            'data-msg-remote' => 'Email already exists',
                                            'placeholder' => 'Email (Required)',
                                            'id' => 'email',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! Form::label('mobile', trans('common.mobile')) !!}<i class="text-danger">*</i>
                                        {!! Form::text('mobile', null, [
                                            'class' => 'form-control mobile jsOptionRequired required',
                                            'data-rule-remote' => route('checkEmployeeDuplicateMobileNo', [$employee->id ?? '']),
                                            'data-msg-remote' => 'Mobile No. already exists',
                                            'id' => 'mobile',
                                            'placeholder' => '9xxxxxxxxx',
                                            'title' => 'Please enter a 10-digit mobile number.',
                                            'pattern' => '(^[0-9]{10}$)',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="form-group col-lg-4">
                                    {!! Form::label('password', trans('users.form.password')) !!}
                                    @if (!isset($employee))
                                        <span class="text-danger">*</span>
                                    @endif
                                    {!! Form::password('password', [
                                        'class' => !empty($employee) ? 'form-control' : 'form-control required',
                                        'autocomplete' => 'off',
                                        'id' => 'password',
                                        'minlength' => 8,
                                    ]) !!}
                                    <small>{{ __('users.form.password_note') }}</small>
                                </div>
                                <div class="form-group col-lg-4">
                                    {!! Form::label('password_confirmation', trans('users.form.confirm_password')) !!}
                                    @if (!isset($employee))
                                        <span class="text-danger">*</span>
                                    @endif
                                    {!! Form::password('password_confirmation', [
                                        'class' => !empty($employee) ? 'form-control' : 'form-control required',
                                        'autocomplete' => 'off',
                                        'equalto' => '#password',
                                        'minlength' => 8,
                                    ]) !!}
                                </div>
                                <div class="form-group col-lg-4">
                                    {!! Form::label('branch_id', trans('Branch')) !!}<i class="text-danger">*</i>
                                    {{ Form::select('branch_id', ['' => 'Select Branch'] + $branchList, null, [
                                                'class' => 'form-control required',
                                                'id' => 'branch_list_id',
                                                'data-placeholder' => 'Select Branch',
                                        ]) 
                                    }}
                                    
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('gender', trans('employee.gender')) !!} <i class="text-danger">*</i>

                                        <div class="radio-inline">
                                            <label class="radio">
                                                {{ Form::radio('gender', 'Male', true, ['class' => 'form-check-input', 'id' => 'male']) }}
                                                <span></span>{{ __('employee.male') }}
                                            </label>

                                            <label class="radio">
                                                {{ Form::radio('gender', 'Female', '', ['class' => 'form-check-input', 'id' => 'female']) }}
                                                <span></span>{{ __('employee.female') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('birth_date', trans('employee.date_of_birth')) !!} <i class="text-danger">*</i>
                                        {!! Form::date(
                                            'birth_date',
                                            isset($employee->birth_date) ? date('Y-m-d', strtotime($employee->birth_date)) : null,
                                            [
                                                'class' => 'form-control required',
                                                'id' => 'birth_date',
                                                'max' => custom_date_format(now()->subDay(), 'Y-m-d'),
                                                'min' => '1900-01-01',
                                            ],
                                        ) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('age', trans('employee.age_years')) !!} <i class="text-danger">*</i>
                                        {!! Form::number('age', null, [
                                            'class' => 'form-control required',
                                            'readonly' => 'readonly',
                                            'id' => 'age',
                                            'placeholder' => 'Age',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('marital_status', trans('employee.marital_status')) !!} <i class="text-danger">*</i>
                                        {!! Form::select('marital_status', ['' => 'Select'] + $maritalstatus, null, [
                                            'class' => 'form-control required ',
                                            'data-placeholder' => 'Select Marital Status',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('hobbies', trans('employee.hobbies')) !!}
                                        {!! Form::text('hobbies', null, [
                                            'class' => 'form-control',
                                            'id' => 'hobbies',
                                            'placeholder' => 'Hobbies',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('photo', trans('employee.photo')) !!}
                                        {!! Form::file('photo', null, ['id' => 'photo']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('reference', trans('employee.reference')) !!}
                                        {!! Form::text('reference', null, [
                                            'class' => 'form-control',
                                            'id' => 'reference',
                                            'placeholder' => 'Reference',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('reference_tel_no', trans('employee.reference_no')) !!}
                                        {!! Form::text('reference_tel_no', null, [
                                            'class' => 'form-control number',
                                            'id' => 'reference_tel_no',
                                            'placeholder' => 'Reference Tel No',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">

                                        <img alt="Logo"
                                            src="{{ isset($employee->photo_path) && !empty($employee->photo_path) ? asset($employee->photo_path) : asset('/media/svg/avatars/001-boy.svg') }}"
                                            class="h-75 align-self-end pt-3" id="emp_photo_preview"
                                            name="emp_photo_preview" style="height: 40%;width: 40%;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade slide" id="contact_information-5" role="tabpanel"
                            aria-labelledby="contact_information-tab-5">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="form-group col-lg-12 pt-7">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                {!! Form::label('present_address', trans('employee.present_address')) !!}<i class="text-danger">*</i>
                                                {!! Form::textarea('present_address', null, [
                                                    'class' => 'form-control required',
                                                    'rows' => 5,
                                                    'placeholder' => 'Present Address',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! Form::label('present_state', trans('common.state')) !!}<i class="text-danger">*</i>
                                                <div>
                                                    {!! Form::select(
                                                        'present_state',
                                                        ['' => 'select'] + $present_state_id,
                                                        isset($employee) ? $employee['present_state_id'] : null,
                                                        [
                                                            'class' => 'form-control required',
                                                            'data-placeholder' => 'Select State',
                                                        ],
                                                    ) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! Form::label('present_city', trans('common.city')) !!}<i class="text-danger">*</i>
                                                <div>
                                                    {{ Form::select('present_city', ['' => 'select'] + $present_city, isset($employee) ? $employee['present_city'] : null, ['class' => 'form-control required select2', 'data-placeholder' => 'Select City']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! Form::label('present_pincode', trans('common.pincode')) !!}
                                                {!! Form::text('present_pincode', null, [
                                                    'class' => 'form-control number',
                                                    'placeholder' => 'Pincode',
                                                ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! Form::label('mobile1', 'Mobile 1') !!}<i class="text-danger">*</i>
                                                {!! Form::text('mobile1', null, [
                                                    'class' => 'form-control required number',
                                                    'minlength' => 10,
                                                    'maxlength' => 10,
                                                    'placeholder' => '9876543210',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">

                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label class="checkbox checkbox-square">
                                                {!! Form::checkbox('same_as_present', '1', null, [
                                                    'id' => 'same_as_present',
                                                    'class' => 'same_as_present',
                                                ]) !!}
                                                Same as Present Address
                                                &nbsp;&nbsp;<span></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                {!! Form::label('permanent_address', trans('employee.permanent_address')) !!}<i class="text-danger">*</i>
                                                {!! Form::textarea('permanent_address', null, [
                                                    'class' => 'form-control required',
                                                    'rows' => 5,
                                                    'placeholder' => 'Permanent Address',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! Form::label('permanent_state', trans('common.state')) !!}<i class="text-danger">*</i>
                                                <div>
                                                    {{ Form::select('permanent_state', ['' => 'select'] + $permanent_state_id, isset($employee) ? $employee['permanent_state_id'] : null, ['class' => 'form-control required', 'data-placeholder' => 'Select State']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! Form::label('permanent_city', trans('common.city')) !!}<i class="text-danger">*</i>
                                                <div>
                                                    {{ Form::select('permanent_city', ['' => 'select'] + $permanent_city, isset($employee) ? $employee['permanent_city'] : null, ['class' => 'form-control required', 'data-placeholder' => 'Select City']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! Form::label('permanent_pincode', trans('common.pincode')) !!}
                                                {!! Form::text('permanent_pincode', null, [
                                                    'class' => 'form-control number',
                                                    'placeholder' => 'Pincode',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade slide" id="document_information-5" role="tabpanel"
                            aria-labelledby="document_information-tab-5">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('uan_no', trans('employee.uan')) !!}
                                        {!! Form::text('uan_no', null, [
                                            'class' => 'form-control jsOptionRequired',
                                            'maxlength' => '12',
                                            'minlength' => '12',
                                            'placeholder' => 'UAN No (Optional)',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                            @php
                                if (isset($parentId)) {
                                    $readonly = 'readonly';
                                    $formControl = 'form-control-solid';
                                } else {
                                    $readonly = '';
                                    $formControl = '';
                                }
                            @endphp
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('aadhar_card_no', trans('employee.aadhar')) !!}<i class="text-danger">*</i>
                                        {!! Form::number('aadhar_card_no', null, [
                                            'class' => 'form-control number aadhar_card_no jsOptionRequired required' . ' ' . $formControl,
                                            'maxlength' => '12',
                                            'minlength' => '12',
                                            'data-rule-remote' => route('checkDuplicateAdhar', [$employee->id ?? '']),
                                            'data-msg-remote' => 'The aadhar number has already been taken.',
                                            'placeholder' => 'Aadhar Card No (Required)',
                                            'id' => 'aadhar_card_no',
                                            'pattern' => '\d{12}',
                                            'title' => 'Please enter 12 digit number',
                                            $readonly,
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
                                            src="{{ isset($employee->employeeDocument) && !empty($employee->employeeDocument->aadharcard_img_path) ? asset($employee->employeeDocument->aadharcard_img_path) : asset('default.jpg') }}"
                                            class="h-75 align-self-end" id="aadharcard_img_preview"
                                            name="aadharcard_img_preview" style="height: 60%;width: 60%;">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('driving_licence_no', trans('employee.driving_licence')) !!}
                                        {!! Form::text('driving_licence_no', null, [
                                            'class' => 'form-control',
                                            'maxlength' => '20',
                                            'placeholder' => 'Driving Licence No (Optional)',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group pt-8">
                                        {!! Form::label('drivinglicence_img', trans('employee.photo')) !!} :
                                        {!! Form::file('drivinglicence_img', ['id' => 'drivinglicence_img']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <img alt="Logo"
                                            src="{{ isset($employee->employeeDocument) && !empty($employee->employeeDocument->drivinglicence_img_path) ? asset($employee->employeeDocument->drivinglicence_img_path) : asset('default.jpg') }}"
                                            class="h-75 align-self-end" id="drivinglicence_img_preview"
                                            name="drivinglicence_img_preview" style="height: 60%;width: 60%;">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('pan_card_no', trans('common.pan_no')) !!}
                                        {!! Form::text('pan_card_no', null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Pan Card No (Optional)',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group pt-8">
                                        {!! Form::label('pancard_img', trans('employee.photo')) !!} :
                                        {!! Form::file('pancard_img', ['id' => 'pancard_img']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <img alt="Logo"
                                            src="{{ isset($employee->employeeDocument) && !empty($employee->employeeDocument->pancard_img_path) ? asset($employee->employeeDocument->pancard_img_path) : asset('default.jpg') }}"
                                            class="h-75 align-self-end" id="pancard_img_preview"
                                            name="pancard_img_preview" style="height: 60%;width: 60%;">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('passport_no', trans('employee.passport_no')) !!}
                                        {!! Form::text('passport_no', null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Passport No (Optional)',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group pt-8">
                                        {!! Form::label('passport_img', trans('employee.photo')) !!} :
                                        {!! Form::file('passport_img', [
                                            'id' => 'passport_img',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <img alt="Logo"
                                            src="{{ isset($employee->employeeDocument) && !empty($employee->employeeDocument->passport_img_path) ? asset($employee->employeeDocument->passport_img_path) : asset('default.jpg') }}"
                                            class="h-75 align-self-end" id="passport_img_preview"
                                            name="passport_img_preview" style="height: 60%;width: 60%;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade slide" id="medical_information-6" role="tabpanel"
                            aria-labelledby="medical_information-tab-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! Form::label('strengths', trans('employee.strengths')) !!}
                                        {!! Form::text('strengths', null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Strengths (Optional)',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! Form::label('weakness', trans('employee.weakness')) !!}
                                        {!! Form::text('weakness', null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Weakness (Optional)',
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::label('blood_group', trans('employee.blood_group')) !!}
                                        <div>
                                            {!! Form::select('blood_group', ['' => 'Select'] + $bloodgroup, null, [
                                                'class' => 'form-control',
                                                'data-placeholder' => 'Select Blood Group',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade slide" id="bank_information-8" role="tabpanel"
                            aria-labelledby="bank_information-tab-8">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! Form::label('account_no', trans('employee.account_no')) !!}
                                        {!! Form::text('account_no', null, [
                                            'class' => 'form-control number jsOptionRequired',
                                            'placeholder' => 'Account No (Optional)',
                                            'id' => 'account_no',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! Form::label('bank_name', trans('employee.bank_name')) !!}
                                        {!! Form::text('bank_name', null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Bank Name (Optional)',
                                        ]) !!}
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! Form::label('beneficiary_name', trans('employee.beneficiary_name')) !!}
                                        {!! Form::text('beneficiary_name', null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Beneficiary Name (Optional)',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {!! Form::label('branch_name', trans('employee.branch_name')) !!}
                                        {!! Form::text('branch_name', null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Branch Name (Optional)',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {!! Form::label('ifsc_code', trans('employee.ifsc_code')) !!}
                                        {!! Form::text('ifsc_code', null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'IFSC Code (Optional)',
                                        ]) !!}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane fade slide last" id="job_information-10" role="tabpanel"
                            aria-labelledby="job_information-tab-10">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        {!! Form::label('experience', trans('employee.previous_emp_with_year')) !!}
                                        {!! Form::textarea('experience', null, [
                                            'class' => 'form-control',
                                            'rows' => 3,
                                            'placeholder' => 'Write something about previous experience (if any)',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        {!! Form::label('total_experience', trans('employee.past_total_experience')) !!}<i class="text-danger">*</i>
                                        {!! Form::text('total_experience', null, [
                                            'class' => 'form-control required',
                                            'id' => 'total_experience',
                                            'placeholder' => 'Total Experience',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        {!! Form::label('join_date', trans('employee.join_date')) !!}<i class="text-danger">*</i>
                                        {!! Form::date(
                                            'join_date',
                                            isset($employee->join_date) && !isset($parentId) ? date('Y-m-d', strtotime($employee->join_date)) : null,
                                                ['class' => 'form-control jsJoinDate required', isset($employee->join_date) && !isset($parentId) ? 'readonly' : '',
                                                ],
                                        ) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        {!! Form::label('department_id', trans('employee.department')) !!}<i class="text-danger">*</i>
                                        <div>
                                            {{ Form::select('department_id', ['' => 'select'] + $department, null, [
                                                'class' => 'form-control required',
                                                'id' => 'department_id',
                                                'data-placeholder' => 'Select Department',
                                                'data-ajaxurl' => route('getDesignation'),
                                            ]) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        {!! Form::label('designation_id', trans('employee.designation')) !!}<i class="text-danger">*</i>
                                        <div>
                                            {!! Form::hidden('designation_id', $employee->designation_id ?? null, ['id' => 'designationid']) !!}

                                            {!! Form::select('designation_id', [], null, [
                                                'class' => 'form-control designation_id required',
                                                'id' => 'designation_id',
                                                'data-placeholder' => 'Select Designation',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row row-cols-2">
                                <div class="col emp_customers_div d-none">
                                    <div class="form-group">
                                        {!! Form::label('emp_customers', trans('customers.customers')) !!}
                                        <div>
                                            {!! Form::select('emp_customers[]', $customers, $employee_customers ?? '', [
                                                'class' => 'form-control emp_customers d-none',
                                                'id' => 'emp_customers',
                                                'data-placeholder' => 'Select Customers',
                                                'multiple' => 'multiple',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        {!! Form::label('appointed_by', trans('Reference By')) !!}
                                        <div>
                                            {{ Form::select(
                                                'appointed_by',
                                                ['' => 'select'] + $appointedBy,
                                                isset($employee) ? $employee['appointed_by'] : null,
                                                [
                                                    'class' => 'form-control',
                                                    'id' => 'appointed_by',
                                                    'data-placeholder' => 'Select Reference By',
                                                ],
                                            ) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        {!! Form::label('designation_of_appointee', 'Designation of Reference By') !!}
                                        {!! Form::text('designation_of_appointee', null, [
                                            'class' => 'form-control',
                                            'readonly' => 'readonly',
                                            'id' => 'designation_of_appointee',
                                            'placeholder' => 'Designation of Reference By  (Auto Fill)',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Accordion-->
                    </div>
                    <!--end::Tab Content-->
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
            <button name="saveBtn" type="submit" class="btn btn-primary jsSaveEmployee mr-2 saveBtn">
                Save
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
        .select2-container {
            width: 100% !important;
        }

        .not-valid-tab {
            border: 1px solid !important;
            border-color: red !important;
        }
    </style>
@endsection

@php
    $divArr = [
        ['id' => 'employee_information-5', 'tab' => 'employee_information-tab-5'],
        ['id' => 'contact_information-5', 'tab' => 'contact_information-tab-5'],
        ['id' => 'document_information-5', 'tab' => 'document_information-tab-5'],
        ['id' => 'medical_information-6', 'tab' => 'medical_information-tab-6'],
        ['id' => 'bank_information-8', 'tab' => 'bank_information-tab-8'],
        ['id' => 'job_information-10', 'tab' => 'job_information-tab-10'],
    ];
@endphp

@section('scripts')
    @include('employee.script')
@endsection
