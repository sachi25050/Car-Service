<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePackageItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'service_id',
        'quantity',
    ];

    // Relationships
    public function package()
    {
        return $this->belongsTo(ServicePackage::class, 'package_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
