{{-- Extends layout --}}
@extends('app')
{{-- Content --}}
@section('content')
@section('title', __('roles.title'))
@component('partials._subheader.subheader-v6', [
    'page_title' => __('roles.title'),
    // 'add_modal' => collect([
    // 'view_action' => route('role.viewPermission'),
    // 'view_text' => __('roles.view_permission'),
    'action' => route('roles.create'),
    'text' => __('common.add'),
    // ]),
    'view_permission' => $current_user->hasAnyAccess(['roles.show', 'users.superadmin']),
    'permission' => $current_user->hasAnyAccess(['roles.add', 'users.superadmin']),
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
                                <div class="col-sm-8 datatable-form-filter no-padding">
                                    {!! Form::text('filter_name', Request::get('filter_name', null), ['class' => 'form-control']) !!}
                                </div>
                            </th>
                            {{-- <th>
                                <div class="col-sm-8 datatable-form-filter">
                                    {!! Form::text('filter_slug',Request::get('filter_slug',null),array('class' => 'form-control', 'style' => 'width: 150px;')) !!}
                                </div>
                            </th> --}}
                            <th></th>
                        </tr>
                        <tr>
                            <th width="5%">{{ __('common.action') }}</th>
                            <th class="d-none"></th>
                            <th width="20%">{{ __('roles.table.name') }}</th>
                            {{-- <th width="20%">{{__('roles.table.slug')}}</th> --}}
                            <th width="20%">{{ __('roles.table.users') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.roles.user-modal')
@endsection

@section('scripts')
<script type="text/javascript">
    (function(window, $) {
        window.LaravelDataTables = window.LaravelDataTables || {};
        window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                data: function(d) {
                    d.name = jQuery(".datatable-form-filter input[name='filter_name']").val();
                    d.slug = jQuery(".datatable-form-filter input[name='filter_slug']").val();
                    d.users = jQuery(".datatable-form-filter input[name='filter_users']").val();
                }
            },
            "columns": [{
                    "name": "action",
                    "data": "action",
                    "title": "Action",
                    "render": null,
                    "orderable": false,
                    "searchable": false,
                }, {
                    "name": "id",
                    "data": "id",
                    "title": "id",
                    "orderable": true,
                    "class": "d-none",
                }, {
                    "name": "name",
                    "data": "name",
                    "title": "Name",
                    "orderable": true,
                    "searchable": false
                },
                //  {
                //     "name": "slug",
                //     "data": "slug",
                //     "title": "Slug",
                //     "orderable": true,
                //     "searchable": false
                // },
                {
                    "name": "users",
                    "data": "users",
                    "title": "Users",
                    "orderable": true,
                    "searchable": false
                },
            ],
            "searching": false,
            "dom": `<'row'<'col-sm-12'tr>>
                <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
            "oLanguage": {
                "sLengthMenu": "Display &nbsp;_MENU_",
            },
            "stateSave": true,
            responsive: true,
            colReorder: true,
            scrollY: false,
            scrollX: true,
            "buttons": [],
            "order": [
                [1, "desc"]
            ],
            "pageLength": page_show_entriess,
        });
    })(window, jQuery);

    $(document).on('click', '.roleModal', function(e) {
        e.preventDefault();

        var roleID = $(this).attr('data-id');
        var target = $(this).data('target-modal');
        var footerHide = $(this).data('footer-hide');
        $.ajax({
            type: "GET",
            url: "{{ route('role.getUsersList') }}",
            data: {
                'role_id': roleID,
            },
        }).always(function() {
            $('.tBody').html(' ')
            $('.modal-footer').show();
        }).done(function(response) {
            var roleData = response.roleData;
            var html = '';
            if (roleData) {
                var userData = roleData.users;
                $.each(userData, function(key, val) {
                    var index = key + 1;
                    var url = '/users/' + val.id;
                    if (val.is_active == 'Yes') {
                        var status = 'Active';
                    } else {
                        var status = 'Inactive';
                    }
                    var firstName = val.first_name ?? '';
                    var lastName = val.last_name ?? '';
                    html += '<tr>';
                    html += '<td>' + index + '</td>';
                    html += '<td><a href="' + url + '" target="_blank">' + firstName + ' ' +
                        lastName + '</a></td>';
                    html += '<td>' + status + '</td>';
                    html += '</tr>';
                });
                $(".tBody").html("").append(html);
            } else {
                html += '<tr><td colspan="3" class="text-center">No Records Found</td></tr>';
                $(".tBody").html("").append(html);
            }

            $(target).modal('toggle');
            if (footerHide) {
                $('.modal-footer').hide();
            }
        });
    });
</script>
@include('comman.datatable_filter')
@include('info')
@endsection
