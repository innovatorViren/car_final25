@include('partials._loginheader.login-header')
<!--begin::Body-->
<body class="header-fixed header-mobile-fixed subheader-enabled sidebar-enabled page-loading">
    <div class="d-flex flex-column flex-root">
        <div class="login login-4 d-flex flex-row-fluid login-reset-password-on">
            <div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat" style="background-image: url('{{ asset('media/bg/bg-3.jpg') }}')">
                <div class="login-form text-center p-7 position-relative overflow-hidden">
                    <div class="d-flex flex-center mb-10">
                        <a href="#" class="text-center pt-2">
                            <img src="{{asset($logo ?? '')}}" class="max-h-75px" alt="" />
                        </a>
                    </div>
                    <div class="d-flex flex-column-fluid flex-column flex-center">
                        <!--begin::reset password-->
                            <div class="login-form login-reset-password pt-11">
                                <!--begin::Form-->
                                    {!! Form::open(array('route' => 'profile.update-password','role'=>"form",'id'=>'profile-update-password','class'=>'form')) !!}
                                    {!! Form::hidden('is_forcefully','Yes') !!}
                                    {!! Form::hidden('user_id',null,['class'=>'jsUserId']) !!}
                                    <!--begin::Title-->
                                    <div class="form-group mb-5 fv-plugins-icon-container text-left">
                                        <div class="justify-content-between mt-n5">
                                            {!! Form::label('password','Password',['class' => 'pt-5'])!!}<i class="text-danger">*</i>
                                        </div>
                                        {!! Form::password('password', ['class' => 'form-control form-control-solid h-auto py-4 px-8 required jsPassword','autocomplete' => 'off']) !!}
                                        <label id="password-error" class="text-left mt-3 jsPasswordErrorMsg text-danger" for="password">
                                            @if ($errors->has('password'))
                                                {{ $errors->first('password') }}
                                            @endif    
                                        </label>                                        
                                    </div>
                                    <div class="form-group mb-5 fv-plugins-icon-container text-left">
                                        <div class="justify-content-between mt-n5">
                                            {!! Form::label('password_confirmation','Confirm Password',['class' => 'pt-5'])!!}<i class="text-danger">*</i>
                                        </div>
                                        {!! Form::password('password_confirmation', ['class' => 'form-control form-control-solid h-auto py-4 px-8 required jsConfirmPassword','autocomplete' => 'off']) !!}

                                        <label id="password_confirmation-error" class="text-left mt-3 text-danger" for="password_confirmation">
                                            @if ($errors->has('password_confirmation'))
                                                {{ $errors->first('password_confirmation') }}
                                            @endif
                                        </label>
                                    </div>
                                    <div class="form-group d-flex flex-wrap flex-center pb-lg-0 pb-3">
                                        <button type="submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4 jsBtnUpdate">{{ __('common.update') }}</button>
                                    </div>
                                {!! Form::close() !!}
                                
                                <!--end::Form-->
                            </div>
                            <!--end::reset password-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{asset('/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{ asset('/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        initValidation();
        var errMessage = "{{(Session::has('warning')) ? session('warning') : ''}}";
        if(errMessage !=''){
            warningMessage(errMessage);
        }
    });
    var initValidation = function(){
        $('#profile-update-password').validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {
                password: {
                    required: true,
                    pwcheck: true,
                    minlength: 8
                },
                password_confirmation: {
                    required: true,
                    equalTo: '#password'
                }
            },
            messages: {
                password: {
                    pwcheck: 'New Password must be minimum 8 characters. New Password must contain at least 1 lowercase, 1 Uppercase, 1 numeric and 1 special character.',
                    minlength: "Please enter atleast 8 digit."
                },
                password_confirmation: {
                    minlength: "Confirm New Password must be at least 8 characters long.",
                    equalTo: "Confirm New Password does not match to password."

                }
            },
            errorPlacement: function (error, element) {
                error.appendTo(element.parent()).addClass('text-danger');
            },
            submitHandler: function (e) {
                return true;
            }
        });
    };
    function warningMessage(err_message) {
        Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success shadow-sm mr-2',
                cancelButton: 'btn btn-danger shadow-sm'
            },
            buttonsStyling: false,
        }).fire({
            title: '',
            html: err_message,
            type: 'warning',
            customClass: {
                confirmButton: 'btn btn-success shadow-sm mr-2',
            },
            buttonsStyling: false,
            showCancelButton: false,
            confirmButtonText: 'OK',
        }).then((result) => {
            
        });
    }
</script>
</html>
