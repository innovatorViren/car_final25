<!--begin::Aside-->
<div class="aside aside-left d-flex flex-column" id="kt_aside">

    <div class="aside-brand d-flex flex-column align-items-center flex-column-auto pt-5 pt-lg-18 pb-10">
        <div class="btn p-0 symbol symbol-60 symbol-light-primary" href="?page=index" id="kt_quick_user_toggle">
            <div class="symbol-label">
                @php
                    $company_logo = '';
                    $company_favicon = '';
                    if (isset($companylogo) && !empty($companylogo)) {
                        $company_logo = $companylogo['company_logo'] ?? '';
                        $company_favicon = $companylogo['company_favicon'] ?? '';
                    }
                @endphp
                @if (!$company_logo)
                    <img alt="Logo" src="{{ asset('storage/noimage/noimage.jpg') }}" class="h-50 align-self-center" />
                @else
                    <img alt="Logo" src="{{ asset('') }}{{ $company_logo }}" class="h-50 align-self-center" />
                @endif
            </div>
        </div>
    </div>

    <!--begin::Nav Wrapper-->
    <div class="aside-nav d-flex flex-column align-items-center flex-column-fluid pb-10">

        <!--begin::Nav-->
        <ul class="nav flex-column">
            <!--begin::Report-->
            <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                data-boundary="window" title="Dashboard">
                <a href="{{ url('/dashboard') }}"
                    class="btn btn-icon btn-hover-text-primary btn-lg mb-1 position-relative" id=""
                    data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window"
                    title="Dashboard">

                    <span class="svg-icon svg-icon-xxl">
                        <svg id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512"
                            width="512" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <path style="fill: gray;"
                                    d="m0 365.908h68.238c1.14-24.069 7.341-46.81 17.55-67.196l-63.218-38.057c-14.499 32.105-22.57 67.736-22.57 105.253z">
                                </path>
                                <path style="fill: gray;"
                                    d="m296.206 113.059-13.23 99.098c25.586 7.347 48.673 20.591 67.696 38.175l77.377-73.991c-36.066-32.752-81.519-55.342-131.843-63.282z">
                                </path>
                                <path style="fill: gray;"
                                    d="m36.539 234.048 65.018 39.141c14.301-19.101 32.563-35.063 53.571-46.668l-49.086-68.096c-27.947 20.233-51.646 45.966-69.503 75.623z">
                                </path>
                                <path style="fill: gray;"
                                    d="m449.042 197.776-78.219 74.796c19.803 26.201 32.125 58.371 33.782 93.336h107.395c0-64.353-23.751-123.155-62.958-168.132z">
                                </path>
                                <path style="fill: gray;"
                                    d="m253.467 206.482 12.863-96.349c-3.428-.136-6.869-.225-10.33-.225-45.247 0-87.743 11.755-124.627 32.353l51.861 71.946c16.719-5.562 34.6-8.581 53.188-8.581 5.754 0 11.439.292 17.045.856z">
                                </path>
                                <path
                                    d="m209.981 356.073c0 25.416 20.603 46.019 46.019 46.019s46.019-20.603 46.019-46.019-46.019-142.987-46.019-142.987-46.019 117.572-46.019 142.987z">
                                </path>
                            </g>
                        </svg>
                    </span>
                </a>
            </li>
            <!--end::Report-->

            <!--begin::Report-->
            <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                data-boundary="window" title="Report">
                <a href="{{ route('reports') }}"
                    class="btn btn-icon btn-hover-text-primary btn-lg mb-1 position-relative" id=""
                    data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window"
                    title="Report">
                    <span class="svg-icon svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <rect style="fill:gray;" fill="#000000" x="13" y="4" width="3" height="16"
                                    rx="1.5"></rect>
                                <rect style="fill:black;" fill="#000000" x="8" y="9" width="3" height="11"
                                    rx="1.5"></rect>
                                <rect style="fill:black;" fill="#000000" x="18" y="11" width="3" height="9"
                                    rx="1.5"></rect>
                                <rect style="fill:black;" fill="#000000" x="3" y="13" width="3" height="7"
                                    rx="1.5"></rect>
                            </g>
                        </svg>
                    </span>
                </a>
            </li>
            <!--end::Report-->

            <!--begin::Master-->
            <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                data-boundary="window" title="Master">
                {{-- <a href="#" class="btn btn-icon btn-hover-text-primary btn-lg mb-1 position-relative" id="kt_quick_master_toggle" data-toggle="tooltip" data-placement="right"
                data-container="body" data-boundary="window" title="Master"> --}}

                <a href="{{ route('masterPages') }}"
                    class="btn btn-icon btn-hover-text-primary btn-lg mb-1 position-relative" id=""
                    data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window"
                    title="Master">
                    <span class="svg-icon svg-icon-xxl">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <rect style="fill:black;" fill="#000000" x="4" y="4" width="7"
                                    height="7" rx="1.5" />
                                <path style="fill:gray;"
                                    d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z" />
                            </g>
                        </svg>
                    </span>
                </a>
            </li>
            <!--end::Master-->

            <!--Start::Users-->
            {{-- @if ($current_user->hasAnyAccess(['users.list', 'users.superadmin'])) --}}
                <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                    data-boundary="window" title="Users">
                    <a href="{{ url('users') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg"
                        data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window"
                        title="User">
                        <span class="svg-icon svg-icon-xxl">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24" />
                                    <path style="fill:gray;"
                                        d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                        fill="#000000" fill-rule="nonzero" />
                                    <path style="fill:black;"
                                        d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                        fill="#000000" fill-rule="nonzero" />
                                </g>
                            </svg>
                        </span>
                    </a>
                </li>
            {{-- @endif --}}
            <!--end::Users-->

            <!--Start::Role-->
            {{-- @if ($current_user->hasAnyAccess(['roles.list', 'users.superadmin'])) --}}
                <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                    data-boundary="window" title="Role">
                    <a href="{{ url('roles') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg"
                        data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window"
                        title="Role">
                        <span class="svg-icon svg-icon-primary svg-icon-xxl">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <polygon style="fill:gray;"
                                        transform="translate(8.885842, 16.114158) rotate(-315.000000) translate(-8.885842, -16.114158) "
                                        points="6.89784488 10.6187476 6.76452164 19.4882481 8.88584198 21.6095684 11.0071623 19.4882481 9.59294876 18.0740345 10.9659914 16.7009919 9.55177787 15.2867783 11.0071623 13.8313939 10.8837471 10.6187476" />
                                    <path style="fill:black;"
                                        d="M15.9852814,14.9852814 C12.6715729,14.9852814 9.98528137,12.2989899 9.98528137,8.98528137 C9.98528137,5.67157288 12.6715729,2.98528137 15.9852814,2.98528137 C19.2989899,2.98528137 21.9852814,5.67157288 21.9852814,8.98528137 C21.9852814,12.2989899 19.2989899,14.9852814 15.9852814,14.9852814 Z M16.1776695,9.07106781 C17.0060967,9.07106781 17.6776695,8.39949494 17.6776695,7.57106781 C17.6776695,6.74264069 17.0060967,6.07106781 16.1776695,6.07106781 C15.3492424,6.07106781 14.6776695,6.74264069 14.6776695,7.57106781 C14.6776695,8.39949494 15.3492424,9.07106781 16.1776695,9.07106781 Z"
                                        transform="translate(15.985281, 8.985281) rotate(-315.000000) translate(-15.985281, -8.985281) " />
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                    </a>
                </li>
            {{-- @endif --}}
            <!--end::Role-->
            <!--Start::Settings-->
            {{-- @if ($current_user->hasAnyAccess(['users.superadmin'])) --}}
                <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                    data-boundary="window" title="Settings">
                    <a href="{{ url('settings') }}" class="btn btn-icon btn-hover-text-primary btn-lg mb-1"
                        id="kt_settings_toggle" data-toggle="tooltip" data-placement="right" data-container="body"
                        data-boundary="window" title="Settings">
                        <span class="svg-icon svg-icon-xxl">
                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 52 52"
                                style="enable-background:new 0 0 52 52;" xml:space="preserve">
                                <path style="fill:gray; width: 24px; height: 24px;" d="M48.872,22.898c-4.643-0.893-6.772-6.318-3.976-10.13l1.964-2.678l-4.243-4.243l-2.638,1.787
                               c-3.914,2.652-9.256,0.321-9.974-4.351L29.5,0h-6l-0.777,4.041c-0.873,4.54-6.105,6.708-9.933,4.115L9.383,5.847L5.14,10.09
                               l1.964,2.678c2.796,3.812,0.666,9.237-3.976,10.13L0,23.5v6l3.283,0.505c4.673,0.719,7.003,6.061,4.351,9.975l-1.787,2.637
                               l4.243,4.243l2.678-1.964c3.812-2.796,9.237-0.667,10.13,3.976L23.5,52h6l0.355-2.309c0.735-4.776,6.274-7.071,10.171-4.213
                               l1.884,1.382l4.243-4.243l-1.787-2.637c-2.651-3.914-0.321-9.256,4.351-9.975L52,29.5v-6L48.872,22.898z M26.5,31
                               c-2.485,0-4.5-2.015-4.5-4.5s2.015-4.5,4.5-4.5s4.5,2.015,4.5,4.5S28.985,31,26.5,31z" />
                                <path d="M26.5,17c-5.247,0-9.5,4.253-9.5,9.5s4.253,9.5,9.5,9.5s9.5-4.253,9.5-9.5S31.747,17,26.5,17z
                               M26.5,31c-2.485,0-4.5-2.015-4.5-4.5s2.015-4.5,4.5-4.5s4.5,2.015,4.5,4.5S28.985,31,26.5,31z" />
                                <g></g>
                                <g></g>
                                <g></g>
                                <g></g>
                                <g></g>
                                <g></g>
                                <g></g>
                                <g></g>
                                <g></g>
                                <g></g>
                                <g></g>
                                <g></g>
                                <g></g>
                                <g></g>
                                <g></g>
                            </svg>
                        </span>
                    </a>
                </li>
            {{-- @endif --}}
            <!--end::Settings-->

            <!--begin::logout-->
            <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                data-boundary="window" title="Logout">
                <a href="Javascript:void(0)"
                    class="btn btn-icon btn-hover-text-primary btn-lg mb-1 position-relative" id=""
                    data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window"
                    title="Logout" onclick="logOutConfirm()">
                    <span class="svg-icon svg-icon-xxl">
                        <svg style="enable-background:new 0 0 24 24;" version="1.1" viewBox="0 0 24 24"
                            xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="info" />
                            <g id="icons">
                                <g id="exit2">
                                    <path
                                        d="M12,10c1.1,0,2-0.9,2-2V4c0-1.1-0.9-2-2-2s-2,0.9-2,2v4C10,9.1,10.9,10,12,10z" />
                                    <path style="fill:gray;"
                                        d="M19.1,4.9L19.1,4.9c-0.3-0.3-0.6-0.4-1.1-0.4c-0.8,0-1.5,0.7-1.5,1.5c0,0.4,0.2,0.8,0.4,1.1l0,0c0,0,0,0,0,0c0,0,0,0,0,0    c1.3,1.3,2,3,2,4.9c0,3.9-3.1,7-7,7s-7-3.1-7-7c0-1.9,0.8-3.7,2.1-4.9l0,0C7.3,6.8,7.5,6.4,7.5,6c0-0.8-0.7-1.5-1.5-1.5    c-0.4,0-0.8,0.2-1.1,0.4l0,0C3.1,6.7,2,9.2,2,12c0,5.5,4.5,10,10,10s10-4.5,10-10C22,9.2,20.9,6.7,19.1,4.9z" />
                                </g>
                            </g>
                        </svg>
                    </span>
                </a>
            </li>
            <!--end::logout-->
        </ul>

        <!--end::Nav-->
    </div>
    <!--end::Nav Wrapper-->
</div>


<!--end::Aside
