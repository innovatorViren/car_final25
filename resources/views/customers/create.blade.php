@extends($theme)
@section('content')
@section('title', __('customers.title'))
@component('partials._subheader.subheader-v6', [
    'page_title' => __('customers.title'),
    'back_action' => route('customers.index'),
    'text' => __('common.back'),
])
@endcomponent

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        @include('components.error')
        {!! Form::open([
            'route' => 'customers.store',
            'role' => 'form',
            'id' => 'customersForm',
            'enctype' => 'multipart/form-data',
        ]) !!}
        @include('customers.form', [
            'customers' => null,
        ])
        {!! Form::close() !!}
    </div>
</div>

@endsection
