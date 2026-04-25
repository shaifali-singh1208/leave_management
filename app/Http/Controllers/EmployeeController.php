<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index()
    {
        $aRows = User::where('role', User::ROLE_EMPLOYEE)->with(['manager', 'department'])->orderBy('id', 'desc')->get();

        return view('admin.employee.index', compact('aRows'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        $aRow     = null;
        $managers = User::where('role', User::ROLE_MANAGER)->orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        return view('admin.employee.manage', compact('aRow', 'managers', 'departments'));
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:8|confirmed',
            'manager_id'    => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
        ]);
        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => User::ROLE_EMPLOYEE,
            'manager_id'    => $request->manager_id ?: null,
            'department_id' => $request->department_id ?: null,
        ]);

        return redirect()->route('admin.employee.index')->with('success', 'Employee created successfully.');
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(User $employee)
    {
        if ($employee->role !== User::ROLE_EMPLOYEE) {
            abort(404);
        }
        $aRow     = $employee;
        $managers = User::where('role', User::ROLE_MANAGER)->orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('admin.employee.manage', compact('aRow', 'managers', 'departments'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, User $employee)
    {
        if ($employee->role !== User::ROLE_EMPLOYEE) {
            abort(404);
        }

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => ['required', 'email', Rule::unique('users', 'email')->ignore($employee->id)],
            'password'      => 'nullable|string|min:8|confirmed',
            'manager_id'    => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'manager_id'    => $request->manager_id ?: null,
            'department_id' => $request->department_id ?: null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $employee->update($data);

        return redirect()->route('admin.employee.index')->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(User $employee)
    {
        if ($employee->role !== User::ROLE_EMPLOYEE) {
            abort(404);
        }
        $employee->delete();

        return redirect()->route('admin.employee.index')->with('success', 'Employee deleted successfully.');
    }
}
