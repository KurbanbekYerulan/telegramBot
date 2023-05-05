@extends('auth.layouts')

@section('content')
    {{ Form::model($data, ['route' => ['material.update', $data,'id' => $data->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'files' => true]) }}
    <div class="card">
        @include('material.form')
        @include('components.footer-buttons', [ 'cancelRoute' => 'material.index', 'id' => $data->id ])
    </div>
    {{ Form::close() }}
@endsection
