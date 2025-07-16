{{-- Extends layout --}}
@extends($theme)
{{-- Content --}}
@section('content')
@section('title', __('users.title'))
@component('partials._subheader.subheader-v6', [
    'page_title' => __('users.title'),
    'back_action' => url('users'),
    'text' => __('common.back'),
])
@endcomponent

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        @include('components.error')
        <div class="card card-custom gutter-b">
            <div class="card-body">
                {!! Form::model($users, [
                    'route' => ['users.update', $users->id],
                    'class' => 'form-horizontal',
                    'id' => 'userForm',
                    'role' => 'form',
                    'method' => 'PATCH',
                    'enctype' => 'multipart/form-data',
                ]) !!}
                {{ Form::hidden('id', $users->id, ['id' => 'userId']) }}
                @include('admin.users.form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection
