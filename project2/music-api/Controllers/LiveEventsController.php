<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LiveEvents;
use App\Http\Controllers\Controller;

class LiveEventsController extends Controller
{
	//Get all events
        //module2/lecture/1_laravel/5 Slide 19
	public function index()
	{
		$event = LiveEvents::all();

        	return response()->json([
            		'message' => 'List of all live events',
            		'data' => $event
        	]);
    	}

        //Get event by ID
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function show(LiveEvents $event)
    	{
        	return response()->json([
            		'message' => 'You requested event with ID: ' . $event->id,
            		'data' => $event
        	]);
    	}

        //Create a new event
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function store(Request $request)
    	{
        	$validated = $request->validate([
            		'venue' => 'required|string|max:100',
            		'artist' => 'required|string|max:100',
            		'day' => 'required|string|max:100'
        	]);

        	$event = LiveEvents::create($validated);

        	return response()->json([
			'success' => true,
            		'message' => 'Live Event  created successfully',
            		'data' => $event
        	], 201);
    	}

        //Update event
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function update(Request $request, LiveEvents $event)
    	{
        	$validated = $request->validate([
            		'venue' => 'required|string|max:100',
            		'artist' => 'required|string|max:100',
            		'day' => 'required|string|max:100'
        	]);

        	$event->update($validated);

        	return response()->json([
            		'message' => 'Live event updated successfully',
            		'data' => $event->fresh()
        	]);
    	}

        //Delete event
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
   	public function destroy(LiveEvents $event)
    	{
        	$event->delete();

        	return response()->json([
            		'success' => true,
            		'message' => 'Live Event deleted successfully'
        	]);
    	}
}
