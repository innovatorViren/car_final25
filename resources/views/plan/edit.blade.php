{{-- Extends layout --}}
@extends($theme)
{{-- Content --}}
@section('content')
@section('title', __('plan.plan') )

@component('partials._subheader.subheader-v6',
[
'page_title' => __('plan.plan'),
'back_action'=> route('outward-challan.index'),
'text' => __('common.back'),
'permission' => true,
])
@endcomponent


{!! Form::model($plan, ['route' => ['plan.update', $plan->id], 'id' => 'planForm','enctype' => 'multipart/form-data']) !!}
    @method('PUT')
    {!! Form::hidden('id', $plan->id, ['id' => 'id']) !!}
    @include('plan.form',[
        'plan' => $plan
    ])
{!! Form::close() !!}

@endsection

@push('scripts')
@include('plan.script')
@endpush
