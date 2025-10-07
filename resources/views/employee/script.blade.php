<script type="text/javascript">
    $(document).ready(function() {
        initValidation();
        
        if ($('#id').val() != null) {
            $('#birth_date').trigger('change');
        }
    });

    var initValidation = function() {

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

        $('#state').select2({
            allowClear: true
        });
        $('#city').select2({
            allowClear: true
        });
        $('#role').select2({
            allowClear: true
        });
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
                $('#' + v.tab).toggleClass('not-valid-tab', notValidField);
            });
        }
    }
</script>
{!! ajax_fill_dropdown('state', 'city', route('get-cities')) !!}
