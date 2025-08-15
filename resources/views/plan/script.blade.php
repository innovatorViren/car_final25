<script type="text/javascript">
    $(document).ready(function() {
        initValidation();
    });

    var initValidation = function() {
        $('#planForm').validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {
                name: {
                    required: true,
                }
            },
            messages: {
                /*name: {
                    required: "The name field is required.",
                },*/
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
    };

    var initValidation = function() {
            $('#planForm').validate({
                debug: false,
                ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
                rules: {},
                messages: {

                },
                errorPlacement: function(error, element) {
                    error.appendTo(element.parent()).addClass('text-danger');
                },
                submitHandler: function(e) {
                    $(".shipping_charge_per").prop("disabled", true);
                    $('.jsBtnLoader').addClass('spinner spinner-white spinner-left');
                    $('.jsBtnLoader').prop('disabled', true);
                    return true;
                }
            });

            $('.saveBtn').on('click',function(){
                $('#planForm').find('.frombtn').val("0");
            })
            $('.saveExitBtn').on('click',function(){
                $('#planForm').find('.frombtn').val("1");
            })

        };
</script>
