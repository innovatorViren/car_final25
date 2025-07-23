{{-- Extends layout --}}
@extends('app')
{{-- Content --}}
@section('content')
@section('title', 'Car Model')

@component('partials._subheader.subheader-v6', [
    'page_title' => __('common.car_model'),
    'add_modal' => collect([
        'action' => route('car-model.create'),
        'target' => '#commonModalID',
        'text' => __('common.add'),
    ]),
    'back_text' => __('common.back'),
    'model_back_action' => route('masterPages'),
    'permission' => $current_user->hasAnyAccess(['car_model.add', 'users.superadmin']),
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
                            <th></th>
                            <th class="d-none"></th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('name',Request::get('filter_car_model',null),array('class' => 'form-control')) !!}</div>
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">{!! Form::text('brand_name',Request::get('filter_brand',null),array('class' => 'form-control')) !!}</div>
                            </th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>{{__('common.action')}}</th>
                            <th class="d-none"></th>
                            <th>{{__('car_model.car_model')}}</th>
                            <th>{{__('car_brand.table.car_brand')}}</th>
                            <th>{{__('car_model.photo')}}</th>
                            <th>{{__('common.status')}}</th>

                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
    </div>
</div>
<div id="load-modal"></div>
@endsection

@section('scripts')

<script type="text/javascript">
    var name = "{{__('car_model.car_model')}}";
    var brand = "{{__('car_brand.table.car_brand')}}";
    var status = "{{__('common.status')}}";
    var photo = "{{__('car_model.photo')}}";

    var action = "{{__('common.action')}}";

    (function(window, $) {
        window.LaravelDataTables = window.LaravelDataTables || {};
        window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                data: function(d) {
                    d.name = jQuery(".datatable-form-filter input[name='name']").val();
                    d.brand = jQuery(".datatable-form-filter input[name='brand_name']").val();
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
            },{
                "name": "name",
                "data": "name",
                "title": name,
                "orderable": true,
                "searchable": false
            }, {
                "name": "brand_name",
                "data": "brand_name",
                "title": brand,
                "orderable": true,
                "searchable": false,
                "width": "100px"
            }, {
                "name": "photo",
                "data": "photo",
                "title": photo,
                "orderable": true,
                "searchable": false
            },{
                "name": "is_active",
                "data": "is_active",
                "title": status,
                "orderable": false,
                "searchable": false,

            }, ],
            "searching": false,
            //"dom": "<\"wrapper\">rtilfp",
            "dom": `<'row'<'col-sm-12'tr>>
            <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
            "oLanguage": {
                "sLengthMenu": "Display &nbsp;_MENU_",
            },
            // "stateSave": true,
            responsive: true,
            colReorder: true,
            "buttons": [],
            "order": [
                [1, "desc"]
            ],
            "pageLength": page_show_entriess,
        });
    })(window, jQuery);
</script>
@include('comman.datatable_filter')
@include('info')
@endsection