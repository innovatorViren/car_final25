"use strict";

var ValidationControls = function () {
  var _login;
    // Private functions
    var validationForm = function () {
        var checkExistUrl = $('#kt_login_signin_form').attr('data-exit-url');
        $('#kt_login_signin_form').validate({
            debug: false,            
            errorPlacement: function (error, element) {
                error.appendTo(element.parent()).addClass('text-danger');
            },
            submitHandler: function (e) {
                if(grecaptcha.getResponse() == "") {
                    $('.jsGRecaptchaError').removeClass('d-none');
                    return false;
                } else {
                    $('.jsGRecaptchaError').addClass('d-none');
                    $('.btn-save').attr("disabled", true);
                    return true;
                }
            }
        });
    }

    var _showForm = function(form) {
      var cls = 'login-' + form + '-on';
      var form = 'kt_login_' + form + '_form';
      _login.removeClass('login-forgot-on');
      _login.removeClass('login-signin-on');
      _login.removeClass('login-signup-on');
      _login.removeClass('login-reset-password-on');

      _login.addClass(cls);

      KTUtil.animateClass(KTUtil.getById(form), 'animate__animated animate__backInUp');
  }

    $('#kt_login_forgot').on('click', function (e) {
      e.preventDefault();
      _showForm('forgot');
    });

    $('#kt_login_forgot_cancel').on('click', function (e) {
      e.preventDefault();
      
      _showForm('signin');
  });

    var validationForgotForm = function () {
      var checkExistUrl = $('#kt_login_forgot_form').attr('data-exit-url');
      $('#kt_login_forgot_form').validate({
          debug: false,            
          errorPlacement: function (error, element) {
            error.appendTo(element.parent()).addClass('text-danger');
          },
          submitHandler: function (e) {
            $('.jsForgotBtn').attr("disabled", true);
            $('.jsForgottenEmailError').html('');
            var emailElement = $('.jsForgottenEmail');
            $.ajax({
                type:'POST',
                url:checkExistUrl,
                data:{"_token": csrfToken,email:emailElement.val()}
            }).done(function(res){
                var success = res.success;
                var message = res.message;
                if(success){
                    $('.jsUserId').val(res.user_id);
                    $('.jsReminderId').val(res.reminder_id);
                    $('.jsCode').val(res.code);
                    startTimer();
                    _showForm('reset-password');
                }else{                    
                    $('.jsForgottenEmailError').html(message);
                }
                $('.jsForgotBtn').attr("disabled", false);
            }).fail(function(res){
                var errorMsg = 'something went wrong please try again';
                if(res.responseJSON && res.responseJSON.message){
                    errorMsg = res.responseJSON.message;
                }
                $('.jsForgotBtn').attr("disabled", false);
                $('.jsForgottenEmailError').show();
                $('.jsForgottenEmailError').html(errorMsg);
            });            
            return false;
          }
      });
  }

	return {
        // public functions
        init: function () {
           _login = $('#kt_login');
            validationForm();
            validationForgotForm();
            
        }
    };

   
}();


