<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
	//Get all users
        //module2/lecture/1_laravel/5 Slide 19
	public function index()
	{
		$user = User::all();

        	return response()->json([
            		'message' => 'List of all users',
            		'data' => $user
        	]);
    	}

        //Get user by ID
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function show(User $user)
    	{
        	return response()->json([
            		'message' => 'You requested user with ID: ' . $user->id,
            		'data' => $user
        	]);
    	}

        //Create a new user
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function store(Request $request)
    	{
        	$validated = $request->validate([
            		'name' => 'required|string|max:100',
            		'email' => 'required|string|max:100',
            		'password' => 'required|string|max:100'
        	]);

		$validated['password'] = Hash::make($validated['password']);

        	$user = User::create($validated);

        	return response()->json([
			'success' => true,
            	'message' => 'User  created successfully',
            	'data' => $user
        	], 201);
    	}

        //Update user
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
    	public function update(Request $request, User $user)
    	{
        	$validated = $request->validate([
            		'name' => 'required|string|max:100',
            		'email' => 'required|string|max:100',
            		'password' => 'required|string|max:100'
        	]);

		$validated['password'] = Hash::make($validated['password']);

        	$user->update($validated);

        	return response()->json([
            	'message' => 'User updated successfully',
            	'data' => $user->fresh()
        	]);
    	}

        //Delete user
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php
   	public function destroy(User $user)
    	{
        	$user->delete();

        	return response()->json([
            		'success' => true,
            		'message' => 'User deleted successfully'
        	]);
    	}
}
