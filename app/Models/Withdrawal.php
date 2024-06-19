<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function users(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function statusLink():BelongsTo
    {
        return $this->belongsTo(WithdrawalStatus::class,'status','id');
    }

}
