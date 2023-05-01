@extends('auth.layouts')

@section('content')
    {{ Form::model($data, ['route' => ['groups.update', $data,'id' => $data->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'files' => true]) }}
    <div class="card">
        @include('groups.form')
        @include('components.footer-buttons', [ 'cancelRoute' => 'groups.index', 'id' => $data->id ])
    </div>
    {{ Form::close() }}
@endsection
