{{-- Extends layout --}}
@extends('app')
{{-- Content --}}
@section('content')
@section('title', __('roles.view_permission'))
@component('partials._subheader.subheader-v6', [
    'page_title' => __('roles.view_permission'),
    'back_action' => url('roles'),
    'text' => __('common.back'),
])
@endcomponent

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <h2>{{ __('roles.form.permissions') }}</h2>
        <div class="row">
            <div class="col-12">
                @include('admin.roles.permission_form')
            </div>
        </div>
    </div>
</div>

@endsection
