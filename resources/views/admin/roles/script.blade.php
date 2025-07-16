<script type="text/javascript">
    jQuery(document).ready(function() {
        initValidation();
        jQuery("#name").on('keyup', function() {
            var Text = jQuery(this).val();
            Text = Text.toLowerCase();
            Text = Text.replace(/[^a-zA-Z0-9]+/g, '-');
            jQuery("#slug").val(Text);
        });
    });

    var initValidation = function() {
        jQuery('#roleForm').validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: {
                name: {
                    required: true,
                },
                slug: {
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
                jQuery('#btn_loader').addClass('spinner spinner-white spinner-left');
                jQuery('#btn_loader').prop('disabled', true);
                return true;
            }
        });
    };

    //     $("#search_role").on("keyup", function() {
    //   var value = this.value.toLowerCase().trim();

    //   /*$(".cls-parent-permission h3").show().filter(function() {
    //     console.log('hii');

    //     return $(this).text().toLowerCase().trim().indexOf(value) == -1;
    //   }).hide();*/
    //   $(".cls-parent-permission h3").show().filter(function() {
    //     if(!$(this).text().toLowerCase().trim().indexOf(value) == -1){
    //         $this.closest('div.cls-parent-permission').fadeOut();
    //         // $(this).parent().parent().hide();
    //     }

    //   })
    // });

    $("#search_role").on("keyup", function() {
        var query = this.value.toLowerCase().trim();
        $('div.staff-container .card-title').each(function() {
            var $this = $(this);
            if ($this.text().toLowerCase().indexOf(query) === -1)
                $this.closest('div.staff-container').fadeOut();
            else $this.closest('div.staff-container').fadeIn();
        });
        if ($(this).val() != '') {
            setTimeout(() => {
                hideParent();
            }, 500);
        } else {
            $('.jsParent').show()
        }

    });

    function hideParent() {
        var parentData = ['jsSide', 'jsMaster', 'jsReport', 'jsPurchase', 'jsCylinder', 'jsProduction', 'jsjobwork ',
            'jsJobwork', 'jsSales', 'jsHRM', 'jsStore', 'jsAccount', 'jsWastage', 'jsSecurity', 'jsQC', 'jsAssets'
        ];
        $(parentData).each(function(index, value) {
            if (!$('.' + value).is(':visible')) {
                $('.' + value + 'Parent').hide();
            }
        });
    }
</script>
