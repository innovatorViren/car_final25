@php
    $flag_row = 0;
    $tmp = 1;
@endphp
@php
    $changePerm = [
        'accounthead' => 'Account Head',
        'accountmaster' => 'Account Master',
        'countries' => 'Country',
    ];

    $cardwisePerm = [
        'users' => 'Side Panel',
        'unit' => 'Master',
        'rm-category' => 'Purchase',
        'grinding' => 'Production',
        'category' => 'Sales',
        'employee' => 'HRM',
        'routes' => 'Shop',
        'transfer' => 'Transfer',
        'purchase' => 'Reports',
    ];
@endphp

@foreach ($all_permission as $area => $permissions)
    @if (array_key_exists($area, $cardwisePerm))
        @php
            $flag_row = 0;
        @endphp
        @if (!$loop->first)
            </div>
            </div>
            </div>
        @endif
        <!-- User & role -->
        <div class="card card-custom gutter-b">
            <div class="card-header" style="min-height: 55px;">
                <h3 class="card-title">{{ $cardwisePerm[$area] }}</h3>
            </div>
            <div class="card-body pt-7 col-12 pb-1">
                <div class="row">
    @endif
    @php
        $flag_row++;
    @endphp
    <div class="col-lg-6">
        <div class="card card-custom card-collapsed card-style mb-5" data-card="true">
            <div class="card-header cls-parent-permission">
                <div class="card-title">
                    <h3 class="card-label">
                        @if (array_key_exists($area, $changePerm) || isset($changePerm[$area]) || strpos($area, 'Sales') !== false)
                            {{ $changePerm[$area] }}
                        @else
                            {{ ucwords(str_replace(['_', '-'], [' ', ' '], $area)) }}
                        @endif
                    </h3>
                </div>
                <div class="card-toolbar">
                    @php
                        $allow = $deny = $counter = 0;
                    @endphp
                    @if (isset($groupPermissions))
                        @foreach ($permissions as $permission)
                            @php $counter++; @endphp
                            @if (array_get($groupPermissions, $permission['permission']))
                                @php $allow++; @endphp
                            @endif
                            @if (array_get($groupPermissions, $permission['permission']))
                                @php $deny++; @endphp
                            @endif
                        @endforeach
                    @endif
                    <a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-2" data-card-tool="toggle">
                        <i class="ki ki-arrow-up icon-nm"></i>
                    </a>
                    <div class="checkbox-inline pt-1">
                        @php
                            $allcheck = false;
                            if (isset($groupPermissions) && $counter === $allow) {
                                $allcheck = true;
                            }
                        @endphp
                        <label class="checkbox checkbox-square check-label">
                            {!! Form::checkbox('grp[' . $tmp . ']', '1', $allcheck, [
                                'id' => $area . '_allow',
                                'data-allow' => $tmp . '_alw',
                                'class' => 'allow all parent_alw styled input-checkbox',
                                'data-total_count' => $counter,
                                'data-selected_count' => $allow,
                            ]) !!}
                            {{-- <input type="checkbox" value="1" id="{{ $area }}_allow"
                                name="grp[{{ $tmp }}]" data-allow="{{ $tmp }}_alw"
                                class="allow all parent_alw styled"
                                {{ isset($groupPermissions) ? ($counter === $allow ? ' checked="checked"' : '') : '' }}> --}}
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @foreach ($permissions as $keyname => $permission)
                    @php
                        if ($area == 'purchase_order' && $permission['label'] == 'edit') {
                            $permission['label'] = 'edit / edit status';
                        }
                    @endphp
                    <div class="d-flex align-items-center cls-sub-permission">
                        <div class="d-flex flex-column flex-grow-1">
                            <span class="font-weight-bold">
                                <label class="sub_title col-md-8">
                                    @if (array_key_exists($permission['label'], $changePerm) && $area != 'report')
                                        {{ $changePerm[$permission['label']] }}
                                    @else
                                        {{ ucwords(str_replace('_', ' ', $permission['label'])) }}
                                    @endif
                                </label>
                            </span>
                        </div>
                        <div class="checkbox-inline pt-1">
                            @php
                                $childcheck = false;
                                if (
                                    isset($groupPermissions) &&
                                    array_get($groupPermissions, $permission['permission'])
                                ) {
                                    $childcheck = true;
                                }
                            @endphp
                            <label class="checkbox checkbox-square">
                                {!! Form::checkbox('permissions[' . $permission['permission'] . ']', '1', $childcheck, [
                                    'id' => $permission['permission'] . '_allow',
                                    'data-allow' => $tmp . '_alw',
                                    'class' => $tmp . '_alw child_alw styled',
                                    'data-area' => $area,
                                ]) !!}

                                {{--  <input type="checkbox" value="1" id="{{ $permission['permission']}}_allow" class="{{$tmp}}_alw child_alw styled" name="permissions[{{ $permission['permission']}}]" {{ isset($groupPermissions) ? (array_get($groupPermissions,$permission['permission']) ? ' checked="checked"' : ''):'' }} /> --}}
                                {{-- <input type="checkbox" name="users"> --}}
                                <span></span>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @php
        $tmp++;
    @endphp
    @if ($loop->last)
        </div>
        </div>
        </div>
    @endif
