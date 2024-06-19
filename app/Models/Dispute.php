<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dispute extends Model
{
    protected $guarded = [];

    public function payment():BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function status():BelongsTo
    {
        return $this->belongsTo(DisputeStatus::class,'dispute_status_id','id');
    }

    public function disputeImage()
    {
        return $this->hasMany(DisputeImage::class);
    }


}
