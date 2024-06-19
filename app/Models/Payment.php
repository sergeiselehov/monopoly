<?php

namespace App\Models;

use App\Common\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory, Filterable;

    protected $guarded = [];

    public function status():BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class,'payment_status_id','id');
    }

    public function commission()
    {
        return $this->morphOne(Commission::class,'commissionable');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, );
    }

}
