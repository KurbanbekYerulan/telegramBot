@extends('auth.layouts')

@section('content')
{{ Form::open(['route' => 'homeworks.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'files' => true]) }}

    <div class="card">
        @include('homeworks.form')
        @include('components.footer-buttons', [ 'cancelRoute' => 'homeworks.index' ])
    </div>
    {{ Form::close() }}
@endsection
