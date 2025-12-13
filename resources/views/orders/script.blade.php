<script type="text/javascript">
    var customerID = 0;
    $(document).ready(function() {
        initValidation();
        $('.jsCustomer').select2({
            allowClear: true
        });

        $('.jsCustomerAddress').select2({
            allowClear: true
        });


        var id = $('#id').val();
        if(id > 0){
            var customer_id = $('#customer_id').val();
        }else{
            var customer_id = null;
        }

    });

    var initValidation = function() {

        $('#orderForm').validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {},
            messages: {},
            errorPlacement: function(error, element) {
                error.appendTo(element.parent()).addClass('text-danger');
            },
            submitHandler: function(e) {
                var isError = false;

                if (isError) {
                    $('.saveBtn').removeClass('spinner spinner-white spinner-left').prop('disabled',
                        false);
                    $('.saveExitBtn').removeClass('spinner spinner-white spinner-left').prop('disabled',
                        false);
                    return false;
                } else {
                    $('form *').prop('disabled', false);
                    $('.saveBtn').addClass('spinner spinner-white spinner-left').prop('disabled', true);
                    $('.saveExitBtn').addClass('spinner spinner-white spinner-left').prop('disabled',
                        true);
                    return true;
                }
            }
        });
    }

    $(document).on('change', '.jsCustomer', function(e) {
        var customer_id = $(this).val();

        if (customer_id !== "") {
            $.ajax({
                type: "GET",
                url: "{{ route('orders.getCustomerAddress') }}",
                data: {
                    'customer_id': customer_id,
                },
            }).always(function() {

            }).done(function(response) {

                var options = '';
                var destId = $("#customerAddressValue").val();


                if (response.customerAddressData.length > 0) {
                    $.each(response.customerAddressData, function(index, val) {
                        var selected = '';
                        if (destId == val.cus_add_id) {
                            selected = 'selected';
                        }
                        if ((val.address_line1 != null) || (val.address_line2 != null) || (val
                                .city != null) || (val.pincode != null) || (val.state !=
                                null) || (val.country != null)) {
                            var addrValue = val.address_line1 + ', ' + val.address_line2 +
                                ', ' + val.pincode + ',- ' + val.state + ', ' +
                                val.country;
                            options += '<optgroup label="' + val.address_type + ' (' + val
                                .is_default + ')">';
                            options += '<option ' + selected + ' value = ' + val.cus_add_id + '>' +
                                addrValue + '</option>';
                            options += '</optgroup>';
                        }
                    });
                    $('#customer_adress_id').html("<option value = ''>Select Customer Address</option>")
                        .append(options);

                    if (destId > 0) {
                        $('#customer_adress_id').val(destId).change();
                    }

                } else {
                    $('#customer_adress_id').html('');
                }
            });
        } else {
            $('#shipping_account_id').html('');
        }
        
    });


    $(document).on('change','#car_brand', function() {
            var carBrandId = $(this).val();

            if (carBrandId) {
                // Make AJAX request to get the Car Models
                $.ajax({
                    url: '/car-models/' + carBrandId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#car_model').empty();
                        $('#car_model').append('<option value="">Select Car Model</option>');

                        // Add the new Car Model options
                        $.each(data, function(index, model) {
                            $('#car_model').append('<option value="' + model.id + '">' + model.name + '</option>');
                        });
                    },
                    error: function() {
                        alert('Error fetching car models');
                    }
                });
            } else {
                // If no car brand is selected, clear the Car Model dropdown
                $('#car_model').empty();
                $('#car_model').append('<option value="">Select Car Model</option>');
            }
        });


    // $('.jsDate').change(function() {

    //     var date = $(this).val();
    //     // $('.jsCutDate').val(pickdate);
    //     $(".jsDoDate").attr('min', $('.jsDate').val());
    // });

</script>
