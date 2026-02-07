<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_id',
        'designation',
        'department',
        'hire_date',
        'salary',
        'address',
        'emergency_contact',
        'emergency_phone',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedAppointments()
    {
        return $this->hasMany(Appointment::class, 'assigned_staff_id');
    }

    public function assignedJobCards()
    {
        return $this->hasMany(JobCard::class, 'assigned_staff_id');
    }
}
