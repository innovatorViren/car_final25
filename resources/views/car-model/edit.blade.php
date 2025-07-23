
{!! Form::model($carModel, ['route' => ['car-model.update', $carModel->id],'id' => 'carModelForm','files' => true]) !!}
@method('PUT')
{!! Form::hidden ('id', $carModel->id ,['id' => 'id' ])!!}
@include('car-model.form',[
        'carModel' => $carModel
    ])

{!! Form::close() !!}

