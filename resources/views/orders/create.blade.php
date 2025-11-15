@extends($theme)

@section('content')

@section('title', $title)

@component('partials._subheader.subheader-v6', [
    'page_title' => $title,
    'back_action' => route('orders.index'),
    'text' => __('common.back'),
])
@endcomponent

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        @include('components.error')
        {!! Form::open([
            'route' => 'orders.store',
            'role' => 'form',
            'id' => 'orderForm',
            'enctype' => 'multipart/form-data',
        ]) !!}

        @include('orders.form', [
            'Order' => null,
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
