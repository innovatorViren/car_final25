{{-- Extends layout --}}
@extends('app')
{{-- Content --}}
@section('content')
@section('title', __('roles.title'))
@component('partials._subheader.subheader-v6', [
    'page_title' => __('roles.title'),
    'back_action' => url('roles'),
    'text' => __('common.back'),
])
@endcomponent

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        @include('components.error')
        {!! Form::open([
            'route' => 'roles.store',
            'class' => 'form-horizontal',
            'id' => 'roleForm',
            'role' => 'form',
            'method' => 'post',
        ]) !!}
        <div class="card card-custom gutter-b">

            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label>{{ __('roles.form.name') }} :<span class="text-danger">*</span></label>
                        {!! Form::text('name', null, ['class' => 'form-control required', 'id' => 'name']) !!}
                        {{-- <input type="text" class="form-control" placeholder=""> --}}
                    </div>

                    <div class="form-group col-lg-6">
                        <label>{{ __('roles.form.slug') }} :<span class="text-danger">*</span></label>
                        {!! Form::text('slug', null, [
                            'class' => 'form-control form-control-solid required',
                            'id' => 'slug',
                            'readonly',
                        ]) !!}
                        {{--  <input type="text" class="form-control form-control-solid" readonly="" placeholder=""> --}}
                    </div>
                </div>
            </div>
        </div>
        <h2>{{ __('roles.form.permissions') }}</h2>
        <div class="row">
            <div class="col-8">
                @include('admin.roles.permission_form')
            </div>
            <div class="col-4">
                <div class="card card-custom gutter-b">
                    <div class="card-body"></div>
                </div>
            </div>
        </div>
        <div class="card-footer pb-5 pt-5">
            <div class="col-6 p-2">
                Alt+A = Add, Alt+S = Save, Alt+B = Back.
            </div>
            <div class="row">
                <div class="col-6 text-right">
                    {!! link_to(URL::full(), 'Cancel', ['class' => 'btn btn-light-primary font-weight-bold']) !!}
                    {!! Form::submit('Save', ['name' => 'save', 'class' => 'btn btn-primary save']) !!}
                </div>
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</div>

@endsection
@section('styles')
<style type="text/css">
    .card.card-style {
        border: 1px solid rgba(0, 0, 0, 0.121569);
        border-color: burlywood;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(window).bind('load', function() {

        shortcut.add("Alt+S", function() {
            console.log('Alt+S');
            jQuery('form input[name="save"],.save').click();
        });
    });
</script>
@include('admin.roles.script')
@endsection
