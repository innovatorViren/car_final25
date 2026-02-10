<script type="text/javascript">
    var customerID = 0;
    $(document).ready(function() {
        initValidation();
        $('.jsCustomer').select2({
            allowClear: true
        });

        $('.jsCustomerAddress').select2({allowClear: true});
        $('.jsCarBrand').select2({allowClear: true});
        $('.jsCarModel').select2({allowClear: true});
        $('.jsFrequency').select2({allowClear: true});


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
    $(document).on('change', '#start_date', function () {
        let selectedDate = $(this).val();
        let today = new Date().toISOString().split('T')[0];

        if (selectedDate === today) {
            let now = new Date();
            let hours = String(now.getHours()).padStart(2, '0');
            let minutes = String(now.getMinutes()).padStart(2, '0');
            $('#start_time').attr('min', hours + ':' + minutes);
        } else {
            $('#start_time').removeAttr('min');
        }
    });

    $('#start_time').on('change', function () {
        let startTime = $(this).val(); // HH:MM

        if (!startTime) return;

        let parts = startTime.split(':');
        let date = new Date();
        date.setHours(parts[0], parts[1], 0);
        date.setHours(date.getHours() + 1);

        let endHour = String(date.getHours()).padStart(2, '0');
        let endMinute = String(date.getMinutes()).padStart(2, '0');

        $('#end_time').val(endHour + ':' + endMinute);
    });

    function loadSlots() {
        let frequency = $('#frequency').val();
        let startDate = $('#start_date').val();
        let startTime = $('#start_time').val();
        let endTime = $('#end_time').val();

        if (!frequency || !startDate || !startTime) return;

        $.ajax({
            url: "{{ route('orders.generate.slots') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                frequency: frequency,
                start_date: startDate,
                start_time: startTime,
                end_time: endTime
            },
            success: function (response) {
                let rows = '';
                response.forEach(slot => {
                    rows += `
                        <tr>
                            <td>${slot.scheduled_date}</td>
                            <td>${slot.start_time}</td>
                            <td>${slot.end_time}</td>
                        </tr>
                    `;
                });
                $('#slotsTable tbody').html(rows);
            }
        });
    }

    $('#frequency, #start_date, #start_time').on('change', loadSlots);


</script>
