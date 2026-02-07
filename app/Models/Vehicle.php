<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'registration_number',
        'make',
        'model',
        'year',
        'color',
        'vehicle_type',
        'fuel_type',
        'mileage',
        'vin_number',
        'insurance_number',
        'insurance_expiry',
        'notes',
    ];

    protected $casts = [
        'insurance_expiry' => 'date',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function jobCards()
    {
        return $this->hasMany(JobCard::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->make} {$this->model} ({$this->registration_number})";
    }
}
