@extends('app-modal')
@section('modal-title', __('employee.employee_left'))
@section('modal-content')
    {!! Form::open(['route' => 'employeeLeft', 'role' => 'form', 'id' => 'leftForm']) !!}
    {!! Form::hidden('empId', null, ['id' => 'empId']) !!}

    <div class="form-group">
        {!! Form::label('left_date', 'Left Date') !!}<i class="text-danger">*</i>
        <div>
            {!! Form::date('left_date', null, ['class' => 'form-control required','max' => '9999-12-31']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('left_reason', 'Reason') !!}
        {!! Form::textarea('left_reason', null, ['class' => 'form-control', 'rows' => 3]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('recruit_again', 'Can we recruit again?') !!}
        <div class="radio-inline ">
            <label class="radio radio-rounded">
                {{ Form::radio('recruit_again', 'Yes', null, ['class' => 'form-check-input required', 'id' => 'recruit_again']) }}
                <span></span>Yes
            </label>
            <label class="radio radio-rounded">
                {{ Form::radio('recruit_again', 'No', null, ['class' => 'form-check-input required', 'id' => 'recruit_again']) }}
                <span></span>No
            </label>
        </div>
    </div>
    
@section('modal-btn', __('common.save'))
{!! Form::close() !!}
@endsection
@include('employee.script')
