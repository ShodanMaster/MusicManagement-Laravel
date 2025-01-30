<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\PlayListController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[IndexController::class, 'index'])->name('index');

Route::prefix('album')->name('album.')->group(function(){

    Route::get('', [AlbumController::class, 'index'])->name('index');
    Route::post('store-album', [AlbumController::class, 'storeAlbum'])->name('storealbum');
    Route::post('update-album/{id}', [AlbumController::class, 'updateAlbum'])->name('updatealbum');
    Route::get('delete-album/{id}', [AlbumController::class, 'deleteAlbum'])->name('deletealbum');
    Route::get('restore-album/{id}', [AlbumController::class, 'restoreAlbum'])->name('restorealbum');
    Route::get('force-delete-album/{id}', [AlbumController::class, 'forceDeleteAlbum'])->name('forcedeletealbum');

    Route::get('get-musics/{id}', [AlbumController::class, 'getMusics'])->name('getmusics');

});
Route::prefix('music')->name('music.')->group(function(){
    Route::get('', [MusicController::class, 'index'])->name('index');
    Route::post('store-music', [MusicController::class, 'storeMusic'])->name('storemusic');
    Route::post('update-music', [MusicController::class, 'updateMusic'])->name('updatemusic');
    Route::post('delete-music', [MusicController::class, 'deleteMusic'])->name('deletemusic');
    Route::post('restore-music', [MusicController::class, 'restoreMusic'])->name('restoremusic');
    Route::post('force-delete-music', [MusicController::class, 'forceDeleteMusic'])->name('forcedeletemusic');
});

Route::prefix('play-list')->name('playlist.')->group(function(){
    Route::get('', [PlayListController::class, 'index'])->name('index');
    Route::post('add-playlist', [PlayListController::class, 'addPlayList'])->name('addplaylist');
});
