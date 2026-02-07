<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_number',
        'appointment_id',
        'customer_id',
        'vehicle_id',
        'status',
        'priority',
        'estimated_completion',
        'actual_start_time',
        'actual_completion_time',
        'total_amount',
        'discount_amount',
        'tax_amount',
        'final_amount',
        'assigned_staff_id',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'estimated_completion' => 'datetime',
        'actual_start_time' => 'datetime',
        'actual_completion_time' => 'datetime',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(Staff::class, 'assigned_staff_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function services()
    {
        return $this->hasMany(JobCardService::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    // Methods
    public function calculateTotal()
    {
        $subtotal = $this->services()->sum('total_price');
        $this->total_amount = $subtotal;
        $this->final_amount = $subtotal - $this->discount_amount + $this->tax_amount;
        $this->save();
    }
}
