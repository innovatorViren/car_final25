@extends('app-modal')
@section('modal-title', !isset($city) ? __('city.add_city') : __('city.edit_city'))
@section('modal-content')
    <div class="form-group">
        {{ Form::label('country', __('common.country')) }}<i class="text-danger">*</i>
        <div class="row">
            <div class="col-md-12">
                {!! Form::select('country_id', ['' => 'Select Country'] + $countries, null, [
                    'class' => 'form-control',
                    'id' => 'country_id',
                    'data-placeholder' => 'Select Country',
                    'required',
                ]) !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('state', __('common.state')) }}<i class="text-danger">*</i>
        <div class="row">
            <div class="col-md-12">
                {!! Form::select('state_id', ['' => 'Select State'] + $states, null, [
                    'class' => 'form-control',
                    'id' => 'state_id',
                    'data-placeholder' => 'Select State',
                    'required',
                ]) !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('name', __('city.name')) }}<i class="text-danger">*</i>
        {{ Form::text('name', null, ['class' => 'form-control']) }}
    </div>


@section('modal-btn', !isset($cities) ? __('common.save') : __('common.update'))
@endsection

@include('cities.script')
