{{-- Extends layout --}}
@extends($theme)
{{-- Content --}}
@section('content')
@section('title', __('rate.rate'))

@component('partials._subheader.subheader-v6', [
    'page_title' => __('rate.rate'),
    // 'action' => route('rate-update.create'),
    // 'text' => __('common.add'),
    'permission' => $current_user->hasAnyAccess(['rate.add', 'users.superadmin']),
])
@endcomponent
@php
    $actionclass = '';
    if (!$current_user->hasAnyAccess(['rate.edit', 'rate.delete', 'users.superadmin'])) {
        $actionclass = 'd-none';
    }
@endphp


<div class="container-fluid">
    @include('components.error')
    <div class="row">
        <div class="col-sm-12">
            <div class="card" id="default">
                <div class="card-body">
                    <div class="table">
                        <table class="table table-separate table-head-custom table-checkable" id="dataTableBuilder">
                            <thead>
                                <tr>
                                    <th colspan="10">
                                        <div class="jsFilterData"></div>
                                    </th>
                                </tr>
                                <tr>
                                    {{-- <th style="width: 7px;" class="{{ $actionclass }}"></th> --}}

                                    {{-- <th>
                                        <div class="datatable-form-filter">
                                            {!! Form::text('filter_name', Request::get('filter_name', null), [
                                                'class' => 'form-control',
                                            ]) !!}
                                        </div>
                                    </th> --}}
                                    <th class="d-none"></th>
                                    <th>
                                        <div class="datatable-form-filter">
                                            {!! Form::text('filter_sub_category', Request::get('filter_sub_category', null), [
                                                'class' => 'form-control',
                                            ]) !!}
                                        </div>
                                    </th>
                                    {{-- <th>
                                        <div class="datatable-form-filter">
                                            {!! Form::text('filter_category', Request::get('filter_category', null), [
                                                'class' => 'form-control',
                                            ]) !!}
                                        </div>
                                    </th> --}}
                                    <th></th>
                                </tr>
                                <tr>
                                    {{-- <th style="width: 7px;" class="{{ $actionclass }} text-center"> --}}
                                        {{-- {{ __('common.action') }}</th> --}}
                                    {{-- <th>{{ __('rate.category') }}</th> --}}
                                    <th class="d-none noVis"></th>
                                    <th class="noVis">{{ __('rate.sub_category') }}</th>
                                    <th>{{ __('rate.product') }}</th>
                                    <th>{{ __('rate.last_updated_date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="load-modal"></div>
@include('info')

@endsection

@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
@endsection
@push('scripts')
<script type="text/javascript">
    var sub_category = "{{ __('rate.sub_category') }}";
    var category = "{{ __('rate.category') }}";
    var product = "{{ __('rate.product') }}";

    var lastUpdatedDate = "{{ __('rate.last_updated_date') }}";

    var action = "{{ __('common.action') }}";
    var is_active = "{{ __('common.status') }}";

    (function(window, $) {
        window.LaravelDataTables = window.LaravelDataTables || {};
        window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                data: function(d) {
                    d.name = jQuery(".datatable-form-filter input[name='filter_name']").val();
                    d.filter_sub_category = jQuery(".datatable-form-filter input[name='filter_sub_category']").val();


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
                //     class: '{{ $actionclass }}',
                // },
                // {
                //     "name": "name",
                //     "data": "name",
                //     "title": category,
                //     "orderable": true,
                //     "searchable": false
                // },
                {
                    "name": "id",
                    "data": "id",
                    "title": "id",
                    "orderable": true,
                    "class": "d-none",
                }, {
                    "name": "name",
                    "data": "sub_category",
                    "title": sub_category,
                    "orderable": true,
                    "searchable": false
                },{
                    "name": "category_id",
                    "data": "product",
                    "title": product,
                    "orderable": true,
                    "searchable": false
                },{
                    "name": "rate_histories.created_at",
                    "data": "lastUpdatedDate",
                    "title": lastUpdatedDate,
                    "orderable": false,
                    "searchable": false
                },
                // {
                //     "name": "is_active",
                //     "data": "is_active",
                //     "title": is_active,
                //     "orderable": false,
                //     "searchable": false,
                //     // class: 'text-center',
                // }
            ],
            "searching": false,
            "dom": `<'row'<'col-sm-12'tr>>
            <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
            "oLanguage": {
                "sLengthMenu": "Display &nbsp;_MENU_",
            },
            "stateSave": true,
            responsive: false,
            colReorder: true,
            // scrollY: false,
            // scrollX: true,
            "buttons": [],
            "order": [
                [0, "desc"]
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
            'jsBuyerscountry',
            'jsBuyername',
            'jsbuyerNo',

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
@endpush
