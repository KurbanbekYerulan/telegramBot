<div class="card-body">
    <div class="row mt-4 mb-4">
        <div class="col">
            <div class="form-group row">
                {{ Form::label('text', 'Вопрос', ['class' => 'col-md-2 from-control-label required']) }}
                <div class="col-md-10">
                    {{ Form::text('text', null, ['class' => 'form-control', 'required' => 'required']) }}
                </div>
            </div>
            <div class="form-group row mt-1">
                {{ Form::label('answerA', 'Ответ А', ['class' => 'col-md-2 from-control-label required']) }}
                <div class="col-md-10">
                    {{ Form::text('answerA', null, ['class' => 'form-control', 'required' => 'required']) }}
                </div>
            </div>
            <div class="form-group row mt-1">
                {{ Form::label('answerB', 'Ответ B', ['class' => 'col-md-2 from-control-label required']) }}
                <div class="col-md-10">
                    {{ Form::text('answerB', null, ['class' => 'form-control', 'required' => 'required']) }}
                </div>
            </div>
            <div class="form-group row mt-1">
                {{ Form::label('correct_one', 'Правильный вариант', ['class' => 'col-md-2 from-control-label required']) }}
                <div class="col-md-10">
                    <select name="correct_one" id="correct_one" required class="form-control">
                        @if($data->correct_one === 'A')
                            <option value="A">A</option>
                            <option value="B">B</option>
                        @elseif($data->correct_one === 'B')
                            <option value="B">B</option>
                            <option value="A">A</option>

                        @else
                            <option value="A">A</option>
                            <option value="B">B</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row mt-1">
                {{ Form::label('group_id', 'Название группы', ['class' => 'col-md-2 from-control-label required']) }}
                <div class="col-md-10">
                    @if($data->group_id ===0 )
                        <div class="col-md-10">
                            <select name="group_id" id="group_id" class="form-control">
                                @foreach($data->group as $d)
                                    <option value="{{$d->id}}">{{$d->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="col-md-10">
                            <select name="group_id" id="group_id" class="form-control">
                                @foreach($data->group as $d)
                                    @if($d->id === $data->group_id)
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                    @endif
                                @endforeach
                                @foreach($data->group as $d)
                                    @if($d->id !== $data->group_id)
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
