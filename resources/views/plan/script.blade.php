<script type="text/javascript">
    $(document).ready(function() {
        initValidation();
        $('#car_size_id').select2({allowClear:true});
        $('#frequency').select2({allowClear:true});
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
</script>
