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
                                        <td width="300">
                                            <div class="form-group ">
                                                {!! Form::label('name', trans('Name')) !!}<span class="text-danger">*</span>
                                                {!! Form::text('name', null, [
                                                    'class' => 'form-control name required',
                                                    'id' => '',
                                                    'placeholder' => 'Select Name',
                                                ]) !!}
                                            </div>
                                        </td>
                                        <td width="150">
                                            <div class="form-group text-right">
                                                {!! Form::label('per_day', trans('Per Day')) !!}<span class="text-danger">*</span>
                                                {!! Form::text('per_day', null, [
                                                    'class' => 'form-control text-right required',
                                                    'min' => '0.1',
                                                ]) !!}
                                            </div>
                                        </td>
                                        <td width="150">
                                            <div class="form-group text-right">
                                                {!! Form::label('weekly_2', trans('Weekly(2)')) !!}<span class="text-danger">*</span>
                                                {!! Form::text('weekly_2', null, [
                                                    'class' => 'form-control text-right required',
                                                    'min' => '0.1',
                                                ]) !!}
                                            </div>
                                        </td>
                                        <td width="150">
                                            <div class="form-group text-right">
                                                {!! Form::label('weekly_4', trans('Weekly(4)')) !!}<span class="text-danger">*</span>
                                                {!! Form::text('weekly_4', null, [
                                                    'class' => 'form-control text-right required',
                                                    'min' => '0.1',
                                                ]) !!}
                                            </div>
                                        </td>
                                        <td width="150">
                                            <div class="form-group text-right">
                                                {!! Form::label('one_time', trans('One Time')) !!}<span class="text-danger">*</span>
                                                {!! Form::text('one_time', null, [
                                                    'class' => 'form-control text-right required',
                                                    'min' => '0.1',
                                                ]) !!}
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
