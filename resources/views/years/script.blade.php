
<script type="text/javascript">
    $(document).ready(function () {
        initValidation();
    });

    var initValidation = function () {
        $('#yearForm').validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {
                // name: {
                //     required: true,
                // },
               
            },
            messages: {
                /*name: {
                    required: "The name field is required.",
                },*/
            },
            errorPlacement: function (error, element) {
                if (element.parent().hasClass('input-group')) {
                    error.appendTo(element.parent().parent()).addClass('text-danger');
                } else {
                    error.appendTo(element.parent()).addClass('text-danger');
                }
            },
            submitHandler: function (e) {
                $('#btn_loader').addClass('spinner spinner-white spinner-left');
                $('#btn_loader').prop('disabled',true);
                return true;
            }
        });
    };

   
    $('#is_default').select2();

    $(document).on('click', '.change-default', function (e) {
        var el = $(this);
        var url = el.data('url');
        var table = el.data('table');
        var id = el.val();
        $('.change-default').not(this).prop('checked', false);  
        
        $.ajax({
            type: "POST",
            url: url,
            data: {
                id: id,
                is_default: el.prop("checked"),
                table: table,
            }
        }).always(function (respons) { }).done(function (respons) {

            message.fire({
                type: 'success',
                title: 'Success',
                text: respons.message
            });


        }).fail(function (respons) {
            if(respons.status == 422){
               message.fire({
                    type: 'warning',
                    title: 'Warning',
                    text: respons.responseJSON.message
                }); 
            } else {
                message.fire({
                    type: 'error',
                    title: 'Error',
                    text: 'something went wrong please try again !'
                });
            }
        });
    });

    $('#is_displayed').select2();

    // $(document).on('click', '.change-displayed', function (e) {
    //     var el = $(this);
    //     console.log(el);
    //     var url = el.data('url');
    //     var table = el.data('table');
    //     var id = el.val();
    //     $('.change-displayed').not(this).prop('checked', false);  
        
    //     $.ajax({
    //         type: "POST",
    //         url: url,
    //         data: {
    //             id: id,
    //             is_displayed: el.prop("checked"),
    //             table: table,
    //         }
    //     }).always(function (respons) { }).done(function (respons) {

    //         message.fire({
    //             type: 'success',
    //             title: 'Success',
    //             text: respons.message
    //         });


    //     }).fail(function (respons) {
    //         if(respons.status == 422){
    //            message.fire({
    //                 type: 'warning',
    //                 title: 'Warning',
    //                 text: respons.responseText
    //             }); 
    //         } else {
    //             message.fire({
    //                 type: 'error',
    //                 title: 'Error',
    //                 text: 'something went wrong please try again !'
    //             });
    //         }
    //     });
    // });
</script>