@endforeach

@section('styles')
    <style>
        .input-checkbox:indeterminate+span {
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10h8'/%3E%3C/svg%3E");
            background-color: #3c3b90;
        }
    </style>
@endsection

@push('scripts')
    <script type="text/javascript">
        jQuery(".select2").select2();
        setIntermediateCheck();

        // For all Permission Jquery - Start
        jQuery(".allow").on('change', function() {
            var attribute = jQuery(this).attr('data-allow');
            var classname = "." + attribute;
            if (jQuery(this).is(':checked')) {
                jQuery(classname).each(function() {
                    id = jQuery(this).prop('id');
                    jQuery(this).prop('checked', true);
                    jQuery(this).parent().addClass('checked');
                    //jQuery(this).trigger("click");
                });
            } else {
                jQuery(classname).each(function() {
                    jQuery(this).prop('checked', false);
                    jQuery(this).parent().removeClass('checked');
                    //jQuery(this).trigger("click");
                });

            }
        });
        jQuery(".deny").on('change', function() {
            var attribute = jQuery(this).attr('data-deny');
            var classname = "." + attribute;
            jQuery(classname).each(function() {
                jQuery(this).prop('checked', true);
            });
        });


        // For deselect when any on is not selected - Start
        jQuery('.child_alw').on('click', function() {
            var totalCheckbox = jQuery(this).parents('.card-body').children('.cls-sub-permission').length;
            var selectedCheckbox = jQuery(this).parents('.card-body').children('.cls-sub-permission').find(
                ':input:checked.child_alw').length
            var area = jQuery(this).data('area');

            if (totalCheckbox == selectedCheckbox) {
                $('#' + area + '_allow').prop({
                    'checked': true,
                    'indeterminate': false
                });
                $('#' + area + '_allow').parent().addClass("checked");
                // jQuery(this).parents('.cls-parent-permission').find('.all_per_div :input.parent_alw').prop('checked', true);
                // jQuery(this).parents('.cls-parent-permission').find('.all_per_div :input.parent_alw').parent().addClass("checked");
            } else {
                if (selectedCheckbox == 0) {
                    $('#' + area + '_allow').prop({
                        'checked': false,
                        'indeterminate': false
                    });
                    $('#' + area + '_allow').parent().removeClass("checked");
                } else {
                    $('#' + area + '_allow').prop('indeterminate', true);
                }
                // jQuery(this).parents('.cls-parent-permission').find('.all_per_div :input.parent_alw').prop('checked', false);
                // jQuery(this).parents('.cls-parent-permission').find('.all_per_div :input.parent_alw').parent().removeClass("checked");
            }
        });
        jQuery('.child_dny').on('click', function() {
            var total_checkbox = jQuery(this).parents('.sub_permission').find(':input.child_dny').length;
            var selected_checkbox = jQuery(this).parents('.sub_permission').find(':input:checked.child_dny').length
            if (total_checkbox == selected_checkbox) {
                jQuery(this).parents('.permission_display').find('.all_per_div :input.parent_dny').prop('checked',
                    true);
            } else {
                jQuery(this).parents('.permission_display').find('.all_per_div :input.parent_alw').prop('checked',
                    false);
            }
        });
        // For deselect when any on is not selected - End


        /* Sub Permission toggle Code Start */
        jQuery('.permission_title').on('click', function() {
            var data_id = jQuery(this).attr('data-id');
            // jQuery(".sub_permission").css("height","124px");
            if (jQuery('#sub_' + data_id).is(":hidden")) {
                jQuery(this).find("i").removeClass("fa-chevron-circle-down");
                jQuery(this).find("i").addClass("fa-chevron-circle-up");
            } else {
                jQuery(this).find("i").addClass("fa-chevron-circle-down");
                jQuery(this).find("i").removeClass("fa-chevron-circle-up");

            }
            jQuery(document).find('#sub_' + data_id).slideToggle("slow", "linear");

        });

        function setIntermediateCheck() {
            $('.parent_alw').each(function(index, value) {
                var id = $(this).attr('id');
                var totalCount = $(this).data('total_count');
                var selectedCount = $(this).data('selected_count');
                if (totalCount != selectedCount && selectedCount > 0) {
                    $(this).prop('indeterminate', true);
                }
            });
        }
    </script>
@endpush
