<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    use HasFactory;

    const CREATED = 1;
    const PROCESSING = 2;
    const WAITING_PAYMENT = 3;
    const PAID = 4;
    const SUCCESSED = 5;
    const FAILED = 6;
    const CLOSED_FAILED = 7;
    const REPEATED_CLOSED_FAILED = 8;
    const ACCEPTED_SUCCESSED = 9;
    const REPEATED_ACCEPTED_SUCCESSED = 10;

    const ERROR_FROM_PG = 11;

    protected $guarded = [];

    const NAMES = [
        self::CREATED => [
            'name' => 'CREATED',
            'name_ru' => 'Добавлена',
        ],
        self::PROCESSING => [
            'name' => 'PROCESSING',
            'name_ru' => 'В процессе',
        ],
        self::WAITING_PAYMENT => [
            'name' => 'WAITING_PAYMENT',
            'name_ru' => 'Ожидает платежа',
        ],
        self::PAID => [
            'name' => 'PAID',
            'name_ru' => 'Отмечена оплаченной',
        ],
        self::SUCCESSED => [
            'name' => 'SUCCESSED',
            'name_ru' => 'Зачеслен',
        ],
        self::FAILED => [
            'name' => 'FAILED',
            'name_ru' => 'Отклонен',
        ],
        self::CLOSED_FAILED => [
            'name' => 'CLOSED_FAILED',
            'name_ru' => 'Закрыто после спора',
        ],
        self::REPEATED_CLOSED_FAILED => [
            'name' => 'REPEATED_CLOSED_FAILED',
            'name_ru' => 'Закрыто повторно после спора',
        ],
        self::ACCEPTED_SUCCESSED => [
            'name' => 'ACCEPTED_SUCCESSED',
            'name_ru' => 'После принятия спора',
        ],
        self::REPEATED_ACCEPTED_SUCCESSED => [
            'name' => 'REPEATED_ACCEPTED_SUCCESSED',
            'name_ru' => 'Повторно после принятия спора',
        ],
    ];

}
