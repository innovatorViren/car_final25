{!! Form::model($year, ['route' => ['year.update', $year->id],'id' => 'yearForm']) !!}
@method('PUT')
{!! Form::hidden ('id', $year->id ,['id' => 'id' ])!!}
@include('years.form',[
        'year' => $year
    ])

{!! Form::close() !!}
