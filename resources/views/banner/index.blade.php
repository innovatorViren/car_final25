{{-- Extends layout --}}
@extends($theme)
{{-- Content --}}
@section('title', $title)

@section('content')

    @component('partials._subheader.subheader-v6', [
        'page_title' => $title,
        'add_modal' => collect([
            'action' => route('banner.create'),
            'target' => '#commonModalID',
            'text' => __('common.add'),
        ]),
        'back_text' => __('common.back'),
        'model_back_action' => route('masterPages'),
        'permission' => $current_user->hasAnyAccess(['banner.add', 'users.superadmin']),
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
                                    <div class="datatable-form-filter no-padding">{!! Form::text('filter_title', null, ['class' => 'form-control']) !!}</div>
                                </th>
                                <th></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th>{{ __('common.action') }}</th>
                                <th class="d-none"></th>
                                <th>{{ __('banner.title') }}</th>
                                <th>{{ __('banner.image') }}</th>
                                <th>{{ __('common.status') }}</th>
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
        var title = "{{ __('banner.title') }}";
        var image = "{{ __('banner.image') }}";
        var status = "{{ __('common.status') }}";
        var action = "{{ __('common.action') }}";

        (function(window, $) {
            window.LaravelDataTables = window.LaravelDataTables || {};
            window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
                "serverSide": true,
                "processing": true,
                "ajax": {
                    data: function(d) {
                        d.filter_title = jQuery(".datatable-form-filter input[name='filter_title']").val();
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
                    "name": "title",
                    "data": "title",
                    "title": title,
                    "orderable": true,
                    "searchable": false
                }, {
                    "name": "image",
                    "data": "image",
                    "title": image,
                    "orderable": true,
                    "searchable": false
                }, {
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
@endsection
