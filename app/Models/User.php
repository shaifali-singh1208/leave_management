<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'manager_id',
        'department_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    const ROLE_EMPLOYEE = 0;
    const ROLE_ADMIN    = 1;
    const ROLE_MANAGER  = 2;

    public static $user_role = [
        self::ROLE_ADMIN    => 'Admin',
        self::ROLE_MANAGER  => 'Manager',
        self::ROLE_EMPLOYEE => 'Employee',
    ];

    /**
     * The manager this employee is assigned to.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Employees assigned to this manager.
     */
    public function employees()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    /**
     * Leave applications submitted by this user.
     */
    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get leave usage for a specific type and year.
     */
    public function getLeaveUsage($leaveTypeId)
    {
        return $this->leaveApplications()->where('leave_type_id', $leaveTypeId)->where('status', LeaveApplication::STATUS_ACTIVE)->get()->sum(function ($leave) {
                return $leave->start_date->diffInDays($leave->end_date) + 1;
            });
    }
}
