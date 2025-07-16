@push('scripts')
    <script type="text/javascript">
        
        var initValidation = function() {
            $('#settingForm').validate({
                debug: false,
                ignore: '.select2-search__field,:hidden:not("textarea,.files,select,input")',
                rules: {


                },
                messages: {
                    /*name: {
                        required: "The name field is required.",
                    },*/
                },


                errorPlacement: function(error, element) {
                    if (element.parent().hasClass('input-group')) {
                        error.appendTo(element.parent().parent()).addClass('text-danger');
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
        };

        $('#country').select2();
        $('#state').select2();
        $('#city').select2();
    </script>

    {!! ajax_fill_dropdown('country_id', 'state_id', route('get-states')) !!}
    {!! ajax_fill_dropdown('state_id', 'city_id', route('get-cities')) !!}
@endpush
