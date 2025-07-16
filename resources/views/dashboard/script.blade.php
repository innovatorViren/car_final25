<script type="text/javascript">
    $(document).on('input', '.jsLoginFilter', function(){
        $('#dataTableBuilder').DataTable().ajax.reload();
    });
    $(document).on('click', '.jsLogout', function(){
        var id = $(this).data('id');
        var tableId = $(this).data('table');

        message.fire({
            title: 'Are you sure',
            text: "You want to logout ?",
            type: 'warning',
            customClass: {
                confirmButton: 'btn btn-success shadow-sm mr-2',
                cancelButton: 'btn btn-danger shadow-sm'
            },
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, logout it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.value) {
                
                $.ajax({
                    url:'@php echo route("userLogout") @endphp',
                    type: 'POST',
                    data:{
                        id:id,
                    },
                    success:function(res){
                        var response = JSON.parse(res);
                        toastr.success(response.message, "Success");
                        $('#'+tableId).DataTable().ajax.reload();
                    }
                });
            }
        });
        
    });
    
</script>