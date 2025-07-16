<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 subheader-transparent" id="kt_subheader">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-2">
            @if (isset($page_title))
                <h2 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{!! $page_title !!}</h2>
            @endif
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center flex-wrap">

            <!-- Filter -->
            @if (isset($excel_id))
                <a href="{{ $excel_link ?? '#' }}"
                    class="btn btn-bg-white btn-icon-success btn-hover-success btn-icon mr-3 my-2 my-lg-0">
                    <i class="far fa-file-excel icon-md"></i>
                </a>
            @endif

            @if (isset($pdf_id))
                <a href="#" class="btn btn-bg-white btn-icon-danger btn-hover-danger btn-icon mr-3 my-2 my-lg-0">
                    <i class="far fa-file-pdf icon-md"></i>
                </a>
            @endif

            @if (isset($print_id))
                <a href="{{ $print_id }}" target="_blank" class="btn btn-bg-white btn-icon-info btn-hover-primary btn-icon mr-3 my-2 my-lg-0">
                    <i class="flaticon2-print icon-md"></i>
                </a>
            @endif

            @if (isset($barcode_id))
                <a href="{{ $barcode_id }}" target="_blank" class="btn btn-bg-white btn-icon-info btn-hover-light-dark btn-icon mr-3 my-2 my-lg-0">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path style="fill:black;" d="M13,5 L15,5 L15,20 L13,20 L13,5 Z M5,5 L5,20 L3,20 C2.44771525,20 2,19.5522847 2,19 L2,6 C2,5.44771525 2.44771525,5 3,5 L5,5 Z M16,5 L18,5 L18,20 L16,20 L16,5 Z M20,5 L21,5 C21.5522847,5 22,5.44771525 22,6 L22,19 C22,19.5522847 21.5522847,20 21,20 L20,20 L20,5 Z"></path>
                                    <polygon style="fill:gray;" points="9 5 9 20 7 20 7 5"></polygon>
                                </g>
                            </svg>
                        </span>
                </a>
            @endif

            @if (isset($print_id_new))
                <div class="btn-group px-2">
                    <button type="button" class="btn btn btn-bg-white btn-icon-info btn-hover-primary btn-icon mr-3 my-2 my-lg-0 dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="flaticon2-print icon-md"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        @if (!empty($print_id_new_1) && !empty($text1))
                            <a class="bg-hover-primary-o-1 dropdown-item" href="{{ $print_id_new_1 }}" target="_blank">{{ $text1 }}</a>
                        @endif
                        @if (!empty($print_id_new_2) && !empty($text2))
                            <a class="bg-hover-primary-o-1 dropdown-item" href="{{ $print_id_new_2 }}" target="_blank">{{ $text2 }}</a>
                        @endif
                    </div>
                </div>
            @endif

            @if (isset($filter_modal_id))
                <a href="javascript:;" data-toggle="modal" data-target="{{ $filter_modal_id }}"
                    class="btn btn-bg-white btn-icon-warning btn-hover-warning btn-icon mr-5"><i
                        class="fas fa-filter"></i></a>
            @endif

            @if (isset($import_model_id) && isset($importPermission) && $importPermission == true)
                <a href="{{ $import_model_id->get('import', 'javaqscrip:void(0)') }}" data-toggle="modal"
                    data-target-modal="{{ $import_model_id->get('target') }}"
                    data-url="{{ $import_model_id->get('import', 'javaqscrip:void(0)') }}"
                    class="btn call-modal btn-primary btn-fixed-height font-weight-bold px-2 px-lg-5 mr-2">
                    <i class="flaticon-upload"></i>{{ $import_model_id->get('text', 'javaqscrip:void(0)') }}
                </a>
            @endif

            @if (isset($generate_modal_id))
                <a href="javascript:;" data-toggle="modal" data-target="{{ $generate_modal_id }}"
                    class="btn btn-bg-warning btn-hover-warning mr-5 text-white">Generate</a>
            @endif

            @if (isset($employeegraph) && $permissiongraph == true)
                <a href="{{ $employeegraph }}" class="btn btn-bg-white btn-icon-dark btn-hover-dark btn-icon mr-5"><i
                        class="flaticon2-graphic text-dark"></i></a>
            @endif

            @if (isset($model_back_action))
                <a href="{{ $model_back_action }}"
                    class="btn btn-outline-dark btn-fixed-height font-weight-bold px-2 px-lg-5 mr-2"><i
                        class="flaticon2-left-arrow-1"></i> &nbsp; {{ $back_text }}
                </a>
            @endif

            @if (isset($action) && isset($permission) && $permission == true)
                <a href="{{ $action }}"
                    class="btn btn-primary btn-fixed-height font-weight-bold px-2 px-lg-5 mr-2 addSrt"><i
                        class="flaticon2-plus"></i> &nbsp; {{ $text }}
                </a>
            @endif

            @if (isset($back_action))
                <a href="{{ $back_action }}"
                    class="btn btn-outline-dark btn-fixed-height font-weight-bold px-2 px-lg-5 mr-2 backSrt"><i
                        class="flaticon2-left-arrow-1"></i> &nbsp; {{ $text }}
                </a>
            @endif

            <!-- Modal Action -->
            @if (isset($modal_id))
                <a href="javascript:;" data-toggle="modal" data-target="{{ $modal_id }}"
                    class="btn btn-primary btn-fixed-height font-weight-bold px-2 px-lg-5 mr-2">
                    <i class="flaticon2-plus"></i> &nbsp; {{ $text }}
                </a>
            @endif

            <!-- Open Model  -->
            @if (isset($add_modal) && isset($permission) && $permission == true)
                <a href="{{ $add_modal->get('action', 'javaqscrip:void(0)') }}" data-toggle="modal"
                    data-target-modal="{{ $add_modal->get('target') }}"
                    data-url="{{ $add_modal->get('action', 'javaqscrip:void(0)') }}"
                    class="btn call-modal btn-primary btn-fixed-height font-weight-bold px-2 px-lg-5 mr-2 disabled"
                    id="addModalDisabled">
                    <i class="flaticon2-plus"></i> &nbsp; {{ $add_modal->get('text', 'javaqscrip:void(0)') }}
                </a>
            @endif
            <!-- End Open Modal -->

            <!-- Dropdown Add button -->
            @if (isset($dropdown_text) && isset($permission) && $permission == true)
                <div class="btn-group px-2">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="flaticon2-plus"></i> {{ $dropdown_text }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        @if (!empty($dropdown_action1) && !empty($text1))
                            <a class="dropdown-item" href="{{ $dropdown_action1 }}">{{ $text1 }}</a>
                        @endif
                        @if (!empty($dropdown_action2) && !empty($text2))
                            <a class="dropdown-item" href="{{ $dropdown_action2 }}">{{ $text2 }}</a>
                        @endif
                        @if (!empty($dropdown_action3) && !empty($text3))
                            <a class="dropdown-item" href="{{ $dropdown_action3 }}">{{ $text3 }}</a>
                        @endif
                        @if (!empty($dropdown_action4) && !empty($text4))
                            <a class="dropdown-item" href="{{ $dropdown_action4 }}">{{ $text4 }}</a>
                        @endif
                    </div>
                </div>
            @endif

            <!--end::Actions-->

            <!--Only purchase_indent module-->

            @if (isset($dropdown_text_pi) && $permission == true)
                <div class="btn-group px-2">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="flaticon2-plus"></i> {{ $dropdown_text_pi }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        @if (!empty($dropdown_action1) && !empty($text1) && $permission1 == true)
                            <a class="dropdown-item" href="{{ $dropdown_action1 }}">{{ $text1 }}</a>
                        @endif
                        @if (!empty($dropdown_action2) && !empty($text2) && $permission2 == true)
                            <a class="dropdown-item" href="{{ $dropdown_action2 }}">{{ $text2 }}</a>
                        @endif
                        @if (!empty($dropdown_action3) && !empty($text3) && $permission3 == true)
                            <a class="dropdown-item" href="{{ $dropdown_action3 }}">{{ $text3 }}</a>
                        @endif
                    </div>
                </div>
            @endif

            <!--begin::Dropdown-->
            {{-- <div class="dropdown dropdown-inline my-2 my-lg-0" data-toggle="tooltip" title="Quick actions"
                data-placement="left">
                <a href="#" class="btn btn-primary btn-icon" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <span class="svg-icon svg-icon-md">

                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path
                                    d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z"
                                    fill="#000000" />
                            </g>
                        </svg>

                    </span>
                </a>
                <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">

                    <ul class="navi navi-hover">
                        <li class="navi-header font-weight-bold py-4">
                            <span class="font-size-lg">Choose Label:</span>
                            <i class="flaticon2-information icon-md text-muted" data-toggle="tooltip"
                                data-placement="right" title="Click to learn more..."></i>
                        </li>
                        <li class="navi-separator mb-3 opacity-70"></li>
                        <li class="navi-item">
                            <a href="#" class="navi-link">
                                <span class="navi-text">
                                    <span class="label label-xl label-inline label-light-success">Customer</span>
                                </span>
                            </a>
                        </li>
                        <li class="navi-item">
                            <a href="#" class="navi-link">
                                <span class="navi-text">
                                    <span class="label label-xl label-inline label-light-danger">Partner</span>
                                </span>
                            </a>
                        </li>
                        <li class="navi-item">
                            <a href="#" class="navi-link">
                                <span class="navi-text">
                                    <span class="label label-xl label-inline label-light-warning">Suplier</span>
                                </span>
                            </a>
                        </li>
                        <li class="navi-item">
                            <a href="#" class="navi-link">
                                <span class="navi-text">
                                    <span class="label label-xl label-inline label-light-primary">Member</span>
                                </span>
                            </a>
                        </li>
                        <li class="navi-item">
                            <a href="#" class="navi-link">
                                <span class="navi-text">
                                    <span class="label label-xl label-inline label-light-dark">Staff</span>
                                </span>
                            </a>
                        </li>
                        <li class="navi-separator mt-3 opacity-70"></li>
                        <li class="navi-footer py-4">
                            <a class="btn btn-clean font-weight-bold btn-sm" href="#">
                                <i class="ki ki-plus icon-sm"></i>Add new</a>
                        </li>
                    </ul>

                </div>
            </div> --}}
            @if (isset($sync_attendance_biomax) && env('ATT_DEVICE_TYPE') == 'BioMax' && $sync_permission)
                < a href="{{ url($sync_attendance_biomax) }}" class="btn btn-success">
                    <i class="flaticon2-refresh"></i>
                    </a>
            @endif

        </div>

    </div>
</div>

<!--end::Subheader-->
