<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\Request;

class LeaveApplicationController extends Controller
{
    // ─── Employee ─────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $aRows = LeaveApplication::where('user_id', auth()->id()) ->with('leaveType') ->filter() ->orderBy('id', 'desc') ->get();

        $leaveTypes = LeaveType::orderBy('name')->get();

        $daysTaken = LeaveApplication::where('user_id', auth()->id())->where('status', LeaveApplication::STATUS_ACTIVE)
            ->whereYear('start_date', now()->year)
            ->get()
            ->sum(fn($l) => $l->start_date->diffInDays($l->end_date) + 1);

        return view('employee.leave-applications.index', compact('aRows', 'leaveTypes', 'daysTaken'));
    }

    public function create()
    {
        $leaveTypes = LeaveType::orderBy('name')->get();
        return view('employee.leave-applications.manage', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date|after_or_equal:today',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'required|string|max:1000',
        ]);

        $hasOverlap = LeaveApplication::where('user_id', auth()->id()) ->where('status', LeaveApplication::STATUS_ACTIVE) ->whereDate('start_date', '<=', $request->end_date) ->whereDate('end_date', '>=', $request->start_date) ->exists();

        if ($hasOverlap) {
            return back()->withErrors(['start_date' => 'You already have an approved leave that overlaps with these dates.'])->withInput();
        }

        LeaveApplication::create([
            'user_id'       => auth()->id(),
            'leave_type_id' => $request->leave_type_id,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'reason'        => $request->reason,
            'status'        => LeaveApplication::STATUS_PENDING,
        ]);

        return redirect()->route('employee.leave-applications.index')->with('success', 'Leave application submitted successfully.');
    }

    public function destroy(LeaveApplication $leaveApplication)
    {
        if ($leaveApplication->user_id !== auth()->id()) {
            abort(403);
        }

        if ($leaveApplication->status !== LeaveApplication::STATUS_PENDING) {
            return back()->with('error', 'Only pending applications can be cancelled.');
        }

        $leaveApplication->delete();

        return redirect()->route('employee.leave-applications.index')->with('success', 'Leave application cancelled.');
    }


    public function managerIndex(Request $request)
    {
        $employeeIds = User::where('manager_id', auth()->id())->pluck('id');

        $aRows = LeaveApplication::whereIn('user_id', $employeeIds) ->with(['user', 'leaveType']) ->filter() ->orderBy('id', 'desc') ->get();

        return view('manager.leave-applications.index', compact('aRows'));
    }

    public function managerReview(Request $request, LeaveApplication $leaveApplication)
    {
        $employeeIds = User::where('manager_id', auth()->id())->pluck('id');

        if (!$employeeIds->contains($leaveApplication->user_id)) {
            abort(403);
        }

        $request->validate([
            'status'  => 'required|in:' . LeaveApplication::STATUS_ACTIVE . ',' . LeaveApplication::STATUS_REJECT,
            'comment' => 'nullable|string|max:500',
        ]);

        $leaveApplication->update([
            'status'          => $request->status,
            'manager_comment' => $request->comment,
        ]);

        return back()->with('success', 'Application status updated.');
    }


    public function adminIndex(Request $request)
    {
        $aRows = LeaveApplication::with(['user', 'leaveType']) ->filter() ->orderBy('id', 'desc') ->get();

        $leaveTypes = LeaveType::orderBy('name')->get();
          
        return view('admin.leave-application.index', compact('aRows', 'leaveTypes'));
    }

    public function adminUpdateStatus(Request $request, LeaveApplication $leaveApplication)
    {
        $request->validate([
            'status'  => 'required|in:' . LeaveApplication::STATUS_PENDING . ',' . LeaveApplication::STATUS_ACTIVE . ',' . LeaveApplication::STATUS_REJECT,
            'comment' => 'nullable|string|max:500',
        ]);

        $aData = [
            'status' => $request->status,
            'manager_comment' => $request->comment
        ];
        
        $leaveApplication->update($aData);

        return back()->with('success', 'Leave application status overridden.');
    }
}
