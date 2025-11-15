@extends($theme)

@section('content')

@section('title', $title)

@component('partials._subheader.subheader-v6', [
    'page_title' => $title,
    'back_action' => route('sales-order.index'),
    'text' => __('common.back'),
])
@endcomponent

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        @include('components.error')
        {!! Form::model($salesOrder, [
            'route' => ['sales-order.update', $salesOrder['id']],
            'role' => 'form',
            'id' => 'salesOrderForm',
            'enctype' => 'multipart/form-data',
        ]) !!}
        @method('PUT')
        {!! Form::hidden('id', $salesOrder['id'], ['id' => 'id']) !!}
        @include('sales-order.form', [
            'salesOrder' => $salesOrder,
        ])

        {!! Form::close() !!}
    </div>
</div>
<div id="load-modal"></div>
@section('styles')
    <style type="text/css">
        .select2-container {
            width: 100% !important;
        }
    </style>
@endsection
@endsection
