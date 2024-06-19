<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportRequestStatus extends Model
{
    use HasFactory;

    const CREATED = 1;
    const INWORK = 2;
    const CLOSED = 3;

    const NAMES = [
        self::CREATED=>'Добавлена',
        self::INWORK=>'В работе',
        self::CLOSED=>'Закрытал'
    ];
    protected $guarded = [];
}
