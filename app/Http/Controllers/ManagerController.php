<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $aRows = User::where('role', User::ROLE_MANAGER)->withCount(['employees'])->orderBy('id', 'desc')->get();

        return view('admin.manager.index', compact('aRows'));
    }

    /**
     * Show the form for creating a new manager.
     */
    public function create()
    {
        $aRow = null;
        return view('admin.manager.manage', compact('aRow'));
    }

    /**
     * Store a newly created manager in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => User::ROLE_MANAGER,
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
        return view('admin.manager.manage', compact('aRow'));
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
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($manager->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
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
