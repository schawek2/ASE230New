<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artists;
use App\Http\Controllers\Controller;

class ArtistsController extends Controller
{

        //Get all artists
        //module2/lecture/1_laravel/5 Slide 19
        public function index()
        {
                $artists = Artists::all();
                return response()->json([
                        'message' => 'List of all Artists',
                        'data' => $artists
		]);
        }

        // Get artist by id
        // module2/code/1_laravel_6/students-api1-6/app/Http/Controllers/Api/StudentCon>

        public function show(Artists $artist)
        {
                return response()->json([
                        'message' => 'You requested artist with ID: ' . $artist->id,
                        'data' => $artist
                ]);
        }

        // Create a new artist
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContr>
        public function store(Request $request)
        {
                $valid = $request->validate([
                        'name' => 'required|string|max:100',
                        'agency' => 'required|string|max:100',
                        'genre' => 'required|string|max:100'
                ]);
		$artist = Artists::create($valid);
                return response()->json([
                        'success' => true,
                        'message' => 'Artist created successfully',
                        'data' => $artist
                ], 201);
        }

        // Update a artist
        ///module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentCont>
        public function update(Request $request, Artists $artist)
        {
                $valid = $request->validate([
                        'name' => 'required|string|max:100',
                        'agency' => 'required|string|max:100',
                        'genre' => 'required|string|max:100'
                ]);

                $artist->update($valid);
                return response()->json([
                        'message' => 'Artist updated successfully',
                        'data' => $artist->fresh()
                ]);
        }

        //Delete artist
        ///module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentCont>
        public function destroy(Artists $artist)
        {
                $artist->delete();

                return response()->json([
                        'success' => true,
                        'message' => 'Artist deleted successfully'
                ]);
        }

}
