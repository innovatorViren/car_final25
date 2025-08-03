{{-- Extends layout --}}
@extends('app')
{{-- Content --}}
@section('content')
@section('title', __('customers.title'))

@component('partials._subheader.subheader-v6', [
    'page_title' => __('customers.title'),
    'action' => route('customers.create'),
    'text' => __('common.add'),
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
                            <th></th>
                            <th class="d-none"></th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_first_name', Request::get('filter_first_name', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_middle_name', Request::get('filter_middle_name', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_last_name', Request::get('filter_last_name', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_mobile', Request::get('filter_mobile', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_email', Request::get('filter_email', null), ['class' => 'form-control']) !!}</div>
                            </th>
                            <th></th>
                        </tr>
                        <tr>
                            <th class="noVis">{{__('common.action')}}</th>
                            <th class="d-none noVis"></th>
                            <th class="noVis">{{ __('customers.first_name') }}</th>
                            <th>{{ __('customers.middle_name') }}</th>
                            <th>{{ __('customers.last_name') }}</th>
                            <th>{{ __('common.mobile') }}</th>
                            <th>{{ __('customers.email') }}</th>
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
@endsection

@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
<script type="text/javascript">
    var id = "{{ __('common.no') }}";
    var first_name = "{{ __('customers.first_name') }}";
    var middle_name = "{{ __('customers.middle_name') }}";
    var last_name = "{{ __('customers.last_name') }}";
    var mobile = "{{ __('common.mobile') }}";
    var email = "{{ __('customers.email') }}";
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
                    d.first_name = jQuery(".datatable-form-filter input[name='filter_first_name']")
                        .val();
                    d.middle_name = jQuery(".datatable-form-filter input[name='filter_middle_name']").val();
                    d.last_name = jQuery(".datatable-form-filter input[name='filter_last_name']").val();
                    d.mobile = jQuery(".datatable-form-filter input[name='filter_mobile']").val();
                    d.email = jQuery(".datatable-form-filter input[name='filter_email']").val();
                }
            },
            "columns": [{
                "name": "action",
                "data": "action",
                "title": action,
                "render": null,
                "orderable": false,
                "searchable": false,
                "width": "80px"
            }, {
                "name": "id",
                "data": "id",
                "title": "id",
                "orderable": true,
                "class": "d-none",
            }, {
                "name": "first_name",
                "data": "first_name",
                "title": first_name,
                "orderable": true,
                "searchable": false,
            }, {
                "name": "middle_name",
                "data": "middle_name",
                "title": middle_name,
                "orderable": false,
                "searchable": false,
                "visible": true, //visibility
            },{
                "name": "last_name",
                "data": "last_name",
                "title": last_name,
                "orderable": false,
                "searchable": false,
                "visible": true, //visibility
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

            },  {
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
