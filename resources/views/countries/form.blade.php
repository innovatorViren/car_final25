@extends('app-modal')
@section('modal-title',( !isset($country)) ? __('country.add_country') : __('country.edit_country') )
@section('modal-content')

<div class="form-group">
    {{Form::label(__('country.name'), __('country.name'))}}<i class="text-danger">*</i>
    {{Form::text('name', null,['class' => 'form-control', 'data-rule-remote' => route('country.checkUniqueName', [$country['id'] ?? '']), 'data-msg-remote' => 'The name has already been taken.']);}}
</div>

@section('modal-btn',( !isset($country)) ? __('common.save') : __('common.update') )
@endsection

@include('countries.script')