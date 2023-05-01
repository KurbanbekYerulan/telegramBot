@extends('auth.layouts')
@section('content')
    <div class="card">
        @foreach($data->chunk(4) as $chunk)
            <div class="card-group">
                @foreach($chunk as $d)
                    <div class="card" style="margin: 5px">
                        <div class="card-body" style="margin: auto"
                             onclick="window.location='{{ route('questions.show',$d->id) }}'">
                            {{$d->name}}
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection
<style>
    .card-group .card:hover {
        transform: translateY(-5px) scale(1.005) translateZ(0);
        box-shadow: 0 24px 36px rgba(0, 0, 0, 0.11),
        0 24px 46px var(--bs-black);
    }

    .card-group .card:hover .overlay {
        transform: scale(4) translateZ(0);
    }

    .card-group .card:active {
        transform: scale(1) translateZ(0);
        box-shadow: 0 15px 24px rgba(0, 0, 0, 0.11),
        0 15px 24px var(--bs-black-rgb);
    }

</style>


