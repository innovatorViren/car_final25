<!--begin::Header Mobile-->
<div id="kt_header_mobile" class="header-mobile header-mobile-fixed">

    <!--begin::Logo-->
    <a href="{{ route('dashboard') }}">
        <img alt="Logo" src="{{asset($favicon)}}" class="logo-default max-h-30px" />
    </a>

    <!--end::Logo-->

    <!--begin::Toolbar-->
    <div class="d-flex align-items-center">
        <button class="btn rounded-0 p-0 burger-icon burger-icon-left" id="kt_header_mobile_toggle">
            <span></span>
        </button>
        <button class="btn btn-hover-icon-primary p-0 ml-2" id="kt_aside_mobile_toggle">
            <span class="svg-icon svg-icon-xl">

                <!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                    height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path
                            d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z"
                            fill="#000000" fill-rule="nonzero" opacity="0.3" />
                        <path
                            d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"
                            fill="#000000" fill-rule="nonzero" />
                    </g>
                </svg>

                <!--end::Svg Icon-->
            </span>
        </button>
    </div>

    <!--end::Toolbar-->
</div>

{{-- <ul class="sticky-toolbar nav flex-column pl-2 pr-2 pt-3 pb-3 mt-4">
	<!--begin::Item-->
 <li class="nav-item mb-2" id="kt_demo_panel_toggle" data-toggle="tooltip" title="" data-placement="right" data-original-title="Check out more demos"> 
		   @php
                $location_name = $header_location->where('id',$user_location_id)->first();
            @endphp
           <p > {{ $location_name->name ?? ''  }}</p>
	</li> 

</ul> --}}

<!--end::Header Mobile-->
