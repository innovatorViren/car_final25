@extends($theme)
@section('content')
@section('title', $title)

@component('partials._subheader.subheader-v6', [
    'page_title' => __('employee.employee'),
    'back_action' => route('employee.index'),
    'text' => __('common.back'),
])
@endcomponent

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        @include('components.error')
        {!! Form::open([
            'route' => 'employee.store',
            'role' => 'form',
            'id' => 'employeeForm',
            'enctype' => 'multipart/form-data',
        ]) !!}

        @include('employee.form', [
            'employee' => null,
        ])

        {!! Form::close() !!}
    </div>
</div>

@endsection
