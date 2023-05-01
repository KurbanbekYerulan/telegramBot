@extends('auth.layouts')

@section('content')
    {{ Form::model($data, ['route' => ['homeworks.update', $data,'id' => $data->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'files' => true]) }}
    <div class="card">
        @include('homeworks.form')
        @include('components.footer-buttons', [ 'cancelRoute' => 'homeworks.index', 'id' => $data->id ])
    </div>
    {{ Form::close() }}
@endsection
