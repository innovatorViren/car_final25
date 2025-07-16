<table class="table">
    <thead>
        <tr>
            <th colspan="52" style="text-align: center;"><b>{{ $company_title }}</b></th>
        </tr>
        <tr>
            <td colspan="52" style="text-align: center;">{{ $company_address }}</td>
        </tr>
        <tr>
            <td colspan="52" style="text-align: center;">Email : {{ $company_email }}</td>
        </tr>
        <tr>
            <td colspan="52"></td>
        </tr>
        <tr>
            <td colspan="52" style="text-align: center;"><b>{{ $module_title }}</b></td>
        </tr>
        <tr>
            <td colspan="52"></td>
        </tr>
        <tr>
            <th>Emp Code</th>
            <th>Employee</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Full Name</th>
            <th>Gender</th>
            <th>Date of Birth</th>
            <th>Age</th>
            <th>Marital Status</th>
            <th>Hobbies</th>
            <th>Reference</th>
            <th>Reference Tel No</th>
            <th>Present Address</th>
            <th>Present State</th>
            <th>Present City/Village</th>
            <th>Present Pin Code</th>
            <th>E-Mail</th>
            <th>Mobile 1</th>
            <th>Mobile 2</th>
            <th>Same as Present Address</th>
            <th>Permanent Address</th>
            <th>Permanent State</th>
            <th>Permanent City/Village</th>
            <th>Permanent Pin Code</th>
            <th>UAN No.</th>
            <th>Aadhar Card</th>
            <th>Driving Licence No.</th>
            <th>PAN No.</th>
            <th>Passport No.</th>
            <th>Strengths</th>
            <th>Weakness</th>
            <th>Blood Group</th>
            <th>Account No</th>
            <th>Bank Name</th>
            <th>Beneficiary Name</th>
            <th>Branch Name</th>
            <th>IFSC Code</th>
            <th>Educational Background With Aggregate % Scored</th>
            <th>Any Course Undertaken</th>
            <th>Previous Employment With Years Of Experience</th>
            <th>Past Total Experience</th>
            <th>Join Date</th>
            <th>Department</th>
            <th>Designation</th>
            <th>Appointed By</th>
            <th>Designation of Appointee</th>
            <th>Left Date</th>
            <th>Left Reason</th>
            <th>Recruit Again</th>
            <th>Rejoin Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($employeeData as $row)
            <tr>
                <td>{{ $row->employee_code }}</td>
                <td>{{ $row->emp_full_name }}</td>
                <td>{{ $row->first_name ?? '' }}</td>
                <td>{{ $row->middle_name ?? '' }}</td>
                <td>{{ $row->last_name ?? '' }}</td>
                <td>{{ $row->person_name ?? '' }}</td>
                <td>{{ $row->gender ?? '' }}</td>
                <td>{{ $row->birth_date ?? '' }}</td>
                <td>{{ $row->age ?? '' }}</td>
                <td>{{ !empty($row->marital_status) ? ucFirst($row->marital_status) : '' }}</td>
                <td>{{ $row->hobbies ?? '' }}</td>
                <td>{{ $row->reference ?? '' }}</td>
                <td>{{ $row->reference_tel_no ?? '' }}</td>
                <td>{{ $row->present_address ?? '' }}</td>
                <td>{{ $row->present_state_name ?? '' }}</td>
                <td>{{ $row->present_city ?? '' }}</td>
                <td>{{ $row->present_pincode ?? '' }}</td>
                <td>{{ $row->email ?? '' }}</td>
                <td>{{ $row->mobile1 ?? '' }}</td>
                <td>{{ $row->mobile2 ?? '' }}</td>
                <td>{{ $row->same_as_present == 1 ? 'Yes' : 'No' }}</td>
                <td>{{ $row->permanent_address ?? '' }}</td>
                <td>{{ $row->permanent_state_name ?? '' }}</td>
                <td>{{ $row->permanent_city ?? '' }}</td>
                <td>{{ $row->permanent_pincode ?? '' }}</td>
                <td>{{ $row->uan_no ?? '' }}</td>
                <td>{{ $row->aadhar_card_no ?? '' }}</td>
                <td>{{ $row->driving_licence_no ?? '' }}</td>
                <td>{{ $row->pan_card_no ?? '' }}</td>
                <td>{{ $row->passport_no ?? '' }}</td>
                <td>{{ $row->strengths ?? '' }}</td>
                <td>{{ $row->weakness ?? '' }}</td>
                <td>{{ $row->blood_group ?? '' }}</td>
                <td>{{ $row->account_no ?? '' }}</td>
                <td>{{ $row->bank_name ?? '' }}</td>
                <td>{{ $row->beneficiary_name ?? '' }}</td>
                <td>{{ $row->branch_name ?? '' }}</td>
                <td>{{ $row->ifsc_code ?? '' }}</td>
                <td>{{ $row->academic_background ?? '' }}</td>
                <td>{{ $row->courses ?? '' }}</td>
                <td>{{ $row->experience ?? '' }}</td>
                <td>{{ $row->total_experience ?? '' }}</td>
                <td>{{ $row->join_date ?? '' }}</td>
                <td>{{ $row->department_name ?? '' }}</td>
                <td>{{ $row->designation_name ?? '' }}</td>
                <td>{{ $row->appointed_by ?? '' }}</td>
                <td>{{ $row->designation_of_appointee ?? '' }}</td>
                <td>{{ $row->left_date ?? '' }}</td>
                <td>{{ $row->rejoin_date ?? '' }}</td>
                <td>{{ $row->left_reason ?? '' }}</td>
                <td>{{ $row->recruit_again ?? '' }}</td>
                <td>{{ $row->is_active ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
