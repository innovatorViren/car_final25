@php
    if ($plan) {
        $readonly = 'readonly';
        $disabled = 'disabled';
    } else {
        $readonly = '';
        $disabled = '';
    }
@endphp
@include('components.error')
<div class="card card-custom gutter-b">
    <div class="card-body">
        <div class="row">
            <div class="form-group col-lg-4">
                {!! Form::label('name', 'Plan Name') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
            </div>
            <div class="form-group col-lg-4">
                {!! Form::label('car_size', 'Car Size') !!}<span class="text-danger">*</span>
                {!! Form::select('car_size_id', ['' => 'Select Car Size'] + $carSizes, null, [
                    'class' => 'form-control required',
                    'id' => 'car_size_id',
                    'style' => 'width: 100%;',
                    'data-placeholder' => 'Select Car Size'
                ]) !!}
            </div>
            <div class="form-group col-lg-4">
                {!! Form::label('frequency', 'Wash Frequency') !!}<span class="text-danger">*</span>
                {!! Form::select('frequency',  ['' => 'Select Frequency'] + $frequency, null, [
                    'class' => 'form-control required', 
                    'id' => 'frequency',
                    'style' => 'width: 100%;',
                    'data-placeholder' => 'Select Frequency'
                ]) !!}
            </div>
        </div>
         <div class="row">
            <div class="form-group col-lg-4">
                {!! Form::label('price', 'Price') !!}<span class="text-danger">*</span>
                {!! Form::number('price', null, ['class' => 'form-control', 'step' => '0.01', 'required']) !!}
            </div>
            <div class="form-group col-lg-4">
                {!! Form::label('description', 'Description') !!}
                {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 2]) !!}
            </div>
        </div>
    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col-12 text-right">
                <a href="" class="mr-2">{{ __('common.cancel') }}</a>
                <button type="submit" class="btn btn-primary saveBtn jsBtnLoader"
                    name="saveBtn">{{ __('common.save') }}</button>
                <button type="submit" class="btn btn-primary saveExitBtn jsBtnLoader"
                    name="saveExitBtn">{{ __('common.save_exit') }}</button>
            </div>
        </div>
    </div>
</div>
