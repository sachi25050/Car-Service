<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCardService extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_card_id',
        'service_id',
        'quantity',
        'unit_price',
        'total_price',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Methods
    public function calculateTotal()
    {
        $this->total_price = $this->unit_price * $this->quantity;
        $this->save();
    }
}
