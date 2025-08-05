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
        {!! Form::model($customers, [
            'route' => ['customers.update', $customers->id],
            'role' => 'form',
            'id' => 'customersForm',
            'enctype' => 'multipart/form-data',
        ]) !!}
        @method('PUT')
        {!! Form::hidden('id', $customers->id, ['id' => 'id']) !!}
        @include('customers.form', [
            'customers' => $customers,
        ])
        {!! Form::close() !!}
    </div>
</div>

@endsection
