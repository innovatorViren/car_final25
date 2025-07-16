<table style="width:100%">
    <tr>
        <th>
            &nbsp;
        </th>
    </tr>

    <tr>
        <th>
            <h3>{{ __('employee.job_information') }}</h3>
        </th><br>
    </tr>
    <tr>
        <th>
            &nbsp;
        </th>
    </tr>
    <tr>
        <th>
            <div class="font-weight-bold" style=" color : #9d9595;">{{ trans('employee.previous_emp_with_year') }}</div>
        </th>
        <th>
            <div class="font-weight-bold" style=" color : #9d9595;">{{ trans('employee.department') }}</div>
        </th>
        <th>
            <div class="font-weight-bold " style=" color : #9d9595;">{{ trans('employee.join_date') }}</div>
        </th>

    </tr>

    <tr>
        <th>
            <h6>
                <div class="font-weight-bold " style=" color : #000000;">{{ $employee->experience }}</div>
            </h6>
        </th>
        <th>
            <h6>
                <div class="font-weight-bold " style=" color : #000000;">
                    {{ isset($employee->DepartmentName) ? $employee->DepartmentName->name : '' }}</div>
            </h6>
        </th>
        <th>
            <h6>
                <div class="font-weight-bold" style=" color : #000000;">
                    {{ date('d-m-Y', strtotime($employee->join_date)) }}</div>
            </h6>
        </th>

    </tr>
    <tr>
        <th>
            &nbsp;
        </th>
    </tr>
    <tr></tr>
    <tr>
        <th>
            <div class="font-weight-bold" style=" color : #9d9595;">{{ trans('employee.past_total_experience') }}</div>
        </th>

        <th>
            <div class="font-weight-bold " style=" color : #9d9595;">{{ trans('employee.designation') }}</div>
        </th>
    </tr>

    <tr>
        <th>
            <h6>
                <div class="font-weight-bold " style=" color : #000000;">{{ $employee->total_experience }}</div>
            </h6>
        </th>

        <th>
            <h6>
                <div class="font-weight-bold " style=" color : #000000;">
                    {{ isset($employee->designationName) ? $employee->designationName->name : '' }}</div>
            </h6>
        </th>
    </tr>
    {{--  --}}
    {{--  --}}

    <tr>
        <th>
            <div class="font-weight-bold" style=" color : #9d9595;">{{ trans('Reference By') }}</div>
        </th>
        <th>
            <div class="font-weight-bold " style=" color : #9d9595;">{{ trans('Designation of Reference By') }}
            </div>
        </th>
    </tr>
    <tr>
        <th>
            <h6>
                <div class="font-weight-bold " style=" color : #000000;">
                    {{ $employee->appointed?->first_name ?? '' }}
                    {{ $employee->appointed?->last_name ?? '' }}</div>
            </h6>
        </th>
        <th>
            <h6>
                <div class="font-weight-bold" style=" color : #000000;">
                    {{ $employee->appointed?->designationName?->name ?? '' }}
                </div>
            </h6>
        </th>

    </tr>
</table>
