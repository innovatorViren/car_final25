<table id='customers' class="table customers">
    <thead>
        <tr>
        <tr>
            <th>#</th>
            <th>{{ __('Company Name') }}</th>
            <th>{{ __('Person Name') }}</th>
            <th>{{ __('Email') }}</th>
            <th>{{ __('GST No') }}</th>
        </tr>
        </tr>
    </thead>

</table>
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var employeeId = "{{ $employee->id }}";
            $('.customers').DataTable({
                searching: false,
                bLengthChange: false,
                ajax: {
                    url: '/employee-customers/' + employeeId,
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'company_name',
                        name: 'company_name'
                    },
                    {
                        data: 'person_name',
                        name: 'person_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'gst_no',
                        name: 'gst_no'
                    },
                    // {
                    //     data: 'is_active',
                    //     name: 'is_active'
                    // },
                ],
            });

        });
    </script>
@endpush
