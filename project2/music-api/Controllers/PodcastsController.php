<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Podcasts;
use App\Http\Controllers\Controller;

class PodcastsController extends Controller
{
	//Get all podcast
        //module2/lecture/1_laravel/5 Slide 19
	public function index()
	{
		$podcast = Podcasts::all();

        	return response()->json([
            		'message' => 'List of all podcasts',
            		'data' => $podcast
        	]);
    	}

        //Get podcast by ID
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function show(Podcasts $podcast)
    	{
        	return response()->json([
            		'message' => 'You requested podcast with ID: ' . $podcast->id,
            		'data' => $podcast
        	]);
    	}

        //Create a new podcast
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function store(Request $request)
    	{
        	$validated = $request->validate([
            		'name' => 'required|string|max:100',
            		'hostName' => 'required|string|max:100',
            		'totalTime' => 'required|integer'
        	]);

        	$podcast = Podcasts::create($validated);

        	return response()->json([
			'success' => true,
            	'message' => 'podcast  created successfully',
            	'data' => $podcast
        	], 201);
    	}

        //Update podcast
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function update(Request $request, Podcasts $podcast)
    	{
        	$validated = $request->validate([
            		'name' => 'required|string|max:100',
            		'hostName' => 'required|string|max:100',
            		'totalTime' => 'required|integer'
        	]);

        	$podcast->update($validated);

        	return response()->json([
            	'message' => 'podcast updated successfully',
            	'data' => $podcast->fresh()
        	]);
    	}

        //Delete podcast
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
   	public function destroy(Podcasts $podcast)
    	{
        	$podcast->delete();

        	return response()->json([
            		'success' => true,
            		'message' => 'Podcast deleted successfully'
        	]);
    	}
}
