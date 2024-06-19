<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalPaymentStatus extends Model
{
    use HasFactory;

    const CREATED = 10;
    const PROCESSING = 20;
    const SUCCESSED = 50;
    const FAILED = 70;

    protected $guarded = [];

    const NAMES = [
        self::CREATED => [
            'name' => 'CREATED',
            'name_ru' => 'Добавлен',
        ],
        self::PROCESSING => [
            'name' => 'PROCESSING',
            'name_ru' => 'Выполняется',
        ],
        self::SUCCESSED => [
            'name' => 'SUCCESSED',
            'name_ru' => 'Выполнен',
        ],
        self::FAILED => [
            'name' => 'FAILED',
            'name_ru' => 'Ошибка',
        ],
    ];
}
