@extends('layout')
@section('content')

    <h1>PlayList</h1>
    <div class="row">
        @foreach ($playlists as $playlist)
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white fs-4">
                    {{$playlist->name}}
                </div>
                <div class="card-body">
                    <ul>
                        @foreach ($playlist->musics as $music)
                            <li>{{$music->name}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endforeach
    </div>

@endsection
