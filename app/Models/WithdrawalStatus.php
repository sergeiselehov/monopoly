<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalStatus extends Model
{
    use HasFactory;

    const CREATED = 1;
    const INWORK = 2;
    const PAID = 3;
    const NAMES = [
        self::CREATED => [
            'name' => 'CREATED',
            'name_ru' => 'Добавлен',
        ],
        self::INWORK => [
            'name' => 'INWORK',
            'name_ru' => 'В работе',
        ],
        self::PAID => [
            'name' => 'PAID',
            'name_ru' => 'Выплачен',
        ],

    ];

    protected $guarded = [];
}
