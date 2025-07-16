{{-- Extends layout --}}
@extends('app')
{{-- Content --}}
@section('content')
@section('title', $title)

@component('partials._subheader.subheader-v6', [
    'page_title' => $title,
])
@endcomponent

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="row">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
