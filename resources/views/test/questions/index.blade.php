@extends('auth.layouts')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        Вопросы {{$data->count()}}
                    </h4>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-sm-6 col-md-3 col-lg-2">
                    <button class="btn btn-success" onclick="window.location='{{ route("questions.create") }}'">
                        Создать вопрос
                    </button>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col">
                    <div class="table-responsive">
                        <table id="attacks-table" class="table">
                            <thead>
                            <tr>
                                <th> Вопрос</th>
                                <th>Действие</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $d)
                                <tr>
                                    <td>{{ $d['text'] }}</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-6 col-md-6 col-lg-2" style="padding: 0px">
                                                <form method="get" action="{{route('questions.edit',$d['id'])}}">
                                                    @method('PUT')
                                                    @csrf
                                                    <button type="submit" class="btn btn-info btn-sm">
                                                        <i class="nav-icon fas fa-edit"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="col-6 col-md-6 col-lg-2" style="margin-left: 5px">
                                                <form method="post" action="{{route('questions.destroy',$d['id'])}}">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="nav-icon fas fa-eraser"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
