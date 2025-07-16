<div id="employeeFilter" class="modal fixed-left fade pr-0" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-aside" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{ __('common.filter') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="for-group">
                        {!! Form::label('personNameFilter', trans('employee.person_name')) !!}
                        {!! Form::select('personNameFilter', ['' => 'Select'] + $employeesData, null, [
                            'class' => 'form-control personNameFilter jsPersonNameFilter',
                            'id' => 'personNameFilter',
                            'data-placeholder' => 'Select Employee',
                        ]) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label>{{ __('employee.join_date') }}</label>
                    <div class='input-group from_to_datepicker'>
                        {!! Form::text('filterjoinDate', null, [
                            'class' => 'form-control date jsDilterJoinDate',
                            'id' => 'filterjoinDate',
                            'readonly',
                        ]) !!}
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('departmentFilter', trans('employee.department')) !!}
                    {!! Form::select('departmentFilter', ['' => 'Select'] + $department, null, [
                        'class' => 'form-control departmentFilter jsDepartmentFilter',
                        'id' => 'department_id',
                        'data-placeholder' => 'Select Department',
                        'data-ajaxurl' => route('getDesignation'),
                    ]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('designationFilter', trans('employee.designation')) !!}
                    {!! Form::select('designationFilter', ['' => 'Select'], null, [
                        'class' => 'form-control designationFilter jsDesignationFilter',
                        'id' => 'designation_id',
                        'data-placeholder' => 'Select Designation',
                    ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('statusFilter', trans('common.status')) !!}
                    {!! Form::select('statusFilter', ['' => 'Select', 'Yes' => 'Active', 'No' => 'Inactive'], null, [
                        'class' => 'form-control statusFilter jsStatusFilter',
                        'id' => 'status_id',
                        'data-placeholder' => 'Select Status',
                    ]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button"
                    class="btn btn-success mr-2 btn_search jsBtnSearch">{{ __('common.search') }}</button>
                <button type="button" class="btn btn-danger btn_reset">{{ __('common.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
