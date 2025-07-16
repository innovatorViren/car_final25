$(document).ready(function () {
    $(document).on('click', '.delete-confrim', function (e) {
        e.preventDefault();

        var el = $(this);
        var url = el.attr('href');
        var redirect = el.attr('data-redirect');
        var id = el.data('id');
        var refresh = '#'+el.data().table;
       console.log(refresh);
       // return false;

        message.fire({
            title: 'Are you sure',
            text: "You want to delete this ?",
            type: 'warning',
            customClass: {
                confirmButton: 'btn btn-success shadow-sm mr-2',
                cancelButton: 'btn btn-danger shadow-sm'
            },
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.value) {
                //showLoader();
                $.ajax({
                    type: "POST",
                    url: url,
                    cache: false,
                    data: {
                        id: id,
                        _method: 'DELETE'
                    }
                }).always(function (respons) {
                    //stopLoader();
                     if(redirect)
                    {   
                        if(respons.success) {
                            window.location = redirect;
                        }
                    } else {
                        if(respons.page_refresh) {
                            location.reload();
                        } else {
                            // window.location.href = redirect;
                            $(refresh).DataTable().ajax.reload();
                        } 
                    } 
                    
                }).done(function (respons) {
                    if(respons.success) {
                        toastr.success(respons.message, "Success");
                    } else {
                        toastr.error(respons.message, "Error");
                    }
                }).fail(function (respons) {
                    var res = respons.responseJSON;
                    var msg = 'something went wrong please try again !' ;

                    if(res.errormessage) {
                        toastr.warning(res.errormessage, "Warning");
                    }
                    toastr.error(msg, "Error");
                });
            }
        });

    });
    //Function for take confirmation from admin user to unassign employee to class and subject and also use for get method route
    $(document).on('click', '.action-confrim', function (e) {
        e.preventDefault();

        var el = $(this);
        var url = el.attr('href');
        var refresh = el.closest('table');
        //console.log(refresh);
       // alert(refresh);
       // return false;
        message.fire({
            title: 'Are you sure',
            text: "You want to delete this ?",
            type: 'warning',
            customClass: {
                confirmButton: 'btn btn-success shadow-sm mr-2',
                cancelButton: 'btn btn-danger shadow-sm'
            },
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.value) {

                //showLoader();
                $.ajax({
                    type: "GET",
                    url: url,
                    cache: false
                }).always(function (respons) {
                    $(refresh).DataTable().ajax.reload();

                }).done(function (respons) {

                    message.fire({
                        type: 'success',
                        title: 'Success',
                        text: respons.message
                    });

                }).fail(function (respons) {
                    var data = respons.responseJSON;
                    message.fire({
                        type: 'error',
                        title: 'Error',
                        text: data.message ? data.message :
                            'something went wrong please try again !'
                    });

                });
            }
        });

    });
    //End of above
    $(document).on('click', '.change-status', function (e) {

        var el = $(this);
        var url = el.data('url');
        var table = el.data('table');
        var id = el.val();
        var refresh = el.data().table;

        $.ajax({
            type: "POST",
            url: url,
            data: {
                id: id,
                status: el.prop("checked"),
                table: table,
            }
        }).always(function (respons) { }).done(function (respons) {

            if (refresh == 'categories') {
                $('#'+refresh).DataTable().ajax.reload();
            }
            message.fire({
                type: 'success',
                title: 'Success',
                text: respons.message
            });

        }).fail(function (respons) {
            if (el.prop("checked") == true) {
                el.prop("checked", false);
            } else {
                el.prop("checked", true);
            }
            message.fire({
                type: 'error',
                title: 'Error',
                text: (typeof respons.responseJSON.message != 'undefined') ? respons.responseJSON.message : 'something went wrong please try again !'
            });

        });

    });


    $(document).on('click', '.change-agent-status', function (e) {

        var el = $(this);
        var table = el.data('table');
        var url = el.data('url');
        var id = el.val();
        var refresh = el.data().table;

        $.ajax({
            type: "POST",
            url: url,
            data: {
                id: id,
                status: el.prop("checked"),
                table: table,
            }
        }).always(function (respons) { }).done(function (respons) {

            if (refresh == 'categories') {
                $('#'+refresh).DataTable().ajax.reload();
            }
            message.fire({
                type: 'success',
                title: 'Success',
                text: respons.message
            });

        }).fail(function (respons) {
            if (el.prop("checked") == true) {
                el.prop("checked", false);
            } else {
                el.prop("checked", true);
            }
            message.fire({
                type: 'error',
                title: 'Error',
                text: (typeof respons.responseJSON.message != 'undefined') ? respons.responseJSON.message : 'something went wrong please try again !'
            });

        });

    });

    $(document).on('click', '.change-employee-status', function (e) {

        var el = $(this);
        var url = el.data('url');
        var table = el.data('table');
        var id = el.val();
        var refresh = el.data().table;
        var status = el.prop("checked");       
        
        var statusChange = false;
        if(status == false){
            var ajaxUrl = $(".employee-left").attr('href')+'?emp_id='+id;
            $(".employee-left").attr('href',ajaxUrl);
            $(".employee-left").attr('data-url',ajaxUrl);
            $(".employee-left").trigger('click');
            setTimeout(function () {
                $("#empId").val(id);
            }, 800);    
        } else {
            statusChange = true;
        }
         
        if(statusChange){
            $.ajax({    
                type: "POST",
                url: url,
                data: {
                    id: id,
                    status: el.prop("checked"),
                    table: table,
                }
            }).always(function (respons) { }).done(function (respons) {
                if (refresh == 'categories') {
                    $('#'+refresh).DataTable().ajax.reload();
                }
                message.fire({
                    type: 'success',
                    title: 'Success',
                    text: respons.message
                });
            }).fail(function (respons) {
                if (el.prop("checked") == true) {
                    el.prop("checked", false);
                } else {
                    el.prop("checked", true);
                }
                message.fire({
                    type: 'error',
                    title: 'Error',
                    text: (typeof respons.responseJSON.message != 'undefined') ? respons.responseJSON.message : 'something went wrong please try again !'
                });

            });
        }

    });

    $(document).on('click', '.call-modal', function (e) {
        e.preventDefault();
        // return false;
        var el = $(this);
        var url = el.data('url');
        var target = el.data('target-modal');
        var footerHide = el.data('footer-hide');

        $.ajax({
            type: "GET",
            url: url
        }).always(function () {
            $('#load-modal').html(' ')
            $('.modal-footer').show();
        }).done(function (res) {
            $('#load-modal').html(res.html);
            $(target).modal('toggle');
            if (footerHide) {
                $('.modal-footer').hide();
            }
        });
    });

    $(document).on('click', ".show-info", function() {
        var infoUrl = $(this).data('url');
        var tableName = $(this).data('table');
        var tableRowId = $(this).data('id');
        $.ajax({
            type: "GET",
            url: infoUrl,
            cache: false,
            data: {
                table_name : tableName,
                id: tableRowId,
            },
            success:function(response){
                console.log(response);
                $("#created_at").html(response.addData.created_at);
                $("#created_by").html(response.addData.created_by);
                $("#created_ip").html(response.addData.created_ip);

                $("#updated_at").html(response.updateData.updated_at);
                $("#updated_by").html(response.updateData.updated_by);
                $("#updated_ip").html(response.updateData.updated_ip);
            }
        });
    });

    //For Date Filter-------------------------------------------
    var start = moment().startOf('month');
    var end = moment().endOf('month');
    var filteredDate = $('#date').val();
    if (filteredDate) {
        var filteredDateArr = $('#date').val().split(' | ');
        var filteredFromDate = filteredDateArr[0];
        var filteredToDate = filteredDateArr[1];
        var start = filteredFromDate;
        var end = filteredToDate;
    }
    var fromYearDate = new Date(defaultFromDate);
    var toYearDate = new Date(defaultToDate);

    $('.from_to_datepicker').daterangepicker({
        buttonClasses: ' btn',
        applyClass: 'btn-primary',
        cancelClass: 'btn-secondary',
        locale: {
            format: 'DD/MM/YYYY'
        },
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'This Year': [fromYearDate, toYearDate],
            'Last Year': [moment(fromYearDate).subtract(1, 'year'), moment(toYearDate).subtract(1, 'year')],
        }
    }, function(start, end, label) {
        $('.from_to_datepicker .form-control').val(start.format('DD-MM-YYYY') + ' | ' + end.format(
            'DD-MM-YYYY'));
    });
    //For Date Filter-------------------------------------------

    $('.datepicker_to_filter').datetimepicker({
        timePicker: true,
        allowInput: false,
        format: 'DD-MM-YYYY',
        mask:true,
    });
});


    //Loader End
    function addLoadSpiner(el) {
        //debugger;
        if (el.length > 0) {
            if ($("#img_" + el[0].id).length > 0) {
                $("#img_" + el[0].id).css('display', 'block');
            }               
            else {
                /*var img = $('<img class="ddloading">');
                img.attr('id', "img_" + el[0].id);
                img.attr('src', 'storage/default/orange_circles.gif');
                img.css({ 'display': 'block', 'width': '30px', 'height': '30px', 'z-index': '100', 'float': 'right' ,'margin-right': '22px','margin-top': '10px'});
                img.prependTo(el[0].nextElementSibling);*/
                var img = $('<span class="ddloading"><i class="fas fa-spinner fa-pulse" style="margin-top: 10px; color: #3c3b90"></i>');
                img.attr('id', "img_" + el[0].id);
                //img.text('<i class="fas fa-spinner fa-pulse"></i>');
                //img.attr('src', 'storage/default/orange_circles.gif');
                img.css({ 'display': 'block', 'width': '22px', 'height': '0px', 'z-index': '999', 'float': 'right' ,'margin-right': '22px'});
                //$(".ddloading").html('');
                img.prependTo(el[0].nextElementSibling);
            }
            el.prop("disabled", true);               
        }
    }

    //Loader End
    function hideLoadSpinner(el) {
        if (el.length > 0) {
            if ($("#img_" + el[0].id).length > 0) {
                setTimeout(function () {
                    $("#img_" + el[0].id).css('display', 'none');
                    el.prop("disabled", false);
                }, 500);                    
            }
        }
    }

    
    function setFilterData(fieldData){

        $('.jsFilterData').html('');
        var htmlData = '';
        $.each(fieldData, function(i, fieldName){
            
            var _field = $('.'+fieldName);
            var _fieldVal = ''
            if(_field.is("select") && _field.val() != '') {
                _fieldVal = $( "option:selected", _field ).text();
            }else{
                _fieldVal = _field.val();    
            }
            
            if(_fieldVal !=''){
                
                htmlData += '<span class="btn btn-light-dark font-weight-bold mr-2 remove-filter jsRemoveFilter" data-field-name="'+fieldName+'"> <i class="ki ki-bold-close icon-sm"></i> '+_fieldVal+'</span>';
            }
        });  
        $('.jsFilterData').append(htmlData);
    }

    $(document).on('click', '.jsRemoveFilter', function(){

        var fieldName = $(this).data('field-name');
        var _field = $('.'+fieldName);        
        if(_field.is("select")) {
            _field.val('').trigger('change');
        }else{
            _field.val('');    
        }
        setTimeout(function(){
            $('.jsBtnSearch').click();
        },200);
    });
    
    function getCustomerWithBranchName(customer_id = null){
        $.ajax({
            url: '/get-customer-with-branch-name',
            data:{},
            success:function(res){
                var data = [{
                        'id':'',
                        'text':'',
                        'html':'',
                    }
                ];
                $.each(res, function(index, value) {
                        
                    var id = value.id;
                    var htmlText = value.company_name+'<i class="font-size-sm"><br>'+value.name+'</i>';
                    var text = value.company_name;
                    
                    if (customer_id > 0 && (id == customer_id)) {
                        var selected = true;
                    } else {
                        var selected = false;
                    }

                    var obj = {
                        'id':id,
                        'text':text,
                        'html':htmlText,
                        'branch':value.name,
                        "selected": selected
                    };
                    data.push(obj);
                });
                $(".jsCustomerWithBranchOption").select2({
                   data: data,
                   templateResult: selectTemplate,
                    allowClear: true,
                   escapeMarkup: function(m) {
                      return m;
                   }
                });
            },
        });
    }

    function getEmployeeWithBranchName(employee_id = null, customer_id = null){
        $.ajax({
            url: '/get-employee-with-branch-name',
            data:{
                customer_id : customer_id,
            },
            success:function(res){
                var data = [{
                        'id':'',
                        'text':'',
                        'html':'',
                    }
                ];
                $.each(res, function(index, value) {
                        
                    var id = value.id;
                    var htmlText = value.person_name+'<i class="font-size-sm"><br>'+value.name+'</i>';
                    var text = value.person_name;
                    
                    if (employee_id > 0 && (id == employee_id)) {
                        var selected = true;
                    } else {
                        var selected = false;
                    }

                    var obj = {
                        'id':id,
                        'text':text,
                        'html':htmlText,
                        'branch':value.name,
                        "selected": selected
                    };
                    data.push(obj);
                });
                $(".jsEmployeeWithBranchOption").select2({
                   data: data,
                   templateResult: selectTemplate,
                    allowClear: true,
                   escapeMarkup: function(m) {
                      return m;
                   }
                });
            },
        });
    }

    function selectTemplate(data) {
        $(data.element).attr('data-branch', data.accType);
        return data.html;
    }

    var isSticky = document.querySelector('[data-module="sticky-table"]');
    if(isSticky){
        setTimeout(function() {
            let xscroller = document.querySelector('.dataTables_scrollBody');
            let head = document.querySelector('.dataTables_scrollHead');
            head['style'].overflow = '';
            xscroller.addEventListener('scroll', function(_e_) {
            head['style'].left = '-' + e.target['scrollLeft'] + 'px';
        });
    }, 1000);
    
    var el = document.querySelector('[data-module="sticky-table"]');
    var scrollPosition = document.documentElement.scrollTop || document.body.scrollTop;
    var thead = el.querySelector('thead');
    var offset = el.getBoundingClientRect();
    
    // Make sure you throttle/debounce this
    window.addEventListener('scroll', function (_event_) {
        var rect = el.getBoundingClientRect();
        scrollPosition = document.documentElement.scrollTop || document.body.scrollTop;
        if (rect.top < thead.offsetHeight) {
            thead.style.width = rect.width + 'px';
            thead.classList.add('thead--is-fixed');
        } else {
            thead.classList.remove('thead--is-fixed');
        }
        });
    }
