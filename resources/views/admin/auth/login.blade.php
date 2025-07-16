
@include('partials._loginheader.login-header')
    <!--begin::Body-->
    <body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled sidebar-enabled page-loading">
        <!--begin::Main-->
        <div class="d-flex flex-column flex-root">
            <!--begin::Login-->
            <div class="login login-4 d-flex flex-row-fluid login-signin-on" id="kt_login">
                <!--begin::Aside-->
                <div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat" style="background-image: url('{{ asset('media/bg/bg-3.jpg') }}')">
                    <!--begin: Aside Container-->
                    <div class="login-form text-center p-7 position-relative overflow-hidden">
                        <!--begin::Logo-->
                        <div class="d-flex flex-center">
                        <a href="#" class="text-center pt-2">
                            <img src="{{asset($logo)}}" class="w-235px" alt="" />
                        </a>
                        </div>
                        <!--end::Logo-->
                        <!--begin::Aside body-->
                        <div class="d-flex flex-column-fluid flex-column flex-center">
                            <!--begin::Signin-->
                            <div class="login-form login-signin py-11">
                                <!--begin::Form-->
                                {!! Form::open(array('route' => 'auth.login.attempt','role'=>"form",'id'=>'kt_login_signin_form','class'=>'form')) !!}
                                
                                    <!--begin::Title-->
                                    <div class="text-center pb-10">
                                        <h3>{{$project_title ?? ''}}</h3>
                                        
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
                                        {!! Form::label('email','Email',['class' => 'pt-5'])!!}<i class="text-danger">*</i>
                                        {!! Form::email('email', null, ['class' => 'form-control form-control-solid h-auto py-4 px-8 rounded-lg required email','autocomplete' => 'off']) !!}
                                        {!! ($errors->has('email') ? $errors->first('email', '<label class="text-danger">:message</label>') : '') !!}
                                        <div class="form-control-feedback">
                                            <i class="icon-user-check text-muted"></i>
                                        </div>
                                    </div>
                                    <!--end::Form group-->
                                    <!--begin::Form group-->
                                    <div class="form-group mb-5 fv-plugins-icon-container text-left">
                                        <div class="justify-content-between mt-n5">
                                            {!! Form::label('password','Password',['class' => 'pt-5'])!!}<i class="text-danger">*</i>
                                        </div>
                                        {!! Form::password('password', ['class' => 'form-control form-control-solid h-auto py-4 px-8 rounded-lg required','autocomplete' => 'off']) !!}
                                        {!! ($errors->has('password') ? $errors->first('password', '<label class="text-danger">:message</label>') : '') !!}
                                        <div class="form-control-feedback">
                                            <i class="icon-user-lock text-muted"></i>
                                        </div>
                                        
                                    </div>
                                    {{-- <div class="text-right">
                                    <a href="javascript:;" class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5" id="kt_login_forgot">Forgot Password ?</a>
                                    </div> --}}
                                    <div class="form-group d-flex flex-wrap justify-content-between align-items-center __web-inspector-hide-shortcut__">
                                        <div class="checkbox-inline"></div>
                                            <div class="text-right">
                                                <a href="javascript:;" class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5" id="kt_login_forgot">Forgot Password ?</a>
                                            </div>                                          
                                    </div>
                                    
                                    <!--end::Form group-->
                                    <!--begin::Action-->
                                    <div class="text-center pt-2">
                                        {!! Form::submit("Sign In", ['name' => 'btnsave','class' => 'btn btn-dark font-weight-bolder font-size-h6 px-8 py-4 my-3']) !!}
                                        
                                    </div>
                                    <!--end::Action-->
                                {!! Form::close() !!}
                                <!--end::Form-->
                            </div>
                            <!--end::Signin-->
                            <!--begin::Signup-->
                            {{-- <div class="login-form login-signup pt-11">
                                <!--begin::Form-->
                                <form class="form" novalidate="novalidate" id="kt_login_signup_form">
                                    <!--begin::Title-->
                                    <div class="text-center pb-8">
                                        <h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Sign Up</h2>
                                        <p class="text-muted font-weight-bold font-size-h4">Enter your details to create your account</p>
                                    </div>
                                    <!--end::Title-->
                                    <!--begin::Form group-->
                                    <div class="form-group">
                                        <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="text" placeholder="Fullname" name="fullname" autocomplete="off" />
                                    </div>
                                    <!--end::Form group-->
                                    <!--begin::Form group-->
                                    <div class="form-group">
                                        <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="email" placeholder="Email" name="email" autocomplete="off" />
                                    </div>
                                    <!--end::Form group-->
                                    <!--begin::Form group-->
                                    <div class="form-group">
                                        <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="password" placeholder="Password" name="password" autocomplete="off" />
                                    </div>
                                    <!--end::Form group-->
                                    <!--begin::Form group-->
                                    <div class="form-group">
                                        <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="password" placeholder="Confirm password" name="cpassword" autocomplete="off" />
                                    </div>
                                    <!--end::Form group-->
                                    <!--begin::Form group-->
                                    <div class="form-group">
                                        <label class="checkbox mb-0">
                                        <input type="checkbox" name="agree" />I Agree the
                                        <a href="#">terms and conditions</a>.
                                        <span></span></label>
                                    </div>
                                    <!--end::Form group-->
                                    <!--begin::Form group-->
                                    <div class="form-group d-flex flex-wrap flex-center pb-lg-0 pb-3">
                                        <button type="button" id="kt_login_signup_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">Submit</button>
                                        <button type="button" id="kt_login_signup_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">Cancel</button>
                                    </div>
                                    <!--end::Form group-->
                                </form>
                                <!--end::Form-->
                            </div> --}}
                            <!--end::Signup-->
                            <!--begin::Forgot-->
                            <div class="login-form login-forgot pt-11">
                                <!--begin::Form-->
                                {{-- <form class="form" novalidate="novalidate" id="kt_login_forgot_form"> --}}
                                    {!! Form::open(array('route' => 'auth.password.request.attempt','role'=>"form",'id'=>'kt_login_forgot_form','class'=>'form')) !!}
                                    <!--begin::Title-->
                                    <div class="text-center pb-8">
                                        <h3>Forgotten Password ?</h3>
                                        <p class="text-muted font-weight-bold font-size-h4">Enter your email to reset your password</p>
                                    </div>
                                    <!--end::Title-->
                                    <!--begin::Form group-->
                                    <div class="form-group">
                                        {!! Form::email('email', null, ['class' => 'form-control form-control-solid h-auto py-4 px-8 rounded-lg font-size-h6 required email','autocomplete' => 'off']) !!}
                                    </div>
                                    <!--end::Form group-->
                                    <!--begin::Form group-->
                                    <div class="form-group d-flex flex-wrap flex-center pb-lg-0 pb-3">
                                        {!! Form::submit("Submit", ['name' => 'btnsubmit','class' => 'btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4','id' => 'kt_login_forgot_submit']) !!}                                       
                                        <button type="button" id="kt_login_forgot_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">Cancel</button>
                                    </div>
                                    <!--end::Form group-->
                                {!! Form::close() !!}
                                <!--end::Form-->
                            </div>
                            <!--end::Forgot-->
                        </div>
                        <!--end::Aside body-->
                        
                    </div>
                    <!--end: Aside Container-->
                </div>
                <!--begin::Aside-->
                <!--begin::Content-->
                {{-- <div class="content order-1 order-lg-2 d-flex flex-column w-100 pb-0" style="background-color: #B1DCED;">
                    <!--begin::Title-->
                    <div class="d-flex flex-column justify-content-center text-center pt-lg-25 pt-md-5 pt-sm-5 px-lg-0 pt-5 px-7">
                        <h3 class="display4 font-weight-bolder my-7 text-dark" style="color: #986923;">{{ $project_title ?? '' }}</h3>
                        <p class="font-weight-bolder font-size-h2-md font-size-lg text-dark opacity-70">{{ $tag_line ?? '' }}</p>
                    </div>
                    <!--end::Title-->
                    <!--begin::Image-->
                    <div class="d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center" style="background-image: url('{{ asset('/media/logos/login/login.png') }}')"></div>
                    <!--end::Image-->
                </div> --}}
                <!--end::Content-->
            </div>
            <!--end::Login-->
        </div>
        <!--end::Main-->
        <script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
        <!--begin::Global Config(global config for global JS scripts)-->
        <script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#8950FC", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#E1E9FF", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };</script>
        <!--end::Global Config-->
        <!--begin::Global Theme Bundle(used by all pages)-->
        <script src="{{asset('/plugins/global/plugins.bundle.js')}}"></script>
        <script src="{{asset('/plugins/custom/prismjs/prismjs.bundle.js')}}"></script>
        <script src="{{asset('/js/scripts.bundle.js')}}"></script>
        <script src="{{ asset('/js/jquery.validate.min.js') }}"></script>
        <!--end::Global Theme Bundle-->
        <!--begin::Page Scripts(used by this page)-->
        <script src="{{asset('plugins/custom/login/login-general.js')}}"></script> 
        <!--end::Page Scripts-->
    </body>
    <!--end::Body-->
</html>