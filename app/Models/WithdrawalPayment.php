<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawalPayment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function status():BelongsTo
    {
        return $this->belongsTo(WithdrawalPaymentStatus::class,'withdrawal_status_id','id');
    }


    public function bankBin()
    {
        return $this->belongsTo(BankBin::class,'bin','bin');
    }



}
