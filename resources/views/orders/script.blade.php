<script type="text/javascript">
    var customerID = 0;
    $(document).ready(function() {
        initValidation();
        $('.jsCustomer').select2({
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
        alert(customer_id);
        var url = "{{ route('sales-order.getPriceList') }}";
        
    });

</script>
