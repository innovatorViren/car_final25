{{-- Extends layout --}}
@extends('app')
{{-- Content --}}
@section('content')
@section('title', __('customers.title'))

@component('partials._subheader.subheader-v6', [
    'page_title' => __('customers.title'),
    'action' => route('customers.create'),
    'text' => __('common.add'),
    'filter_modal_id' => '#customerFilter',
    'permission' => $current_user->hasAnyAccess(['customers.add', 'users.superadmin']),
])
    ,
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
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_company_name', Request::get('filter_company_name', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_person_name', Request::get('filter_person_name', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_email', Request::get('filter_email', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_mobile', Request::get('filter_mobile', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_gst_type', Request::get('filter_gst_type', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_gst_no', Request::get('filter_gst_no', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_pan_no', Request::get('filter_pan_no', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_credit_days', Request::get('filter_credit_days', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_credit_limit', Request::get('filter_credit_limit', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th></th>
                        </tr>
                        <tr>
                            {{-- <th class="noVis">{{__('common.action')}}</th> --}}
                            <th class="noVis">{{ __('common.no') }}</th>
                            <th class="d-none noVis"></th>
                            <th class="noVis">{{ __('customers.company_name') }}</th>
                            <th>{{ __('customers.person_name') }}</th>
                            <th>{{ __('customers.email') }}</th>
                            <th>{{ __('common.mobile') }}</th>
                            <th>{{ __('customers.gst_type') }}</th>
                            <th>{{ __('common.gst_no') }}</th>
                            <th>{{ __('customers.pan_no') }}</th>
                            <th>{{ __('customers.credit_day') }}</th>
                            <th>{{ __('customers.credit_limit') }}</th>
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

@include('customers.filter')
@endsection

@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
<script type="text/javascript">
    var id = "{{ __('common.no') }}";
    var company_name = "{{ __('customers.company_name') }}";
    var person_name = "{{ __('customers.person_name') }}";
    var mobile = "{{ __('common.mobile') }}";
    var email = "{{ __('customers.email') }}";
    var gst_type = "{{ __('customers.gst_type') }}";
    var gst_no = "{{ __('common.gst_no') }}";
    var pan_no = "{{ __('common.pan_no') }}";
    var credit_days = "{{ __('customers.credit_day') }}";
    var credit_limit = "{{ __('customers.credit_limit') }}";
    var action = "{{ __('common.action') }}";
    var is_active = "{{ __('common.status') }}";

    (function(window, $) {
        window.LaravelDataTables = window.LaravelDataTables || {};
        window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                data: function(d) {
                    d.lang = jQuery(".datatable-form-filter select[name='filter_lang']").val();
                    d.company_name = jQuery(".datatable-form-filter input[name='filter_company_name']")
                        .val();
                    d.person_name = jQuery(".datatable-form-filter input[name='filter_person_name']")
                        .val();
                    d.mobile = jQuery(".datatable-form-filter input[name='filter_mobile']").val();
                    d.email = jQuery(".datatable-form-filter input[name='filter_email']").val();
                    d.gst_type = jQuery(".datatable-form-filter input[name='filter_gst_type']").val();
                    d.gst_no = jQuery(".datatable-form-filter input[name='filter_gst_no']").val();
                    d.pan_no = jQuery(".datatable-form-filter input[name='filter_pan_no']").val();
                    d.credit_days = jQuery(".datatable-form-filter input[name='filter_credit_days']")
                        .val();
                    d.credit_limit = jQuery(".datatable-form-filter input[name='filter_credit_limit']")
                        .val();

                    d.customerfilter = jQuery("select[name='customerfilter']").val();
                    d.statefilter = jQuery("select[name='statefilter']").val();
                    d.product_type = $('.jsProductTypefilter').val();
                    d.type_filter = $('.jsTypeFilter').val();
                    d.gstTypeFilter = jQuery("select[name='gstTypeFilter']").val();
                }
            },
            "columns": [{
                "name": "rownum",
                "data": "rownum",
                "title": id,
                "orderable": false,
                "searchable": false
            }, {
                "name": "id",
                "data": "id",
                "title": "id",
                "orderable": true,
                "class": "d-none",
            }, {
                "name": "company_name",
                "data": "company_name",
                "title": company_name,
                "orderable": true,
                "searchable": false,
            }, {
                "name": "person_name",
                "data": "person_name",
                "title": person_name,
                "orderable": false,
                "searchable": false,
                "visible": false, //visibility
            }, {
                "name": "email",
                "data": "email",
                "title": email,
                "orderable": false,
                "searchable": false,
                "visible": false, //visibility
            }, {
                "name": "mobile",
                "data": "mobile",
                "title": mobile,
                "orderable": false,
                "searchable": false

            }, {
                "name": "gst_type",
                "data": "gst_type",
                "title": gst_type,
                "orderable": false,
                "searchable": false,
                "visible": false, //visibility
            }, {
                "name": "gst_no",
                "data": "gst_no",
                "title": gst_no,
                "orderable": false,
                "searchable": false
            }, {
                "name": "pan_no",
                "data": "pan_no",
                "title": pan_no,
                "orderable": false,
                "searchable": false,
                "visible": false, //visibility
            }, {
                "name": "credit_days",
                "data": "credit_days",
                "title": credit_days,
                "orderable": false,
                "searchable": false,
                "visible": false, //visibility
            }, {
                "name": "credit_limit",
                "data": "credit_limit",
                "title": credit_limit,
                "orderable": false,
                "searchable": false,
                "visible": false, //visibility
            }, {
                "name": "is_active",
                "data": "is_active",
                "title": is_active,
                "orderable": false,
                "searchable": false,
                "visible": false, //visibility
            }],
            "searching": false,
            //"dom": "<\"wrapper\">rtilfp",
            "dom": `<'row'<'col-sm-12'tr>>
            <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
            "oLanguage": {
                "sLengthMenu": "Display &nbsp;_MENU_",
            },
            "stateSave": true,
            stateSaveParams: function(settings, data) {
                data.customerfilter_id = $('#customerfilter_id').val();
                data.state_id = $('#state_id').val();
                data.managed_by = $('#managed_by').val();
                data.type_filter = $('.jsTypeFilter').val();

            },
            stateLoadParams: function(settings, data) {
                $('#customerfilter_id').val(data.customerfilter_id);
                $('#state_id').val(data.state_id);
                $('#managed_by').val(data.managed_by);
                $('.jsTypeFilter').val(data.type_filter).trigger('change');

            },
            "initComplete": function(settings, json) {
                $('.jsBtnSearch').click();
            },
            responsive: false,
            colReorder: true,
            scrollY: false,
            scrollX: true,
            "buttons": [],
            "order": [
                [1, "desc"]
            ],
            "pageLength": page_show_entriess,
            //dom: 'Bfrtip',//visibility
            dom: `Bfrt<'row'<'col-sm-6 col-md-6'i><'col-sm-6 col-md-6 dataTables_pager'lp>>`, //visibility
            buttons: [ //visibility
                {
                    extend: 'colvis',
                    columns: ':not(.noVis)',
                    text: 'Column visibility',
                }
            ],
            columnDefs: [{targets: ["_all"],
             render: function(data, type, row) {
                 return `<span style="white-space: nowrap;">${data ?? ''}</span>`;
             }}],
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
            'jscustomerfilter',
            'jsstatefilter',
            'jsGstTypeFilter'
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

    $(document).on('click', '.copy-btn', function() {
        var $obj = $(this);
        var txt = $obj.parents('td').find('span.cust-text').text();
        copyToClipboard(txt);
    });
    $(document).on('click', '.jPrimaryManaged', function() {
        $('#commonModalID').modal('show');
    });

    function copyToClipboard(txt) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(txt).select();
        document.execCommand("copy");
        $temp.remove();
    }
</script>
@include('comman.datatable_filter')
{{-- @include('show-info') --}}
@endsection
