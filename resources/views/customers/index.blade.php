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
    'column_visibility' => true,
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
                            <th colspan="8">
                                <div class="jsFilterData"></div>
                            </th>
                        </tr>
                        
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
            dom: `Bfrt<'row'<'col-sm-6 col-md-6'i><'col-sm-6 col-md-6 dataTables_pager'lp>>`, //visibility
            buttons: [ 
                {
                    extend: 'colvis',
                    columns: ':not(.noVis)',
                    text: '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><path d="M1.5,5 L4.5,5 C5.32842712,5 6,5.67157288 6,6.5 L6,17.5 C6,18.3284271 5.32842712,19 4.5,19 L1.5,19 C0.671572875,19 1.01453063e-16,18.3284271 0,17.5 L0,6.5 C-1.01453063e-16,5.67157288 0.671572875,5 1.5,5 Z M18.5,5 L22.5,5 C23.3284271,5 24,5.67157288 24,6.5 L24,17.5 C24,18.3284271 23.3284271,19 22.5,19 L18.5,19 C17.6715729,19 17,18.3284271 17,17.5 L17,6.5 C17,5.67157288 17.6715729,5 18.5,5 Z" fill="#000000"/><rect fill="#000000" opacity="0.3" x="8" y="5" width="7" height="14" rx="1.5"/></g></svg>',
                }
            ],
            columnDefs: [{targets: ["_all"],
             render: function(data, type, row) {
                 return `<span style="white-space: nowrap;">${data ?? ''}</span>`;
             }}],
        });
        const table = window.LaravelDataTables["dataTableBuilder"];
        table.buttons().container().appendTo('#custom-column-visibility-container');
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
