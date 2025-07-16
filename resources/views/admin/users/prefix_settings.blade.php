@extends($theme)
@section('title', $title)
@section('content')
    @php
        $drivers = [
            'smtp' => 'smtp',
            'mailgun' => 'mailgun',
            'mandrill' => 'mandrill',
            'ses' => 'ses',
            'sparkpost' => 'sparkpost',
            'log' => 'log',
        ];
        $prefix_quotation_arr = ['class' => 'form-control', 'placeholder' => 'Quotation'];
    @endphp
    <style type="text/css">
        .col-sm-2,
        .col-sm-3,
        .col-sm-4 {
            float: left;
        }

        .form-group {
            display: block;
            clear: both;
        }

        .form-control {
            margin-bottom: 10px;
        }
    </style>
    {!! Form::model($settings, ['route' => ['prefix_settings_store'], 'class' => 'form-horizontal']) !!}
    <div class="col-md-12">
        <div class="panel panel-white">
            <div class="panel-heading">
                <h4 class="panel-title">Prefix Settings</h4>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <div class="form-group {{ $errors->has('prefix_pipeline') ? 'has-error' : '' }}">
                    {!! Html::decode(
                        Form::label('prefix_pipeline', 'Pipeline Code' . ':<span class="has-stik">*</span>', [
                            'class' => 'col-sm-2 control-label',
                        ]),
                    ) !!}
                    <div class="col-sm-4">
                        {!! Form::text('prefix_pipeline', null, ['class' => 'form-control', 'placeholder' => 'Pipeline', 'required']) !!}
                        {!! $errors->first('prefix_pipeline', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('prefix_cpo') ? 'has-error' : '' }}">
                    {!! Html::decode(
                        Form::label('host', 'CPO' . ':<span class="has-stik">*</span>', ['class' => 'col-sm-2 control-label']),
                    ) !!}
                    <div class="col-sm-4">
                        {!! Form::text('prefix_cpo', null, ['class' => 'form-control', 'placeholder' => 'CPO']) !!}
                        {!! $errors->first('prefix_cpo', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('prefix_sales_order') ? 'has-error' : '' }}">
                    {!! Html::decode(
                        Form::label('prefix_sales_order', 'Sales Order' . ':<span class="has-stik">*</span>', [
                            'class' => 'col-sm-2 control-label',
                        ]),
                    ) !!}
                    <div class="col-sm-4">
                        {!! Form::text('prefix_sales_order', null, ['class' => 'form-control', 'placeholder' => 'SalesOrder']) !!}
                        {!! $errors->first('prefix_sales_order', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('prefix_purchase_order') ? 'has-error' : '' }}">
                    {!! Html::decode(
                        Form::label('prefix_purchase_order', 'Purchase Order' . ':<span class="has-stik">*</span>', [
                            'class' => 'col-sm-2 control-label',
                        ]),
                    ) !!}
                    <div class="col-sm-4">
                        {!! Form::text('prefix_purchase_order', null, [
                            'class' => 'form-control customTabindex',
                            'placeholder' => 'Purchase Order',
                        ]) !!}
                        {!! $errors->first('prefix_purchase_order', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('prefix_quotation') ? 'has-error' : '' }}">
                    {!! Html::decode(
                        Form::label('prefix_quotation', 'Quotation' . ':<span class="has-stik">*</span>', [
                            'class' => 'col-sm-2 control-label',
                        ]),
                    ) !!}
                    <div class="col-sm-4">
                        {!! Form::text('prefix_quotation', null, $prefix_quotation_arr) !!}
                        {!! $errors->first('prefix_quotation', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('prefix_proforma_invoice') ? 'has-error' : '' }}">
                    {!! Html::decode(
                        Form::label('prefix_proforma_invoice', 'Proforma Invoice.' . ':<span class="has-stik">*</span>', [
                            'class' => 'col-sm-2 control-label',
                        ]),
                    ) !!}
                    <div class="col-sm-4">
                        {!! Form::text('prefix_proforma_invoice', null, [
                            'class' => 'form-control',
                            'placeholder' => 'Proforma Invoice',
                        ]) !!}
                        {!! $errors->first('prefix_proforma_invoice', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('raw_barcode_prefix') ? 'has-error' : '' }}">
                    {!! Html::decode(
                        Form::label('raw_barcode_prefix', 'Raw Material Barcode.' . ':<span class="has-stik">*</span>', [
                            'class' => 'col-sm-2 control-label',
                        ]),
                    ) !!}
                    <div class="col-sm-4">
                        {!! Form::text('raw_barcode_prefix', null, ['class' => 'form-control', 'placeholder' => 'Proforma Invoice']) !!}
                        {!! $errors->first('raw_barcode_prefix', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('raw_barcode_layout') ? 'has-error' : '' }}">
                    {!! Html::decode(
                        Form::label('raw_barcode_layout', 'Raw Material Layout.' . ':<span class="has-stik">*</span>', [
                            'class' => 'col-sm-2 control-label',
                        ]),
                    ) !!}
                    <div class="col-sm-4">
                        {!! Form::text('raw_barcode_layout', null, ['class' => 'form-control', 'placeholder' => 'Proforma Invoice']) !!}
                        {!! $errors->first('raw_barcode_layout', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('theme_color') ? 'has-error' : '' }}">
                    {!! Html::decode(
                        Form::label('theme_color', 'Theme Color' . ':<span class="has-stik">*</span>', [
                            'class' => 'col-sm-2 control-label',
                        ]),
                    ) !!}
                    <div class="col-sm-4">
                        {!! Form::color('theme_color', null, ['class' => 'form-control', 'placeholder' => 'Proforma Invoice']) !!}
                        {!! $errors->first('theme_color', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group text-right">
                    {!! Html::decode(Form::label('action_button', ' ', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-12">
                        {!! Form::submit('Save', ['name' => 'save', 'class' => 'btn btn-primary']) !!}
                        {!! link_to(URL::full(), 'Cancel', ['class' => 'customTabindex btn btn-warning cancel']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
@push('scripts')
    <script type="text/javascript">
        // show user modal
        jQuery(document).on('click', '#new_in_modal a.cancel,.close', function(e) {
            e.preventDefault();
            jQuery('#new_in_modal').modal('hide');
        });
    </script>
@endpush
