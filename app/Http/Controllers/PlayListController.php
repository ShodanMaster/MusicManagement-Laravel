<?php

namespace App\Http\Controllers;

use App\Models\PlayList;
use Exception;
use Illuminate\Http\Request;

class PlayListController extends Controller
{
    public function index(){
        $playlists = PlayList::all();
        return view('playList', compact('playlists'));
    }

    public function addPlayList(Request $request){
        // dd($request->all());
        try{
            $playlist = PlayList::where('name',$request->playlist)->first();

            if($playlist){
                $playlist->musics()->attach(decrypt($request->musicId));
            }else{

                $playlist = PlayList::create([
                    'name' => $request->playlist,
                ]);

                $playlist->musics()->attach(decrypt($request->musicId));
            }

            return response()->json([
                'status' => 200,
                'message' => 'Added To PlayList',
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 400,
                'message' => 'Something Went Wrong: '.$e->getMessage(),
            ]);
        }
    }
}
