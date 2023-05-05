<div class="card-body">
    <div class="row mt-4 mb-4">
        <div class="col">
            <div class="form-group row">
                {{ Form::label('description', 'Напишите какие материалы нужно учить', ['class' => 'col-md-2 from-control-label required']) }}
                <div class="col-md-10">
                    {{ Form::textarea('description', null, ['class' => 'form-control', 'required' => 'required']) }}
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
