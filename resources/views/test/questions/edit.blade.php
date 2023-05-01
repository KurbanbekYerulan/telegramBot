@extends('auth.layouts')

@section('content')
    {{ Form::model($data, ['route' => ['questions.update', $data,'id' => $data->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'files' => true]) }}
    <div class="card">
        @include('test.questions.form')
        @include('components.footer-buttons', [ 'cancelRoute' => 'test.index', 'id' => $data->id ])
    </div>
    {{ Form::close() }}
@endsection
