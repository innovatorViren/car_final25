@extends('app-modal')
@section('modal-title',( !isset($carModel)) ? __('car_model.add_car_model') : __('car_model.edit_car_model') )
@section('modal-content')

<div class="form-group">
    {{Form::label(__('car_model.name'), __('car_model.name'))}}<i class="text-danger">*</i>
    {{Form::text('name', null,['class' => 'form-control', 'data-rule-remote' => route('country.checkUniqueName', [$country['id'] ?? '']), 'data-msg-remote' => 'The name has already been taken.']);}}
</div>
<div class="form-group">
    {{Form::label('brand',__('car_brand.table.car_brand'))}}<i class="text-danger">*</i>
    <div class="row">
        <div class="col-md-12">
            {!! Form::select('car_brand_id', [''=>"Select Brand"] + $carBrand, null, ['class' => 'form-control', 'id' => 'car_brand_id', 'data-placeholder' => 'Select Brand','required']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-lg-12">
        {!! Form::label('model_photo', trans('car_model.photo')) !!}
        {!! Form::file('model_photo', [
            'class' => 'form-control',
            'id' => 'photo',
            'accept' => 'image/png,image/jpg,image/jpeg',
            'filesize' => '1485760',
            'data-msg-accept' => 'Please upload file in these format only (jpg, jpeg, png).',
            'data-msg-maxsize' => 'File size must be less than 1 MB',
        ]) !!}
    </div>
    <div class="form-group col-lg-12">
        <img src="{{(isset($carModel->model_photo) && !empty($carModel->model_photo)) ? asset($carModel->model_photo)  : asset('default.jpg')}}" 
        class="img-preview img-rounded max-h-100px mt-3"id="carmodel_img_preview" style="height: 100%;width: 50%;"
        name="carmodel_img_preview"  alt="...">
        
    </div>
</div>

@section('modal-btn',( !isset($carModel)) ? __('common.save') : __('common.update') )
@endsection

@include('car-model.script')