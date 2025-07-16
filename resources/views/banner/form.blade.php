@extends('app-modal')
@section('modal-title', !isset($banner) ? __('banner.add_banner') : __('banner.edit_banner'))
@section('modal-content')
    @php
        $required = '';
        if (!isset($banner)) {
            $required = 'required';
        }
    @endphp
    <div class="form-group">
        {{ Form::label('title', __('banner.title')) }}<i class="text-danger">*</i>
        {{ Form::text('title', null, ['class' => 'form-control required']) }}
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                {!! Form::label('image', trans('banner.image')) !!}<i class="text-danger">*</i>
                {!! Form::file('image', ['class' => $required, 'id' => 'image']) !!}
            </div>
        </div>
        <div class="col">
            <img alt="Logo"
                src="{{ isset($banner->image) && !empty($banner->image) ? asset($banner->image) : asset('/media/users/no-image.png') }}"
                class="h-75 align-self-end" id="image_preview" name="image_preview" style="width: 50%;">
        </div>
    </div>
@section('modal-btn', !isset($banner) ? __('common.save') : __('common.update'))
@endsection

@include('banner.script')
