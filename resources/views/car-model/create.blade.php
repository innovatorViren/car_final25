
{!! Form::open(['route' => 'car-model.store','id' => 'carModelForm','files' => true]) !!}
@include('car-model.form',[
        'carModel' => null
    ])

{!! Form::close() !!}