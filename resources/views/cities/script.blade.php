<script type="text/javascript">
    $(document).ready(function() {
        initValidation();
    });

    var initValidation = function() {
        $('#cityForm').validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {
                name: {
                    required: true,
                },
                gst_code: {
                    required: true,
                },
                input_type: {
                    required: true,
                },
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

        $('#country_id').select2({
            allowClear: true
        });

        $('#state_id').select2({
            allowClear: true
        });

    };
</script>
{!! ajax_fill_dropdown('country_id', 'state_id', route('get-states')) !!}
