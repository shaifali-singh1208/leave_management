<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'reason',
        'status',
        'manager_comment',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    const STATUS_ACTIVE  = 10;
    const STATUS_REJECT  = 11;
    const STATUS_PENDING = 5;

    public static $leave_status = [
        self::STATUS_REJECT  => 'Rejected',
        self::STATUS_ACTIVE  => 'Approved',
        self::STATUS_PENDING => 'Pending',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    /**
     * Scope for filtering leave applications based on request parameters.
     */
    public function scopeFilter($query)
    {
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        if (request()->filled('leave_type_id')) {
            $query->where('leave_type_id', request('leave_type_id'));
        }

        if (request()->filled('date_from')) {
            $query->whereDate('start_date', '>=', request('date_from'));
        }

        if (request()->filled('date_to')) {
            $query->whereDate('end_date', '<=', request('date_to'));
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query;
    }
}
