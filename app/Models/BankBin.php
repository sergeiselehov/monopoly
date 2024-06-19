<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankBin extends Model
{
    protected $fillable = [
        'bin',
        'bank_name',
        'bank_id',
        'payment_type'
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
