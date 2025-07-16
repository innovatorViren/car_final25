{{-- Extends layout --}}
@extends($theme)
{{-- Content --}}
@section('title', __('year.year'))
@section('content')


@component('partials._subheader.subheader-v6',
[
'page_title' => __('year.year'),
'add_modal' => collect([
'action' => route('year.create'),
'target' => '#commonModalID',
'text' => __('common.add'),
]),
'back_text' => __('common.back'),
'model_back_action' => route('masterPages'),
'permission' => $current_user->hasAnyAccess(['years.add', 'users.superadmin']),

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
                                <div class="datatable-form-filter no-padding">
                                    {!! Form::text('yearname',Request::get('yearname',null),array('class' => 'form-control')) !!}
                                </div> 
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">
                                    {!! Form::text('from_date',Request::get('from_date',null),array('class' => 'form-control')) !!}
                                </div> 
                            </th>
                            <th>
                                <div class="datatable-form-filter no-padding">
                                    {!! Form::text('to_date',Request::get('to_date',null),array('class' => 'form-control')) !!}
                                </div> 
                            </th>
                            <th>
                                {{--  <div class="datatable-form-filter no-padding">
                                     {!! Form::text('is_default',Request::get('is_default',null),array('class' => 'form-control')) !!}
                                 </div>  --}}
                             </th>
                        
                        </tr>
                        <tr>
                            <th>{{__('common.action')}}</th>
                            <th class="d-none"></th>
                            <th>{{__('year.year')}}</th>
                            <th width="20%">{{__('year.from_date')}}</th>
                            <th width="20%">{{__('year.to_date')}}</th>
                            <th>{{__('year.is_displayed')}}</th>
                            <th>{{__('year.is_default')}}</th>
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
    var yearname = "{{__('year.year')}}";
    var is_displayed = "{{ __('year.is_displayed') }}";
    var is_default = "{{__('year.is_default')}}";
    var from_date = "{{__('year.from_date')}}";
    var to_date = "{{__('year.to_date')}}";
    var action = "{{__('common.action')}}";

    (function(window, $) {
        window.LaravelDataTables = window.LaravelDataTables || {};
        window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                data: function(d) {
                    d.yearname = jQuery(".datatable-form-filter input[name='yearname']").val();
                    //d.is_default = jQuery(".datatable-form-filter input[name='is_default']").val();
                    d.from_date = jQuery(".datatable-form-filter input[name='from_date']").val();
                    d.to_date = jQuery(".datatable-form-filter input[name='to_date']").val();
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
                "name": "yearname",
                "data": "yearname",
                "title": yearname,
                "orderable": true,
                "searchable": false
            },
            {
                "name": "from_date",
                "data": "from_date",
                "title": from_date,
                "orderable": true,
                "searchable": false
            },
            {
                "name": "to_date",
                "data": "to_date",
                "title": to_date,
                "orderable": true,
                "searchable": false
            },
            {
                "name": "is_displayed",
                "data": "is_displayed",
                "title": is_displayed,
                "orderable": false,
                "searchable": false
            },
            {
                "name": "is_default",
                "data": "is_default",
                "title": is_default,
                "orderable": false,
                "searchable": false
            }, ],
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
            //scrollY: false,
            //scrollX: true,
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
@include('years.script')

@endsection


