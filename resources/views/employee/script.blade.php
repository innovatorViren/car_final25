<script type="text/javascript">
    $(document).ready(function() {
        initValidation();
        $('#designation_id').trigger('change');
        if ($('#id').val() != null) {
            $('#department_id').trigger('change');
            $('#birth_date').trigger('change');
        }
        var parentId = '{{ $parentId ?? '' }}';
        var designation_id = "{{ old('designation_id') ?? '' }}";

        if (parentId > 0) {
            $('#birth_date').trigger('change');
        }

        if (parentId > 0 && designation_id > 0) {
            $('#department_id').trigger('change');
        }
        $('.jsJoinDate').attr('max', defaultToDate)
    });

    var initValidation = function() {
        $("#leftForm").validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select,input")',
            errorPlacement: function(error, element) {
                if (element.is(":radio")) {
                    error.appendTo(element.closest('.form-group')).addClass('text-danger');
                } else {
                    error.appendTo(element.parent()).addClass('text-danger');
                }
            },
            submitHandler: function(e) {
                $('#btn_loader').addClass('spinner spinner-white spinner-left');
                $('#btn_loader').prop('disabled', true);
                return true;
            }
        });

        $(document).on('change', '#designation_id, #department_id', function() {
            changeCustomersVisibility();
        });

        $('#employeeForm').validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select,input")',
            rules: {},
            messages: {

            },
            errorPlacement: function(error, element) {
                error.appendTo(element.parent()).addClass('text-danger');
            },
            submitHandler: function(e) {
                $('#btn_loader').addClass('spinner spinner-white spinner-left');
                $('#btn_loader').prop('disabled', true);
                return true;
            }
        });

        $('#marital_status').select2({
            allowClear: true
        });
        $('#branch_list_id').select2({
            allowClear: true
        });
        $('#present_state').select2({
            allowClear: true
        });
        $('#present_city').select2({
            allowClear: true
        });
        $('#permanent_city').select2({
            allowClear: true
        });
        $('#role').select2({
            allowClear: true
        });
        $('#blood_group').select2({
            allowClear: true
        });
        $('#emp_customers').select2({
            allowClear: true
        });

        $('#permanent_state,#designation_id,#department_id,#wage_type,#working_hour,#appointed_by,#designation_appointee,#employee_type,#machine_in,#machine_out,#shift,#process_id,#status_id, #chart_of_account_id,#cylinder_process_id')
            .select2({
                allowClear: true
            });

        $('#sales_emp_id').select2({
            allowClear: true
        });

        function changeCustomersVisibility() {
            var department_name = $('#department_id option:selected').text();
            var designation_name = $('#designation_id option:selected').text();
            if (department_name == 'Sales') {
                // $('#emp_customers').removeClass('d-none');
                // $('#emp_customers').addClass('required');
                // $('.emp_customers_div').removeClass('d-none');
            } else {
                $('#emp_customers').addClass('d-none');
                $('#emp_customers').removeClass('required');
                $('.emp_customers_div').addClass('d-none');
            }
        }

        // login form display
        // $("#create_user").on("click", function () {
        //     if ($(this).is(":checked")) {
        //     var returnVal = this.value;
        //     if (returnVal == '1') {
        //         $('#add_user_role').removeClass('d-none');
        //     } else {
        //         $('#add_user_role').addClass('d-none');
        //     }
        //     }
        // });

        // age caclulation
        jQuery("#birth_date").change(function() {
            var birth_date = jQuery(this).val().split("-");
            var dob = new Date(birth_date[0], birth_date[1] - 1, birth_date[2])
            var today = new Date();
            var todayTime = today.getTime();
            var dobTime = dob.getTime();
            var dayDiff = Math.ceil(today.getTime() - dob.getTime()) / (1000 * 60 * 60 * 24 * 365);
            var age = parseInt(dayDiff);
            jQuery("#age").val(age);
        });

        // same as present address
        $("#same_as_present").on("click", function() {

            var button = $(this); 
            button.prop('disabled', true);
            setTimeout(function() {
                button.prop('disabled', false);
            }, 3000);
            
            if ($(this).is(":checked")) {
                var returnVal = this.value;

                if (returnVal == '1') {
                    var present_address = $('#present_address').val();
                    var present_state = $('#present_state option:selected').val();
                    var present_city = $('#present_city').val();
                    var present_pincode = $('#present_pincode').val();

                    $('#permanent_address').val(present_address);
                    $('#permanent_state').val(present_state).change();
                    setTimeout(function() {
                        $('#permanent_city').val(present_city).change();
                    }, 3000);
                    $('#permanent_pincode').val(present_pincode);
                }
            } else {
                $('#permanent_address').val('');
                $('#permanent_state').val('').change();
                $('#permanent_city').val('');
                $('#permanent_pincode').val('');
            }
        });

        $("#appointed_by").change(function() {

            var appointedID = $(this).val();

            $.ajax({
                url: "{{ route('getAppointee') }}",
                data: {
                    appointedID: appointedID
                }

            }).done(function(response) {

                var designation_of_appointee = response;
                $('#designation_of_appointee').val(designation_of_appointee);
                console.log(response);
            });
        });

        $("#abc li").click(function() {
            alert(this.id); // id of clicked li by directly accessing DOMElement property
            alert($(this).attr('id')); // jQuery's .attr() method, same but more verbose
            alert($(this).html()); // gets innerHTML of clicked li
            alert($(this).text()); // gets text contents of clicked li
        });

        $(document).ready(function() {
            checkNavigation();

            let tabs = $('.nextPrev li');
            $('#prevtab').on('click', function() {
                console.log(tabs.find('.active').parent())

                tabs.find('.active').parent().prev('li').find('a[data-toggle="tab"]').tab('show');

                var currentSlide = $('.active-slide');
                var prevSlide = currentSlide.prev('.slide');

                if (prevSlide.length === 0) {
                    prevSlide = $('.slide').last();
                }

                currentSlide.removeClass('active-slide');
                prevSlide.addClass('active-slide');

                checkNavigation();
            });
            $('#nexttab').on('click', function() {
                console.log(tabs.find('.active').parent());

                tabs.find('.active').parent().next('li').find('a[data-toggle="tab"]').tab('show');

                var currentSlide = $('.active-slide');
                var nextSlide = currentSlide.next('.slide');

                //if nextslide is last slide, go back to the first
                if (nextSlide.length === 0) {
                    nextSlide = $('.slide').first();
                }

                currentSlide.removeClass('active-slide');
                nextSlide.addClass('active-slide');

                checkNavigation();
            });
        })

        function checkNavigation() {

            if ($('.active-slide').hasClass('first')) {
                $('#prevtab').hide();
                $('#nexttab').show();
            } else if ($('.active-slide').hasClass('last')) {
                $('#nexttab').hide();
                $('#prevtab').show();
            } else {
                $('#prevtab').show();
                $('#nexttab').show();
            }

        }


        $(document).on('change', '#department_id', function(e) {
            if ($(this).find('option:selected').text() == 'Production') {
                $(".processDisplay").removeClass('d-none');
                $("#process_id").addClass('required');
                $(".cmProcessDisplay").addClass('d-none');
                $("#cylinder_process_id").removeClass('required');
                $("#cylinder_process_id").val('');
            } else if ($(this).find('option:selected').text() == 'CM Production') {
                $(".cmProcessDisplay").removeClass('d-none');
                $("#cylinder_process_id").addClass('required');
                $(".processDisplay").addClass('d-none');
                $("#process_id").removeClass('required');
                $("#process_id").val('');
            } else {
                $(".processDisplay").addClass('d-none');
                $("#process_id").removeClass('required');
                $("#process_id").val('');
                $(".cmProcessDisplay").addClass('d-none');
                $("#cylinder_process_id").removeClass('required');
                $("#cylinder_process_id").val('');
            }

            e.preventDefault();

            var department_id = $("#department_id option:selected").val();

            var ajaxUrl = $(this).attr('data-ajaxurl');
            var s = $('#designation_id');
            addLoadSpiner(s);
            $.ajax({
                type: "GET",
                url: ajaxUrl,
                data: {
                    'department_id': department_id
                },
            }).always(function() {

            }).done(function(response) {

                var options = '';

                $.each(response, function(index, catval) {

                    if (typeof $('#designationid').val() !== "undefined" && $(
                            '#designationid').val() == catval.id) {
                        var selected = 'selected';
                    } else {
                        var selected = '';
                    }

                    options += '<option ' + selected + ' value = ' + catval.id + '>' +
                        catval.name + '</option>';
                });

                $('#designation_id').html("<option value = ''>Select Designation</option>").append(
                    options);
                hideLoadSpinner(s);
                changeCustomersVisibility();
            });
        });

        function favicon(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#emp_photo_preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#photo").change(function() {
            favicon(this);
        });

        function passportImg(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#passport_img_preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#passport_img").change(function() {
            passportImg(this);
        });

        function pancardImg(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#pancard_img_preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#pancard_img").change(function() {
            pancardImg(this);
        });

        function drivinglicenceImg(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#drivinglicence_img_preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#drivinglicence_img").change(function() {
            drivinglicenceImg(this);
        });

        function aadharcardImg(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#aadharcard_img_preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#aadharcard_img").change(function() {
            aadharcardImg(this);
        });

        $(document).on('click', '#btn_loader', function() {
            if ($("#leftForm").valid()) {
                var el = $(this);
                var formData = $('#leftForm').serializeArray();
                var url = $('#leftForm').attr('action');

                $.ajax({
                    type: "POST",
                    url: url,
                    cache: false,
                    data: formData,
                }).always(function(respons) {

                }).done(function(respons) {
                    if (respons.success) {
                        //toastr.success(respons.message, "Success");
                        location.reload();
                    } else {
                        toastr.error(respons.message, "Error");
                    }
                }).fail(function(respons) {
                    var res = respons.responseJSON;
                    var msg = 'something went wrong please try again !';

                    if (res.errormessage) {
                        toastr.warning(res.errormessage, "Warning");
                    }
                    toastr.error(msg, "Error");
                });
            }
        });
    };


    $(document).on('click', '.jsShowImage', function() {
        $('#commonModalID').modal('show');
        $('.jsImg').attr('src', $(this).attr('src'));
    });


    $(document).on('click', '.jsSaveEmployee', function() {
        checkValidation();
    });

    function checkValidation() {

        var divArr = JSON.parse('@php echo isset($divArr) ?  json_encode($divArr) : ""; @endphp');
        if (divArr != '') {
            $(divArr).each(function(i, v) {

                var notValidField = false;
                $("#" + v.id + " .required").each(function() {
                    if (!$(this).valid()) {
                        notValidField = true;
                    }
                });

                $("#" + v.id + " .jsOptionRequired").each(function() {
                    if (!$(this).valid()) {
                        notValidField = true;
                    }
                });

                /*if(!notValidField && v.id == 'document_information-5' && !$('.jsOptionRequired').valid()){
                    notValidField = true;
                }*/
                $('#' + v.tab).toggleClass('not-valid-tab', notValidField);
            });
        }
    }
</script>
{!! ajax_fill_dropdown('present_state', 'present_city', route('get-cities')) !!}
{!! ajax_fill_dropdown('permanent_state', 'permanent_city', route('get-cities')) !!}
