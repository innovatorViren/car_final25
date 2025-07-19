<script type="text/javascript">
    $(document).ready(function () {
        initValidation();
    });

    var initValidation = function () {
        $('#carBrandForm').validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {
                name: {
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
            errorPlacement: function (error, element) {
                error.appendTo(element.parent()).addClass('text-danger');
            },
            submitHandler: function (e) {
                $('#btn_loader').addClass('spinner spinner-white spinner-left');
                $('#btn_loader').prop('disabled',true);
                return true;
            }
        });
    };
    function carBrandImg(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#carbrand_img_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#brand_logo").change(function() {
        carBrandImg(this);
    });
</script>