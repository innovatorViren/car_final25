{!! Form::open(['route' => 'employee.assign_customer_update', 'method'=>'Post', 'role' => 'form', 'id' => 'customerListForm']) !!}
<div class="modal fade" id="customerModalID" data-backdrop="static" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('employee.select_customer') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div data-scroll="false">
                    <div class="row">
                        <div class="col-lg-12">
                            {!! Form::hidden('id', !empty($id) ? $id : '0' , ['class' => 'emp_id']) !!}
                            @if ($customerList->count() > 0)
                                {!! Form::label('assign_customer',trans("employee.assign_customer"))!!}
                                <br><br>
                                @php
                                    if(count($customerEmployeeId) != count($customerIds)){
                                        $checkOrUncheck = '';
                                    }else{
                                        $checkOrUncheck = 'checked';
                                    }
                                @endphp    
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" {{ $checkOrUncheck }} class="checkbox_animated parent-checkbox" name="parent_checkbox">
                                    <span></span><div class="p-2">{{ __('employee.select_all') }}</div>
                                </label>
                                @foreach ($customerList as $row)
                                    @php
                                        $city = $row->customerAddress->city->name ?? '';
                                        $state = $row->customerAddress->state->name ?? '';
                                        if(!in_array($row->id,$customerEmployeeId)){
                                            $childCustomer = '';
                                        }else{
                                            $childCustomer = 'checked';
                                        }

                                    @endphp
                                    <table >
                                        <tr>
                                            <label class="checkbox checkbox-lg">
                                                <input type="checkbox" {{ $childCustomer }} class="checkbox_animated checkRow child-checkbox" name="assign_customer[]" value="{{ $row->id }}">
                                                <span></span><div class="p-2">{{ $row->company_name . ' - ' .  $city .', '. $state }}</div>
                                            </label>
                                        </tr>
                                    </table>
                                @endforeach
                            @else
                                <table>
                                    <tr>
                                        <td colspan="8" class="text-center jsNoCustomer"><b>{{ __('employee.no_customer_found') }}</b></td>
                                    </tr>
                                    <span class="text-danger item-error d-none"></span>
                                </table>
                            @endif                                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold btn_loader" data-dismiss="modal">{{ __('common.close') }}</button>
                <button type="submit" class="btn btn-success font-weight-bold btn_loader">{{ __('common.save') }}</button>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
<script type="text/javascript">
    $(document).ready(function () {
        $('#btn_loader').addClass('btn-success').removeClass('btn-primary');
        initValidation();        
    });

    var initValidation = function () {
        let isSubmitting = false;
        $('#customerListForm').validate({
            debug: false,
            ignore: '.select2-search__field,:hidden:not("textarea,.files,select,input")',
            rules: {},
            messages: {},
            errorPlacement: function (error, element) {
                error.appendTo(element.parent()).addClass('text-danger');
            },
            submitHandler: function (data) {
                var isError = false;
                var id = $(".emp_id").val();
                var emptyCustomerList = $('.jsNoCustomer').text();
                if (id > 0 && (emptyCustomerList != '' || emptyCustomerList != null)) {
                    isError = true;
                }else{
                    $('.item-error').removeClass('d-none').html('Price list or Customer list is not found');
                    isError = false;
                }
                
                if (!isError) {
                    $('.btn_loader').prop('disabled', false);
                    return false;
                } else {
                    $('.btn_loader').addClass('spinner spinner-white spinner-left');
                    $('.btn_loader').prop('disabled', true);
                    return true;
                }
            }
        });
    };

    $(document).on('click', '.parent-checkbox', function() {   
        var isChecked = $(this).prop('checked');
        $('.child-checkbox').prop('checked', isChecked);
    });

    $(document).on('click', '.child-checkbox', function() {
        var totalCheckboxes = $('.child-checkbox').length;
        
        var checkedCheckboxes = $('.child-checkbox:checked').length;
        if (totalCheckboxes == checkedCheckboxes) {
            $('.parent-checkbox').prop('checked', true);
        } else {
            $('.parent-checkbox').prop('checked', false);
        }
    });

    $(document).on('click', '#btn_loader', function(){
        $('#customerListForm').submit();
    });

    $(document).on('click', '.jsCloseModal', function(){
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });
</script>
