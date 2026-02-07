<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'job_card_id',
        'customer_id',
        'invoice_date',
        'due_date',
        'subtotal',
        'discount_amount',
        'tax_percentage',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'status',
        'payment_terms',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
    ];

    // Relationships
    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Methods
    public function calculateTotals()
    {
        $this->tax_amount = ($this->subtotal - $this->discount_amount) * ($this->tax_percentage / 100);
        $this->total_amount = $this->subtotal - $this->discount_amount + $this->tax_amount;
        $this->balance_amount = $this->total_amount - $this->paid_amount;
        
        // Update status
        if ($this->balance_amount <= 0) {
            $this->status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
        } elseif ($this->due_date && $this->due_date->isPast() && $this->balance_amount > 0) {
            $this->status = 'overdue';
        }
        
        $this->save();
    }

    public function updatePaidAmount()
    {
        $this->paid_amount = $this->payments()->sum('amount');
        $this->calculateTotals();
    }
}
