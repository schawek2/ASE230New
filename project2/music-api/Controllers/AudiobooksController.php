<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AudioBooks;
use App\Http\Controllers\Controller;

class AudiobooksController extends Controller
{
        //Get all audiobooks
	//module2/lecture/1_laravel/5 Slide 19
    public function index()
    {
        $audiobook = AudioBooks::all();

        return response()->json([
            'message' => 'List of all audiobooks',
            'data' => $audiobook
        ]);
    }

	//Get audiobook by ID
	//module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    public function show(AudioBooks $audiobook)
    {
        return response()->json([
            'message' => 'You requested audiobook with ID: ' . $audiobook->id,
            'data' => $audiobook
        ]);
    }

	//Create a new audiobook
	//module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bookName' => 'required|string|max:100',
            'writerName' => 'required|string|max:100',
            'readerName' => 'required|string|max:100'
        ]);

        $audiobook = AudioBooks::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Audiobook  created successfully',
            'data' => $audiobook
        ], 201);
    }

	//Update audiobook
	//module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    public function update(Request $request, AudioBooks $audiobook)
    {
        $validated = $request->validate([
            'bookName' => 'required|string|max:100',
            'writerName' => 'required|string|max:100',
            'readerName' => 'required|string|max:100'
        ]);

        $audiobook->update($validated);

        return response()->json([
            'message' => 'Audiobook updated successfully',
            'data' => $audiobook->fresh()
        ]);
    }

	//Delete audiobook
	//module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    public function destroy(AudioBooks $audiobook)
    {
        $audiobook->delete();

        return response()->json([
            'success' => true,
            'message' => 'Audiobook deleted successfully'
        ]);
    }
}
