@extends('auth.layouts')

@section('content')
{{ Form::open(['route' => 'questions.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'files' => true]) }}

    <div class="card">
        @include('test.questions.form')
        @include('components.footer-buttons', [ 'cancelRoute' => 'questions.index' ])
    </div>
    {{ Form::close() }}
@endsection
