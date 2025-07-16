{!! Form::model($city, ['route' => ['city.update', $city->id], 'id' => 'cityForm']) !!}
    @method('PUT')
    {!! Form::hidden('id', $city->id, ['id' => 'id']) !!}
    @include('cities.form',[
        'city' => $city
    ])
{!! Form::close() !!}
