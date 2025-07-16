{!! Form::open(['route' => 'settings.store', 'id' => 'settingForm','enctype'=>'multipart/form-data']) !!}

<!--begin::Accordion-->

<div class="row">
    <div class="form-group col-lg-4">
        {{ Form::hidden('group', 'company', ['class' => 'form-control', 'required']) }}

        {{ Form::label('project_title', __('settings.project_title')) }}<i class="text-danger">*</i>
        {{ Form::text('project_title', $settings['project_title'] ?? '', ['class' => 'form-control', 'required']) }}
    </div>

    <div class="form-group col-lg-4">
        {{ Form::label('company_name', __('settings.company_name')) }}<i class="text-danger">*</i>
        {{ Form::text('company_name', $settings['company_name'] ?? '', ['class' => 'form-control', 'required']) }}
    </div>
</div>
<div class="row">
    <div class="form-group col-lg-4">
        {{ Form::label('company_email', __('settings.company_email')) }}<i class="text-danger">*</i>
        {{ Form::email('company_email', $settings['company_email'] ?? '', ['class' => 'form-control', 'required']) }}
    </div>

    <div class="form-group col-lg-4">
        {{ Form::label('company_mobile', __('settings.company_mobile')) }}<i class="text-danger">*</i>
        {{ Form::text('company_mobile', $settings['company_mobile'] ?? '', ['class' => 'form-control number', 'required', 'maxlength' => 10]) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('company_address', __('settings.company_address')) }}<i class="text-danger">*</i>
    {{ Form::textarea('company_address', $settings['company_address'] ?? '', ['class' => 'form-control', 'rows' => 2, 'id' => 'exampleTextarea', 'required']) }}
</div>

<div class="row">
    <div class="form-group col-lg-4">
        {{ Form::label('country', __('settings.country')) }}<i class="text-danger">*</i>
        {!! Form::select('country_id', ['' => 'Select Country'] + $countries, $settings['country'] ?? null, [
            'class' => 'form-control',
            'style' => 'width: 100%;',
            'id' => 'country',
            'data-placeholder' => 'Select country',
            'required',
        ]) !!}
    </div>

    <div class="form-group col-lg-4">
        {{ Form::label('state', __('settings.state')) }}<i class="text-danger">*</i>
        {!! Form::select('state_id', ['' => 'Select State'] + $states, $settings['state'] ?? null, [
            'class' => 'form-control',
            'style' => 'width: 100%;',
            'id' => 'state',
            'data-placeholder' => 'Select state',
            'required',
        ]) !!}
    </div>
    <div class="form-group col-lg-4">
        {{ Form::label('city', __('settings.city')) }}<i class="text-danger">*</i>
        {!! Form::select('city_id', ['' => 'Select City'] + $cities, $settings['city'] ?? null, [
            'class' => 'form-control',
            'style' => 'width: 100%;',
            'id' => 'city',
            'data-placeholder' => 'Select city',
            'required',
        ]) !!}
    </div>
</div>

<div class="row">
    <div class="form-group col-lg-4">
        {{ Form::label('pincode', __('settings.pincode')) }}<i class="text-danger">*</i>
        {{ Form::text('pincode', $settings['pincode'] ?? '', ['class' => 'form-control', 'required']) }}
    </div>

    <div class="form-group col-lg-4">
        {{ Form::label('pan_no', __('settings.pan_no')) }}<i class="text-danger">*</i>
        {{ Form::text('pan_no', $settings['pan_no'] ?? '', ['class' => 'form-control', 'required', 'pattern' => '(^([a-zA-Z]{5})([0-9]{4})([a-zA-Z]{1})$)']) }}
    </div>
    <div class="form-group col-lg-4">
        {{ Form::label('gst_no', __('settings.gst_no')) }}<i class="text-danger">*</i>
        {{ Form::text('gst_no', $settings['gst_no'] ?? '', ['class' => 'form-control', 'required', 'pattern' => '(^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$)']) }}
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
            {!! Form::label('company_logo', __('settings.company_logo')) !!}
            {!! Form::file('company_logo', ['id' => 'company_logo', 
            'accept' => 'image/png, image/jpg, image/jpeg',
            'filesize' => '10485760',
            'data-msg-accept'=>'Please Upload Valid Image - Png | Jpg | Jpeg',
            'data-msg-maxsize' => 'File size must be less than 10 MB',]) !!}
        </div>
        <br>
        <div class="form-group">
            <img alt="Logo"
                src="{{ (isset($settings['company_logo']) && !empty($settings['company_logo'])) ? asset($settings['company_logo']) : asset('default.jpg') }}"
                class="h-75 align-self-end" id="logo_preview"
                name="logo_preview" style="height: 30%;width: 30%;">
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
            {!! Form::label('company_favicon', __('settings.company_favicon')) !!}
            {!! Form::file('company_favicon', ['id' => 'company_favicon', 'accept' => 'image/png, image/jpg, image/jpeg',
            'filesize' => '10485760',
            'data-msg-accept'=>'Please Upload Valid Image - Png | Jpg | Jpeg',
            'data-msg-maxsize' => 'File size must be less than 10 MB',]) !!}
        </div>
        <br>
        <div class="form-group">
            <img alt="Favicon"
                src="{{ (isset($settings['company_favicon']) && !empty($settings['company_favicon'])) ? asset($settings['company_favicon']) : asset('default.jpg') }}"
                class="h-75 align-self-end" id="flaticon_preview"
                name="flaticon_preview" style="height: 30%;width: 30%;">
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
            {!! Form::label('company_brochure', __('settings.company_brochure')) !!}
            {!! Form::file('company_brochure', ['id' => 'company_brochure', 'accept' => 'application/pdf',
            'filesize' => '10485760',
            'data-msg-accept'=>'Please Upload Valid PDF',
            'data-msg-maxsize' => 'File size must be less than 10 MB',]) !!}
        </div>
        <br>
        <div class="form-group">
            @if((isset($settings['company_brochure']) && !empty($settings['company_brochure'])) )
                <a href="{{ asset($settings['company_brochure']) }}", target="_blank">{{ __('settings.view_brochure') }}</a>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 text-right">
        <a href="" class="mr-2">{{ __('common.cancel') }}</a>
        <button type="submit" class="btn btn-primary">{{ __('common.save') }}</button>
    </div>
</div>


{!! Form::close() !!}
