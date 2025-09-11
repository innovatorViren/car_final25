@push('scripts')
<script type="text/javascript">
    var userID = 0;

    @if (!empty($userPermissions))
        var permissionArr = @php echo json_encode($userPermissions) @endphp;
    @else
        var permissionArr = [];
    @endif
    @if (!empty($groupPermissions))
        var groupPermissionArr = @php echo json_encode($groupPermissions) @endphp;
    @else
        var groupPermissionArr = [];
    @endif
    $(document).ready(function() {
        userID = $('#userId').val();

        initValidation();

        if (userID > 0) {
            $('.emp_type:checked').trigger('change');
        }

        $(document).on('change', '.cls-role', function() {
            var roleId = $(this).val();
            if (roleId) {
                $.ajax({
                    url: "{{route('getRolePermissions')}}",
                    type: "GET",
                    data : {role_id : roleId},
                    success: function(response) {
                        if (response.status == "success") {
                            $(".cls-treeview").kendoTreeView({
                                checkboxes: {
                                    checkChildren: true,
                                },
                                check: onCheck,
                                dataSource: response.data.rolePermissionData
                            });
                            $(".cls-treeview").find('.k-checkbox:checked').attr("disabled", "disabled");
                            onCheck();
                        }
                    }
                });
            }
        });

        if (typeof $('#userId').val() != "undefined" && $('#userId').val() != "") {
            var permissions = $.map(permissionArr, function(value, index) {
                if(value.text == 'Purchase'){
                    $(value.items).each(function(i,item){
                        
                    });
                }
                return [value];
            });
                $(".cls-treeview").kendoTreeView({
                    checkboxes: {
                        checkChildren: true
                    },
                    check: onCheck,
                    dataSource: permissions
                });
                onCheck();
        }
    });

    var initValidation = function() {
        $.validator.addMethod("pwcheck", function (value) {
            return /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/.test(value) // consists of only these
        });

        var rules = {
                password: {
                    required: true,
                    pwcheck: true,
                    minlength: 8
                },
                password_confirmation: {
                    equalTo: '#password'
                }
            };

        if($("#userId").val() != ''){
            rules = {};
        }

        $('#userForm').validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select")',
            rules: rules,
            messages: {
                password: {
                    pwcheck: 'Password must have (1) atleast 8 characters (2) atleast 1 uppercase (3) atleast 1 lowercase (4) atleast 1 number (5) atleast 1 special char',
                    minlength: "Please enter atleast 8 digit."
                },
                password_confirmation: {
                    required: "Confirm password does not match with password",
                    minlength: "Confirm Password must be at least 8 characters long.",
                    equalTo: "Confirm password does not match with password"
                }
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

        $('#location_id').select2({allowClear:true});
        $('#roles_id').select2({allowClear:true});
        $('#emp_id').select2({allowClear:true});
        $('#process').select2({
            'placeholder' :'Select Process'
        });
        //$(".employeeData").show();
            @php if(!isset($users)) { @endphp
            $("#emp_id").addClass('required');
            @php } @endphp


        $(".emp_type").change(function(e){
            var emp_type = $(this).val();
            if(emp_type == 'employee'){
                $(".employeeData").show();
                $("#emp_id").addClass('required');
                $('#last_name').addClass('required');
                $('.cls-role').removeAttr('disabled');
                $('.showTreeViewPermission').removeClass('d-none');
            } else if(emp_type == 'customer') {
                $(".employeeData").hide();
                $("#emp_id").removeClass('required');
                $('#last_name').removeClass('required');
                if (userID > 0) {
                    $('.cls-role').attr('disabled', 'disabled');
                }
                $('.showTreeViewPermission').addClass('d-none');
            } else {
                $(".employeeData").hide();
                $("#emp_id").removeClass('required');
                $('#last_name').addClass('required');
                $('.cls-role').removeAttr('disabled');
                $('.showTreeViewPermission').removeClass('d-none');
            }
        });

        $('#emp_id').change(function(){
            var emp_id = $(this).val();
            if(emp_id){
                $.ajax({
                    url: "{{route('getEmployeeData')}}",
                    data : {emp_id : emp_id}

                }).done(function(response) {

                    $('#first_name').val(response.first_name);
                    $('#first_name').addClass('form-control-solid');
                    $('#first_name').attr('readonly', true);

                    $('#middle_name').val(response.middle_name);
                    $('#middle_name').addClass('form-control-solid');
                    $('#middle_name').attr('readonly', true);

                    $('#last_name').val(response.last_name);
                    $('#last_name').addClass('form-control-solid');
                    $('#last_name').attr('readonly', true);

                });
            }
        });
    };

    function checkedNodeIds(nodes, checkedNodes) {
        var treeview = $(".cls-treeview").data("kendoTreeView");
        for (var i = 0; i < nodes.length; i++) {
            if (nodes[i].checked) {
                if ($.inArray(nodes[i].id, groupPermissionArr) != -1) {
                    var node = treeview.findByUid(nodes[i].uid);
                    node.find('.k-checkbox').attr("disabled", "disabled");
                }
                var node1 = treeview.findByUid(nodes[i].uid);
                if (!node1.find('.k-checkbox').prop('disabled')) {
                    checkedNodes.push(nodes[i].id);
                }
            }
            if (nodes[i].hasChildren) {
                checkedNodeIds(nodes[i].children.view(), checkedNodes);
            }
        }
    }

    // show checked node IDs on datasource change
    function onCheck() {
        var checkedNodes = [],
            treeView = $(".cls-treeview").data("kendoTreeView"),
            message;

        checkedNodeIds(treeView.dataSource.view(), checkedNodes);
        $('#user_permission').val(checkedNodes.join(","));
    }
</script>

@endpush
