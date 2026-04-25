<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ManagerController extends Controller
{
    /**
     * Display a listing of managers.
     */
    public function index()
    {
        $aRows = User::where('role', User::ROLE_MANAGER)->with(['department'])->withCount(['employees'])->orderBy('id', 'desc')->get();

        return view('admin.manager.index', compact('aRows'));
    }

    /**
     * Show the form for creating a new manager.
     */
    public function create()
    {
        $aRow = null;
        $departments = Department::orderBy('name')->get();
        return view('admin.manager.manage', compact('aRow', 'departments'));
    }

    /**
     * Store a newly created manager in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:8|confirmed',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => User::ROLE_MANAGER,
            'department_id' => $request->department_id ?: null,
        ]);

        return redirect()->route('admin.manager.index')->with('success', 'Manager created successfully.');
    }

    /**
     * Show the form for editing the specified manager.
     */
    public function edit(User $manager)
    {
        if ($manager->role !== User::ROLE_MANAGER) {
            abort(404);
        }

        $aRow = $manager;
        $departments = Department::orderBy('name')->get();
        return view('admin.manager.manage', compact('aRow', 'departments'));
    }

    /**
     * Update the specified manager in storage.
     */
    public function update(Request $request, User $manager)
    {
        if ($manager->role !== User::ROLE_MANAGER) {
            abort(404);
        }

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => ['required', 'email', Rule::unique('users', 'email')->ignore($manager->id)],
            'password'      => 'nullable|string|min:8|confirmed',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'department_id' => $request->department_id ?: null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $manager->update($data);

        return redirect()->route('admin.manager.index')->with('success', 'Manager updated successfully.');
    }

    /**
     * Remove the specified manager from storage.
     */
    public function destroy(User $manager)
    {
        if ($manager->role !== User::ROLE_MANAGER) {
            abort(404);
        }
        User::where('manager_id', $manager->id)->update(['manager_id' => null]);

        $manager->delete();

        return redirect()->route('admin.manager.index')->with('success', 'Manager deleted successfully.');
    }
}
