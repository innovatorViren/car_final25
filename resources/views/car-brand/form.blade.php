@extends('app-modal')
@section('modal-title',( !isset($carBrands)) ? __('car_brand.add_car_brand') : __('car_brand.edit_car_brand') )
@section('modal-content')

<div class="form-group">
    {{Form::label(__('car_brand.name'), __('car_brand.name'))}}<i class="text-danger">*</i>
    {{Form::text('name', null,['class' => 'form-control', 'data-rule-remote' => route('country.checkUniqueName', [$country['id'] ?? '']), 'data-msg-remote' => 'The name has already been taken.']);}}
</div>
<div class="row">
    <div class="form-group col-lg-12">
        {!! Form::label('brand_logo', trans('car_brand.logo')) !!}
        {!! Form::file('brand_logo', [
            'class' => 'form-control',
            'id' => 'brand_logo',
            'accept' => 'image/png,image/jpg,image/jpeg',
            'filesize' => '1485760',
            'data-msg-accept' => 'Please upload file in these format only (jpg, jpeg, png).',
            'data-msg-maxsize' => 'File size must be less than 1 MB',
        ]) !!}
    </div>
    <div class="form-group col-lg-12">
        <img src="{{(isset($carBrand->brand_logo) && !empty($carBrand->brand_logo)) ? asset($carBrand->brand_logo)  : asset('default.jpg')}}" 
        class="img-preview img-rounded max-h-100px mt-3"id="carbrand_img_preview" style="height: 100%;width: 50%;"
        name="carbrand_img_preview"  alt="...">
        
    </div>
</div>

@section('modal-btn',( !isset($carBrands)) ? __('common.save') : __('common.update') )
@endsection

@include('car-brand.script')