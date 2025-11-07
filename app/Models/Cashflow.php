<?php

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
        'cover'
    ];

    public $timestamps = true;

    // Relasi: Cashflow dimiliki oleh 1 User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
