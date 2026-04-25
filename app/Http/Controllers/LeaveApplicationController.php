<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\LeaveApplicationExport;

class LeaveApplicationController extends Controller
{


    /**
     * leave application list of emp.
     */
    public function index(Request $request)
    {
        $aRows = LeaveApplication::where('user_id', auth()->id())->with('leaveType')->filter()->orderBy('id', 'desc')->get();

        $leaveTypes = LeaveType::orderBy('name')->get();

        foreach ($leaveTypes as $type) {
            $type->days_taken = auth()->user()->getLeaveUsage($type->id);
        }

        $daysTaken = $leaveTypes->sum('days_taken');

        return view('employee.leave-applications.index', compact('aRows', 'leaveTypes', 'daysTaken'));
    }


    /**
     * manage application
     */
    public function create()
    {
        $leaveTypes = LeaveType::orderBy('name')->get();
        return view('employee.leave-applications.manage', compact('leaveTypes'));
    }



    /**
     * store leave request
     */
    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date|after_or_equal:today',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'required|string|max:1000',
        ]);

        $hasOverlap = LeaveApplication::where('user_id', auth()->id())->where('status', LeaveApplication::STATUS_ACTIVE)->whereDate('start_date', '<=', $request->end_date)->whereDate('end_date', '>=', $request->start_date)->exists();

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

        return redirect()->route('employee.leave-request.index')->with('success', 'Leave application submitted successfully.');
    }



    /**
     * delete leave application
     */
    public function destroy($id)
    {
        $leaveApplication = LeaveApplication::findOrFail($id);

        if ($leaveApplication->user_id !== auth()->id()) {
            abort(403);
        }

        if ($leaveApplication->status != LeaveApplication::STATUS_PENDING) {
            return back()->with('error', 'Only pending applications can be cancelled.');
        }

        $leaveApplication->delete();

        return redirect()->route('employee.leave-request.index')->with('success', 'Leave application cancelled.');
    }


    /**
     * maanger index file
     */
    public function managerIndex(Request $request)
    {
        $employeeIds = User::where('manager_id', auth()->id())->pluck('id');

        $aRows = LeaveApplication::whereIn('user_id', $employeeIds)->with(['user', 'leaveType'])->filter()->orderBy('id', 'desc')->get();

        return view('manager.leave-applications.index', compact('aRows'));
    }


    public function managerReview(Request $request, LeaveApplication $leaveApplication)
    {
        $aUser = $request->user()->id;
        $employeeIds = User::where('manager_id', $aUser)->pluck('id');

        if (!$employeeIds->contains($leaveApplication->user_id)) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(LeaveApplication::$leave_status)),
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
        $aRows = LeaveApplication::with(['user', 'leaveType'])->filter()->orderBy('id', 'desc')->get();
        $leaveTypes = LeaveType::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        return view('admin.leave-application.index', compact('aRows', 'leaveTypes', 'departments'));
    }


    /**
     * override status by admin
     */
    public function adminUpdateStatus(Request $request, LeaveApplication $leaveApplication)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(LeaveApplication::$leave_status)),
        ]);

        $aData = [
            'status' => $request->status,
        ];
        $leaveApplication->update($aData);

        return back()->with('success', 'Leave application status overridden.');
    }

    /**
     * Export all leave applications to CSV
     */
    public function exportCsv(Request $request)
    {
        return (new LeaveApplicationExport($request))->download();
    }

    /**
     * Fetch Application Leave list
     */
    public function FetchLeaveApplication(Request $request)
    {
        $aRows = LeaveApplication::orderBy('id', 'desc')->get();

        $returnData = [];

        foreach ($aRows as $aRow) {
            $returnData[] = [
                'id' => $aRow->id,
                'employee_name' => $aRow->user->name,
                'email' => $aRow->user->email,
                'department_name' => $aRow->user->department->name ?? null,
                'leave_type' => $aRow->leaveType->name ?? null,
                'start_date' => $aRow->start_date,
                'end_date' => $aRow->end_date,
                'status' => $aRow->status,
            ];
        }

        return response()->json(['status' => 'success', 'data'   => $returnData]);
    }
}
