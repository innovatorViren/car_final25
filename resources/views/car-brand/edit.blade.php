
{!! Form::model($carBrand, ['route' => ['car-brand.update', $carBrand->id],'id' => 'carBrandForm','files' => true]) !!}
@method('PUT')
{!! Form::hidden ('id', $carBrand->id ,['id' => 'id' ])!!}
@include('car-brand.form',[
        'carBrand' => $carBrand
    ])

{!! Form::close() !!}

