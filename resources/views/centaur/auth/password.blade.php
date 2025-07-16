
@include('partials._loginheader.login-header')
    <!--begin::Body-->
    <body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled sidebar-enabled page-loading">
        <!--begin::Main-->
        <div class="d-flex flex-column flex-root">
            <!--begin::Login-->
            <div class="login login-4 login-signin-on d-flex flex-row-fluid" id="kt_login">
                <!--begin::Aside-->
                <div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat" style="background-image: url('{{ asset('media/bg/bg-3.jpg') }}')">
                    <!--begin: Aside Container-->
                    <div class="login-form text-center p-7 position-relative overflow-hidden">
                        <!--begin::Logo-->
                        {{-- <div class="d-flex flex-center mb-10"> --}}
                        <a href="#" class="text-center pt-2">
                            <img src="{{asset($logo)}}" class="max-h-75px w-235px" alt="" />
                        </a>
                        {{-- </div> --}}
                        <!--end::Logo-->
                        <!--begin::Aside body-->
                        <div class="d-flex flex-column-fluid flex-column flex-center">
                            <!--begin::Signin-->
                            <div class="login-form login-signin py-11">
                                <!--begin::Form-->            

                                {!! Form::open(array('route' => ['auth.password.reset.attempt', $code],'role'=>"form",'id'=>'reset_password_form','class'=>'form')) !!}
                                
                                    <!--begin::Title-->
                                    <div class="text-center pb-10">
                                        <h3>Reset Your Password</h3>
                                        
                                    </div>
                                    @if($message = Session::get('error'))
                                    <div role="alert" class="alert alert-danger">
                                        <div class="alert-text">{{Session::get('error')}}</div>
                                    </div>
                                    @endif

                                    @if($message = Session::get('success'))
                                    <div role="alert" class="alert alert-success">
                                        <div class="alert-text">{{Session::get('success')}}</div>
                                    </div>
                                    @endif
                                    <!--end::Title-->
                                    <!--begin::Form group-->
                                    <div class="form-group mb-5 fv-plugins-icon-container text-left">
                                        {!! Form::label('password','Password',['class' => 'pt-5'])!!}<i class="text-danger">*</i>
                                        {!! Form::password('password', ['class' => 'form-control form-control-solid h-auto py-4 px-8 rounded-lg required','autocomplete' => 'off', 'minlength'=>8]) !!}
                                    </div>
                                    <!--end::Form group-->
                                    <!--begin::Form group-->
                                    <div class="form-group mb-5 fv-plugins-icon-container text-left">
                                        <div class="justify-content-between mt-n5">
                                            {!! Form::label('password_confirmation','Confirm Password',['class' => 'pt-5'])!!}<i class="text-danger">*</i>
                                        </div>
                                        {!! Form::password('password_confirmation', ['class' => 'form-control form-control-solid h-auto py-4 px-8 rounded-lg required','autocomplete' => 'off', 'equalto'=>"#password" ,'data-msg-equalTo'=>"Please enter password and confirm password same.", 'minlength'=>8]) !!}
                                        
                                    </div>
                                    
                                    
                                    <!--end::Form group-->
                                    <!--begin::Action-->
                                    <div class="text-center pt-2">
                                        {!! Form::submit("Save", ['name' => 'btnsave','class' => 'btn btn-dark font-weight-bolder font-size-h6 px-8 py-4 my-3']) !!}
                                        
                                    </div>
                                    <!--end::Action-->
                                {!! Form::close() !!}
                                <!--end::Form-->
                            </div>
                            <!--end::Signin-->
                            
                            
                        </div>
                        <!--end::Aside body-->
                        
                    </div>
                    <!--end: Aside Container-->
                </div>
                <!--begin::Aside-->
                <!--begin::Content-->
                {{-- <div class="content order-1 order-lg-2 d-flex flex-column w-100 pb-0" style="background-color: #B1DCED;">
                    <!--begin::Title-->
                    <div class="d-flex flex-column justify-content-center text-center pt-lg-40 pt-md-5 pt-sm-5 px-lg-0 pt-5 px-7">
                        <h3 class="display4 font-weight-bolder my-7 text-dark" style="color: #986923;">Jayshri Propack Pvt. Ltd.</h3>
                        <p class="font-weight-bolder font-size-h2-md font-size-lg text-dark opacity-70">Manufacture of Flexible Packaging Material</p>
                    </div>
                    <!--end::Title-->
                    <!--begin::Image-->
                    <div class="content-img d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center" style="background-image: url('{{ asset('/media/logos/login/login.jpg') }}')"></div>
                    <!--end::Image-->
                </div> --}}
                <!--end::Content-->
            </div>
            <!--end::Login-->
        </div>
        <!--end::Main-->
        
        <script>var KTAppSettings =''; </script>
        <script src="{{asset('/plugins/global/plugins.bundle.js')}}"></script>
        <script src="{{asset('/js/scripts.bundle.js')}}"></script>
        <script src="{{ asset('/js/jquery.validate.min.js') }}"></script>
        <script src="{{asset('/js/pages/custom/login/reset.js')}}"></script> 
    </body>
    <!--end::Body-->
</html>