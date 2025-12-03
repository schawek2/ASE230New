<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TopSongs;
use App\Http\Controllers\Controller;

class TopSongsController extends Controller
{
	//Get all top songs
        //module2/lecture/1_laravel/5 Slide 19
	public function index()
	{
		$song = TopSongs::all();

        	return response()->json([
            		'message' => 'List of all top songs',
            		'data' => $song
        	]);
    	}

        //Get top song by ID
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function show(TopSongs $song)
    	{
        	return response()->json([
            		'message' => 'You requested top song with ID: ' . $song->id,
            		'data' => $song
        	]);
    	}

        //Create a new song
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function store(Request $request)
    	{
        	$validated = $request->validate([
            		'name' => 'required|string|max:100',
            		'artist' => 'required|string|max:100',
            		'songRank' => 'required|integer'
        	]);

        	$song = TopSongs::create($validated);

        	return response()->json([
			'success' => true,
            	'message' => 'Top song  created successfully',
            	'data' => $song
        	], 201);
    	}

        //Update top song
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function update(Request $request, TopSongs $song)
    	{
        	$validated = $request->validate([
            		'name' => 'required|string|max:100',
            		'artist' => 'required|string|max:100',
            		'songRank' => 'required|integer'
        	]);

        	$song->update($validated);

        	return response()->json([
            	'message' => 'Top song  updated successfully',
            	'data' => $song->fresh()
        	]);
    	}

        //Delete top song
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
   	public function destroy(TopSongs $song)
    	{
        	$song->delete();

        	return response()->json([
            		'success' => true,
            		'message' => 'Top song deleted successfully'
        	]);
    	}
}
