<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'service_type',
        'duration_minutes',
        'base_price',
        'is_active',
        'requires_appointment',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
        'requires_appointment' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function packageItems()
    {
        return $this->hasMany(ServicePackageItem::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function jobCardServices()
    {
        return $this->hasMany(JobCardService::class);
    }
}
