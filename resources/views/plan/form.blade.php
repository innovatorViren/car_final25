@php
    if ($plan) {
        $readonly = 'readonly';
        $disabled = 'disabled';
    } else {
        $readonly = '';
        $disabled = '';
    }
@endphp
<div class="container-fluid">
    <div class="row">
        @include('components.error')
        <div class="col-sm-12">
            <div class="card" id="default">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-separate table-head-custom table-checkable">
                                <tbody>
                                    <tr class="data-row">
                                        <td width="150">
                                                <div class="form-group">
                                                    {!! Form::label('name', 'Plan Name') !!}
                                                    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
                                                </div>
                                        </td>
                                        <td width="150">
                                            <div class="form-group ">
                                                {!! Form::label('car_size', 'Car Size') !!}<span class="text-danger">*</span>
                                                 {!! Form::select('car_size_id', $carSizes, null, ['class' => 'form-control','id' => 'car_size_id', 'placeholder' => 'Select Car Size']) !!}
                                            </div>
                                        </td>
                                        <td width="150">
                                            <div class="form-group">

                                                {!! Form::label('frequency', 'Wash Frequency') !!}<span class="text-danger">*</span>
                                                {!! Form::select('frequency', $frequency, null, ['class' => 'form-control', 'id' => 'frequency','placeholder' => 'Select Frequency']) !!}
                                            </div>
                                        </td>
                                        <td width="150">
                                            <div class="form-group text-right">
                                                {!! Form::label('price', 'Price') !!}<span class="text-danger">*</span>
                                                {!! Form::number('price', null, ['class' => 'form-control', 'step' => '0.01', 'required']) !!}
                                            </div>
                                        </td>
                                        <td width="150">
                                            <div class="form-group">
                                                {!! Form::label('description', 'Description') !!}
                                                {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3]) !!}
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="" class="mr-2">{{ __('common.cancel') }}</a>
                            {!! Form::hidden('from_btn', null, ['class' => 'frombtn']) !!}
                            <button type="submit" class="btn btn-primary saveBtn jsBtnLoader"
                                name="saveBtn">{{ __('common.save') }}</button>
                            <button type="submit" class="btn btn-primary saveExitBtn jsBtnLoader"
                                name="saveExitBtn">{{ __('common.save_exit') }}</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
