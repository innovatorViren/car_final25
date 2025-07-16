
{!! Form::open(['route' => 'country.store','id' => 'countryForm']) !!}
@include('countries.form',[
        'countries' => null
    ])

{!! Form::close() !!}