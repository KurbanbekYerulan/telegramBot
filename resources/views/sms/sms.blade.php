@extends('auth.layouts')
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route("message.send") }}" method="post">
                @csrf
                {{ Form::label('message', 'Сообщение', ['class' => 'col-md-2 from-control-label']) }}
                <br>
                {{ Form::textarea('message', null, ['class' => 'form-control', 'required' => 'required']) }}
                <br>
                <button class="btn btn-success" type="submit">Отправить</button>
            </form>
        </div>
    </div>
@endsection
