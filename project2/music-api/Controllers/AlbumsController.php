<?php

 //using module2/lecture/1_laravel/5 Slide 17
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Http\Controllers\Controller;

class AlbumsController extends Controller
{

	//Get all albums
	//module2/lecture/1_laravel/5 Slide 19
	public function index()
	{
		$albums = Album::all();
		return response()->json([
			'message' => 'List of all Albums',
			'data' => $albums
		]);
	}

	// Get album by id
	// module2/code/1_laravel_6/students-api1-6/app/Http/Controllers/Api/StudentController.php

	public function show(Album $album)
	{
		return response()->json([
			'message' => 'You requested album with ID: ' . $album->id,
			'data' => $album
		]);
	}

	// Create a new album 
	//module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentController.php
	public function store(Request $request)
	{
		$valid = $request->validate([
			'albumName' => 'required|string|max:100',
			'artistName' => 'required|string|max:100',
			'numOfSongs' => 'required|integer'
		]);

 		$album = Album::create($valid);
		return response()->json([
			'success' => true,
			'message' => 'Album created successfully',
			'data' => $album
		], 201);
	}

	// Update a album
	///module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentController.php
	public function update(Request $request, Album $album)
	{
		$valid = $request->validate([
                        'albumName' => 'required|string|max:100',
                        'artistName' => 'required|string|max:100',
                        'numOfSongs' => 'required|integer'
                ]);

		$album->update($valid);
		return response()->json([
			'message' => 'Album updated successfully',
			'data' => $album->fresh()
		]);
	}

	//Delete album
	///module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentController.php
	public function destroy(Album $album)
	{
		$album->delete();

		return response()->json([
			'success' => true,
			'message' => 'Album deleted successfully'
		]);
	}
}
