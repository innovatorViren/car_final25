{{-- Extends layout --}}
@extends($theme)
{{-- Content --}}
@section('content')
@section('title', __('plan.plan') )

@component('partials._subheader.subheader-v6',
[
'page_title' => __('plan.plan'),
'back_action'=> url('plan'),
'text' => __('common.back'),
'permission' => true,
])
@endcomponent

{!! Form::open(['route' => 'plan.store','id' => 'planForm','role'=>"form",]) !!}

@include('plan.form',[
        'plan' => null
    ])

{!! Form::close() !!}

@endsection

@push('scripts')
@include('plan.script')
@endpush
