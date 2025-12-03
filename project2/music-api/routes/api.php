<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AlbumsController;
use App\Http\Controllers\ArtistsController;
use App\Http\Controllers\AudiobooksController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\LiveEventsController;
use App\Http\Controllers\PlaylistsController;
use App\Http\Controllers\PodcastsController;
use App\Http\Controllers\TopArtistsController;
use App\Http\Controllers\TopSongsController;
use App\Http\Controllers\UsersController;

Route::apiResource('albums', AlbumsController::class);
Route::apiResource('artists', ArtistsController::class);
Route::apiResource('audiobooks', AudiobooksController::class);
Route::apiResource('employees', EmployeesController::class);
Route::apiResource('live-events', LiveEventsController::class);
Route::apiResource('playlists', PlaylistsController::class);
Route::apiResource('podcasts', PodcastsController::class);
Route::apiResource('top-artists', TopArtistsController::class);
Route::apiResource('top-songs', TopSongsController::class);
Route::apiResource('users', UsersController::class);

