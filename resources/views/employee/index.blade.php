{{-- Extends layout --}}
@extends('app')
{{-- Content --}}
@section('content')
@section('title', $title)

@component('partials._subheader.subheader-v6', [
    'page_title' => __('employee.employee'),
    'action' => route('employee.create'),
    'text' => __('common.add'),
    'filter_modal_id' => '#employeeFilter',
    'excel_id' => '',
    'excel_link' => route('employeeExport'),
    'permission' => $current_user->hasAnyAccess(['employee.add', 'users.superadmin']),
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
                            {{--  <th></th> --}}
                            <th class="d-none"></th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_employee_code', Request::get('filter_employee_code', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_person_name', Request::get('filter_person_name', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_mobile1', Request::get('filter_mobile1', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_department_id', Request::get('filter_department_id', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_designation_id', Request::get('filter_designation_id', null), [
                                    'class' => 'form-control',
                                ]) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_birth_date', Request::get('filter_birth_date', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_join_date', Request::get('filter_join_date', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_left_date', Request::get('filter_left_date', null), ['class' => 'form-control']) !!}</div>
                            </th>

                            <th></th>
                        </tr>
                        <tr>
                            {{--  <th>{{__('common.action')}}</th> --}}
                            <th class="d-none noVis"></th>
                            <th class="noVis">{{ __('employee.emp_code') }}</th>
                            <th width="20%">{{ __('employee.person_name') }}</th>
                            <th>{{ __('employee.mobile') }}</th>
                            <th>{{ __('employee.department') }}</th>
                            <th>{{ __('employee.designation') }}</th>
                            <th>{{ __('employee.birth_date') }}</th>
                            <th>{{ __('employee.join_date') }}</th>
                            <th>{{ __('employee.left_date') }}</th>
                            <th>{{ __('common.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="load-modal"></div>
@include('employee.filter')
@include('info')
@endsection

@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
<script type="text/javascript">
    var employee_code = "{{ __('employee.emp_code') }}";
    var person_name = "{{ __('employee.person_name') }}";
    var mobile1 = "{{ __('employee.mobile') }}";
    var department_id = "{{ __('employee.department') }}";
    var designation_id = "{{ __('employee.designation') }}";
    var birth_date = "{{ __('employee.birth_date') }}";
    var join_date = "{{ __('employee.join_date') }}";
    var left_date = "{{ __('employee.left_date') }}";
    var action = "{{ __('common.action') }}";
    var is_active = "{{ __('common.status') }}";
    var type = "{{ $type }}";


    (function(window, $) {
        if (type != '') {
            jQuery("select[name='statusFilter']").val(type);
            var fieldName = (type == "Yes") ? "Active" : "Inactive";
            var htmlData =
                '<span class="btn btn-light-dark font-weight-bold mr-2 remove-filter jsRemoveFilter" data-field-name="jsStatusFilter"> <i class="ki ki-bold-close icon-sm"></i> ' +
                fieldName + '</span>';
            $('.jsFilterData').append(htmlData);

        }

        window.LaravelDataTables = window.LaravelDataTables || {};
        window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                data: function(d) {
                    d.lang = jQuery(".datatable-form-filter select[name='filter_lang']").val();
                    d.employee_code = jQuery(
                        ".datatable-form-filter input[name='filter_employee_code']").val();
                    d.person_name = jQuery(".datatable-form-filter input[name='filter_person_name']")
                        .val();
                    d.mobile1 = jQuery(".datatable-form-filter input[name='filter_mobile1']").val();
                    d.department_id = jQuery(
                        ".datatable-form-filter input[name='filter_department_id']").val();
                    d.designation_id = jQuery(
                        ".datatable-form-filter input[name='filter_designation_id']").val();
                    d.birth_date = jQuery(".datatable-form-filter input[name='filter_birth_date']")
                        .val();
                    d.join_date = jQuery(".datatable-form-filter input[name='filter_join_date']").val();
                    d.left_date = jQuery(".datatable-form-filter input[name='filter_left_date']").val();

                    d.filterjoinDate = jQuery("input[name='filterjoinDate']").val();
                    d.personNameFilter = jQuery("select[name='personNameFilter']").val();
                    d.departmentFilter = jQuery("select[name='departmentFilter']").val();
                    d.designationFilter = jQuery("select[name='designationFilter']").val();
                    d.statusFilter = jQuery("select[name='statusFilter']").val();


                }
            },
            "columns": [
                // {
                //     "name": "action",
                //     "data": "action",
                //     "title": action,
                //     "render": null,
                //     "orderable": false,
                //     "searchable": false,
                //     // "width": "80px"
                // },
                {
                    "name": "id",
                    "data": "id",
                    "title": "id",
                    "orderable": true,
                    "class": "d-none",
                    "exportable": false,
                }, {
                    "name": "employee_code",
                    "data": "employee_code",
                    "title": employee_code,
                    "orderable": true,
                    "searchable": false
                }, {
                    "name": "person_name",
                    "data": "first_name",
                    "title": person_name,
                    "orderable": true,
                    "searchable": false
                }, {
                    "name": "mobile1",
                    "data": "mobile1",
                    "title": mobile1,
                    "orderable": true,
                    "searchable": false
                }, {
                    "name": "department_id",
                    "data": "department_id",
                    "title": department_id,
                    "orderable": true,
                    "searchable": false
                }, {
                    "name": "designation_id",
                    "data": "designation_id",
                    "title": designation_id,
                    "orderable": true,
                    "searchable": false
                }, {
                    "name": "birth_date",
                    "data": "birth_date",
                    "title": birth_date,
                    "orderable": true,
                    "searchable": false
                }, {
                    "name": "join_date",
                    "data": "join_date",
                    "title": join_date,
                    "orderable": true,
                    "searchable": false
                }, {
                    "name": "left_date",
                    "data": "left_date",
                    "title": left_date,
                    "orderable": true,
                    "visible": false,
                    "searchable": false,
                }, {
                    "name": "is_active",
                    "data": "is_active",
                    "title": is_active,
                    "render": null,
                    "orderable": false,
                    "searchable": false
                }
            ],
            "searching": false,
            //"dom": "<\"wrapper\">rtilfp",
            "dom": `<'row'<'col-sm-12'tr>>
            <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
            "oLanguage": {
                "sLengthMenu": "Display &nbsp;_MENU_",
            },
            "stateSave": true,
            stateSaveParams: function(settings, data) {
                data.personNameFilter = $('#personNameFilter').val();
                data.filterjoinDate = $('#filterjoinDate').val();
                data.department_id = $('#department_id').val();
                data.designation_id = $('#designation_id').val();
            },
            stateLoadParams: function(settings, data) {
                $('#personNameFilter').val(data.personNameFilter);
                $('#filterjoinDate').val(data.filterjoinDate);
                $('#department_id').val(data.department_id);
                $('#designation_id').val(data.designation_id);
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
                [0, "desc"]
            ],
            "pageLength": page_show_entriess,
            // dom: 'Bfrtip',//visibility
            dom: `Bfrt<'row'<'col-sm-6 col-md-6'i><'col-sm-6 col-md-6 dataTables_pager'lp>>`, //visibility
            buttons: [
                //visibility
                {
                    extend: 'colvis',
                    columns: ':not(.noVis)',
                    text: 'Column visibility',
                }
            ],
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
            'jsPersonNameFilter',
            'jsDilterJoinDate',
            'jsDepartmentFilter',
            'jsDesignationFilter',
            'jsStatusFilter',
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

    $(document).on('change', '#department_id', function(e) {

        e.preventDefault();

        var department_id = $("#department_id option:selected").val();

        var ajaxUrl = $(this).attr('data-ajaxurl');
        $.ajax({
            type: "GET",
            url: ajaxUrl,
            data: {
                'department_id': department_id
            },
        }).always(function() {

        }).done(function(response) {

            var options = '';

            $.each(response, function(index, catval) {

                if (typeof $('#designationid').val() !== "undefined" && $('#designationid')
                    .val() == catval.id) {
                    var selected = 'selected';
                } else {
                    var selected = '';
                }

                options += '<option ' + selected + ' value = ' + catval.id + '>' + catval.name +
                    '</option>';
            });

            $('#designation_id').html("<option value = ''>Select Designation</option>").append(options);
        });
    });

    $('.departmentFilter,.designationFilter,.personNameFilter').select2({
        allowClear: true
    });

    $(document).on('click', '.copy-btn', function() {
        var $obj = $(this);
        var txt = $obj.parents('td').find('span.emp-text').text();
        copyToClipboard(txt);
    });

    function copyToClipboard(txt) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(txt).select();
        document.execCommand("copy");
        $temp.remove();
    }
</script>
@include('employee.script')
@include('comman.datatable_filter')
@endsection
