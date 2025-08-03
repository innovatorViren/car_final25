@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            initValidation();
            checkNavigation();
            if ($('#id').val() != null) {
                $('#gst_type').trigger('change');                
            };
            let tabs = $('.nextPrev li');
            $('#prevtab').on('click', function() {

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
        });

        $("#same_as_present").on("click", function() {
            if ($(this).is(":checked")) {
                var returnVal = this.value;

                if (returnVal == '1') {
                    var address_line1 = $('#address_line1').val();
                    var address_line2 = $('#address_line2').val();
                    var city = $('#city').val();
                    var pincode = $('#pincode').val();
                    var country_id = $('#country option:selected').val();

                    var mobile = $('#mobile').val();
                    var phone = $('#phone').val();
                    var mobile2 = $('#mobile2').val();

                    $('#factory_address_line1').val(address_line1);
                    $('#factory_address_line2').val(address_line2);
                    $('#factory_city').val(city);
                    $('#factory_pincode').val(pincode);
                    $('#factory_country').val(country_id).change();

                    $('#factory_mobile').val(mobile);
                    $('#factory_phone').val(phone);
                    $('#factory_mobile2').val(mobile2);

                }
            } else {
                $('#factory_address_line1').val('');
                $('#factory_address_line2').val('');
                $('#factory_city').val('');
                $('#factory_pincode').val('');
                $('#factory_country').val('').change();
                $('#factory_state').val('').change();
                $('#factory_mobile').val('');
                $('#factory_phone').val('');
                $('#factory_mobile2').val('');
            }
        });


        var initValidation = function() {
            $('#customersForm').validate({
                debug: false,
                ignore: '.select2-search__field,:hidden:not("textarea,.files,select,input")',

                errorPlacement: function(error, element) {

                    error.appendTo(element.parent()).addClass('text-danger');

                },
                submitHandler: function(e) {
                    $('#btn_loader').addClass('spinner spinner-white spinner-left');
                    $('#btn_loader').prop('disabled', true);
                    return true;
                }
            });
            $('#country').select2({
                allowClear: true
            });
            $('#factory_country').select2({
                allowClear: true
            });
            $('#state').select2({
                allowClear: true
            });
            $('#city').select2({
                allowClear: true
            });



           

            function aadharCard(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#aadhar_card_preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#aadharcard_img").change(function() {
                aadharCard(this);
            });
        };

        $(document).on('click', '.jsSaveCustomer', function() {
            checkValidation();
        });

        function checkValidation() {
            var divArr = JSON.parse('@php echo isset($divArr) ?  json_encode($divArr) : ""; @endphp');
            if (divArr != '') {
                $(divArr).each(function(i, v) {

                    var notValidField = false;
                    $("#" + v.id + " .required").each(function() {
                        console.log($(this).valid());
                        if (!$(this).valid()) {
                            notValidField = true;
                        }
                    });

                    $('#' + v.tab).toggleClass('not-valid-tab', notValidField);
                });
            }
        }

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
    </script>

    {!! ajax_fill_dropdown('country_id', 'state_id', route('get-states')) !!}
    {!! ajax_fill_dropdown('state_id', 'city_id', route('get-cities')) !!}
@endpush
