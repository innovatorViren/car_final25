{!! Form::open([
    'route' => 'banner.store',
    'id' => 'bannerForm',
    'class' => 'form-horizontal',
    'files' => true,
]) !!}
@include('banner.form', [
    'banner' => null,
])
{!! Form::close() !!}
