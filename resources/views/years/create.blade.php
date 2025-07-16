{!! Form::open(['route' => 'year.store', 'id' => 'yearForm']) !!}
@include('years.form', [
    'year' => null,
])

{!! Form::close() !!}
