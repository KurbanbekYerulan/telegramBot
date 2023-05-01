@extends('auth.layouts')

@section('content')
{{ Form::open(['route' => 'groups.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'files' => true]) }}

    <div class="card">
        @include('groups.form')
        @include('components.footer-buttons', [ 'cancelRoute' => 'groups.index' ])
    </div><!--card-->
    {{ Form::close() }}
@endsection
