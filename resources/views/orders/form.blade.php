@php

    if (!empty($order)) {
        $disabled = 'disabled';
        $accDisabled = 'disabled';
    } else {
        $disabled = '';
        $accDisabled = '';
    }

    $date = now();
@endphp

<div class="card card-custom gutter-b">
    <div class="card-body">
        <div class="d-flex justify-content-between pb-5 pb-md-5 flex-column flex-md-row">
            <div class="form-group col-lg-6 px-0">
                <div class="row">
                    <div class="form-group col-lg-8">
                        @if (!empty($customerId))
                            {!! Form::label('customer_id', 'Customer') !!} <i class="text-danger">*</i>
                            {!! Form::select('customer_id', ['' => 'Select'] + $customers, $customerId ?? '', [
                                'class' => 'form-control jsCustomer required',
                                'data-placeholder' => 'Select Customer',
                                'disabled',
                            ]) !!}
                            {!! Form::hidden('customer_id', $customerId) !!}
                        @else
                            {!! Form::label('customer_id', 'Customer') !!} <i class="text-danger">*</i>
                            {!! Form::select('customer_id', ['' => 'Select'] + $customers, null, [
                                'class' => 'form-control jsCustomer required',
                                'data-placeholder' => 'Select Customer',
                                $disabled,
                            ]) !!}
                        @endif
                    </div>
                </div>
                <input type="hidden" name="shipping_account_value"
                    value="{{ $order->customer_adress_id ?? '' }}" id="customerAddressValue">
                <div class="row">
                    <div class="form-group col-lg-12">
                        {!! Form::label('customer_adress_id', trans('Address')) !!}<span class="text-danger">*</span>
                        {!! Form::select('customer_adress_id', [], null, [
                            'class' => 'form-control jsCustomerAddress required',
                            'id' => 'customer_adress_id',
                            'data-placeholder' => 'Select Customer Address',
                        ]) !!}
                    </div>
                </div>

                <div class="row">
                <!-- Car Brand Dropdown -->
                    <div class="form-group col-lg-4"><span class="text-danger">*</span>
                        {!! Form::label('car_brand', 'Select Car Brand') !!}
                        {!! Form::select('car_brand', ['' => 'Select'] + $carBrands, null, ['id' => 'car_brand', 'class' => 'form-control jsCarBrand','data-placeholder' => 'Select Car Brand',]) !!}
                    </div>

                    <!-- Car Model Dropdown (Initially empty) -->
                    <div class="form-group col-lg-4"><span class="text-danger">*</span>
                        {!! Form::label('car_model', 'Select Car Model') !!}
                        {!! Form::select('car_model', [], null, ['id' => 'car_model', 'class' => 'form-control jsCarModel','data-placeholder' => 'Select Car Model']) !!}
                    </div>
                    <div class="form-group col-lg-4">
                        {!! Form::label('frequency', 'Wash Frequency') !!}<span class="text-danger">*</span>
                        {!! Form::select('frequency',  ['' => 'Select Frequency'] + $frequency, null, [
                            'class' => 'form-control jsFrequency required', 
                            'id' => 'frequency',
                            'style' => 'width: 100%;',
                            'data-placeholder' => 'Select Frequency'
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column align-items-md-end px-0">
                <input type="hidden" name="code" value="{{ $generateCode ?? '' }}">
                <h2 class="text-right"><b>{{ $generateCode ?? '' }}</b></h2>
                <div class="form-group row mt-3">
                    {!! Form::label('date', trans('common.date'), ['class' => 'col-lg-4 col-form-label text-right']) !!}
                    <div class="col-lg-8 pr-0">
                        {!! Form::date('date',\Carbon\Carbon::now(), [
                            'class' => 'form-control defult-date jsDate required',
                            'id' => 'date',
                            'max' => '9999-12-31',
                            'readonly',
                        ]) !!}
                    </div>
                </div>
                
            </div>
        </div>

        <div class="row">
            <div class="form-group col-lg-6">
                {!! Form::label('start_date', 'Start Date') !!}<span class="text-danger">*</span>
                {!! Form::date('start_date', null, [
                    'class' => 'form-control',
                    'id' => 'start_date',
                    'min' => \Carbon\Carbon::now()->format('Y-m-d')
                ]) !!}
            </div>

            <div class="form-group col-lg-6">
                {!! Form::label('start_time', 'Start Time') !!}<span class="text-danger">*</span>
                {!! Form::time('start_time', null, [
                    'class' => 'form-control',
                    'id' => 'start_time'
                ]) !!}
            </div>
            <div class="form-group col-lg-6">
                {!! Form::label('end_time', 'End Time') !!}<span class="text-danger">*</span>
                {!! Form::time('end_time', null, [
                    'class' => 'form-control',
                    'id' => 'end_time',
                    'readonly'
                ]) !!}
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <h5 class="mt-4">Scheduled Slots</h5>
                <table class="table table-bordered" id="slotsTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
