@extends('layout')
@section('content')

{{-- Create Album Modal --}}
<div class="modal fade" id="createAlbumModal" tabindex="-1" aria-labelledby="createAlbumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white fs-4">
          <h1 class="modal-title fs-5" id="createAlbumModalLabel">Create Album</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div id="createAlbumMessage">
            <ul id="createMessage"></ul>
        </div>
        <form action="" id="createAlbumForm">
            <div class="modal-body">
                <div class="form-group">
                    <label for="name" class="form-label">Name: </label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Album Name">
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

{{-- Edit Album Modal --}}
<div class="modal fade" id="editAlbumModal" tabindex="-1" aria-labelledby="editAlbumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white fs-4">
          <h1 class="modal-title fs-5" id="editAlbumModalLabel">Edit Album</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div id="editAlbumMessage">
            <ul id="editMessage"></ul>
        </div>
        <form action="" id="editAlbumForm">

            <div class="modal-body">
                <input type="hidden" name="id" id="edit-id">
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

{{-- Musics Modal --}}
<div class="modal fade" id="musicsModal" tabindex="-1" aria-labelledby="musicsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h1 class="modal-title fs-5" id="musicsModalLabel">Musics</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ul id="musicList">

          </ul>
        </div>
      </div>
    </div>
</div>

    <div class="d-flex justify-content-between">
        <h1>Album</h1>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createAlbumModal">
            Create Album
        </button>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($albums as $album)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>
                    {{$album->name}} |
                       <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#musicsModal" data-id="{{encrypt($album->id)}}" data-name="{{$album->name}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                            </svg>
                        </button>
                </td>

                <td>
                    @if(!$album->trashed())
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editAlbumModal" data-id="{{encrypt($album->id)}}" data-name="{{$album->name}}">
                        Edit
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id="deleteAlbum" data-id="{{encrypt($album->id)}}">Delete</button>
                    @else
                    <button type="button" class="btn btn-success btn-sm" id="restoreAlbum" data-id="{{encrypt($album->id)}}">Restore</button>
                    @endif
                    <button type="button" class="btn btn-danger btn-sm" id="forceDeleteAlbum" data-id="{{encrypt($album->id)}}">Force Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
