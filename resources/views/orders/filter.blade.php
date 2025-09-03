<div id="shopOrderFilter" class="modal fixed-left fade pr-0" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-aside" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{ __('common.filter') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>{{ __('orders.order_date') }}</label>
                    <div class='input-group from_to_datepicker'>
                        {!! Form::text('dateFilter', null, ['class' => 'form-control date jsdate required ignore','id'=>'date','readonly']) !!}
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('customer',trans("orders.customer_name"))!!}
                    {!! Form::select('customerFilter',  [''=>'Select'] + $customer, null, ['class' => 'form-control customer jsCustomerFilter','data-placeholder'=>'Select Customer','id'=>'customerFilter']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button"
                    class="btn btn-success mr-2 btn_search jsBtnSearch">{{ __('common.search') }}</button>
                <button type="button" class="btn btn-danger btn_reset">{{ __('common.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
