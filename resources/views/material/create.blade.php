@extends('auth.layouts')

@section('content')
{{ Form::open(['route' => 'material.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'files' => true]) }}

    <div class="card">
        @include('material.form')
        @include('components.footer-buttons', [ 'cancelRoute' => 'material.index' ])
    </div>
    {{ Form::close() }}
@endsection
