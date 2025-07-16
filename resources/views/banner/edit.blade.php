{!! Form::model($banner, [
    'route' => ['banner.update', $banner->id],
    'id' => 'bannerForm',
    'class' => 'form-horizontal',
    'files' => true,
]) !!}
@method('PUT')
{!! Form::hidden('id', $banner->id, ['id' => 'id']) !!}
@include('banner.form', [
    'banner' => $banner,
])
{!! Form::close() !!}
