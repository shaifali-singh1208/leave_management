<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    /**
     * Display listing
     */
    public function index()
    {
        $aRows = LeaveType::orderBy('id', 'desc')->get();

        return view('admin.leave-type.index', compact('aRows'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $aRow = null;
        return view('admin.leave-type.manage', compact('aRow'));
    }

    /**
     * Store new leave type
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'entitlement_days' => 'required|integer|min:1',
        ]);

        LeaveType::create([
            'name' => $request->name,
            'entitlement_days' => $request->entitlement_days,
        ]);

        return redirect()->route('admin.leave-type.index')->with('success', 'Leave Type created successfully');
    }

    /**
     * Show edit form
     */
    public function edit(LeaveType $leaveType)
    {
        $aRow = $leaveType;
        return view('admin.leave_type.manage', compact('aRow'));
    }

    /**
     * Update leave type
     */
    public function update(Request $request, LeaveType $leaveType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'entitlement_days' => 'required|integer|min:1',
        ]);


        $leaveType->update([
            'name' => $request->name,
            'entitlement_days' => $request->entitlement_days,
        ]);

        return redirect()->route('admin.leave-type.index')->with('success', 'Leave Type updated successfully');
    }


    public function destroy(LeaveType $leaveType) {
        
    }
}
