@php

    if (!empty($salesOrder)) {
        $po_date = null;
        $type_btn_disabled = 'disabled';
        $disabled = 'disabled';
        $accDisabled = 'disabled';
        $price_list = $salesOrder->price_list_id ?? null;
    } else {
        $po_date = now();
        $type_btn_disabled = '';
        $disabled = '';
        $totalGst = 0;
        $accDisabled = '';
        $price_list = null;
    }

    $date = now();
@endphp

<div class="card card-custom gutter-b">
    <div class="card-body">
        <div class="d-flex justify-content-between pb-5 pb-md-5 flex-column flex-md-row">
            <h1 class="display-4 font-weight-boldest mb-10"></h1>
            <div class="d-flex flex-column align-items-md-end px-0">
                <h2 class="text-right"><b>{{ $generateCode ?? $salesOrder['po_number'] }}</b></h2>
                <div class="form-group row mt-3">
                    {!! Form::label('date', trans('common.date'), ['class' => 'col-form-label text-right']) !!}<i class="text-danger">*</i>
                    <div class="col-lg-9 pr-0">
                        {!! Form::date('date', $salesOrder['date'] ?? $date, [
                            'class' => 'form-control defult-date required',
                            'id' => 'date',
                            'max' => '9999-12-31',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 form-group">
                {!! form::hidden('delete_ids', null, ['class' => 'jsDeletedId']) !!}
                {!! Form::label('customer_id', trans('sales_order.customer')) !!}
                <i class="text-danger">*</i>
                {!! Form::select('customer_id', ['' => 'Select Customer'] + $customers, null, [
                    'class' => 'form-control required jsCustomer jsCustomerWithBranchOption',
                    'id' => 'customer_id',
                    'data-placeholder' => 'Select Customer',
                    $type_btn_disabled,
                ]) !!}
            </div>
            <div class="col-lg-4 form-group">
                {!! Form::label('price_list', trans('sales_order.price_list')) !!}
                <i class="text-danger">*</i>
                {!! Form::select('price_list', $pricelist ?? [], $price_list, [
                    'class' => 'form-control required jsPriceList',
                    'id' => 'price_list',
                    'data-placeholder' => 'Select Price List',
                    $type_btn_disabled,
                ]) !!}
                <span class="text-danger price_list_error d-none"></span>
            </div>
            <div class="col-lg-4 form-group">
                {!! Form::label('po_source', trans('sales_order.po_source')) !!}
                <i class="text-danger">*</i>
                {!! Form::select('po_source', ['' => 'Select Source'] + $po_source, null, [
                    'class' => 'form-control required jsPoSource',
                    'data-placeholder' => 'Select Source',
                ]) !!}
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 form-group">
                {!! Form::label('po_date', trans('sales_order.customer_po_date')) !!}
                <i class="text-danger">*</i>
                {!! Form::date('po_date', $salesOrder->po_date ?? null, [
                    'class' => 'form-control required',
                    'min' => '2000-01-01',
                    'max' => '9999-12-31',
                ]) !!}
            </div>
            <div class="col-lg-3 form-group">
                {!! Form::label('po_no', trans('sales_order.customer_po_no')) !!}
                <i class="text-danger">*</i>
                {!! Form::text('po_no', $salesOrder->po_no ?? null, [
                    'class' => 'form-control required',
                ]) !!}
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    {!! Form::label('remarks', trans('purchase_order.remarks')) !!}
                    {!! Form::textarea('remarks', $salesOrder->remark ?? null, ['class' => 'form-control', 'rows' => 2]) !!}
                </div>
            </div>


            <div class="col-lg-3">
                <div class="form-group">
                    {!! Form::label('sceme_type', trans('sales_order.scheme_type')) !!}<br>
                    <div class="radio-inline pt-4">
                        <label class="radio">
                            {{ Form::radio('sceme_type', 'offer', false, ['class' => 'form-check-input jsTypeSelect']) }}
                            <span></span>Offer
                        </label>
                        {{-- <label class="radio">
                            {{ Form::radio('sceme_type', 'discount', false, ['class' => 'form-check-input jsTypeSelect']) }}
                            <span></span>discount
                        </label> --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                {{-- Offer dropdown --}}
                <div class="form-group jsOfferBox" style="display:none;">
                    {!! Form::label('offer_id', trans('sales_order.offer')) !!}
                    <i class="text-danger">*</i>
                    {!! Form::select('offer_id', ['' => ''] + $offers, null, ['class' => 'form-control jsOfferSelect','data-placeholder' => 'Select Offer',]) !!}
                </div>

                {{-- Discount dropdown --}}
                {{-- <div class="form-group jsDiscountBox" style="display:none;">
                    <label for="discount_id">Select Discount</label>
                    {!! Form::select('discount_id', ['' => ''] + $discounts, null, ['class' => 'form-control jsDiscountSelect','data-placeholder' => 'Select Discount',]) !!}
                </div> --}}
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    {!! Form::label('client_po_image', trans('sales_order.client_po_image_pdf')) !!}
                    {!! Form::file('client_po_image', ['id' => 'client_po_image']) !!}
                </div>
            </div>
            <div class="col-lg-3">
                <img alt="Logo"
                    src="{{ isset($salesOrder->po_image) && !empty($salesOrder->po_image) ? asset($salesOrder->po_image) : asset('/media/users/no-image.png') }}"
                    class="h-75 align-self-end" id="client_po_image_preview" name="client_po_image_preview"
                    style="height: 30%;width: 30%;">
            </div>
        </div>

    </div>
</div>
<div class="card-footer pb-5 pt-5">
    <div class="row">
        <div class="col-12 text-right">
            <a href="" class="mr-2">{{ __('common.cancel') }}</a>
            {!! Form::hidden('from_btn', null, ['class' => 'frombtn']) !!}
            <button type="submit" class="btn btn-primary mr-2 btn_loader saveBtn"
                name="saveBtn">{{ __('common.save') }}</button>
            <div class="btn-group dropup">
                <button type="submit" class="btn btn-primary btn_loader saveExitBtn"
                    name="saveExitBtn">{{ __('common.save_exit') }}</button>
            </div>
        </div>
    </div>
</div>
@section('scripts')
    @include('orders.script')
@endsection
