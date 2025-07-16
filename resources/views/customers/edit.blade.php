@extends($theme)
@section('content')
@section('title', __('customers.title'))
@component('partials._subheader.subheader-v6', [
    'page_title' => __('customers.title'),
    'back_action' => route('customers.index'),
    'text' => __('common.back'),
])
@endcomponent
@php
    $lead_id = $lead_id ?? null;
@endphp
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        @include('components.error')
        {!! Form::model($customers, [
            'route' => ['customers.update', $customers->id],
            'role' => 'form',
            'id' => 'customersForm',
            'enctype' => 'multipart/form-data',
        ]) !!}
        @method('PUT')
        {!! Form::hidden('id', $customers->id, ['id' => 'id']) !!}
        {!! Form::hidden('leadId', $lead_id ?? '', ['id' => 'leadId']) !!}
        @if(!$lead_id)
        {!! Form::hidden('customer_addresses_id', $customers->customerAddress->id, [
            'id' => 'customer_addresses_id',
        ]) !!}
        {!! Form::hidden('customer_bank_details_id', $customers->customerBankDetails->id ?? '', [
            'id' => 'customer_bank_details_id',
        ]) !!}
        @endif
        @include('customers.form', [
            'customers' => $customers,
        ])
        {!! Form::close() !!}
    </div>
</div>

@endsection