// Class Definition
/* var KTLogin = function() {
    var _login;

    var _showForm = function(form) {
        var cls = 'login-' + form + '-on';
        var form = 'kt_login_' + form + '_form';

        _login.removeClass('login-forgot-on');
        _login.removeClass('login-signin-on');
        _login.removeClass('login-signup-on');

        _login.addClass(cls);

        KTUtil.animateClass(KTUtil.getById(form), 'animate__animated animate__backInUp');
    }

    var _handleSignInForm = function() {
        var validation;

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
			KTUtil.getById('kt_login_signin_form'),
			{
				fields: {
					username: {
						validators: {
							notEmpty: {
								message: 'Username is required'
							}
						}
					},
					password: {
						validators: {
							notEmpty: {
								message: 'Password is required'
							}
						}
					}
				},
				plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    //defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
					bootstrap: new FormValidation.plugins.Bootstrap()
				}
			}
		);

        $('#kt_login_signin_submit').on('click', function (e) {
            e.preventDefault();

            validation.validate().then(function(status) {
		        if (status == 'Valid') {
                    swal.fire({
		                text: "All is cool! Now you submit this form",
		                icon: "success",
		                buttonsStyling: false,
		                confirmButtonText: "Ok, got it!",
                        customClass: {
    						confirmButton: "btn font-weight-bold btn-light-primary"
    					}
		            }).then(function() {
						KTUtil.scrollTop();
					});
				} else {
					swal.fire({
		                text: "Sorry, looks like there are some errors detected, please try again.",
		                icon: "error",
		                buttonsStyling: false,
		                confirmButtonText: "Ok, got it!",
                        customClass: {
    						confirmButton: "btn font-weight-bold btn-light-primary"
    					}
		            }).then(function() {
						KTUtil.scrollTop();
					});
				}
		    });
        });

        // Handle forgot button
        

        // Handle signup
        $('#kt_login_signup').on('click', function (e) {
            e.preventDefault();
            _showForm('signup');
        });
    }

    var _handleSignUpForm = function(e) {
        var validation;
        var form = KTUtil.getById('kt_login_signup_form');

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
			form,
			{
				fields: {
					fullname: {
						validators: {
							notEmpty: {
								message: 'Username is required'
							}
						}
					},
					email: {
                        validators: {
							notEmpty: {
								message: 'Email address is required'
							},
                            emailAddress: {
								message: 'The value is not a valid email address'
							}
						}
					},
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'The password is required'
                            }
                        }
                    },
                    cpassword: {
                        validators: {
                            notEmpty: {
                                message: 'The password confirmation is required'
                            },
                            identical: {
                                compare: function() {
                                    return form.querySelector('[name="password"]').value;
                                },
                                message: 'The password and its confirm are not the same'
                            }
                        }
                    },
                    agree: {
                        validators: {
                            notEmpty: {
                                message: 'You must accept the terms and conditions'
                            }
                        }
                    },
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					bootstrap: new FormValidation.plugins.Bootstrap()
				}
			}
		);

        $('#kt_login_signup_submit').on('click', function (e) {
            e.preventDefault();

            validation.validate().then(function(status) {
		        if (status == 'Valid') {
                    swal.fire({
		                text: "All is cool! Now you submit this form",
		                icon: "success",
		                buttonsStyling: false,
		                confirmButtonText: "Ok, got it!",
                        customClass: {
    						confirmButton: "btn font-weight-bold btn-light-primary"
    					}
		            }).then(function() {
						KTUtil.scrollTop();
					});
				} else {
					swal.fire({
		                text: "Sorry, looks like there are some errors detected, please try again.",
		                icon: "error",
		                buttonsStyling: false,
		                confirmButtonText: "Ok, got it!",
                        customClass: {
    						confirmButton: "btn font-weight-bold btn-light-primary"
    					}
		            }).then(function() {
						KTUtil.scrollTop();
					});
				}
		    });
        });

        // Handle cancel button
        $('#kt_login_signup_cancel').on('click', function (e) {
            e.preventDefault();

            _showForm('signin');
        });
    }

    var _handleForgotForm = function(e) {
        var validation;

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
			KTUtil.getById('kt_login_forgot_form'),
			{
				fields: {
					email: {
						validators: {
							notEmpty: {
								message: 'Email address is required'
							},
                            emailAddress: {
								message: 'The value is not a valid email address'
							}
						}
					}
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					bootstrap: new FormValidation.plugins.Bootstrap()
				}
			}
		);

        // Handle submit button
        $('#kt_login_forgot_submit').on('click', function (e) {
            e.preventDefault();

            validation.validate().then(function(status) {
		        if (status == 'Valid') {
                    // Submit form
                    KTUtil.scrollTop();
				} else {
					swal.fire({
		                text: "Sorry, looks like there are some errors detected, please try again.",
		                icon: "error",
		                buttonsStyling: false,
		                confirmButtonText: "Ok, got it!",
                        customClass: {
    						confirmButton: "btn font-weight-bold btn-light-primary"
    					}
		            }).then(function() {
						KTUtil.scrollTop();
					});
				}
		    });
        });

        // Handle cancel button
        $('#kt_login_forgot_cancel').on('click', function (e) {
            e.preventDefault();

            _showForm('signin');
        });
    }

    // Public Functions
    return {
        // public functions
        init: function() {
            _login = $('#kt_login');

            _handleSignInForm();
            _handleSignUpForm();
            _handleForgotForm();
        }
    };
}(); */

// Class Initialization
/* jQuery(document).ready(function() {
    KTLogin.init();
}); */
jQuery(document).ready(function () {
    ValidationControls.init();
    initValidation();
});
const otpInputs = $('.jsOtpInput input');
const timerDisplay = $('.jsTimer');
const resendButton = $('.jsResendCode');
let totalTime = 900; // 10 minutes in seconds
let timeLeft = totalTime; // 10 minutes in seconds
let timerId;
var initValidation = function () {
    $('#kt_login_reset_password_form').validate({
        debug: false,
        ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
        rules: {
          rest_password: {
            required: true,
            pwcheck: true,
            minlength: 8
          },
          rest_confirm_password: {
            required: true,
            equalTo: '#rest_password'
          }
        },
        messages: {
          rest_password: {
            pwcheck: 'New Password must be minimum 8 characters. New Password must contain at least 1 lowercase, 1 Uppercase, 1 numeric and 1 special character.',
            minlength: "Please enter atleast 8 digit."
          },
          rest_confirm_password: {
            minlength: "Confirm New Password must be at least 8 characters long.",
            equalTo: "Confirm New Password does not match to password."
          }
        },            
        errorPlacement: function (error, element) {
          if (element.hasClass('jsOtp')) {
            element.addClass('border-danger border-2');
          }else{
            error.appendTo(element.parent()).addClass('text-danger');
          }
        },
        submitHandler: function (e) {
          return false;
        }
    });
    $.validator.addMethod("pwcheck", function (value) {
        return /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/.test(value) // consists of only these
    });
};
function startTimer() {
    timerId = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(timerId);
            $(timerDisplay).html("Code expired");
            // $(resendButton).attr('disabled',false);
            $('.jsOtp').each(function(e){
              $(this).attr('disabled',true);
            });
        } else {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            $(timerDisplay).html(`Time remaining: ${minutes}:${seconds.toString().padStart(2, '0')}`);
            timeLeft--;
        }
    }, 1000);
}

