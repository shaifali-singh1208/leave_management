<?php

namespace App\Exports;

use App\Models\LeaveApplication;
use App\Models\User;
use Illuminate\Http\Request;

class LeaveApplicationExport
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function download()
    {
        $aRows = LeaveApplication::with(['user', 'leaveType'])->get();

        $fileName = 'leave_report_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [ 'ID', 'Employee', 'Role', 'Department', 'Leave Type', 'Start Date', 'End Date', 'Days', 'Status', 'Reason', 'Applied At' ];

        $callback = function () use ($aRows, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($aRows as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->user->name,
                    User::$user_role[$row->user->role] ?? 'Unknown',
                    $row->user->department->name ?? 'N/A',
                    $row->leaveType->name ?? 'N/A',
                    $row->start_date->format('d-M-Y'),
                    $row->end_date->format('d-M-Y'),
                    $row->start_date->diffInDays($row->end_date) + 1,
                    LeaveApplication::$leave_status[$row->status] ?? 'Unknown',
                    $row->reason,
                    $row->created_at->format('d-M-Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
