<div id="customerFilter" class="modal fixed-left fade pr-0" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-aside" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('common.filter') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('customerfilter', trans('customers.company_name')) !!}
                    {!! Form::select('customerfilter', ['' => 'Select'] + $customers, null, [
                        'class' => 'form-control customerfilter jscustomerfilter',
                        'id' => 'customerfilter_id',
                        'data-placeholder' => 'Company Name',
                    ]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('statefilter', trans('customers.state')) !!}
                    {!! Form::select('statefilter', ['' => 'Select'] + $states, null, [
                        'class' => 'form-control state jsstatefilter',
                        'id' => 'state_id',
                        'data-placeholder' => 'State',
                    ]) !!}
                </div>

               

                {{-- <div class="form-group">
                    {!! Form::label('product_type', trans('customers.product_type')) !!}
                    {!! Form::select('product_type', ['' => 'Select'] + $productType, null, [
                        'class' => 'form-control jsProductTypefilter ',
                        'id' => 'product_type',
                        'data-placeholder' => 'Product Type',
                    ]) !!}
                </div> --}}

                <div class="form-group">
                    {!! Form::label('gstTypeFilter', trans('customers.gst_type')) !!}
                    {!! Form::select('gstTypeFilter', ['' => 'Select'] + $gst_type, null, [
                        'class' => 'form-control gstTypeFilter jsGstTypeFilter',
                        'id' => 'gstTypeFilter_id',
                        'data-placeholder' => 'GST Type',
                    ]) !!}
                </div>

                {{-- <div class="form-group">
                    {!! Form::label('type_filter', trans('customers.type')) !!}
                    {!! Form::select('type_filter', ['' => 'Select', 'Roll'=>'Roll','Pouch'=>'Pouch'], null, [
                        'class' => 'form-control jsTypeFilter ',
                        'id' => 'type_filter','data-placeholder' => 'Type'
                    ]) !!}
                </div> --}}

            </div>
            <div class="modal-footer">
                <button type="button"
                    class="btn btn-success mr-2 btn_search jsBtnSearch">{{ __('common.search') }}</button>
                <button type="button" class="btn btn-danger btn_reset">{{ __('common.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        $('#customerfilter_id,#state_id,.jsGstTypeFilter').select2({
            allowClear: true
        });
    </script>
@endpush
