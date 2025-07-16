<script type="text/javascript">
    $(document).ready(function() {
        initUpdateProfileValidation();
        initUpdatePasswordValidation();
        _initAside();
        _initForm();
        var errMessage = "{{(Session::has('warning')) ? session('warning') : ''}}";
        if(errMessage !=''){
            warningMessage(errMessage);
        }
    });

    var initUpdateProfileValidation = function () {
            
        $('#profile-update-form').validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {
                first_name: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true
                },
                
            },
            messages: {
            },
            errorPlacement: function (error, element) {
                error.appendTo(element.parent()).addClass('text-danger');
            },
            submitHandler: function (e) {
                return true;
            }
        });
    };

    var initUpdatePasswordValidation = function () {
        $.validator.addMethod("pwcheck", function (value) {
            return /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/.test(value) // consists of only these
        });

        $('#profile-update-password').validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {
                current_password: {
                    required: true,
                },
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
                current_password: {
                    minlength: "Your password must at least 8 digits."
                },
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

    var avatar;
    var offcanvas;

    // Private functions
    var _initAside = function () {
        // Mobile offcanvas for mobile mode
        offcanvas = new KTOffcanvas('kt_profile_aside', {
            overlay: true,
            baseClass: 'offcanvas-mobile',
            //closeBy: 'kt_user_profile_aside_close',
            toggleBy: 'kt_subheader_mobile_toggle'
        });
    }

    var _initForm = function() {
        avatar = new KTImageInput('kt_profile_avatar');
    }
    function warningMessage(err_message) {
        message.fire({
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