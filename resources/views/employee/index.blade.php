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
    'permission' => $current_user->hasAnyAccess(['employee.add', 'users.superadmin']),
    'column_visibility' => true,
])
@endcomponent

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom gutter-b">

            <div class="card-body">
                <table class="table table-separate table-head-custom table-checkable" id="dataTableBuilder">
                    <thead>
                        <tr>
                            <th colspan="7">
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
                            <th></th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_mobile1', Request::get('filter_mobile1', null), ['class' => 'form-control']) !!}</div>
                            </th>

                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            {{--  <th>{{__('common.action')}}</th> --}}
                            <th class="d-none noVis"></th>
                            <th class="noVis">{{ __('employee.emp_code') }}</th>
                            <th width="20%">{{ __('employee.person_name') }}</th>
                            <th width="20%">{{ __('employee.email') }}</th>
                            <th>{{ __('employee.mobile') }}</th>
                            <th width="20%">{{ __('Aadhar No.') }}</th>
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
    var email = "{{ __('employee.email') }}";
    var mobile1 = "{{ __('employee.mobile') }}";
    var aashar_no = "{{ __('Aadhar No') }}";
    var action = "{{ __('common.action') }}";
    var is_active = "{{ __('common.status') }}";
    {{-- var type = "{{ $type }}"; --}}
    let type = @json($type ?? null);


    (function(window, $) {

        if (type != null) {
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
                    d.employee_code = jQuery(
                        ".datatable-form-filter input[name='filter_employee_code']").val();
                    d.person_name = jQuery(".datatable-form-filter input[name='filter_person_name']")
                        .val();
                    d.mobile1 = jQuery(".datatable-form-filter input[name='filter_mobile1']").val();
                    d.statusFilter = jQuery("select[name='statusFilter']").val();


                }
            },
            "columns": [
                {{-- // {
                //     "name": "action",
                //     "data": "action",
                //     "title": action,
                //     "render": null,
                //     "orderable": false,
                //     "searchable": false,
                //     // "width": "80px"
                // }, --}}
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
                },
                {
                    "name": "person_name",
                    "data": "first_name",
                    "title": person_name,
                    "orderable": true,
                    "searchable": false
                },
                {
                    "name": "email",
                    "data": "email",
                    "title": email,
                    "orderable": true,
                    "searchable": false
                },
                {
                    "name": "mobile1",
                    "data": "mobile1",
                    "title": mobile1,
                    "orderable": true,
                    "searchable": false
                }, 
                {
                    "name": "aashar_no",
                    "data": "aadhar_card_no",
                    "title": aashar_no,
                    "orderable": true,
                    "searchable": false
                },
                {
                    "name": "is_active",
                    "data": "is_active",
                    "title": is_active,
                    "render": null,
                    "orderable": false,
                    "searchable": false
                }
            ],
            "searching": false,
            "dom": `<'row'<'col-sm-12'tr>>
            <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
            "oLanguage": {
                "sLengthMenu": "Display &nbsp;_MENU_",
            },
            "stateSave": true,
            stateSaveParams: function(settings, data) {
               {{-- data.statusFilter = $('#statusFilter').val(); --}}
            },
            stateLoadParams: function(settings, data) {
                {{-- $('#statusFilter').val(data.statusFilter); --}}
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
            dom: `Bfrt<'row'<'col-sm-6 col-md-6'i><'col-sm-6 col-md-6 dataTables_pager'lp>>`, //visibility
            buttons: [ 
                {
                    extend: 'colvis',
                    columns: ':not(.noVis)',
                    text: '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><path d="M1.5,5 L4.5,5 C5.32842712,5 6,5.67157288 6,6.5 L6,17.5 C6,18.3284271 5.32842712,19 4.5,19 L1.5,19 C0.671572875,19 1.01453063e-16,18.3284271 0,17.5 L0,6.5 C-1.01453063e-16,5.67157288 0.671572875,5 1.5,5 Z M18.5,5 L22.5,5 C23.3284271,5 24,5.67157288 24,6.5 L24,17.5 C24,18.3284271 23.3284271,19 22.5,19 L18.5,19 C17.6715729,19 17,18.3284271 17,17.5 L17,6.5 C17,5.67157288 17.6715729,5 18.5,5 Z" fill="#000000"/><rect fill="#000000" opacity="0.3" x="8" y="5" width="7" height="14" rx="1.5"/></g></svg>',
                }
            ],
        });
        const table = window.LaravelDataTables["dataTableBuilder"];
        table.buttons().container().appendTo('#custom-column-visibility-container');
    })(window, jQuery);

    $('#dataTableBuilder').on('column-visibility.dt', function(e, settings, column, state) {
        var table = $(this).DataTable();
        table.columns.adjust();
    }); //visibility

    jQuery('.btn_search').on('click', function(e) {
        window.LaravelDataTables["dataTableBuilder"].draw();
        $('.close').trigger('click');

        var fieldList = [,
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

    $('.statusFilter').select2({
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
