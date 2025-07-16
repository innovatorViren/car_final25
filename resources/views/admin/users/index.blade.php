{{-- Extends layout --}}
@extends($theme)
{{-- Content --}}
@section('content')
@section('title', __('users.title'))
@component('partials._subheader.subheader-v6', [
    'page_title' => __('users.title'),
    'action' => route('users.create'),
    'text' => __('common.add'),
    'filter_modal_id' => '#userFilter',
    'permission' => $current_user->hasAnyAccess(['users.add', 'users.superadmin']),
])
@endcomponent

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom gutter-b">

            <div class="card-body">
                <table class="table table-separate table-head-custom table-checkable" id="dataTableBuilder">
                    <thead>
                        <tr>
                            <th colspan="10">
                                <div class="jsFilterData"></div>
                            </th>
                        </tr>
                        <tr>
                            <th></th>
                            <th class="d-none"></th>
                            <th>
                                <div class="datatable-form-filter no-padding">
                                    {!! Form::text('filter_name', Request::get('filter_name', null), ['class' => 'form-control']) !!}
                                </div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">
                                    {!! Form::text('filter_email', Request::get('filter_email', null), ['class' => 'form-control']) !!}
                                </div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">
                                    {!! Form::text('filter_role', Request::get('filter_role', null), ['class' => 'form-control']) !!}
                                </div>
                            </th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th class="noVis" width="10%">{{ __('common.no') }}</th>
                            <th class="d-none noVis"></th>
                            <th class="noVis" width="20%">{{ __('Name') }}</th>
                            <th width="20%">{{ __('common.email') }}</th>
                            <th width="20%">{{ __('users.form.roles') }}</th>
                            <th width="20%">{{ __('users.form.user_type') }}</th>
                            <th width="10%">{{ __('common.status') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
    </div>
</div>
@include('admin.users.filter')
@section('scripts')
    <script type="text/javascript">
        (function(window, $) {
            window.LaravelDataTables = window.LaravelDataTables || {};
            window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
                "serverSide": true,
                "processing": true,
                "ajax": {
                    data: function(d) {
                        d.name = jQuery(".datatable-form-filter input[name='filter_name']").val();
                        d.email = jQuery(".datatable-form-filter input[name='filter_email']").val();
                        d.role = jQuery(".datatable-form-filter input[name='filter_role']").val();

                        d.userTypeFilter = jQuery("select[name='userTypeFilter']").val();
                        d.rolefilter = jQuery("select[name='rolefilter']").val();
                    }
                },
                "columns": [{
                    "name": "rownum",
                    "data": "rownum",
                    "title": "Sr. No",
                    "render": null,
                    "orderable": false,
                    "searchable": false,
                }, {
                    "name": "id",
                    "data": "id",
                    "title": "id",
                    "orderable": true,
                    "class": "d-none",
                }, {
                    "name": "name",
                    "data": "name",
                    "title": "Name",
                    "orderable": true,
                    "searchable": false
                }, {
                    "name": "email",
                    "data": "email",
                    "title": "Email",
                    "orderable": true,
                    "searchable": false
                }, {
                    "name": "role",
                    "data": "role",
                    "title": "Role",
                    "orderable": true,
                    "searchable": false
                }, {
                    "name": "emp_type",
                    "data": "emp_type",
                    "title": "User Type",
                    "orderable": true,
                    "searchable": false
                }, {
                    "name": "is_active",
                    "data": "is_active",
                    "title": "Status",
                    "render": null,
                    "orderable": false,
                    "searchable": false
                }],
                "searching": false,
                "dom": `<'row'<'col-sm-12'tr>>
                <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
                "oLanguage": {
                    "sLengthMenu": "Display &nbsp;_MENU_",
                },
                "stateSave": true,
                stateSaveParams: function(settings, data) {
                    data.userTypeFilter_id = $('#userTypeFilter_id').val();
                    data.role_id = $('#role_id').val();

                },
                stateLoadParams: function(settings, data) {
                    $('#userTypeFilter_id').val(data.userTypeFilter_id);
                    $('#role_id').val(data.role_id);

                },
                "initComplete": function(settings, json) {
                    $('.jsBtnSearch').click();
                },
                responsive: true,
                colReorder: true,
                scrollY: false,
                scrollX: true,
                "buttons": [],
                "order": [
                    [1, "desc"]
                ],
                "pageLength": page_show_entriess,
                dom: 'Bfrtip', //visibility
                // buttons: [//visibility
                //     {
                //         extend: 'colvis',
                //         columns: ':not(.noVis)',
                //         text: 'Column visibility',
                //     }
                // ],
            });
        })(window, jQuery);

        $('#dataTableBuilder').on('column-visibility.dt', function(e, settings, column, state) {
            var table = $(this).DataTable();
            table.columns.adjust();
        }); //visibility

        jQuery('.btn_search').on('click', function(e) {
            window.LaravelDataTables["dataTableBuilder"].draw();
            $('.close').trigger('click');

            var fieldList = [
                'jsUserTypeFilter',
                'jsRolefilter',
            ];
            setFilterData(fieldList);
            e.preventDefault();
        });

        jQuery(".btn_reset").on('click', function(e) {
            jQuery(".datatable-form-filter input").val("");
            jQuery(".datatable-form-filter select").val("");
            window.LaravelDataTables["dataTableBuilder"].state.clear();
            window.location.reload();
        });
    </script>
    @include('comman.datatable_filter')
@endsection
@stop
