{{-- Extends layout --}}
@extends($theme)
{{-- Content --}}
@section('content')
@section('title', __('plan.plan'))

@component('partials._subheader.subheader-v6', [
    'page_title' => __('plan.plan'),
    'action' => route('plan.create'),
    'text' => __('common.add'),
    'permission' => $current_user->hasAnyAccess(['plan.add', 'users.superadmin'])
])
@endcomponent

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
                                    <th></th>
                                    <th class="d-none"></th>
                                    <th>
                                        <div class="datatable-form-filter no-padding">{!! Form::text('filter_name',Request::get('filter_name',null),array('class' => 'form-control')) !!}</div>
                                    </th>
                                    <th>
                                        <div class="datatable-form-filter no-padding">{!! Form::text('filter_car_size',Request::get('filter_car_size',null),array('class' => 'form-control')) !!}</div>
                                    </th>
                                    <th>
                                        <div class="datatable-form-filter no-padding">{!! Form::text('filter_frequency',Request::get('filter_frequency',null),array('class' => 'form-control')) !!}</div>
                                    </th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>{{ __('common.action') }}</th>
                                    <th class="d-none noVis"></th>
                                    <th>{{ __('plan.name') }}</th>
                                    <th>{{ __('plan.car_size') }}</th>
                                    <th>{{ __('plan.frequency') }}</th>
                                    <th>{{ __('plan.price') }}</th>
                                    <th>{{__('common.status')}}</th>
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
    var name = "{{ __('plan.name') }}";
    var car_size = "{{ __('plan.car_size') }}";
    var frequency = "{{ __('plan.frequency') }}";

    var price = "{{ __('plan.price') }}";

    var action = "{{ __('common.action') }}";
    var is_active = "{{ __('common.status') }}";

    (function(window, $) {
        window.LaravelDataTables = window.LaravelDataTables || {};
        window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                data: function(d) {
                    d.filter_name = jQuery(".datatable-form-filter input[name='filter_name']").val();
                    d.filter_car_size = jQuery(".datatable-form-filter input[name='filter_car_size']").val();
                    d.filter_frequency = jQuery(".datatable-form-filter input[name='filter_frequency']").val();
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
                }, {
                    "name": "id",
                    "data": "id",
                    "title": "id",
                    "orderable": true,
                    "class": "d-none",
                }, {
                    "name": "name",
                    "data": "name",
                    "title": name,
                    "orderable": true,
                    "searchable": false
                },{
                    "name": "car_size",
                    "data": "car_size",
                    "title": car_size,
                    "orderable": true,
                    "searchable": false
                },{
                    "name": "frequency",
                    "data": "frequency",
                    "title": frequency,
                    "orderable": false,
                    "searchable": false
                },
                {
                    "name": "price",
                    "data": "price",
                    "title": price,
                    "orderable": false,
                    "searchable": false
                },
                {
                    "name": "is_active",
                    "data": "is_active",
                    "title": is_active,
                    "orderable": false,
                    "searchable": false,

                },
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
            dom: `Bfrt<'row'<'col-sm-6 col-md-6'i><'col-sm-6 col-md-6 dataTables_pager'lp>>`, //
        });
    })(window, jQuery);

    $('#dataTableBuilder').on('column-visibility.dt', function(e, settings, column, state) {
        var table = $(this).DataTable();
        table.columns.adjust();
    }); //visibility

    
</script>
@include('comman.datatable_filter')
@endpush
