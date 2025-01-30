<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Music;
use App\Models\PlayList;
use Exception;
use Illuminate\Http\Request;

class MusicController extends Controller
{
    public function index(){
        $albums  = Album::all();
        $musics = Music::withTrashed()->get();
        $playlists = PlayList::all();
        return view('music', compact('albums', 'musics', 'playlists'));
    }

    public function storeMusic(Request $request){
        // dd($request->all());
        $validated = $request->validate([
            'album' => 'required|string',
            'name' => 'required|string|unique:musics,name',
        ]);

        try{
            Music::create([
                'album_id' => decrypt($request->album),
                'name' => $request->name,
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Music Saved Successfully',
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 400,
                'message' => 'Something Went Wrong: '.$e->getMessage(),
            ]);
        }
    }

    public function updateMusic(Request $request){
        // dd($request->all());
        $validated = $request->validate([
            'album' => 'string',
            'name' => 'required|string|unique:musics,name,' . decrypt($request->id),
        ]);
        // $albumId = $request->album ? $request->album : $request->oldAlbumId;
        // dd(decrypt($albumId));
        try{
            $music = Music::findOrFail(decrypt($request->id));
            // dd($music);
            if($music){
                $albumId = $request->albumId ? $request->albumId : $request->oldAlbumId;
                // dd(($albumId));
                $music->update([
                    'album_id' => decrypt($albumId),
                    'name' => $request->name,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Music Updated Successfully!'
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Music Not Found!'
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

    public function deleteMusic(Request $request){
        // dd($request->all());
        try{
            $music = Music::find(decrypt($request->id));
            if($music){
                $music->delete();

                return response()->json([
                    'status' => 200,
                    'message' => 'Music Deleted Successfully',
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Music Not Found',
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

    public function restoreMusic(Request $request){
        // dd($request->all());
        try{
            $music = Music::withTrashed()->find(decrypt($request->id));
            if($music){
                $music->restore();

                return response()->json([
                    'status' => 200,
                    'message' => 'Music Restored Successfully',
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Music Not Found',
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

    public function forceDeleteMusic(Request $request){
        // dd($request->all());
        try{
            $music = Music::withTrashed()->find(decrypt($request->id));
            if($music){
                $music->forceDelete();

                return response()->json([
                    'status' => 200,
                    'message' => 'Music Deleted Permenently',
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Music Not Found',
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
