{{-- Extends layout --}}
@extends('app')
{{-- Content --}}
@section('content')
@section('title', 'Car Brand')

@component('partials._subheader.subheader-v6', [
    'page_title' => __('common.car_brand'),
    'add_modal' => collect([
        'action' => route('car-brand.create'),
        'target' => '#commonModalID',
        'text' => __('common.add'),
    ]),
    'back_text' => __('common.back'),
    'model_back_action' => route('masterPages'),
    'permission' => $current_user->hasAnyAccess(['car_brand.add', 'users.superadmin']),
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
                                <div class="datatable-form-filter no-padding">{!! Form::text('filter_car_brand',Request::get('filter_car_brand',null),array('class' => 'form-control')) !!}</div>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>{{__('common.action')}}</th>
                            <th class="d-none"></th>
                            <th>{{__('car_brand.table.car_brand')}}</th>
                            <th>{{__('car_brand.logo')}}</th>
                            <th>{{__('car_brand.no_of_model')}}</th>
                            <th>{{__('Sequence')}}</th>
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
    var car_brand = "{{__('car_brand.table.car_brand')}}";
    var no_of_model = "{{__('car_brand.no_of_model')}}";
    var status = "{{__('common.status')}}";
    var logo = "{{__('car_brand.logo')}}";
    var sequence = "Sequence";

    var action = "{{__('common.action')}}";

    (function(window, $) {
        window.LaravelDataTables = window.LaravelDataTables || {};
        window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                data: function(d) {
                    d.name = jQuery(".datatable-form-filter input[name='filter_car_brand']").val();
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
                "title": car_brand,
                "orderable": true,
                "searchable": false
            }, {
                "name": "logo",
                "data": "logo",
                "title": logo,
                "orderable": true,
                "searchable": false
            },{
                "name": "car_model_count",
                "data": "car_model_count",
                "title": no_of_model,
                "orderable": true,
                "searchable": false,
                "width": "100px"
            }, 
            {
                    "name": "sequence",
                    "data": "sequence",
                    "title": sequence,
                    "orderable": true,
                    "searchable": false
                },
            {
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
                [5, "desc"]
            ],
            "rowReorder": {
                //'selector' : 'tr>td:not(:last-child)', // I allow all columns for dragdrop except the last
                'dataSrc' : 'sequence',
                {{-- "selector" : "td:nth-child(6)" --}}
                "selector": "td:nth-child(1), td:nth-child(2), td:nth-child(3), td:nth-child(4), td:nth-child(5), td:nth-child(6)"
                //'update' : false // this is key to prevent DT auto update
            },
            "pageLength": page_show_entriess,
        });
    })(window, jQuery);
    $(document).ready(function(){ 
        var table = $('#dataTableBuilder').DataTable();
        table.on( 'row-reorder', function ( e, diff, edit ) {
            var myArray = [];
            for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                var rowData = table.row( diff[i].node ).data();
                myArray.push({
                    id: rowData.id,   // record id from datatable
                    position: diff[i].newData  // new position
                });
            }
            var jsonString = JSON.stringify(myArray);
            // alert(jsonString);
            //console.log(jsonString);
             $.ajax({
                url     : "{{ route('brand-reorder') }}",
                type    : 'POST',
                data    : jsonString,
                dataType: 'json',
                success : function ( json ) 
                {
                     $('#dataTableBuilder').DataTable().ajax.reload(); // now refresh datatable
                   
                }
            }); 
        });
    });
</script>
@include('comman.datatable_filter')
@include('info')
@endsection