function resendOTP() {
  var userId = $('.jsUserId').val();
  timeLeft = totalTime;
  $('.jsOtp').each(function(e){
    $(this).val('').attr('disabled',false);
  });
  $('.jsOtpErrorMsg').html('');
  // $(resendButton).attr('disabled',true);
  $('.jsFocus1').focus();
  clearInterval(timerId);
  startTimer();
  var _ths = $('.jsResendCode');
  $.ajax({
    url:_ths.attr('data-url'),
    method:'POST',
    data:{"_token": csrfToken,'user_id':userId},
  }).done(function(res){
    var success = res.success;
    var message = res.message;
    if(success){
      toastr.success(res.message, "Success");    
    }else{
      toastr.error('something went wrong try again later', "Error");
    }
  }).fail(function(res){
    toastr.error('something went wrong try again later', "Error");
  });
}
$(document).on('input', '.jsOtp', function(e){
  var index =parseInt($(this).attr('data-index'));
  if (e.target.value.length > 1) {
    e.target.value = e.target.value.slice(0, 1);
  }
  if (e.target.value.length === 1) {
    if (index < otpInputs.length) {
      var nextindex = index + 1;
      $('.jsFocus'+nextindex+'').focus();
    }
  }
});
$(document).on('keydown', '.jsOtp', function(e){
  var index =parseInt($(this).attr('data-index'));
  if (e.key === 'Backspace' && !$(this).val()) {
      if (index > 0) {
        var previndex = index - 1;
        $('.jsFocus'+previndex+'').focus();
      }
  }
  if (e.key === 'e') {
      e.preventDefault();
  }
});
$(document).on('click', '.jsVerifyButton', function(e){
  $('.jsOtpErrorMsg').html('');
    if($('#kt_login_reset_password_form').valid()){
      var otp = Array.from(otpInputs).map(input => input.value).join('');
      $('.jsOtpVal').val('');
      if (otp.length === 6) {
          if (timeLeft > 0) {
            $('.jsVerifyButton').attr('disabled',true);            
            $('.jsOtpVal').val(otp);
            var userId = $('.jsUserId').val();
            var reminderId = $('.jsReminderId').val();
            var code = $('.jsCode').val();
            var ajaxUrl = $(this).attr('data-url');
            $.ajax({
              url:ajaxUrl,
              method:'POST',
              data:{
                "_token": csrfToken,
                'user_id':userId,
                'reminder_id':reminderId,
                'otp':otp,
                'code':code,
                'password':$('.jsRestPassword').val(),
              }
            }).done(function(res){
              var success = res.success;
              var message = res.message;
              if(success){
                var data = res.data;
                location.reload();
                $('.jsOtpErrorMsg').html('');
              }else{
                $('.jsVerifyButton').attr('disabled',false);
                $('.jsOtpErrorMsg').html(message); 
              }
            }).fail(function(res){
              var errorMsg = 'something went wrong please try again';
              if(res.responseJSON && res.responseJSON.message){
                errorMsg = res.responseJSON.message;
              }
              $('.jsOtpErrorMsg').html(errorMsg);
              $('.jsVerifyButton').attr('disabled',false);
            });
            return false;
          } else {
            $('.jsOtpErrorMsg').html('Your OTP has been expired');
            $('.jsVerifyButton').attr('disabled',false);
            return false;
          }
      }
    }
});
$(document).on('click', '.jsPasswordShoHide', function(e){
  $(this).toggleClass("fa-eye fa-eye-slash");
  var _ths = $('.jsLoginPassword');
  if (_ths.attr("type") == "password") {
    _ths.attr("type", "text");
  } else {
    _ths.attr("type", "password");
  }
});
$(document).on('click', '.jsForgotBtn', function(e){
  $('#kt_login_forgot_form').valid()
});