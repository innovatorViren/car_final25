
{!! Form::model($country, ['route' => ['country.update', $country->id],'id' => 'countryForm']) !!}
@method('PUT')
{!! Form::hidden ('id', $country->id ,['id' => 'id' ])!!}
@include('countries.form',[
        'country' => $country
    ])

{!! Form::close() !!}