@section('scripts')
    <script>
        $(document).on('submit', '#createAlbumForm',function (e) {
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
                url: "{{route('album.storealbum')}}",
                data: formData,
                processData: false, // Don't process the data
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    $('#createAlbumMessage').removeClass();
                    $('#createAlbumMessage').html('');
                    if (response.status === 200) {
                        $('#createAlbumModal').modal('hide');

                        $('#createAlbumForm')[0].reset();
                        alert(response.message);
                        location.reload();
                    }
                },
                error: function (xhr, status, error) {
                    $('#createAlbumMessage').html('');
                    $('#createAlbumMessage').removeClass().addClass('alert alert-danger');

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function (key, message) {
                            $('#createAlbumMessage').append('<li>' + message[0] + '</li>');
                        });
                    }else if (xhr.responseJSON && xhr.responseJSON.message) {
                        $('#createAlbumMessage').append('<li>' + xhr.responseJSON.message + '</li>');
                    }
                }
            });

        });

        $('#createAlbumModal').on('hidden.bs.modal', function () {
            $('#createAlbumMessage').removeClass();
            $('#createAlbumMessage').html('');
            $('#createAlbumForm')[0].reset();
        });

        $(document).on('shown.bs.modal', '#editAlbumModal', function (e) {
            var button = $(e.relatedTarget);
            var albumId = button.data('id');
            var albumName = button.data('name');

            $('#edit-id').val(albumId);
            $('#edit-name').val(albumName);
        });

        $(document).on('submit', '#editAlbumForm',function (e) {
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
                url: "album/update-album/" + $('#edit-id').val(),
                data: formData,
                dataType: "json",
                processData: false, // Don't process the data
                contentType: false,
                success: function (response) {
                    console.log(response);
                    $('#editAlbumMessage').removeClass();
                    $('#editAlbumMessage').html('');
                    if (response.status === 200) {
                        $('#editAlbumModal').modal('hide');

                        $('#editAlbumForm')[0].reset();
                        alert(response.message);
                        location.reload();
                    }else if(response.status === 404){
                        $('#editAlbumModal').modal('hide');

                        $('#editAlbumForm')[0].reset();
                        alert(response.message);
                        location.reload();
                    }else{
                        alert('Something Went Wrong!');
                        location.reload();
                    }
                },
                error: function (xhr, status, error) {
                    $('#editAlbumMessage').html('');
                    $('#editAlbumMessage').removeClass().addClass('alert alert-danger');

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function (key, message) {
                            $('#editAlbumMessage').append('<li>' + message[0] + '</li>');
                        });
                    }else if (xhr.responseJSON && xhr.responseJSON.message) {
                        $('#editAlbumMessage').append('<li>' + xhr.responseJSON.message + '</li>');
                    }
                }
            });
        });

        $('#editAlbumModal').on('hidden.bs.modal', function () {
            $('#editAlbumMessage').removeClass();
            $('#editAlbumMessage').html('');
            $('#editAlbumForm')[0].reset();
        });

        $(document).on('click', '#deleteAlbum',function (e) {
            e.preventDefault();
            console.log('inside');

            var albumId = $(this).data('id');

            if (confirm('Are you sure you want to delete this album?')) {
                $.ajax({
                    type: "GET",
                    url: "album/delete-album/"+albumId,
                    success: function (response) {
                        console.log(response);

                        if(response.status === 200){
                            alert(response.message);
                            location.reload();
                        }else if(response.status === 404){
                            alert(response.message);
                            location.reload();
                        }else{
                            alert('Something went wrong! Please try again.');
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

        $(document).on('click', '#restoreAlbum',function (e) {
            e.preventDefault();
            console.log('inside');

            var albumId = $(this).data('id');

            if (confirm('Are you sure you want to Restore this album?')) {
                $.ajax({
                    type: "GET",
                    url: "album/restore-album/"+albumId,
                    success: function (response) {
                        console.log(response);

                        if(response.status === 200){
                            alert(response.message);
                            location.reload();
                        }else if(response.status === 404){
                            alert(response.message);
                            location.reload();
                        }else{
                            alert('Something went wrong! Please try again.');
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

        $(document).on('click', '#forceDeleteAlbum',function (e) {
            e.preventDefault();
            console.log('inside');

            var albumId = $(this).data('id');

            if (confirm('Are you sure you want to Delete this album Permenently?')) {
                $.ajax({
                    type: "GET",
                    url: "album/force-delete-album/"+albumId,
                    success: function (response) {
                        console.log(response);

                        if(response.status === 200){
                            alert(response.message);
                            location.reload();
                        }else if(response.status === 404){
                            alert(response.message);
                            location.reload();
                        }else{
                            alert('Something went wrong! Please try again.');
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

        $(document).on('shown.bs.modal', '#musicsModal', function (e) {
            e.preventDefault();
            console.log('inside');
            var button = $(e.relatedTarget);
            var albumId = button.data('id');
            var album = button.data('name');
            $('.modal-title').html(`${album}'s Musics`)
            $.ajax({
                type: "GET",
                url: "album/get-musics/"+ albumId,
                success: function (response) {
                    console.log(response);
                    if(response.status === 200){
                            $.each(response.data, function (key, music) {
                                 $('#musicList').append('<li>' + music.music + '</li>');
                            });
                    }else if(response.status === 404){
                        $('#musicList').append('<li class="decoration-none text-muted">No Data Available</li>');
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
        });

        // with Fetch
        // document.addEventListener('shown.bs.modal', function (e) {
        //     var button = e.relatedTarget;  // Button that triggered the modal
        //     var albumId = button.getAttribute('data-id');  // Get album ID from the data attribute
        //     var album = button.getAttribute('data-name');  // Get album name from the data attribute
        //     document.querySelector('.modal-title').innerHTML = `${album}'s Musics`; // Set modal title

        //     fetch(`album/get-musics/${albumId}`)
        //         .then(response => {
        //             if (!response.ok) {
        //                 throw new Error(`HTTP error! status: ${response.status}`);
        //             }
        //             return response.json();  // Parse JSON from the response
        //         })
        //         .then(data => {
        //             console.log(data);
        //             if (data.status === 200) {
        //                 // Clear previous music list
        //                 document.getElementById('musicList').innerHTML = '';

        //                 // Iterate over each music in the response data and append to the list
        //                 data.data.forEach(music => {
        //                     let li = document.createElement('li');
        //                     li.textContent = music.music; // Add music name to the list item
        //                     document.getElementById('musicList').appendChild(li);
        //                 });
        //             }
        //         })
        //         .catch(error => {
        //             // Handle errors
        //             if (error.message.includes('400')) {
        //                 alert("Bad Request: " + error.message); // Handle 400 errors
        //             } else if (error.message.includes('404')) {
        //                 alert("Error 404: The album was not found."); // Handle 404 errors
        //             } else if (error.message.includes('500')) {
        //                 alert("Server Error: " + error.message); // Handle 500 errors
        //             } else {
        //                 alert("An error occurred: " + error.message); // General error message
        //             }
        //             console.error(error); // Log error details for debugging
        //         });
        // });


        $('#musicsModal').on('hidden.bs.modal', function () {
            $('#musicList').html('');
            $('.modal-title').html('');
        });

    </script>
@endsection
