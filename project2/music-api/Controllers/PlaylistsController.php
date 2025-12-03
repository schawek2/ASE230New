<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Playlists;
use App\Http\Controllers\Controller;

class PlaylistsController extends Controller
{
	//Get all playlists
        //module2/lecture/1_laravel/5 Slide 19
	public function index()
	{
		$playlist = Playlists::all();

        	return response()->json([
            		'message' => 'List of all playlists',
            		'data' => $playlist
        	]);
    	}

        //Get playlist by ID
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function show(Playlists $playlist)
    	{
        	return response()->json([
            		'message' => 'You requested playlist with ID: ' . $playlist->id,
            		'data' => $playlist
        	]);
    	}

        //Create a new playlist
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function store(Request $request)
    	{
        	$validated = $request->validate([
            		'usersName' => 'required|string|max:100',
            		'playlistName' => 'required|string|max:100',
            		'genre' => 'required|string|max:100'
        	]);

        	$playlist = Playlists::create($validated);

        	return response()->json([
			'success' => true,
            		'message' => 'playlist  created successfully',
            		'data' => $playlist
        	], 201);
    	}

        //Update playlist
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function update(Request $request, Playlists $playlist)
    	{
        	$validated = $request->validate([
            		'usersName' => 'required|string|max:100',
            		'playlistName' => 'required|string|max:100',
            		'genre' => 'required|string|max:100'
        	]);

        	$playlist->update($validated);

        	return response()->json([
            		'message' => 'Playlist updated successfully',
            		'data' => $playlist->fresh()
        	]);
    	}

        //Delete playlist
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
   	public function destroy(Playlists $playlist)
    	{
        	$playlist->delete();

        	return response()->json([
            		'success' => true,
            		'message' => 'Playlist deleted successfully'
        	]);
    	}
}
