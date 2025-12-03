<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TopArtists;
use App\Http\Controllers\Controller;

class TopArtistsController extends Controller
{
	//Get all top artists
        //module2/lecture/1_laravel/5 Slide 19
	public function index()
	{
		$artist = TopArtists::all();

        	return response()->json([
            		'message' => 'List of all top artists',
            		'data' => $artist
        	]);
    	}

        //Get top artsit by ID
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function show(TopArtists $artist)
    	{
        	return response()->json([
            		'message' => 'You requested artist with ID: ' . $artist->id,
            		'data' => $artist
        	]);
    	}

        //Create a new top artist
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function store(Request $request)
    	{
        	$validated = $request->validate([
            		'name' => 'required|string|max:100',
            		'genre' => 'required|string|max:100',
            		'artistRank' => 'required|integer'
        	]);

        	$artist = TopArtists::create($validated);

        	return response()->json([
			'success' => true,
            	'message' => 'Top artist  created successfully',
            	'data' => $artist
        	], 201);
    	}

        //Update artist
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function update(Request $request, TopArtists $artist)
    	{
        	$validated = $request->validate([
            		'name' => 'required|string|max:100',
            		'genre' => 'required|string|max:100',
            		'artistRank' => 'required|integer'
        	]);

        	$artist->update($validated);

        	return response()->json([
            	'message' => 'Top artist updated successfully',
            	'data' => $artist->fresh()
        	]);
    	}

        //Delete artist
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
   	public function destroy(TopArtists $artist)
    	{
        	$artist->delete();

        	return response()->json([
            		'success' => true,
            		'message' => 'Artist deleted successfully'
        	]);
    	}
}
