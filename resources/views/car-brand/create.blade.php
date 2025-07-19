
{!! Form::open(['route' => 'car-brand.store','id' => 'carBrandForm','files' => true]) !!}
@include('car-brand.form',[
        'carBrand' => null
    ])

{!! Form::close() !!}