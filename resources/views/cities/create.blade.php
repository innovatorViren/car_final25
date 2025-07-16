{!! Form::open(['route' => 'city.store', 'id' => 'cityForm']) !!}
@include('cities.form', [
    'cities' => null,
])
{!! Form::close() !!}
