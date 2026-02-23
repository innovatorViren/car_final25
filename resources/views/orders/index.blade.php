{{-- Extends layout --}}
@extends('app')
{{-- Content --}}
@section('content')
@section('title', $title)

@component('partials._subheader.subheader-v6', [
    'page_title' => $title,
    'action' => route('orders.create'),
    'text' => __('common.add'),
    'permission' => $current_user->hasAnyAccess(['orders.list','users.superadmin']),
    'filter_modal_id' => '#shopOrderFilter',
    'column_visibility' => true,
])
@endcomponent

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        @include('components.error')
        <div class="card card-custom gutter-b">

            <div class="card-body">
                <table class="table table-separate table-head-custom table-checkable" id="dataTableBuilder">
                    <thead>
                        <tr>
                            <th colspan="6"> 
                                <div class="jsFilterData"></div>
                            </th>
                        </tr>
                        <tr>
                            <th class="noVis">{{__('common.action')}}</th>
                            <th class="noVis">{{ __('orders.order_no') }}</th>
                            <th>{{ __('orders.customer_name') }}</th>
                            <th>{{ __('orders.start_date') }}</th>
                            <th>{{ __('orders.end_date') }}</th>
                            <th>{{ __('orders.pay_amount') }}</th>
                            <th>{{ __('orders.status') }}</th>
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
@include('orders.filter')
@include('info')
@endsection

@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
<script type="text/javascript">
    var action = "{{ __('common.action') }}";
    var order_no = "{{ __('orders.order_no') }}";
    var customer = "{{ __('orders.customer_name') }}";
    var start_date = "{{ __('orders.start_date') }}";
    var end_date = "{{ __('orders.end_date') }}";
    var pay_amount = "{{ __('orders.pay_amount') }}";
    var status = "{{ __('orders.status') }}";



    (function(window, $) {
        $('.jsStatus').select2({allowClear:true});
        window.LaravelDataTables = window.LaravelDataTables || {};
        window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                data: function(d) {
                    d.datefilter = jQuery("input[name='dateFilter']").val();
                    d.customerFilter = jQuery("select[name='customerFilter']").val();
                }
            },
            "columns": [
            {
                "name": "action",
                "data": "action",
                "title": action,
                "render": null,
                "orderable": false,
                "searchable": false,
                "width": "80px"
            },
            {
                "name": "code",
                "data": "code",
                "title": order_no,
                "orderable": true,
                "searchable": true
            },
            {
                "name": "start_date",
                "data": "start_date",
                "title": start_date,
                "orderable": true,
                "searchable": true
            },{
                "name": "end_date",
                "data": "end_date",
                "title": end_date,
                "orderable": true,
                "searchable": true
            },{
                "name": "customer_name",
                "data": "customer_name",
                "title": customer,
                "orderable": true,
                "searchable": true
            },{
                "name": "pay_amount",
                "data": "pay_amount",
                "title": pay_amount,
                "orderable": false,
                "searchable": false,
                'class':'text-right'
            },{
                "name": "status",
                "data": "status",
                "title": status,
                "orderable": false,
                "searchable": false,
            }],
            "searching": false,
            //"dom": "<\"wrapper\">rtilfp",
            "dom": `<'row'<'col-sm-12'tr>>
            <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
            "oLanguage": {
                "sLengthMenu": "Display &nbsp;_MENU_",
            },
            "stateSave": true,
            responsive: true,
            colReorder: true,
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
        });
        const table = window.LaravelDataTables["dataTableBuilder"];
        table.buttons().container().appendTo('#custom-column-visibility-container');
    })(window, jQuery);
    $('#dataTableBuilder').on('column-visibility.dt', function(e, settings, column, state) {
        var table = $(this).DataTable();
        table.columns.adjust();
    }); 
    jQuery('.btn_search').on('click', function (e) {
        window.LaravelDataTables["dataTableBuilder"].draw();
        $('.close').trigger('click');
        var fieldList = [
            'jsdate',
            'jsCustomerFilter',
        ];        
        setFilterData(fieldList);
        e.preventDefault();
    });

    jQuery(".btn_reset").on('click', function (e) {
        jQuery(".datatable-form-filter input").val("");
        jQuery(".datatable-form-filter select").val("");
        window.LaravelDataTables["dataTableBuilder"].state.clear();
        window.location.reload();
    });

    $('#customerFilter').select2({
        allowClear: true
    });
</script>
@include('comman.datatable_filter')
@endsection
