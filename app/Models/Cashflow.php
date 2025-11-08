<?php
// app/Models/Cashflow.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cashflow extends Model
{
    protected $table = 'cashflows';

    protected $fillable = [
        'user_id',
        'type',
        'source',
        'label',
        'description',
        'amount',
        'cover',
        'created_date'
    ];

    public $timestamps = true;

    protected $casts = [
        'created_date' => 'date',
        'amount' => 'decimal:2'
    ];

    // Relasi: Cashflow dimiliki oleh 1 User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk filter
    public function scopeSearch($query, $search)
    {
        return $query->where('label', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
    }

    public function scopeType($query, $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }
        return $query;
    }

    public function scopeSource($query, $source)
    {
        if ($source) {
            return $query->where('source', $source);
        }
        return $query;
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween('created_date', [$startDate, $endDate]);
        }
        return $query;
    }
}