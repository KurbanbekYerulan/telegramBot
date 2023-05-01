@extends('auth.layouts')

@section('content')
{{ Form::open(['route' => 'schedule.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'files' => true]) }}

    <div class="card">
        @include('schedule.form')
        @include('components.footer-buttons', [ 'cancelRoute' => 'schedule.index' ])
    </div>
    {{ Form::close() }}
@endsection
