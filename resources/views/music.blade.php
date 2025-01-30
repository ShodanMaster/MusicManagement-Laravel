@extends('layout')
@section('content')

{{-- Create Music Modal --}}
<div class="modal fade" id="createMusicModal" tabindex="-1" aria-labelledby="createMusicModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white fs-4">
          <h1 class="modal-title fs-5" id="createMusicModalLabel">Create Music</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div id="createMusicMessage">
            <ul id="createMessage"></ul>
        </div>
        <form action="" id="createMusicForm">
            <div class="modal-body">
                <div class="form-group">
                    <label for="album" class="form-label">Album: </label>
                    <select class="form-control" name="album" id="album">
                        <option value="" disabled selected>--select album--</option>
                        @foreach ($albums as $album)
                            <option value="{{encrypt($album->id)}}">{{$album->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name" class="form-label">Name: </label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Music Name">
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
      </div>
    </div>
</div>

{{-- Edit Music Modal --}}
<div class="modal fade" id="editMusicModal" tabindex="-1" aria-labelledby="editMusicModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white fs-4">
          <h1 class="modal-title fs-5" id="editMusicModalLabel">Edit Music</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div id="editMusicMessage">
            <ul id="editMessage"></ul>
        </div>
        <form action="" id="editMusicForm">

            <div class="modal-body">
                <input type="hidden" name="id" id="edit-id">
                <input type="hidden" name="oldAlbumId" id="edit-album-id">
                <div class="form-group">
                    <label for="album" class="form-label">Album: </label>
                    <select class="form-control" name="albumId" id="album">
                        <option value="" disabled selected>--select album--</option>
                        @foreach ($albums as $album)
                            <option value="{{encrypt($album->id)}}">{{$album->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit-name" class="form-label">Name: </label>
                    <input type="text" class="form-control" name="name" id="edit-name">
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
      </div>
    </div>
</div>

{{-- Add To PlayList --}}
<div class="modal fade" id="playListModal" tabindex="-1" aria-labelledby="playListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h1 class="modal-title fs-5" id="playListModalLabel"></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" id="playListForm">
            <div class="modal-body">
                <input type="hidden" name="musicId" id="music-id">
                <div class="form-group">
                    <label for="playlist" class="form-label">PlayList</label>
                    <input type="text" class="form-control" id="playlist" name="playlist" list="playlist-options">
                    <datalist id="playlist-options">
                        @foreach ($playlists as $playlist)
                            <option value="{{$playlist->name}}"></option>
                        @endforeach
                    </datalist>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
      </div>
    </div>
</div>

<div class="d-flex justify-content-between mb-3">
    <h1>Music</h1>
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createMusicModal">
        Create Music
    </button>
</div>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col" colspan="2">name</th>
            <th scope="col">Album</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($musics as $music)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$music->name}}</td>
                <td>
                    @if (!$music->trashed())
                        <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#playListModal" data-name="{{$music->name}}" data-id="{{encrypt($music->id)}}">Add to playlist</button>
                    @else
                        <span class="btn btn-danger btn-sm text-white">retore music first</span>
                    @endif
                </td>
                <td>{{$music->album->name}}</td>
                <td>
                    @if (!$music->trashed())
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editMusicModal" data-id="{{encrypt($music->id)}}" data-name="{{$music->name}}" data-album-id="{{encrypt($music->album->id)}}">
                            Edit
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" id="deleteMusic" data-id="{{encrypt($music->id)}}">Delete</button>

                    @else
                        <button type="button" class="btn btn-success btn-sm" id="restoreMusic" data-id="{{encrypt($music->id)}}">Restore</button>

                    @endif
                        <button type="button" class="btn btn-danger btn-sm" id="forceDeleteMusic" data-id="{{encrypt($music->id)}}">Force Delete</button>
                </td>
            </tr>
        @endforeach
    </tbody>

</table>
@endsection

@section('scripts')
    <script>
        $(document).on('submit', '#createMusicForm',function (e) {
            e.preventDefault();

            // console.log('inside');
            var formData = new FormData(this);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "{{route('music.storemusic')}}",
                data: formData,
                processData: false, // Don't process the data
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    console.log(response.message);
                    $('#createMusicMessage').removeClass();
                    $('#createMusicMessage').html('');
                    if (response.status === 200) {
                        $('#createMusicModal').modal('hide');

                        // $('#createMusicModal')[0].reset();
                        $('#createMusicForm')[0].reset();
                        alert(response.message);
                        location.reload();
                    }

                },
                error: function (xhr, status, error) {
                    $('#createMusicMessage').html('');
                    $('#createMusicMessage').removeClass().addClass('alert alert-danger');

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function (key, message) {
                            $('#createMusicMessage').append('<li>' + message[0] + '</li>');
                        });
                    }else if (xhr.responseJSON && xhr.responseJSON.message) {
                        $('#createMusicMessage').append('<li>' + xhr.responseJSON.message + '</li>');
                    }
                }
            });

        });

        $('#createMusicModal').on('hidden.bs.modal', function () {
            $('#createMusicMessage').removeClass();
            $('#createMusicMessage').html('');
            $('#createMusicForm')[0].reset();
        });

        $(document).on('shown.bs.modal', '#editMusicModal', function (e) {
            var button = $(e.relatedTarget);
            var oldalbumId = button.data('album-id');
            var musicId = button.data('id');
            var musicName = button.data('name');

            $('#edit-album-id').val(oldalbumId);
            $('#edit-id').val(musicId);
            $('#edit-name').val(musicName);
        });

        $(document).on('submit', '#editMusicForm',function (e) {
            e.preventDefault();
            console.log('insde');
            var formData = new FormData(this);
            console.log(formData);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $.ajax({
                type: "POST",
                url: "{{route('music.updatemusic')}}",
                data: formData,
                dataType: "json",
                processData: false, // Don't process the data
                contentType: false,
                success: function (response) {
                    console.log(response);
                    $('#editMusicMessage').removeClass();
                    $('#editMusicMessage').html('');
                    if (response.status === 200) {
                        $('#editMusicModal').modal('hide');

                        $('#editMusicForm')[0].reset();
                        alert(response.message);
                        location.reload();
                    }else if(response.status === 404){
                        $('#editMusicModal').modal('hide');

                        $('#editMusicForm')[0].reset();
                        alert(response.message);
                        location.reload();
                    }else{
                        alert('Something Went Wrong!');
                        // location.reload();
                    }
                },
                error: function (xhr, status, error) {
                    $('#editMusicMessage').html('');
                    $('#editMusicMessage').removeClass().addClass('alert alert-danger');

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function (key, message) {
                            $('#editMusicMessage').append('<li>' + message[0] + '</li>');
                        });
                    }else if (xhr.responseJSON && xhr.responseJSON.message) {
                        $('#editMusicMessage').append('<li>' + xhr.responseJSON.message + '</li>');
                    }
                }
            });
        });

        $('#editMusicModal').on('hidden.bs.modal', function () {
            $('#editMusicMessage').removeClass();
            $('#editMusicMessage').html('');
            $('#editMusicForm')[0].reset();
        });

        $(document).on('click', '#deleteMusic',function (e) {
            e.preventDefault();

            // console.log('inside');
            var button = $(e.target);
            var id = button.data('id')
            // console.log(id);
            var formData = new FormData();
            formData.append('id', id);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if (confirm('Are you sure you want to delete this album?')) {
                $.ajax({
                    type: "POST",
                    url: "{{route('music.deletemusic')}}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log(response);

                        if(response.status === 200){
                            alert(response.message);
                            location.reload();
                        }else if(response.status === 404){
                            alert(response.message);
                            location.reload();
                        }else{
                            alert('Something Went Wrong!');
                            location.reload();
                        }

                    },
                    error: function (xhr, status, error) {
                            // Check for specific status and provide a meaningful message
                            if (xhr.status === 400) {
                                alert("Bad Request: " + xhr.responseText);  // Handle 400 errors
                            } else if (xhr.status === 404) {
                                alert("Error 404: The album was not found.");  // Handle 404 errors
                            } else if (xhr.status === 500) {
                                alert("Server Error: " + xhr.responseText);  // Handle 500 errors
                            } else {
                                alert("An error occurred: " + error);  // General error message
                            }
                            location.reload();
                            console.error(xhr.responseText);  // Log error details for debugging
                        }
                });
            }

        });

        $(document).on('click', '#restoreMusic',function (e) {
            e.preventDefault();

            // console.log('inside');
            var button = $(e.target);
            var id = button.data('id')
            // console.log(id);
            var formData = new FormData();
            formData.append('id', id);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if (confirm('Are you sure you want to restore this album?')) {
                $.ajax({
                    type: "POST",
                    url: "{{route('music.restoremusic')}}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log(response);

                        if(response.status === 200){
                            alert(response.message);
                            location.reload();
                        }else if(response.status === 404){
                            alert(response.message);
                            location.reload();
                        }else{
                            alert('Something Went Wrong!');
                            location.reload();
                        }

                    },
                    error: function (xhr, status, error) {
                            // Check for specific status and provide a meaningful message
                            if (xhr.status === 400) {
                                alert("Bad Request: " + xhr.responseText);  // Handle 400 errors
                            } else if (xhr.status === 404) {
                                alert("Error 404: The album was not found.");  // Handle 404 errors
                            } else if (xhr.status === 500) {
                                alert("Server Error: " + xhr.responseText);  // Handle 500 errors
                            } else {
                                alert("An error occurred: " + error);  // General error message
                            }
                            location.reload();
                            console.error(xhr.responseText);  // Log error details for debugging
                        }
                });
            }

        });

        $(document).on('click', '#forceDeleteMusic',function (e) {
            e.preventDefault();

            // console.log('inside');
            var button = $(e.target);
            var id = button.data('id')
            // console.log(id);
            var formData = new FormData();
            formData.append('id', id);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if (confirm('Are you sure you want to delete this album permenently?')) {
                $.ajax({
                    type: "POST",
                    url: "{{route('music.forcedeletemusic')}}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log(response);

                        if(response.status === 200){
                            alert(response.message);
                            location.reload();
                        }else if(response.status === 404){
                            alert(response.message);
                            location.reload();
                        }else{
                            alert('Something Went Wrong!');
                            location.reload();
                        }

                    },
                    error: function (xhr, status, error) {
                            // Check for specific status and provide a meaningful message
                            if (xhr.status === 400) {
                                alert("Bad Request: " + xhr.responseText);  // Handle 400 errors
                            } else if (xhr.status === 404) {
                                alert("Error 404: The album was not found.");  // Handle 404 errors
                            } else if (xhr.status === 500) {
                                alert("Server Error: " + xhr.responseText);  // Handle 500 errors
                            } else {
                                alert("An error occurred: " + error);  // General error message
                            }
                            location.reload();
                            console.error(xhr.responseText);  // Log error details for debugging
                        }
                });
            }

        });

        $(document).on('shown.bs.modal', '#playListModal', function (e) {
            var button = $(e.relatedTarget);
            var musicId = button.data('id');
            var musicName = button.data('name');

            $('#music-id').val(musicId);
            $('.modal-title').html(`Add ${musicName} to Playlist`);
        });

        $(document).on('submit', '#playListForm',function (e) {
            e.preventDefault();

            console.log('inside');

            var formData = new FormData(this);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "{{route('playlist.addplaylist')}}",
                data: formData,
                processData: false, // Don't process the data
                contentType: false,
                success: function (response) {
                    console.log(response);

                    if(response.status === 200){
                        $('#playListModal').modal('hide');
                        $('#playListForm')[0].reset();
                        alert(response.message);
                        // location.reload();
                    }
                },
                error: function (xhr, status, error) {
                    alert('Error: ' + error);
                    console.error("AJAX error:", error);
                }
            });

        });

        $('#playListModal').on('hidden.bs.modal', function () {

            $('#playListForm')[0].reset();
        });
    </script>
@endsection
