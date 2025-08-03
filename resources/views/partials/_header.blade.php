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
                        
                        <li class="menu-item {{ Route::currentRouteNamed('customers.*') ? 'menu-item-active' : '' }}"
                            aria-haspopup="true">
                            <a href="{{ route('customers.index') }}" class="menu-link">
                                <span class="menu-text">{{ __('header.customers') }}</span>
                            </a>
                        </li>
                        <li class="menu-item {{ Route::currentRouteNamed('employee.*') ? 'menu-item-active' : '' }}"
                            aria-haspopup="true">
                            <a href="{{ route('employee.index') }}" class="menu-link">
                                <span class="menu-text">{{ __('header.employee') }}</span>
                            </a>
                        </li>
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
