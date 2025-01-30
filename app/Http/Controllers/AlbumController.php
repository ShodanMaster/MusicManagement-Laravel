<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Exception;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function index(){
        $albums = Album::withTrashed()->get();
        return view('album', compact('albums'));
    }

    public function storeAlbum(Request $request){
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|unique:albums,name',
        ]);

        try{
            Album::create([
                'name' => $request->name,
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Album Saved Successfully',
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 400,
                'message' => 'Something Went Wrong: '.$e->getMessage(),
            ]);
        }
    }

    public function updateAlbum(Request $request, $id){
        // dd($request->all());
        // dd(decrypt($id));
        $validated = $request->validate([
            'name' => 'required|string|unique:albums,name,' . decrypt($id),
        ]);
        try{
            $album = Album::find(decrypt($id));
            if($album){
                $album->update([
                    'name' => $request->name,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Album Updated Successfully',
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Album Not Found',
                ]);
            }
        }catch(Exception $e){
            // dd($e);
            return response()->json([
                'status' => 400,
                'message' => 'Something Went Wrong: '.$e->getMessage(),
            ]);
        }

    }

    public function deleteAlbum($id){
        try{
            $album = Album::find(decrypt($id));
            if($album){
                $album->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Album Deleted Successfully!'
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Album Not Found!',
                ]);
            }
        }catch(Exception $e){
            // dd($e);
            return response()->json([
                'status' => 400,
                'message' => 'Something Went Wrong: '.$e->getMessage(),
            ]);
        }
    }

    public function restoreAlbum($id){
        try{
            $album = Album::withTrashed()->find(decrypt($id));
            if($album){
                $album->restore();
                return response()->json([
                    'status' => 200,
                    'message' => 'Album Restored Successfully!'
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Album Not Found!',
                ]);
            }
        }catch(Exception $e){
            // dd($e);
            return response()->json([
                'status' => 400,
                'message' => 'Something Went Wrong: '.$e->getMessage(),
            ]);
        }
    }

    public function forceDeleteAlbum($id){
        try{
            $album = Album::withTrashed()->find(decrypt($id));
            if($album){
                $album->forceDelete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Album Deleted Permenently!'
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Album Not Found!',
                ]);
            }
        }catch(Exception $e){
            // dd($e);
            return response()->json([
                'status' => 400,
                'message' => 'Something Went Wrong: '.$e->getMessage(),
            ]);
        }
    }

    public function getMusics($id){
        try{
            $album = Album::find(decrypt($id));

            if($album){
                $musics = $album->musics->map(function($music){
                    return [
                        'music' => $music->name,
                    ];
                });

                return response()->json([
                    'status' => 200,
                    'message' => 'Musics Found',
                    'data' => $musics
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Musics Not Found'
                ]);
            }
        }catch(Exception $e){
            // dd($e);
            return response()->json([
                'status' => 400,
                'message' => 'Something Went Wrong: '.$e->getMessage(),
            ]);
        }
    }
}
