<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employees;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class EmployeesController extends Controller
{
        //Get all employees
        //module2/lecture/1_laravel/5 Slide 19
    public function index()
    {
        $employees = Employees::all();

        return response()->json([
            'message' => 'List of all employees',
            'data' => $employees
        ]);
    }

        //Get employee by ID
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php>
    public function show(Employees $employee)
    {
        return response()->json([
            'message' => 'You requested employee with ID: ' . $employee->id,
            'data' => $employee
        ]);
    }

        //Create a new employee
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php>
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|max:100',
            'department' => 'required|string|max:100',
	    'password' => 'required|string|max:100'
        ]);

	$validated['password'] = Hash::make($validated['password']);

        $employee = Employees::create($validated);

	return response()->json([
	    'success' => true,
            'message' => 'Employee  created successfully',
            'data' => $employee
        ], 201);
    }

        //Update employee
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php>
    public function update(Request $request, Employees $employees)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|max:100',
            'department' => 'required|string|max:100',
	    'password' => 'required|string|max:100'
        ]);

	$validated['password'] = Hash::make($validated['password']);

        $employees->update($validated);

        return response()->json([
            'message' => 'Employee updated successfully',
            'data' => $employees->fresh()
        ]);
    }

        //Delete employee
        //module2/code/1_laravel/6/student-api1-6/app/Http/Controllers/api/StudentContoller.php>
    public function destroy(Employees $employee)
    {
        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully'
        ]);
    }
}
