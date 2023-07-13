<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\TopAlbumsController;
use App\Http\Controllers\AlbumController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/static-page', [StaticPageController::class, 'index'])
    ->middleware('regeneratePage');

Route::get('/top-albums', [TopAlbumsController::class, 'index'])->name('top-albums');
Route::get('/albums/{id}', [AlbumController::class, 'show'])->name('album');
Route::get('/404', function () {
    return view('404');
})->name('404');
