<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_number',
        'invoice_id',
        'customer_id',
        'payment_date',
        'payment_method',
        'amount',
        'reference_number',
        'notes',
        'received_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // Events
    protected static function booted()
    {
        static::created(function ($payment) {
            $payment->invoice->updatePaidAmount();
        });

        static::updated(function ($payment) {
            $payment->invoice->updatePaidAmount();
        });

        static::deleted(function ($payment) {
            $payment->invoice->updatePaidAmount();
        });
    }
}
