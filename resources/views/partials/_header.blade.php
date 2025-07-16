<!-- begin::Header-->
<style type="text/css">
    @media (min-width: 992px) {
        .header-menu .menu-nav>.menu-item .menu-submenu>.menu-subnav .menu-content .menu-heading>.menu-text {
            font-size: 1.20rem !important;
        }
    }
</style>

<div id="kt_header" class="header header-fixed">
    <!--begin::Header Wrapper-->
    <div class="header-wrapper rounded-top-xl d-flex flex-grow-1 align-items-center">
        <!--begin::Container-->
        <div class="container-fluid d-flex align-items-center justify-content-end justify-content-lg-between flex-wrap">
            <!--begin::Menu Wrapper-->
            <div class="header-menu-wrapper header-menu-wrapper-left py-lg-2" id="kt_header_menu_wrapper">
                <!--begin::Menu-->
                <div id="kt_header_menu"
                    class="header-menu header-menu-mobile header-menu-layout-default header-menu-root-arrow">
                    <!--begin::Nav-->
                    <ul class="menu-nav">
                        

                            <li class="menu-item menu-item-submenu menu-item-rel {{ isActive(['customers.*'], 'menu-item-here') }}"
                                data-menu-toggle="click" aria-haspopup="true">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                    <span class="menu-text">{{ __('header.sales') }}</span>
                                    <span class="menu-desc"></span>
                                    <!-- <i class="menu-arrow"></i> -->
                                </a>
                                <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                    <ul class="menu-subnav">
                                        <li class="menu-item {{ Route::currentRouteNamed('customers.*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true">
                                            <a href="{{ url('customers') }}" class="menu-link">
                                                <span class="svg-icon menu-icon">

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path
                                                                d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z"
                                                                fill="#000000" />
                                                            <rect fill="#000000" opacity="0.3"
                                                                transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)"
                                                                x="16.3255682" y="2.94551858" width="3"
                                                                height="18" rx="1" />
                                                        </g>
                                                    </svg>

                                                </span>
                                                <span class="menu-text">{{ __('header.customers') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {{-- End : Sales --}}
                        {{-- Start : HRM --}}
                            <li class="menu-item menu-item-submenu menu-item-rel {{ isActive(['employee.*'], 'menu-item-here') }}"
                                data-menu-toggle="click" aria-haspopup="true">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                    <span class="menu-text">{{ __('header.hrm') }}</span>
                                    <span class="menu-desc"></span>
                                    {{-- <i class="menu-arrow"></i> --}}
                                </a>
                                <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                    <ul class="menu-subnav">
                                        <li class="menu-item {{ Route::currentRouteNamed('employee.*') ? 'menu-item-active' : '' }}"
                                            aria-haspopup="true">
                                            <a href="/employee" class="menu-link">
                                                <span class="svg-icon menu-icon">

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path
                                                                d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z"
                                                                fill="#000000" />
                                                            <rect fill="#000000" opacity="0.3"
                                                                transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)"
                                                                x="16.3255682" y="2.94551858" width="3"
                                                                height="18" rx="1" />
                                                        </g>
                                                    </svg>
                                                </span>
                                                <span class="menu-text">{{ __('header.employee') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {{-- End : HRM --}}
                    </ul>
                </div>
            </div>
            <div class="d-flex align-items-center py-3">

                <div class="dropdown dropdown-inline" title="" data-placement="left">
                    <a href="#" class="btn btn-sm btn-light-info ml-3 flex-shrink-0" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="icon-sm far fa-calendar-alt"></span>&nbsp;&nbsp;
                        @php
                            $yearname = $header_year->first();
                            $yearname1 =
                                Session::get('default_year_name') != ''
                                    ? Session::get('default_year_name')
                                    : $yearname->yearname ?? '';
                        @endphp
                        {{ $yearname1 }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right p-0" style="">
                        <ul class="navi navi-hover py-5">
                            @php
                                $default_year = Session::get('default_year');
                            @endphp

                            @if (isset($header_year) && $header_year->count() > 0)
                                @foreach ($header_year as $y)
                                    @if ($y->is_displayed == 'Yes')
                                        <li class="navi-item">
                                            <a href="{{ route('years.changeYear', [$y->id, '_url' => Request::getRequestUri()]) }}"
                                                class="navi-link {{ isset($default_year) && $y->id == $default_year->id ? 'active' : '' }}">
                                                <span class="font-weight-bolder text-dark-50 pr-3">
                                                    FY
                                                </span>
                                                <span class="navi-text">{{ $y->yearname ?? '' }}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
