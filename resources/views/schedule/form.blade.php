<div class="card-body">
    <div class="row mt-4 mb-4">
        <div class="col">
            <div class="form-group row">
                {{ Form::label('weekDay', 'День недели', ['class' => 'col-md-2 from-control-label required']) }}
                <div class="col-md-10">
                    <select name="weekDay" id="weekDay" class="form-control">
                        <option value="Понедельник">Понедельник</option>
                        <option value="Вторник">Вторник</option>
                        <option value="Среда">Среда</option>
                        <option value="Четверг">Четверг</option>
                        <option value="Пятница">Пятница</option>
                        <option value="Суббота">Суббота</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
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
                                <option value="{{$data->group_id}}">{{$data->group->name}}</option>
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
            <div class="form-group row">
                {{ Form::label('timeOt', 'От', ['class' => 'col-md-2 from-control-label required']) }}
                <div class="col-md-10">
                    {{ Form::time('timeOt', null, ['class' => 'form-control', 'required' => 'required']) }}
                </div>
            </div>
            <div class="form-group row">
                {{ Form::label('timeDo', 'До', ['class' => 'col-md-2 from-control-label required']) }}
                <div class="col-md-10">
                    {{ Form::time('timeDo', null, ['class' => 'form-control', 'required' => 'required']) }}
                </div>
            </div>
        </div>
    </div>
</div>
