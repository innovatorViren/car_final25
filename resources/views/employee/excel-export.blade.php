<table class="table">
    <thead>
        <tr>
            <th colspan="8" style="text-align: center;"><b>{{ $company_title }}</b></th>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center;">{{ $company_address }}</td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center;">Email : {{ $company_email }}</td>
        </tr>
        <tr>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center;"><b>{{ $module_title }}</b></td>
        </tr>
        <tr>
            <td colspan="8"></td>
        </tr>
        <tr>
            <th>Emp Code</th>
            <th>Employee</th>
            <th>Mobile</th>
            <th>Department</th>
            <th>Designation</th>
            <th>Join Date</th>
            <th>Left Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($employeeData as $row)
            <tr>
                <td>{{ $row->employee_code }}</td>
                <td>{{ $row->emp_full_name }}</td>
                <td>{{ $row->mobile1 }}</td>
                <td>{{ $row->department_id }}</td>
                <td>{{ $row->designation_id }}</td>
                <td>{{ $row->join_date }}</td>
                <td>{{ $row->left_date }}</td>
                <td>{{ $row->is_active }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
