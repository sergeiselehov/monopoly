<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function status():BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class,'payment_status_id','id');
    }

    public function merchant(){
        return $this->belongsTo(Merchant::class,'merchant_id','id');
    }



}
